<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('word_lists', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();        // e.g. 'year1', 'animals'
            $table->string('label');                 // display name
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('word_list_id')->constrained()->onDelete('cascade');
            $table->string('word');
            $table->timestamps();

            $table->unique(['word_list_id', 'word']); // no duplicates within a list
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('words');
        Schema::dropIfExists('word_lists');
    }
};
