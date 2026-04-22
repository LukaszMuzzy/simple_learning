<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('word_definition_groups', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->string('slug', 60)->unique();
            $table->string('description', 500)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(99);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('definition_group_members', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('word_definition_id');

            $table->primary(['group_id', 'word_definition_id']);
            $table->foreign('group_id')->references('id')->on('word_definition_groups')->cascadeOnDelete();
            $table->foreign('word_definition_id')->references('id')->on('word_definitions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('definition_group_members');
        Schema::dropIfExists('word_definition_groups');
    }
};
