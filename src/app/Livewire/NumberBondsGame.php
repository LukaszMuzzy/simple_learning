<?php

namespace App\Livewire;

use App\Models\GameAnswer;
use App\Models\GameSession;
use Livewire\Component;

class NumberBondsGame extends Component
{
    // Setup phase
    public string $phase = 'setup'; // setup, playing, summary

    // Settings
    public int $totalMax = 10;           // maximum value for the total (top number)
    public int $questionCount = 10;
    public int $timePerQuestion = 0;     // 0 = no limit
    public string $missingPosition = 'random'; // random, top, parts
    public string $answerMode = 'type';  // type, multiple_choice

    // Playing phase
    public int $currentQuestion = 0;
    public int $bondTotal = 0;
    public int $partA = 0;
    public int $partB = 0;
    public string $missingSlot = 'top'; // top, left, right
    public int $correctAnswer = 0;
    public string $userAnswer = '';
    public array $choices = [];
    public ?string $feedback = null;
    public bool $answered = false;
    public int $timeLeft = 0;
    public int $questionStartTime = 0;

    // Results tracking
    public array $results = [];
    public int $correctCount = 0;
    public int $wrongCount = 0;
    public ?int $sessionId = null;

    // Summary
    public int $totalTimeSeconds = 0;
    public int $gameStartTime = 0;

    public function mount(): void {}

    public function startGame(): void
    {
        $this->phase = 'playing';
        $this->currentQuestion = 0;
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->results = [];
        $this->gameStartTime = time();

        if (auth()->check()) {
            $session = GameSession::create([
                'user_id'         => auth()->id(),
                'game_type'       => 'number_bonds',
                'settings'        => [
                    'total_max'        => $this->totalMax,
                    'missing_position' => $this->missingPosition,
                    'answer_mode'      => $this->answerMode,
                    'time_per_question'=> $this->timePerQuestion,
                ],
                'total_questions' => $this->questionCount,
            ]);
            $this->sessionId = $session->id;
        }

        $this->generateQuestion();
    }

    public function generateQuestion(): void
    {
        $this->userAnswer = '';
        $this->feedback   = null;
        $this->answered   = false;
        $this->questionStartTime = time();

        // Generate two parts that sum to something <= totalMax
        $this->partA      = rand(0, $this->totalMax);
        $this->partB      = rand(0, $this->totalMax - $this->partA);
        $this->bondTotal  = $this->partA + $this->partB;

        // Decide which slot is missing
        $this->missingSlot = match ($this->missingPosition) {
            'top'   => 'top',
            'parts' => (rand(0, 1) ? 'left' : 'right'),
            default => ['top', 'left', 'right'][rand(0, 2)],
        };

        $this->correctAnswer = match ($this->missingSlot) {
            'top'   => $this->bondTotal,
            'left'  => $this->partA,
            'right' => $this->partB,
        };

        if ($this->answerMode === 'multiple_choice') {
            $this->generateChoices();
        }

        $this->timeLeft = $this->timePerQuestion;
    }

    private function generateChoices(): void
    {
        $choices = [$this->correctAnswer];
        $range   = max(5, (int) ($this->totalMax * 0.4) + 2);

        while (count($choices) < 4) {
            $wrong = $this->correctAnswer + rand(-$range, $range);
            if ($wrong >= 0 && $wrong <= $this->totalMax && $wrong !== $this->correctAnswer && !in_array($wrong, $choices)) {
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

        $hasAnswer    = $this->userAnswer !== '';
        $userInt      = $hasAnswer ? (int) $this->userAnswer : null;
        $isCorrect    = $hasAnswer && $userInt === $this->correctAnswer;

        $this->answered = true;
        $this->feedback = $isCorrect ? 'correct' : 'incorrect';

        if ($isCorrect) {
            $this->correctCount++;
        } else {
            $this->wrongCount++;
        }

        $questionText = match ($this->missingSlot) {
            'top'   => "? = {$this->partA} + {$this->partB}",
            'left'  => "{$this->bondTotal} = ? + {$this->partB}",
            'right' => "{$this->bondTotal} = {$this->partA} + ?",
        };

        $this->results[] = [
            'question'       => $questionText,
            'correct_answer' => $this->correctAnswer,
            'user_answer'    => $userInt,
            'is_correct'     => $isCorrect,
            'time_taken'     => $timeTaken,
        ];

        if ($this->sessionId) {
            GameAnswer::create([
                'game_session_id'   => $this->sessionId,
                'question'          => $questionText,
                'correct_answer'    => $this->correctAnswer,
                'user_answer'       => $userInt,
                'is_correct'        => $isCorrect,
                'time_taken_seconds'=> $timeTaken,
            ]);
        }
    }

    public function tick(): void
    {
        if ($this->phase !== 'playing' || $this->timePerQuestion === 0) {
            return;
        }

        $this->timeLeft = max(0, $this->timeLeft - 1);

        if ($this->timeLeft <= 0) {
            if (!$this->answered) {
                $this->submitAnswer();
            }
            $this->advanceQuestion();
        }
    }

    public function submitAndNext(mixed $answer = null): void
    {
        $this->submitAnswer($answer);
        $this->advanceQuestion();
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
        $this->phase = 'summary';

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
        $this->phase     = 'setup';
        $this->sessionId = null;
        $this->results   = [];
        $this->correctCount = 0;
        $this->wrongCount   = 0;
    }

    public function render()
    {
        return view('livewire.number-bonds-game');
    }
}
