<?php

namespace App\Livewire;

use App\Data\WordLists;
use App\Models\GameSession;
use Livewire\Component;

class AnagramGame extends Component
{
    public string $phase = 'setup'; // setup | playing | feedback | summary

    // ── Settings ────────────────────────────────────────────────────────────────
    public string $wordListKey   = 'year1';
    public int    $questionCount = 10;
    public int    $timePerWord   = 0; // seconds per word; 0 = unlimited

    // ── Playing ─────────────────────────────────────────────────────────────────
    public int    $currentIndex    = 0;
    public string $currentWord     = '';
    public array  $words           = [];
    public string $userAnswer      = '';
    public bool   $answered        = false;
    public bool   $lastCorrect     = false;
    public bool   $usedHint        = false;
    public string $hintLetter      = ''; // first letter, revealed on hint request

    // ── Timer ────────────────────────────────────────────────────────────────────
    public int $answerTimeLeft = 0;

    // ── Results ─────────────────────────────────────────────────────────────────
    public array $results           = [];
    public int   $correctCount      = 0;
    public int   $wrongCount        = 0;
    public int   $hintUsedCount     = 0;
    public int   $totalTimeSeconds  = 0;
    public int   $gameStartTime     = 0;
    public int   $questionStartTime = 0;
    public ?int  $sessionId         = null;

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
        $this->hintUsedCount = 0;
        $this->results       = [];
        $this->gameStartTime = time();

        if (auth()->check()) {
            $session = GameSession::create([
                'user_id'         => auth()->id(),
                'game_type'       => 'anagram',
                'settings'        => [
                    'word_list'    => $this->wordListKey,
                    'time_per_word' => $this->timePerWord,
                ],
                'total_questions' => $this->questionCount,
            ]);
            $this->sessionId = $session->id;
        }

        $this->loadCurrentWord();
        $this->dispatch('scroll-to-top');
    }

    private function loadCurrentWord(): void
    {
        $this->currentWord       = $this->words[$this->currentIndex];
        $this->userAnswer        = '';
        $this->answered          = false;
        $this->lastCorrect       = false;
        $this->usedHint          = false;
        $this->hintLetter        = '';
        $this->phase             = 'playing';
        $this->answerTimeLeft    = $this->timePerWord;
        $this->questionStartTime = time();
    }

    /** Polled every second when a timer is active. */
    public function tick(): void
    {
        if ($this->phase === 'playing' && $this->timePerWord > 0 && !$this->answered) {
            $this->answerTimeLeft = max(0, $this->answerTimeLeft - 1);
            if ($this->answerTimeLeft <= 0) {
                $this->submitWithAnswer('');
            }
        }
    }

    /** Reveal the first letter as a text hint. */
    public function useHint(): void
    {
        if ($this->answered || $this->usedHint) {
            return;
        }
        $this->usedHint   = true;
        $this->hintLetter = mb_strtoupper(mb_substr($this->currentWord, 0, 1));
        $this->hintUsedCount++;
    }

    /** Give up on the current word — counts as wrong. */
    public function giveUp(): void
    {
        if ($this->answered) {
            return;
        }
        $this->submitWithAnswer('');
    }

    /** Called from Alpine with the assembled tile word. */
    public function submitWithAnswer(string $answer): void
    {
        if ($this->answered) {
            return;
        }

        $this->userAnswer  = trim($answer);
        $this->answered    = true;
        $isCorrect         = mb_strtolower($this->userAnswer) === mb_strtolower($this->currentWord);
        $this->lastCorrect = $isCorrect;

        if ($isCorrect) {
            $this->correctCount++;
        } else {
            $this->wrongCount++;
        }

        $this->results[] = [
            'word'        => $this->currentWord,
            'user_answer' => $this->userAnswer,
            'is_correct'  => $isCorrect,
            'used_hint'   => $this->usedHint,
            'time_taken'  => time() - $this->questionStartTime,
        ];

        $this->phase = 'feedback';
    }

    public function proceedToNext(): void
    {
        if (!$this->answered) {
            $this->submitWithAnswer('');
            return;
        }
        $this->advanceToNext();
    }

    private function advanceToNext(): void
    {
        $this->currentIndex++;

        if ($this->currentIndex >= count($this->words)) {
            $this->finishGame();
        } else {
            $this->loadCurrentWord();
        }
    }

    private function finishGame(): void
    {
        $this->totalTimeSeconds = time() - $this->gameStartTime;
        $this->phase            = 'summary';

        if ($this->sessionId) {
            GameSession::where('id', $this->sessionId)->update([
                'correct_answers'    => $this->correctCount,
                'wrong_answers'      => $this->wrongCount,
                'time_taken_seconds' => $this->totalTimeSeconds,
                'completed_at'       => now(),
            ]);
        }
    }

    public function resetGame(): void
    {
        $this->phase        = 'setup';
        $this->sessionId    = null;
        $this->results      = [];
        $this->correctCount = 0;
        $this->wrongCount   = 0;
        $this->words        = [];
    }

    public function getWordListLabels(): array
    {
        return WordLists::labels();
    }

    public function render()
    {
        return view('livewire.anagram-game', [
            'wordListLabels' => $this->getWordListLabels(),
            'wordListSize'   => count(WordLists::get($this->wordListKey)),
        ]);
    }
}
