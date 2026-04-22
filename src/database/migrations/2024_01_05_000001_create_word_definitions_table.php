<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('word_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('word', 100);
            $table->string('definition', 500);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->timestamps();

            $table->unique('word');
        });

        // Seed with the built-in vocabulary dataset
        $rows = [
            // ── Easy ────────────────────────────────────────────────────────
            ['word' => 'ancient',   'difficulty' => 'easy',   'definition' => 'very old; from a long time ago'],
            ['word' => 'beneath',   'difficulty' => 'easy',   'definition' => 'under or lower than something else'],
            ['word' => 'brave',     'difficulty' => 'easy',   'definition' => 'willing to face danger without showing fear'],
            ['word' => 'calm',      'difficulty' => 'easy',   'definition' => 'peaceful and quiet; not worried or excited'],
            ['word' => 'distant',   'difficulty' => 'easy',   'definition' => 'far away in place or time'],
            ['word' => 'enormous',  'difficulty' => 'easy',   'definition' => 'very large in size; much bigger than usual'],
            ['word' => 'fierce',    'difficulty' => 'easy',   'definition' => 'very strong, violent, or dangerous'],
            ['word' => 'gentle',    'difficulty' => 'easy',   'definition' => 'kind, soft, and careful with others'],
            ['word' => 'grateful',  'difficulty' => 'easy',   'definition' => 'feeling thankful for something done or given'],
            ['word' => 'hollow',    'difficulty' => 'easy',   'definition' => 'having an empty space inside'],
            ['word' => 'island',    'difficulty' => 'easy',   'definition' => 'a piece of land completely surrounded by water'],
            ['word' => 'joyful',    'difficulty' => 'easy',   'definition' => 'very happy; full of great pleasure'],
            ['word' => 'knowledge', 'difficulty' => 'easy',   'definition' => 'facts and information gained by studying or experiencing things'],
            ['word' => 'lonely',    'difficulty' => 'easy',   'definition' => 'unhappy because of having no friends or company'],
            ['word' => 'mystery',   'difficulty' => 'easy',   'definition' => 'something that is difficult to understand or explain'],

            // ── Medium ──────────────────────────────────────────────────────
            ['word' => 'absurd',      'difficulty' => 'medium', 'definition' => 'completely silly or impossible to believe'],
            ['word' => 'agile',       'difficulty' => 'medium', 'definition' => 'able to move quickly and easily'],
            ['word' => 'cunning',     'difficulty' => 'medium', 'definition' => 'clever at getting what you want, often by tricking people'],
            ['word' => 'delicate',    'difficulty' => 'medium', 'definition' => 'very fine and fragile; easily damaged or hurt'],
            ['word' => 'flexible',    'difficulty' => 'medium', 'definition' => 'able to bend without breaking; willing to change'],
            ['word' => 'generous',    'difficulty' => 'medium', 'definition' => 'happy to give more than is usual or expected'],
            ['word' => 'hesitate',    'difficulty' => 'medium', 'definition' => 'to pause before doing something because you are unsure'],
            ['word' => 'immortal',    'difficulty' => 'medium', 'definition' => 'living or lasting forever; never dying'],
            ['word' => 'jealous',     'difficulty' => 'medium', 'definition' => 'feeling unhappy because someone else has something you want'],
            ['word' => 'magnificent', 'difficulty' => 'medium', 'definition' => 'very grand and impressive to look at'],
            ['word' => 'negotiate',   'difficulty' => 'medium', 'definition' => 'to discuss something with others in order to reach an agreement'],
            ['word' => 'obedient',    'difficulty' => 'medium', 'definition' => 'willing to do what you are told without arguing'],
            ['word' => 'peculiar',    'difficulty' => 'medium', 'definition' => 'strange or unusual in a way that is hard to explain'],
            ['word' => 'sincere',     'difficulty' => 'medium', 'definition' => 'truly meant; honest and without pretence'],
            ['word' => 'vivid',       'difficulty' => 'medium', 'definition' => 'very bright and clear; producing sharp pictures in your mind'],

            // ── Hard ────────────────────────────────────────────────────────
            ['word' => 'abundance',   'difficulty' => 'hard',   'definition' => 'a very large quantity; more than enough of something'],
            ['word' => 'catastrophe', 'difficulty' => 'hard',   'definition' => 'a sudden terrible event that causes great damage or suffering'],
            ['word' => 'conspicuous', 'difficulty' => 'hard',   'definition' => 'very noticeable and easy to see; standing out from others'],
            ['word' => 'desolate',    'difficulty' => 'hard',   'definition' => 'empty, bleak, and making people feel very unhappy'],
            ['word' => 'eloquent',    'difficulty' => 'hard',   'definition' => 'able to express ideas and feelings clearly and effectively'],
            ['word' => 'ferocious',   'difficulty' => 'hard',   'definition' => 'extremely fierce, violent, and uncontrolled'],
            ['word' => 'gruesome',    'difficulty' => 'hard',   'definition' => 'causing shock or disgust; very unpleasant to see or hear about'],
            ['word' => 'illuminate',  'difficulty' => 'hard',   'definition' => 'to light something up brightly; to explain and make something clearer'],
            ['word' => 'jubilant',    'difficulty' => 'hard',   'definition' => 'feeling very happy and excited because of a success'],
            ['word' => 'luminous',    'difficulty' => 'hard',   'definition' => 'giving off or reflecting bright light; glowing'],
            ['word' => 'notorious',   'difficulty' => 'hard',   'definition' => 'famous for something bad or shocking'],
            ['word' => 'ominous',     'difficulty' => 'hard',   'definition' => 'making you feel that something bad is about to happen'],
            ['word' => 'persevere',   'difficulty' => 'hard',   'definition' => 'to keep trying to do something despite difficulties'],
            ['word' => 'resilient',   'difficulty' => 'hard',   'definition' => 'able to recover quickly after difficulties or setbacks'],
            ['word' => 'tranquil',    'difficulty' => 'hard',   'definition' => 'calm, peaceful, and free from noise or disturbance'],
        ];

        $now = now();
        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('word_definitions')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('word_definitions');
    }
};
