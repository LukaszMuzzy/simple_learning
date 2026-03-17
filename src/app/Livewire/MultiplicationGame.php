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
    public int $timePerQuestion = 0; // 0 = no limit
    public string $answerMode = 'type'; // type, multiple_choice
    public bool $examMode = false; // when true, no per-question feedback; reveal all at end

    // Playing phase
    public int $currentQuestion = 0;
    public int $num1 = 0;
    public int $num2 = 0;
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
                'user_id' => auth()->id(),
                'game_type' => 'multiplication',
                'settings' => [
                    'answer_mode' => $this->answerMode,
                    'time_per_question' => $this->timePerQuestion,
                    'exam_mode' => $this->examMode,
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

        $this->num1 = rand(0, 12);
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
            // Auto-submit whatever answer the user has (or empty = wrong)
            if (!$this->answered) {
                $this->submitAnswer();
            }
            // Auto-advance regardless — timer governs the pace
            $this->advanceQuestion();
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
    }

    public function render()
    {
        return view('livewire.multiplication-game');
    }
}
