<?php

namespace App\Livewire\Admin;

use App\Data\WordDefinitions;
use App\Data\WordLists;
use Livewire\Component;

class GameLinks extends Component
{
    public string $selectedGame = 'multiplication';

    // ── Multiplication settings ──────────────────────────────────────────────
    public int    $mult_questionCount  = 10;
    public int    $mult_timePerQuestion = 0;
    public int    $mult_timePerGame    = 0;
    public string $mult_answerMode     = 'type';
    public bool   $mult_examMode       = false;
    public array  $mult_selectedNumbers = [];

    // ── Addition / Subtraction settings ─────────────────────────────────────
    public string $add_operation       = 'mix';
    public int    $add_questionCount   = 10;
    public int    $add_timePerQuestion = 0;
    public int    $add_maxDigits       = 2;
    public bool   $add_allowNegative   = false;
    public string $add_answerMode      = 'type';

    // ── Number Bonds settings ────────────────────────────────────────────────
    public int    $nb_totalMax         = 10;
    public int    $nb_questionCount    = 10;
    public int    $nb_timePerQuestion  = 0;
    public string $nb_missingPosition  = 'random';
    public string $nb_answerMode       = 'type';

    // ── Time Telling settings ────────────────────────────────────────────────
    public int    $time_questionCount    = 10;
    public int    $time_timePerQuestion  = 0;
    public array  $time_selectedPrecisions = ['hour', 'half', 'quarter'];
    public array  $time_selectedModes    = ['digital_to_analog', 'text_to_analog', 'analog_to_digital', 'analog_to_text', 'voice_to_analog'];

    // ── Spelling settings ────────────────────────────────────────────────────
    public string $spell_wordListKey   = 'year1';
    public int    $spell_questionCount = 10;
    public int    $spell_displayTime   = 4;
    public int    $spell_timePerAnswer = 0;
    public string $spell_hintType      = 'blanks';
    public bool   $spell_examMode      = false;

    // ── Anagram settings ─────────────────────────────────────────────────────
    public string $ana_wordListKey   = 'year1';
    public int    $ana_questionCount = 10;
    public int    $ana_timePerWord   = 0;

    // ── Word Definitions settings ────────────────────────────────────────────
    public string $def_wordSet       = 'difficulty:all';
    public int    $def_questionCount = 10;

    // ────────────────────────────────────────────────────────────────────────

    public function toggleMultNumber(int $n): void
    {
        if (in_array($n, $this->mult_selectedNumbers)) {
            $this->mult_selectedNumbers = array_values(array_filter(
                $this->mult_selectedNumbers,
                fn ($v) => $v !== $n
            ));
        } else {
            $this->mult_selectedNumbers[] = $n;
            sort($this->mult_selectedNumbers);
        }
    }

    public function toggleTimePrecision(string $precision): void
    {
        if (in_array($precision, $this->time_selectedPrecisions, true)) {
            $new = array_values(array_filter($this->time_selectedPrecisions, fn ($p) => $p !== $precision));
            $this->time_selectedPrecisions = empty($new) ? ['hour'] : $new;
        } else {
            $this->time_selectedPrecisions[] = $precision;
        }
    }

    public function toggleTimeMode(string $mode): void
    {
        if (in_array($mode, $this->time_selectedModes, true)) {
            $new = array_values(array_filter($this->time_selectedModes, fn ($m) => $m !== $mode));
            $this->time_selectedModes = empty($new) ? ['digital_to_analog'] : $new;
        } else {
            $this->time_selectedModes[] = $mode;
        }
    }

    public function getShareableUrl(): string
    {
        return match ($this->selectedGame) {
            'multiplication'    => $this->multiplicationUrl(),
            'addition'          => $this->additionUrl(),
            'number-bonds'      => $this->numberBondsUrl(),
            'time'              => $this->timeUrl(),
            'spelling'          => $this->spellingUrl(),
            'anagram'           => $this->anagramUrl(),
            'word-definitions'  => $this->wordDefinitionsUrl(),
            default             => url('/'),
        };
    }

    private function multiplicationUrl(): string
    {
        $params = [
            'questions'   => $this->mult_questionCount,
            'tpq'         => $this->mult_timePerQuestion,
            'tpg'         => $this->mult_timePerGame,
            'mode'        => $this->mult_answerMode,
            'exam'        => $this->mult_examMode ? 1 : 0,
        ];
        if (!empty($this->mult_selectedNumbers)) {
            $params['focus'] = implode(',', $this->mult_selectedNumbers);
        }
        return route('math.multiplication') . '?' . http_build_query($params);
    }

    private function additionUrl(): string
    {
        $params = [
            'op'        => $this->add_operation,
            'questions' => $this->add_questionCount,
            'tpq'       => $this->add_timePerQuestion,
            'digits'    => $this->add_maxDigits,
            'neg'       => $this->add_allowNegative ? 1 : 0,
            'mode'      => $this->add_answerMode,
        ];
        return route('math.addition-subtraction') . '?' . http_build_query($params);
    }

    private function numberBondsUrl(): string
    {
        $params = [
            'max'       => $this->nb_totalMax,
            'questions' => $this->nb_questionCount,
            'tpq'       => $this->nb_timePerQuestion,
            'missing'   => $this->nb_missingPosition,
            'mode'      => $this->nb_answerMode,
        ];
        return route('math.number-bonds') . '?' . http_build_query($params);
    }

    private function timeUrl(): string
    {
        $params = [
            'questions'   => $this->time_questionCount,
            'tpq'         => $this->time_timePerQuestion,
            'precisions'  => implode(',', $this->time_selectedPrecisions),
            'modes'       => implode(',', $this->time_selectedModes),
        ];
        return route('time.game') . '?' . http_build_query($params);
    }

    private function spellingUrl(): string
    {
        $params = [
            'list'        => $this->spell_wordListKey,
            'questions'   => $this->spell_questionCount,
            'display'     => $this->spell_displayTime,
            'tpa'         => $this->spell_timePerAnswer,
            'hint'        => $this->spell_hintType,
            'exam'        => $this->spell_examMode ? 1 : 0,
        ];
        return route('english.spelling') . '?' . http_build_query($params);
    }

    private function anagramUrl(): string
    {
        $params = [
            'list'      => $this->ana_wordListKey,
            'questions' => $this->ana_questionCount,
            'tpw'       => $this->ana_timePerWord,
        ];
        return route('english.anagram') . '?' . http_build_query($params);
    }

    private function wordDefinitionsUrl(): string
    {
        $params = [
            'set'       => $this->def_wordSet,
            'questions' => $this->def_questionCount,
        ];
        return route('english.word-definitions') . '?' . http_build_query($params);
    }

    public function getGameLabel(): string
    {
        return match ($this->selectedGame) {
            'multiplication'   => '× Multiplication',
            'addition'         => '± Addition & Subtraction',
            'number-bonds'     => '○ Number Bonds',
            'time'             => '🕐 Time Telling',
            'spelling'         => '📝 Spelling',
            'anagram'          => '🔀 Anagram',
            'word-definitions' => '📖 Word Definitions',
            default            => 'Learning Game',
        };
    }

    public function getGameDescription(): string
    {
        return match ($this->selectedGame) {
            'multiplication'   => $this->multiplicationDescription(),
            'addition'         => $this->additionDescription(),
            'number-bonds'     => $this->numberBondsDescription(),
            'time'             => $this->timeDescription(),
            'spelling'         => $this->spellingDescription(),
            'anagram'          => $this->anagramDescription(),
            'word-definitions' => $this->wordDefinitionsDescription(),
            default            => '',
        };
    }

    private function multiplicationDescription(): string
    {
        $parts = [];
        $parts[] = $this->mult_questionCount . ' questions';
        if (!empty($this->mult_selectedNumbers)) {
            $parts[] = 'Tables: ' . implode(', ', $this->mult_selectedNumbers);
        } else {
            $parts[] = 'All tables (0–12)';
        }
        $parts[] = $this->mult_answerMode === 'multiple_choice' ? 'Multiple choice' : 'Type answer';
        if ($this->mult_timePerQuestion > 0) $parts[] = $this->mult_timePerQuestion . 's per question';
        if ($this->mult_timePerGame > 0) {
            $m = intdiv($this->mult_timePerGame, 60); $s = $this->mult_timePerGame % 60;
            $parts[] = 'Game timer: ' . ($m > 0 ? "{$m}m" : '') . ($s > 0 ? "{$s}s" : '');
        }
        if ($this->mult_examMode) $parts[] = 'Exam mode';
        return implode(' · ', $parts);
    }

    private function additionDescription(): string
    {
        $op = ['mix' => 'Addition & Subtraction', 'add' => 'Addition only', 'subtract' => 'Subtraction only'][$this->add_operation] ?? '';
        $parts = [$op, $this->add_questionCount . ' questions', $this->add_maxDigits . '-digit numbers'];
        if ($this->add_allowNegative) $parts[] = 'Negatives allowed';
        $parts[] = $this->add_answerMode === 'multiple_choice' ? 'Multiple choice' : 'Type answer';
        if ($this->add_timePerQuestion > 0) $parts[] = $this->add_timePerQuestion . 's per question';
        return implode(' · ', $parts);
    }

    private function numberBondsDescription(): string
    {
        $pos = ['random' => 'Random position', 'top' => 'Missing total', 'parts' => 'Missing parts'][$this->nb_missingPosition] ?? '';
        $parts = [$this->nb_questionCount . ' questions', 'Max ' . $this->nb_totalMax, $pos];
        $parts[] = $this->nb_answerMode === 'multiple_choice' ? 'Multiple choice' : 'Type answer';
        if ($this->nb_timePerQuestion > 0) $parts[] = $this->nb_timePerQuestion . 's per question';
        return implode(' · ', $parts);
    }

    private function timeDescription(): string
    {
        $precisionLabels = ['hour' => 'Full Hours', 'half' => 'Half Hours', 'quarter' => 'Quarters',
            'twenty' => '20 min', 'ten' => '10 min', 'five' => '5 min', 'minute' => 'Every minute'];
        $parts = [$this->time_questionCount . ' questions'];
        $prec = array_map(fn($p) => $precisionLabels[$p] ?? $p, $this->time_selectedPrecisions);
        $parts[] = implode(', ', $prec);
        if ($this->time_timePerQuestion > 0) $parts[] = $this->time_timePerQuestion . 's per question';
        return implode(' · ', $parts);
    }

    private function spellingDescription(): string
    {
        $lists  = WordLists::labels();
        $label  = $lists[$this->spell_wordListKey] ?? $this->spell_wordListKey;
        $hints  = ['none' => 'No hint', 'blanks' => 'Blank lines', 'puzzle' => 'Puzzle letters'][$this->spell_hintType] ?? '';
        $parts  = [$label, $this->spell_questionCount . ' words', $hints];
        if ($this->spell_displayTime === 0) $parts[] = 'Manual hide';
        else $parts[] = 'Show ' . $this->spell_displayTime . 's';
        if ($this->spell_examMode) $parts[] = 'Exam mode';
        return implode(' · ', $parts);
    }

    private function anagramDescription(): string
    {
        $lists = WordLists::labels();
        $label = $lists[$this->ana_wordListKey] ?? $this->ana_wordListKey;
        $parts = [$label, $this->ana_questionCount . ' words'];
        if ($this->ana_timePerWord > 0) $parts[] = $this->ana_timePerWord . 's per word';
        return implode(' · ', $parts);
    }

    private function wordDefinitionsDescription(): string
    {
        $parts = [$this->def_questionCount . ' questions'];
        if (str_starts_with($this->def_wordSet, 'difficulty:')) {
            $diff = ucfirst(substr($this->def_wordSet, 11));
            $parts[] = $diff === 'All' ? 'All difficulties' : $diff . ' words';
        } else {
            $parts[] = 'Custom group';
        }
        return implode(' · ', $parts);
    }

    public function render()
    {
        return view('livewire.admin.game-links', [
            'shareableUrl'     => $this->getShareableUrl(),
            'gameLabel'        => $this->getGameLabel(),
            'gameDescription'  => $this->getGameDescription(),
            'wordListLabels'   => WordLists::labels(),
            'defSourceOptions' => WordDefinitions::sourceOptions(),
        ]);
    }
}
