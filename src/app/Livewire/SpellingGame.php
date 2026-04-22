<?php

namespace App\Livewire;

use App\Data\WordLists;
use App\Models\GameSession;
use Livewire\Component;

class SpellingGame extends Component
{
    public string $phase = 'setup'; // setup | showing | typing | feedback | summary

    // ── Settings ────────────────────────────────────────────────────────────────
    public string $wordListKey  = 'year1';
    public int    $questionCount = 10;
    public int    $displayTime   = 4;  // seconds to show word (0 = manual hide)
    public int    $timePerAnswer = 0;  // seconds to type   (0 = unlimited)
    public string $hintType      = 'blanks'; // none | blanks | puzzle
    public bool   $examMode      = false;

    // ── Playing ─────────────────────────────────────────────────────────────────
    public int    $currentIndex    = 0;
    public string $currentWord     = '';
    public array  $words           = [];
    public string $userAnswer      = '';
    public bool   $answered        = false;
    public bool   $lastCorrect     = false;

    // ── Timers ──────────────────────────────────────────────────────────────────
    public int $displayTimeLeft = 0;
    public int $answerTimeLeft  = 0;

    // ── Results ─────────────────────────────────────────────────────────────────
    public array $results       = [];
    public int   $correctCount  = 0;
    public int   $wrongCount    = 0;
    public int   $totalTimeSeconds = 0;
    public int   $gameStartTime   = 0;
    public int   $questionStartTime = 0;
    public ?int  $sessionId = null;

    // ────────────────────────────────────────────────────────────────────────────

    public function startGame(): void
    {
        $all = WordLists::get($this->wordListKey);
        shuffle($all);
        $this->words         = array_slice($all, 0, min($this->questionCount, count($all)));
        $this->questionCount = count($this->words);

        $this->currentIndex  = 0;
        $this->correctCount  = 0;
        $this->wrongCount    = 0;
        $this->results       = [];
        $this->gameStartTime = time();

        if (auth()->check()) {
            $session = GameSession::create([
                'user_id'         => auth()->id(),
                'game_type'       => 'spelling',
                'settings'        => [
                    'word_list'      => $this->wordListKey,
                    'hint_type'      => $this->hintType,
                    'display_time'   => $this->displayTime,
                    'time_per_answer'=> $this->timePerAnswer,
                    'exam_mode'      => $this->examMode,
                ],
                'total_questions' => $this->questionCount,
            ]);
            $this->sessionId = $session->id;
        }

        $this->showCurrentWord();
        $this->dispatch('scroll-to-top');
    }

    private function showCurrentWord(): void
    {
        $this->currentWord        = $this->words[$this->currentIndex];
        $this->userAnswer         = '';
        $this->answered           = false;
        $this->lastCorrect        = false;
        $this->phase              = 'showing';
        $this->displayTimeLeft    = $this->displayTime;
        $this->questionStartTime  = time();
    }

    /** User manually requests to hide the word and start typing. */
    public function readyToType(): void
    {
        $this->phase          = 'typing';
        $this->answerTimeLeft = $this->timePerAnswer;
    }

    /** Polled every second — handles both display and answer timers. */
    public function tick(): void
    {
        if ($this->phase === 'showing' && $this->displayTime > 0) {
            $this->displayTimeLeft = max(0, $this->displayTimeLeft - 1);
            if ($this->displayTimeLeft <= 0) {
                $this->phase          = 'typing';
                $this->answerTimeLeft = $this->timePerAnswer;
            }
            return;
        }

        if ($this->phase === 'typing' && $this->timePerAnswer > 0 && !$this->answered) {
            $this->answerTimeLeft = max(0, $this->answerTimeLeft - 1);
            if ($this->answerTimeLeft <= 0) {
                $this->submitAnswer();
                $this->maybeProceed();
            }
        }
    }

    /** Called from Alpine with the concatenated letter-box answer. */
    public function submitWithAnswer(string $answer): void
    {
        $this->userAnswer = $answer;
        $this->submitAnswer();
    }

    /** Submit answer (called explicitly or by timer). */
    public function submitAnswer(): void
    {
        if ($this->answered) {
            return;
        }

        $input     = trim($this->userAnswer);
        $isCorrect = mb_strtolower($input) === mb_strtolower($this->currentWord);

        $this->answered    = true;
        $this->lastCorrect = $isCorrect;

        if ($isCorrect) {
            $this->correctCount++;
        } else {
            $this->wrongCount++;
        }

        $this->results[] = [
            'word'        => $this->currentWord,
            'user_answer' => $input,
            'is_correct'  => $isCorrect,
            'time_taken'  => time() - $this->questionStartTime,
        ];

        // In exam mode: skip feedback phase and go straight to the next word
        if ($this->examMode) {
            $this->advanceToNext();
        } else {
            $this->phase = 'feedback';
        }
    }

    /** "Next Word" button pressed from feedback screen (non-exam mode). */
    public function proceedToNext(): void
    {
        if (!$this->answered) {
            $this->submitAnswer();
            return;
        }
        $this->advanceToNext();
    }

    /** Called after answer timer runs out in exam or timed mode. */
    private function maybeProceed(): void
    {
        if ($this->examMode) {
            // already advanced inside submitAnswer
            return;
        }
        // In timed non-exam mode: show feedback briefly then auto-advance is
        // handled by "Next Word" button; we just land on feedback phase.
        $this->phase = 'feedback';
    }

    private function advanceToNext(): void
    {
        $this->currentIndex++;

        if ($this->currentIndex >= count($this->words)) {
            $this->finishGame();
        } else {
            $this->showCurrentWord();
        }
    }

    private function finishGame(): void
    {
        $this->totalTimeSeconds = time() - $this->gameStartTime;
        $this->phase            = 'summary';

        if ($this->sessionId) {
            GameSession::where('id', $this->sessionId)->update([
                'correct_answers'  => $this->correctCount,
                'wrong_answers'    => $this->wrongCount,
                'time_taken_seconds' => $this->totalTimeSeconds,
                'completed_at'     => now(),
            ]);
        }
    }

    public function toggleExamMode(): void
    {
        $this->examMode = !$this->examMode;
    }

    public function resetGame(): void
    {
        $this->phase       = 'setup';
        $this->sessionId   = null;
        $this->results     = [];
        $this->correctCount = 0;
        $this->wrongCount  = 0;
        $this->words       = [];
    }

    /** The hint shown during the typing phase (unused in puzzle mode). */
    public function getHintString(): string
    {
        $word = $this->currentWord;
        $len  = mb_strlen($word);

        if ($this->hintType === 'blanks') {
            return implode(' ', array_fill(0, $len, '_'));
        }

        return ''; // none | puzzle
    }

    /** Word lists for the setup form. */
    public function getWordListLabels(): array
    {
        return WordLists::labels();
    }

    public function render()
    {
        return view('livewire.spelling-game', [
            'hint'           => $this->getHintString(),
            'wordListLabels' => $this->getWordListLabels(),
            'wordListSize'   => count(WordLists::get($this->wordListKey)),
        ]);
    }
}
