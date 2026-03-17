<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $lists = [
        [
            'slug'        => 'year1',
            'label'       => 'Year 1 — Starter (KS1)',
            'description' => 'UK National Curriculum Year 1 statutory words.',
            'sort_order'  => 1,
            'words'       => [
                'the', 'a', 'do', 'to', 'today', 'of', 'said', 'says', 'are',
                'were', 'was', 'is', 'his', 'has', 'I', 'you', 'your', 'they',
                'be', 'he', 'she', 'we', 'me', 'my', 'by', 'all', 'call',
                'ball', 'tall', 'fall', 'small', 'walk', 'talk', 'old', 'cold',
                'gold', 'hold', 'told', 'pull', 'full', 'push', 'could',
                'would', 'should', 'go', 'no', 'so', 'into', 'when', 'then',
                'them', 'with', 'see', 'for', 'now', 'down', 'look', 'come',
                'like', 'have', 'some', 'what', 'here', 'there', 'out', 'this',
            ],
        ],
        [
            'slug'        => 'year2',
            'label'       => 'Year 2 — Elementary (KS1)',
            'description' => 'UK National Curriculum Year 2 statutory words.',
            'sort_order'  => 2,
            'words'       => [
                'door', 'floor', 'poor', 'because', 'find', 'kind', 'mind',
                'behind', 'child', 'children', 'wild', 'climb', 'most', 'only',
                'both', 'every', 'great', 'break', 'steak', 'pretty',
                'beautiful', 'after', 'fast', 'last', 'past', 'father',
                'class', 'grass', 'pass', 'plant', 'path', 'bath', 'hour',
                'move', 'prove', 'improve', 'sure', 'sugar', 'eye', 'could',
                'any', 'many', 'clothes', 'busy', 'people', 'water', 'again',
                'half', 'money', 'parents', 'Christmas', 'different',
                'friend', 'school', 'where', 'whole', 'whose', 'thought',
                'through', 'enough', 'laugh', 'eight', 'once', 'always',
                'never', 'even',
            ],
        ],
        [
            'slug'        => 'year3_4',
            'label'       => 'Year 3 & 4 — Intermediate (KS2)',
            'description' => 'UK National Curriculum Year 3–4 statutory words.',
            'sort_order'  => 3,
            'words'       => [
                'accident', 'actually', 'address', 'answer', 'appear', 'arrive',
                'believe', 'bicycle', 'breath', 'breathe', 'build', 'busy',
                'calendar', 'caught', 'centre', 'century', 'certain', 'circle',
                'complete', 'consider', 'continue', 'decide', 'describe',
                'different', 'difficult', 'disappear', 'early', 'earth',
                'eight', 'enough', 'exercise', 'experience', 'experiment',
                'extreme', 'famous', 'favourite', 'February', 'forward',
                'fruit', 'grammar', 'group', 'guard', 'guide', 'heard',
                'heart', 'height', 'history', 'imagine', 'increase',
                'important', 'interest', 'island', 'knowledge', 'learn',
                'length', 'library', 'material', 'medicine', 'mention',
                'minute', 'natural', 'naughty', 'notice', 'occasion',
                'often', 'opposite', 'ordinary', 'particular', 'peculiar',
                'perhaps', 'popular', 'position', 'possess', 'potatoes',
                'pressure', 'probably', 'promise', 'purpose', 'quarter',
                'question', 'recent', 'regular', 'reign', 'remember',
                'sentence', 'separate', 'special', 'straight', 'strange',
                'strength', 'suppose', 'surprise', 'therefore', 'though',
                'thought', 'through', 'various', 'weight', 'woman', 'women',
            ],
        ],
        [
            'slug'        => 'year5_6',
            'label'       => 'Year 5 & 6 — Advanced (KS2)',
            'description' => 'UK National Curriculum Year 5–6 statutory words.',
            'sort_order'  => 4,
            'words'       => [
                'accommodate', 'accompany', 'aggressive', 'amateur', 'ancient',
                'apparent', 'appreciate', 'attached', 'available', 'average',
                'awkward', 'bargain', 'bruise', 'category', 'cemetery',
                'committee', 'communicate', 'community', 'competition',
                'conscience', 'conscious', 'controversy', 'convenience',
                'correspond', 'criticise', 'curiosity', 'definite',
                'desperate', 'determined', 'develop', 'dictionary',
                'disastrous', 'embarrass', 'environment', 'equip',
                'especially', 'exaggerate', 'excellent', 'existence',
                'explanation', 'familiar', 'foreign', 'frequently',
                'government', 'guarantee', 'harass', 'hindrance',
                'identity', 'immediate', 'individual', 'interfere',
                'interrupt', 'language', 'leisure', 'lightning',
                'marvellous', 'mischievous', 'muscle', 'necessary',
                'neighbour', 'nuisance', 'occupy', 'occurred', 'occurrence',
                'parliament', 'persuade', 'physical', 'prejudice',
                'privilege', 'profession', 'programme', 'pronunciation',
                'queue', 'recognise', 'recommend', 'relevant', 'restaurant',
                'rhyme', 'rhythm', 'sacrifice', 'secretary', 'shoulder',
                'signature', 'sincere', 'soldier', 'stomach', 'sufficient',
                'suggest', 'symbol', 'system', 'temperature', 'thorough',
                'twelfth', 'variety', 'vegetable', 'vehicle', 'yacht',
            ],
        ],
        [
            'slug'        => 'tricky',
            'label'       => 'Tricky Words',
            'description' => 'Common exception words that do not follow standard phonics rules.',
            'sort_order'  => 5,
            'words'       => [
                'said', 'have', 'like', 'some', 'come', 'love', 'were',
                'there', 'little', 'one', 'once', 'what', 'when',
                'which', 'where', 'who', 'why', 'how', 'their', 'people',
                'water', 'again', 'half', 'money', 'Mr', 'Mrs', 'looked',
                'called', 'asked', 'could', 'should', 'would', 'thought',
                'through', 'because', 'beautiful', 'pretty', 'friend',
                'school', 'whole', 'clothes', 'busy', 'parents', 'Christmas',
                'laugh', 'eight', 'enough', 'always', 'every', 'floor',
                'poor', 'door', 'sure', 'sugar', 'eye', 'climb', 'kind',
                'behind', 'find', 'mind', 'wild', 'child', 'children',
            ],
        ],
        [
            'slug'        => 'animals',
            'label'       => 'Animal Names',
            'description' => 'A fun list of animal names from simple to challenging.',
            'sort_order'  => 6,
            'words'       => [
                'cat', 'dog', 'fish', 'bird', 'frog', 'duck', 'bear',
                'deer', 'wolf', 'lion', 'tiger', 'horse', 'sheep', 'goat',
                'rabbit', 'hamster', 'parrot', 'turtle', 'monkey', 'donkey',
                'giraffe', 'penguin', 'dolphin', 'elephant', 'kangaroo',
                'crocodile', 'butterfly', 'hedgehog', 'squirrel', 'flamingo',
                'cheetah', 'leopard', 'rhinoceros', 'hippopotamus', 'gorilla',
                'chimpanzee', 'jellyfish', 'octopus', 'alligator', 'chameleon',
            ],
        ],
        [
            'slug'        => 'days_months',
            'label'       => 'Days & Months',
            'description' => 'Days of the week and months of the year.',
            'sort_order'  => 7,
            'words'       => [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                'Saturday', 'Sunday',
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December',
            ],
        ],
    ];

    public function up(): void
    {
        $now = now();

        foreach ($this->lists as $listData) {
            $words = $listData['words'];
            unset($listData['words']);

            // Insert the list if it doesn't exist yet; skip if already present
            DB::table('word_lists')->insertOrIgnore([
                ...$listData,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $listId = DB::table('word_lists')->where('slug', $listData['slug'])->value('id');

            if (!$listId) {
                continue;
            }

            // Insert words, ignoring duplicates
            $rows = array_map(fn ($w) => [
                'word_list_id' => $listId,
                'word'         => $w,
                'created_at'   => $now,
                'updated_at'   => $now,
            ], array_unique($words));

            DB::table('words')->insertOrIgnore($rows);
        }
    }

    public function down(): void
    {
        $slugs = array_column($this->lists, 'slug');

        $ids = DB::table('word_lists')->whereIn('slug', $slugs)->pluck('id');
        DB::table('words')->whereIn('word_list_id', $ids)->delete();
        DB::table('word_lists')->whereIn('slug', $slugs)->delete();
    }
};
