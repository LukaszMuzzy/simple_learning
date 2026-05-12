<?php

namespace App\Livewire;

use App\Models\GameSession;
use Livewire\Component;

class TimeGame extends Component
{
    public string $phase = 'setup'; // setup | playing | feedback | summary

    // Settings
    public int $questionCount = 10;
    public int $customQuestionCount = 10;
    public int $timePerQuestion = 0;
    public array $selectedPrecisions = ['hour', 'half', 'quarter'];
    public array $selectedModes = ['digital_to_analog', 'text_to_analog', 'analog_to_digital', 'analog_to_text', 'voice_to_analog'];

    // Playing
    public int $currentIndex = 0;
    public array $questions = [];
    public array $currentQuestion = [];
    public int $questionStartTime = 0;
    public bool $answered = false;
    public bool $lastCorrect = false;
    public ?string $feedback = null;
    public int $timeLeft = 0;

    // User answers
    public string $digitalHours = '';
    public string $digitalMinutes = '';
    public string $textInput = '';
    public int $clockHours = 12;
    public int $clockMinutes = 0;

    // Results
    public array $results = [];
    public int $correctCount = 0;
    public int $wrongCount = 0;
    public int $totalTimeSeconds = 0;
    public int $gameStartTime = 0;
    public ?int $sessionId = null;

    public function mount(): void
    {
        $q = request()->query();

        if (!empty($q['questions'])) {
            $n = max(1, min(100, (int) $q['questions']));
            $this->questionCount = $this->customQuestionCount = $n;
        }

        if (isset($q['tpq'])) {
            $this->timePerQuestion = max(0, (int) $q['tpq']);
        }

        if (!empty($q['precisions'])) {
            $allowed = ['hour', 'half', 'quarter', 'twenty', 'ten', 'five', 'minute'];
            $vals = array_filter(
                explode(',', $q['precisions']),
                fn ($p) => in_array($p, $allowed, true)
            );
            if (!empty($vals)) {
                $this->selectedPrecisions = array_values($vals);
            }
        }

        if (!empty($q['modes'])) {
            $allowed = ['digital_to_analog', 'text_to_analog', 'analog_to_digital', 'analog_to_text', 'voice_to_analog'];
            $vals = array_filter(
                explode(',', $q['modes']),
                fn ($m) => in_array($m, $allowed, true)
            );
            if (!empty($vals)) {
                $this->selectedModes = array_values($vals);
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
        $n = max(1, min(100, (int) $this->customQuestionCount));
        $this->questionCount = $n;
        $this->customQuestionCount = $n;
    }

    public function togglePrecision(string $precision): void
    {
        if (in_array($precision, $this->selectedPrecisions, true)) {
            $this->selectedPrecisions = array_values(array_filter(
                $this->selectedPrecisions,
                fn ($p) => $p !== $precision
            ));
        } else {
            $this->selectedPrecisions[] = $precision;
        }

        if (empty($this->selectedPrecisions)) {
            $this->selectedPrecisions = ['hour'];
        }
    }

    public function toggleMode(string $mode): void
    {
        if (in_array($mode, $this->selectedModes, true)) {
            $this->selectedModes = array_values(array_filter(
                $this->selectedModes,
                fn ($m) => $m !== $mode
            ));
        } else {
            $this->selectedModes[] = $mode;
        }

        if (empty($this->selectedModes)) {
            $this->selectedModes = ['digital_to_analog'];
        }
    }

    public function startGame(): void
    {
        $this->phase = 'playing';
        $this->currentIndex = 0;
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->results = [];
        $this->gameStartTime = time();
        $this->questions = [];

        for ($i = 0; $i < $this->questionCount; $i++) {
            $this->questions[] = $this->buildQuestion();
        }

        if (auth()->check()) {
            $session = GameSession::create([
                'user_id' => auth()->id(),
                'game_type' => 'time_telling',
                'settings' => [
                    'question_count' => $this->questionCount,
                    'time_per_question' => $this->timePerQuestion,
                    'precisions' => $this->selectedPrecisions,
                    'modes' => $this->selectedModes,
                ],
                'total_questions' => $this->questionCount,
            ]);
            $this->sessionId = $session->id;
        }

        $this->loadCurrentQuestion();
        $this->dispatch('scroll-to-top');
    }

    private function buildQuestion(): array
    {
        $mode = $this->selectedModes[array_rand($this->selectedModes)];
        [$h, $m] = $this->generateTimeByPrecision();
        $digital = $this->formatDigital($h, $m);
        $words = $this->timeToWords($h, $m);

        return [
            'mode' => $mode,
            'hour' => $h,
            'minute' => $m,
            'digital' => $digital,
            'words' => $words,
            'text_options' => $mode === 'analog_to_text' ? $this->buildTextOptions($h, $m, $words) : [],
        ];
    }

    private function loadCurrentQuestion(): void
    {
        $this->currentQuestion = $this->questions[$this->currentIndex];
        $this->digitalHours = '';
        $this->digitalMinutes = '';
        $this->textInput = '';
        $this->clockHours = 12;
        $this->clockMinutes = 0;
        $this->answered = false;
        $this->lastCorrect = false;
        $this->feedback = null;
        $this->questionStartTime = time();
        $this->timeLeft = $this->timePerQuestion;
    }

    public function setClockAnswer(int $hours, int $minutes): void
    {
        $this->clockHours = (($hours % 12) + 12) % 12;
        if ($this->clockHours === 0) {
            $this->clockHours = 12;
        }
        $this->clockMinutes = (($minutes % 60) + 60) % 60;
    }

    public function submitAnswer(?string $option = null): void
    {
        if ($this->answered) {
            return;
        }

        if ($option !== null) {
            $this->textInput = $option;
        }

        $question = $this->currentQuestion;
        $mode = $question['mode'];
        $targetHour = $question['hour'];
        $targetMinute = $question['minute'];
        $timeTaken = time() - $this->questionStartTime;
        $isCorrect = false;
        $answerLabel = 'No answer';

        if (in_array($mode, ['digital_to_analog', 'text_to_analog', 'voice_to_analog'], true)) {
            $answerLabel = $this->formatDigital($this->clockHours, $this->clockMinutes);
            $distance = $this->minutesDistance($targetHour, $targetMinute, $this->clockHours, $this->clockMinutes);
            $isCorrect = $distance <= 2;
        } elseif ($mode === 'analog_to_digital') {
            $h = (int) $this->digitalHours;
            $m = (int) $this->digitalMinutes;
            $answerLabel = $this->digitalHours !== '' || $this->digitalMinutes !== ''
                ? $this->formatDigital($h, $m)
                : 'No answer';
            $ok = $this->digitalHours !== '' && $this->digitalMinutes !== ''
                && $h >= 1 && $h <= 12 && $m >= 0 && $m <= 59;
            $isCorrect = $ok && $h === $targetHour && $m === $targetMinute;
        } elseif ($mode === 'analog_to_text') {
            $answerLabel = $this->textInput !== '' ? $this->textInput : 'No answer';
            $isCorrect = $this->normalizeText($this->textInput) === $this->normalizeText($question['words']);
        }

        $this->answered = true;
        $this->lastCorrect = $isCorrect;
        $this->feedback = $isCorrect ? 'correct' : 'incorrect';

        if ($isCorrect) {
            $this->correctCount++;
        } else {
            $this->wrongCount++;
        }

        $this->results[] = [
            'mode' => $mode,
            'target_digital' => $question['digital'],
            'target_words' => $question['words'],
            'user_answer' => $answerLabel,
            'is_correct' => $isCorrect,
            'time_taken' => $timeTaken,
        ];

        $this->phase = 'feedback';
    }

    public function proceedToNext(): void
    {
        if (!$this->answered) {
            return;
        }

        $this->currentIndex++;
        if ($this->currentIndex >= count($this->questions)) {
            $this->finishGame();
            return;
        }

        $this->phase = 'playing';
        $this->loadCurrentQuestion();
    }

    public function tick(): void
    {
        if ($this->phase !== 'playing' || $this->timePerQuestion <= 0 || $this->answered) {
            return;
        }

        $this->timeLeft = max(0, $this->timeLeft - 1);
        if ($this->timeLeft === 0) {
            $this->submitAnswer();
        }
    }

    public function resetGame(): void
    {
        $this->phase = 'setup';
        $this->sessionId = null;
        $this->results = [];
        $this->correctCount = 0;
        $this->wrongCount = 0;
        $this->questions = [];
        $this->currentQuestion = [];
        $this->answered = false;
        $this->feedback = null;
    }

    private function finishGame(): void
    {
        $this->phase = 'summary';
        $this->totalTimeSeconds = time() - $this->gameStartTime;

        if ($this->sessionId) {
            GameSession::where('id', $this->sessionId)->update([
                'correct_answers' => $this->correctCount,
                'wrong_answers' => $this->wrongCount,
                'time_taken_seconds' => $this->totalTimeSeconds,
                'completed_at' => now(),
            ]);
        }
    }

    private function generateTimeByPrecision(): array
    {
        $precision = $this->selectedPrecisions[array_rand($this->selectedPrecisions)];
        $hour = rand(1, 12);

        $minutePool = match ($precision) {
            'hour' => [0],
            'half' => [30],
            'quarter' => [15, 45],
            'twenty' => [0, 20, 40],
            'ten' => [0, 10, 20, 30, 40, 50],
            'five' => [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55],
            default => range(0, 59),
        };

        $minute = $minutePool[array_rand($minutePool)];
        return [$hour, $minute];
    }

    private function buildTextOptions(int $targetHour, int $targetMinute, string $targetWords): array
    {
        $options = [$targetWords];
        $attempts = 0;

        while (count($options) < 4 && $attempts < 40) {
            [$h, $m] = $this->generateTimeByPrecision();
            if ($h === $targetHour && $m === $targetMinute) {
                $attempts++;
                continue;
            }
            $candidate = $this->timeToWords($h, $m);
            if (!in_array($candidate, $options, true)) {
                $options[] = $candidate;
            }
            $attempts++;
        }

        shuffle($options);
        return $options;
    }

    private function parseDigitalInput(string $input): array
    {
        $trimmed = trim($input);
        if (!preg_match('/^(\d{1,2})\s*:\s*(\d{2})$/', $trimmed, $m)) {
            return [false, 0, 0];
        }

        $h = (int) $m[1];
        $min = (int) $m[2];
        if ($h < 1 || $h > 12 || $min < 0 || $min > 59) {
            return [false, 0, 0];
        }

        return [true, $h, $min];
    }

    private function formatDigital(int $hour, int $minute): string
    {
        return sprintf('%d:%02d', $hour, $minute);
    }

    private function minutesDistance(int $h1, int $m1, int $h2, int $m2): int
    {
        $a = (($h1 % 12) * 60) + $m1;
        $b = (($h2 % 12) * 60) + $m2;
        $diff = abs($a - $b);
        return min($diff, 720 - $diff);
    }

    private function normalizeText(string $text): string
    {
        $value = strtolower(trim($text));
        $value = str_replace("'", '', $value);
        $value = preg_replace('/\s+/', ' ', $value ?? '');
        return $value ?? '';
    }

    private function hourWord(int $hour): string
    {
        $words = [
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
        ];

        return $words[$hour] ?? 'one';
    }

    private function minuteWord(int $minutes): string
    {
        $map = [
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            21 => 'twenty one',
            22 => 'twenty two',
            23 => 'twenty three',
            24 => 'twenty four',
            25 => 'twenty five',
            26 => 'twenty six',
            27 => 'twenty seven',
            28 => 'twenty eight',
            29 => 'twenty nine',
        ];

        return $map[$minutes] ?? (string) $minutes;
    }

    public function timeToWords(int $hour, int $minute): string
    {
        if ($minute === 0) {
            return $this->hourWord($hour) . " o'clock";
        }

        if ($minute === 15) {
            return 'quarter past ' . $this->hourWord($hour);
        }

        if ($minute === 30) {
            return 'half past ' . $this->hourWord($hour);
        }

        if ($minute === 45) {
            $nextHour = $hour === 12 ? 1 : $hour + 1;
            return 'quarter to ' . $this->hourWord($nextHour);
        }

        if ($minute < 30) {
            return $this->minuteWord($minute) . ' past ' . $this->hourWord($hour);
        }

        $to = 60 - $minute;
        $nextHour = $hour === 12 ? 1 : $hour + 1;
        return $this->minuteWord($to) . ' to ' . $this->hourWord($nextHour);
    }

    public function modeLabel(string $mode): string
    {
        return match ($mode) {
            'digital_to_analog' => 'Digital to Analog',
            'text_to_analog' => 'Text to Analog',
            'analog_to_digital' => 'Analog to Digital',
            'analog_to_text' => 'Analog to Text',
            'voice_to_analog' => 'Voice to Analog',
            default => 'Time Telling',
        };
    }

    public function precisionLabel(string $precision): string
    {
        return match ($precision) {
            'hour' => 'Full Hours',
            'half' => 'Half Hours',
            'quarter' => 'Quarters',
            'twenty' => '20 Minutes',
            'ten' => '10 Minutes',
            'five' => '5 Minutes',
            'minute' => 'Every Minute',
            default => $precision,
        };
    }

    public function render()
    {
        return view('livewire.time-game');
    }
}
