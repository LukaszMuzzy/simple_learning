<?php

namespace App\Data;

class WordLists
{
    public static function labels(): array
    {
        return [
            'year1'        => 'Year 1 — Starter (KS1)',
            'year2'        => 'Year 2 — Elementary (KS1)',
            'year3_4'      => 'Year 3 & 4 — Intermediate (KS2)',
            'year5_6'      => 'Year 5 & 6 — Advanced (KS2)',
            'tricky'       => 'Tricky Words',
            'animals'      => 'Animal Names',
            'days_months'  => 'Days & Months',
        ];
    }

    public static function get(string $key): array
    {
        return self::all()[$key] ?? [];
    }

    public static function all(): array
    {
        return [
            // UK National Curriculum Year 1 statutory words
            'year1' => [
                'the', 'a', 'do', 'to', 'today', 'of', 'said', 'says', 'are',
                'were', 'was', 'is', 'his', 'has', 'I', 'you', 'your', 'they',
                'be', 'he', 'she', 'we', 'me', 'my', 'by', 'all', 'call',
                'ball', 'tall', 'fall', 'small', 'walk', 'talk', 'old', 'cold',
                'gold', 'hold', 'told', 'pull', 'full', 'push', 'could',
                'would', 'should', 'go', 'no', 'so', 'into', 'when', 'then',
                'them', 'with', 'see', 'for', 'now', 'down', 'look', 'come',
                'like', 'have', 'some', 'what', 'here', 'there', 'out', 'this',
            ],

            // UK National Curriculum Year 2 statutory words
            'year2' => [
                'door', 'floor', 'poor', 'because', 'find', 'kind', 'mind',
                'behind', 'child', 'children', 'wild', 'climb', 'most', 'only',
                'both', 'every', 'great', 'break', 'steak', 'pretty',
                'beautiful', 'after', 'fast', 'last', 'past', 'father',
                'class', 'grass', 'pass', 'plant', 'path', 'bath', 'hour',
                'move', 'prove', 'improve', 'sure', 'sugar', 'eye', 'could',
                'any', 'many', 'clothes', 'busy', 'people', 'water', 'again',
                'half', 'money', 'parents', 'Christmas', 'because', 'different',
                'friend', 'school', 'where', 'whole', 'whose', 'thought',
                'through', 'enough', 'laugh', 'eight', 'once', 'always',
                'never', 'every', 'even', 'plant', 'kind', 'behind',
            ],

            // UK National Curriculum Year 3–4 statutory words
            'year3_4' => [
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

            // UK National Curriculum Year 5–6 statutory words
            'year5_6' => [
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

            // Common exception / tricky words
            'tricky' => [
                'said', 'have', 'like', 'some', 'come', 'love', 'were',
                'there', 'little', 'one', 'once', 'were', 'what', 'when',
                'which', 'where', 'who', 'why', 'how', 'their', 'people',
                'water', 'again', 'half', 'money', 'Mr', 'Mrs', 'looked',
                'called', 'asked', 'could', 'should', 'would', 'thought',
                'through', 'because', 'beautiful', 'pretty', 'friend',
                'school', 'whole', 'clothes', 'busy', 'parents', 'Christmas',
                'laugh', 'eight', 'enough', 'always', 'every', 'floor',
                'poor', 'door', 'sure', 'sugar', 'eye', 'climb', 'kind',
                'behind', 'find', 'mind', 'wild', 'child', 'children',
            ],

            // Animal names (fun list)
            'animals' => [
                'cat', 'dog', 'fish', 'bird', 'frog', 'duck', 'bear',
                'deer', 'wolf', 'lion', 'tiger', 'horse', 'sheep', 'goat',
                'rabbit', 'hamster', 'parrot', 'turtle', 'monkey', 'donkey',
                'giraffe', 'penguin', 'dolphin', 'elephant', 'kangaroo',
                'crocodile', 'butterfly', 'hedgehog', 'squirrel', 'flamingo',
                'cheetah', 'leopard', 'rhinoceros', 'hippopotamus', 'gorilla',
                'chimpanzee', 'jellyfish', 'octopus', 'alligator', 'chameleon',
            ],

            // Days of the week + months
            'days_months' => [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                'Saturday', 'Sunday',
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December',
            ],
        ];
    }
}
