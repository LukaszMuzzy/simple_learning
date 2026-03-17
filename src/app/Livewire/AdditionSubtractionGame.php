<?php

namespace App\Livewire;

use App\Models\GameAnswer;
use App\Models\GameSession;
use Livewire\Component;

class AdditionSubtractionGame extends Component
{
    // Setup phase
    public string $phase = 'setup'; // setup, playing, summary

    // Settings
    public string $operation = 'mix'; // add, subtract, mix
    public int $questionCount = 10;
    public int $timePerQuestion = 0; // 0 = no limit
    public int $maxDigits = 2;
    public bool $allowNegative = false;
    public string $answerMode = 'type'; // type, multiple_choice

    // Playing phase
    public int $currentQuestion = 0;
    public int $num1 = 0;
    public int $num2 = 0;
    public string $currentOperation = '+';
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

    public function mount(): void
    {
        // Component initialized in setup phase
    }

    public function startGame(): void
    {
        $this->phase = 'playing';
        $this->currentQuestion = 0;
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->results = [];
        $this->gameStartTime = time();

        // Create session if user is logged in
        if (auth()->check()) {
            $session = GameSession::create([
                'user_id' => auth()->id(),
                'game_type' => 'addition_subtraction',
                'settings' => [
                    'operation' => $this->operation,
                    'max_digits' => $this->maxDigits,
                    'allow_negative' => $this->allowNegative,
                    'answer_mode' => $this->answerMode,
                    'time_per_question' => $this->timePerQuestion,
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
        $this->feedback = null;
        $this->answered = false;
        $this->questionStartTime = time();

        $max = (int) pow(10, $this->maxDigits) - 1;
        $min = $this->maxDigits === 1 ? 0 : (int) pow(10, $this->maxDigits - 1);

        if ($this->operation === 'mix') {
            $this->currentOperation = rand(0, 1) ? '+' : '-';
        } else {
            $this->currentOperation = $this->operation === 'add' ? '+' : '-';
        }

        if ($this->currentOperation === '+') {
            // Addition: result must stay within [min, max].
            // num1 in [min, max-min] guarantees there is always room for num2 >= min.
            $this->num1 = rand($min, $max - $min);
            $this->num2 = rand($min, $max - $this->num1);
        } else {
            // Subtraction
            if ($this->allowNegative) {
                // Both numbers free in [min, max]; result stays within [-max+min, max-min].
                $this->num1 = rand($min, $max);
                $this->num2 = rand($min, $max);
            } else {
                // Result must be >= min (keeps it within the chosen digit size).
                // num1 must be at least 2*min so num2 can be in [min, num1-min].
                $num1Min = $min === 0 ? 0 : $min * 2;
                // Guard: if 2*min > max (shouldn't happen for sane digit counts), fall back.
                if ($num1Min > $max) {
                    $num1Min = $max;
                }
                $this->num1 = rand($num1Min, $max);
                $num2Max    = $this->num1 - $min;
                $this->num2 = rand($min, $num2Max);
            }
        }

        $this->correctAnswer = $this->currentOperation === '+'
            ? $this->num1 + $this->num2
            : $this->num1 - $this->num2;

        if ($this->answerMode === 'multiple_choice') {
            $this->generateChoices();
        }

        $this->timeLeft = $this->timePerQuestion;
    }

    private function generateChoices(): void
    {
        $choices = [$this->correctAnswer];
        $range = max(10, (int) abs($this->correctAnswer * 0.3) + 5);

        while (count($choices) < 4) {
            $wrong = $this->correctAnswer + rand(-$range, $range);
            if ($wrong !== $this->correctAnswer && !in_array($wrong, $choices)) {
                if ($this->allowNegative || $wrong >= 0) {
                    $choices[] = $wrong;
                }
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

        $questionText = "{$this->num1} {$this->currentOperation} {$this->num2}";

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
                'correct_answer' => $this->correctAnswer,
                'user_answer' => $userAnswerInt,
                'is_correct' => $isCorrect,
                'time_taken_seconds' => $timeTaken,
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
                'correct_answers' => $this->correctCount,
                'wrong_answers' => $this->wrongCount,
                'time_taken_seconds' => $this->totalTimeSeconds,
                'completed_at' => now(),
            ]);
        }
    }

    public function resetGame(): void
    {
        $this->phase = 'setup';
        $this->sessionId = null;
        $this->results = [];
        $this->correctCount = 0;
        $this->wrongCount = 0;
    }

    public function render()
    {
        return view('livewire.addition-subtraction-game');
    }
}
