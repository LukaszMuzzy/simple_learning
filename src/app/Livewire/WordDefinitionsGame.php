<?php

namespace App\Livewire;

use App\Data\WordDefinitions;
use App\Models\GameSession;
use Livewire\Component;

class WordDefinitionsGame extends Component
{
    public string $phase = 'setup'; // setup | question | feedback | summary

    // ── Settings ────────────────────────────────────────────────────────────────
    public string $wordSet       = 'difficulty:all'; // 'difficulty:easy|medium|hard|all' or 'group:ID'
    public int    $questionCount = 10;

    // ── Playing ─────────────────────────────────────────────────────────────────
    public int   $currentIndex    = 0;
    public array $questions       = [];
    public array $currentQuestion = [];
    public int   $selectedOption  = -1;
    public bool  $answered        = false;
    public bool  $lastCorrect     = false;

    // ── Results ─────────────────────────────────────────────────────────────────
    public array $results           = [];
    public int   $correctCount      = 0;
    public int   $wrongCount        = 0;
    public int   $totalTimeSeconds  = 0;
    public int   $gameStartTime     = 0;
    public int   $questionStartTime = 0;
    public ?int  $sessionId         = null;

    // ────────────────────────────────────────────────────────────────────────────

    public function startGame(): void
    {
        $this->questions     = WordDefinitions::generateQuestions($this->wordSet, $this->questionCount);
        $this->questionCount = count($this->questions);
        $this->currentIndex  = 0;
        $this->correctCount  = 0;
        $this->wrongCount    = 0;
        $this->results       = [];
        $this->gameStartTime = time();

        if (auth()->check()) {
            $session = GameSession::create([
                'user_id'         => auth()->id(),
                'game_type'       => 'word_definitions',
                'settings'        => ['word_set' => $this->wordSet],
                'total_questions' => $this->questionCount,
            ]);
            $this->sessionId = $session->id;
        }

        $this->loadCurrentQuestion();
        $this->dispatch('scroll-to-top');
    }

    private function loadCurrentQuestion(): void
    {
        $this->currentQuestion   = $this->questions[$this->currentIndex];
        $this->selectedOption    = -1;
        $this->answered          = false;
        $this->lastCorrect       = false;
        $this->phase             = 'question';
        $this->questionStartTime = time();
    }

    public function selectAnswer(int $optionIndex): void
    {
        if ($this->answered) {
            return;
        }

        $this->selectedOption = $optionIndex;
        $this->answered       = true;
        $isCorrect            = $optionIndex === $this->currentQuestion['answer_idx'];
        $this->lastCorrect    = $isCorrect;

        if ($isCorrect) {
            $this->correctCount++;
        } else {
            $this->wrongCount++;
        }

        $this->results[] = [
            'word'         => $this->currentQuestion['word'],
            'definition'   => $this->currentQuestion['definition'],
            'options'      => $this->currentQuestion['options'],
            'correct_idx'  => $this->currentQuestion['answer_idx'],
            'selected_idx' => $optionIndex,
            'is_correct'   => $isCorrect,
            'time_taken'   => time() - $this->questionStartTime,
        ];

        $this->phase = 'feedback';
    }

    public function proceedToNext(): void
    {
        $this->currentIndex++;

        if ($this->currentIndex >= count($this->questions)) {
            $this->finishGame();
        } else {
            $this->loadCurrentQuestion();
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
        $this->questions    = [];
    }

    public function render()
    {
        return view('livewire.word-definitions-game', [
            'sourceOptions' => WordDefinitions::sourceOptions(),
            'maxCount'      => WordDefinitions::maxCount($this->wordSet),
        ]);
    }
}
