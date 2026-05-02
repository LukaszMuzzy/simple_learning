<?php

namespace App\Livewire;

use App\Models\GameAnswer;
use App\Models\GameSession;
use Livewire\Component;

class MultiplicationGame extends Component
{
    public string $phase = 'setup'; // setup, playing, summary

    // Settings
    public int $questionCount = 10;
    public int $customQuestionCount = 10;
    public int $timePerQuestion = 0; // 0 = no limit
    public int $timePerGame = 0;     // 0 = no limit, else total seconds for the whole game
    public int $customGameMins = 0;
    public int $customGameSecs = 0;
    public string $answerMode = 'type'; // type, multiple_choice
    public bool $examMode = false; // when true, no per-question feedback; reveal all at end
    // Numbers the user wants to practise (empty = all 0–12)
    public array $selectedNumbers = [];

    // Playing phase
    public int $currentQuestion = 0;
    public int $num1 = 0;
    public int $num2 = 0;
    public int $correctAnswer = 0;
    public string $userAnswer = '';
    public array $choices = [];
    public ?string $feedback = null;
    public bool $answered = false;
    public int $timeLeft = 0;      // per-question countdown
    public int $gameTimeLeft = 0;  // whole-game countdown
    public int $questionStartTime = 0;

    // Results tracking
    public array $results = [];
    public int $correctCount = 0;
    public int $wrongCount = 0;
    public ?int $sessionId = null;

    // Summary
    public int $totalTimeSeconds = 0;
    public int $gameStartTime = 0;
    public int $questionsAnswered = 0;  // actually reached, may be < questionCount when game timer ends early
    public bool $endedByGameTimer = false;
    public array $difficultSequences = []; // top wrong/slow patterns for logged-in users

    public function mount(): void
    {
        $focus = request()->query('focus', '');
        if ($focus !== '') {
            $nums = array_filter(
                array_map('intval', explode(',', $focus)),
                fn ($n) => $n >= 0 && $n <= 12
            );
            if (!empty($nums)) {
                $this->selectedNumbers = array_values(array_unique($nums));
                sort($this->selectedNumbers);
            }
        }
    }

    public function setQuestionCount(int $n): void
    {
        $this->questionCount = $n;
        $this->customQuestionCount = $n;
    }

    public function applyCustomQuestionCount(): void
    {
        $n = max(1, min(200, (int) $this->customQuestionCount));
        $this->questionCount = $n;
        $this->customQuestionCount = $n;
    }

    public function setTimePerGame(int $seconds): void
    {
        $this->timePerGame = $seconds;
        $this->customGameMins = intdiv($seconds, 60);
        $this->customGameSecs = $seconds % 60;
    }

    public function applyCustomGameTime(): void
    {
        $mins = max(0, (int) $this->customGameMins);
        $secs = max(0, min(59, (int) $this->customGameSecs));
        $this->timePerGame = $mins * 60 + $secs;
    }

    public function toggleNumber(int $n): void
    {
        if (in_array($n, $this->selectedNumbers)) {
            $this->selectedNumbers = array_values(array_filter(
                $this->selectedNumbers,
                fn ($v) => $v !== $n
            ));
        } else {
            $this->selectedNumbers[] = $n;
            sort($this->selectedNumbers);
        }
    }

    public function startGame(): void
    {
        $this->phase = 'playing';
        $this->currentQuestion = 0;
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->results = [];
        $this->gameStartTime = time();

        $this->gameTimeLeft = $this->timePerGame;

        if (auth()->check()) {
            $session = GameSession::create([
                'user_id' => auth()->id(),
                'game_type' => 'multiplication',
                'settings' => [
                    'answer_mode' => $this->answerMode,
                    'time_per_question' => $this->timePerQuestion,
                    'time_per_game' => $this->timePerGame,
                    'exam_mode' => $this->examMode,
                    'selected_numbers' => $this->selectedNumbers,
                ],
                'total_questions' => $this->questionCount,
            ]);
            $this->sessionId = $session->id;
        }

        $this->generateQuestion();
    }

    private function pickNumber(): int
    {
        $pool = empty($this->selectedNumbers) ? range(0, 12) : $this->selectedNumbers;
        return $pool[array_rand($pool)];
    }

    public function generateQuestion(): void
    {
        $this->userAnswer = '';
        $this->feedback = null;
        $this->answered = false;
        $this->questionStartTime = time();

        $this->num1 = $this->pickNumber();
        $this->num2 = rand(0, 12);
        $this->correctAnswer = $this->num1 * $this->num2;

        if ($this->answerMode === 'multiple_choice') {
            $this->generateChoices();
        }

        $this->timeLeft = $this->timePerQuestion;
    }

    private function generateChoices(): void
    {
        $choices = [$this->correctAnswer];

        while (count($choices) < 4) {
            $wrongNum1 = rand(0, 12);
            $wrongNum2 = rand(0, 12);
            $wrong = $wrongNum1 * $wrongNum2;

            if (!in_array($wrong, $choices)) {
                $choices[] = $wrong;
            }
        }

        shuffle($choices);
        $this->choices = $choices;
    }

    public function submitAnswer(mixed $answer = null): void
    {
        if ($this->answered) {
            return;
        }

        $timeTaken = time() - $this->questionStartTime;

        if ($answer !== null) {
            $this->userAnswer = (string) $answer;
        }

        // Only mark correct if the user actually provided an answer
        $hasAnswer = $this->userAnswer !== '';
        $userAnswerInt = $hasAnswer ? (int) $this->userAnswer : null;
        $isCorrect = $hasAnswer && $userAnswerInt === $this->correctAnswer;

        $this->answered = true;
        $this->feedback = $isCorrect ? 'correct' : 'incorrect';

        if ($isCorrect) {
            $this->correctCount++;
        } else {
            $this->wrongCount++;
        }

        $questionText = "{$this->num1} × {$this->num2}";

        $this->results[] = [
            'question' => $questionText,
            'correct_answer' => $this->correctAnswer,
            'user_answer' => $userAnswerInt,
            'is_correct' => $isCorrect,
            'time_taken' => $timeTaken,
        ];

        if ($this->sessionId) {
            GameAnswer::create([
                'game_session_id' => $this->sessionId,
                'question' => $questionText,
                'num1' => $this->num1,
                'num2' => $this->num2,
                'correct_answer' => $this->correctAnswer,
                'user_answer' => $userAnswerInt,
                'is_correct' => $isCorrect,
                'time_taken_seconds' => $timeTaken,
            ]);
        }
    }

    public function tick(): void
    {
        if ($this->phase !== 'playing') {
            return;
        }

        // Game-level countdown
        if ($this->timePerGame > 0) {
            $this->gameTimeLeft = max(0, $this->gameTimeLeft - 1);

            if ($this->gameTimeLeft <= 0) {
                if (!$this->answered) {
                    $this->submitAnswer();
                }
                $this->endedByGameTimer = true;
                $this->finishGame();
                return;
            }
        }

        // Per-question countdown
        if ($this->timePerQuestion > 0) {
            $this->timeLeft = max(0, $this->timeLeft - 1);

            if ($this->timeLeft <= 0) {
                if (!$this->answered) {
                    $this->submitAnswer();
                }
                $this->advanceQuestion();
            }
        }
    }

    public function nextQuestion(): void
    {
        if (!$this->answered) {
            $this->submitAnswer();
        }
        $this->advanceQuestion();
    }

    private function advanceQuestion(): void
    {
        $this->currentQuestion++;

        if ($this->currentQuestion >= $this->questionCount) {
            $this->finishGame();
        } else {
            $this->generateQuestion();
        }
    }

    private function finishGame(): void
    {
        $this->totalTimeSeconds = time() - $this->gameStartTime;
        $this->questionsAnswered = count($this->results);
        $this->phase = 'summary';

        // Unanswered questions (game timer cut session short) count as wrong in DB totals
        $skipped = $this->questionCount - $this->questionsAnswered;
        $totalWrong = $this->wrongCount + max(0, $skipped);

        if ($this->sessionId) {
            GameSession::where('id', $this->sessionId)->update([
                'correct_answers' => $this->correctCount,
                'wrong_answers' => $totalWrong,
                'time_taken_seconds' => $this->totalTimeSeconds,
                'completed_at' => now(),
            ]);
        }

        // Check whether the user has any tricky tables (just a boolean flag for the nudge in summary)
        if (auth()->check()) {
            $hasTricky = GameAnswer::query()
                ->join('game_sessions', 'game_answers.game_session_id', '=', 'game_sessions.id')
                ->where('game_sessions.user_id', auth()->id())
                ->where('game_sessions.game_type', 'multiplication')
                ->whereNotNull('game_answers.num1')
                ->where('game_answers.is_correct', false)
                ->exists();
            $this->difficultSequences = $hasTricky ? ['has_tricky'] : [];
        }
    }

    public function submitAndNext(mixed $answer = null): void
    {
        $this->submitAnswer($answer);
        $this->advanceQuestion();
    }

    public function toggleExamMode(): void
    {
        $this->examMode = !$this->examMode;
    }

    public function resetGame(): void
    {
        $this->phase = 'setup';
        $this->sessionId = null;
        $this->results = [];
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->gameTimeLeft = 0;
        $this->questionsAnswered = 0;
        $this->endedByGameTimer = false;
        $this->difficultSequences = [];
    }

    public function render()
    {
        return view('livewire.multiplication-game');
    }
}
