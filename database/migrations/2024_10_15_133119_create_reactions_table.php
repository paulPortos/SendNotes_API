<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->boolean('has_liked');
            $table->boolean('has_disliked');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('notes_id')->constrained('notes')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'notes_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'notes_id']);
        });
    }
};
