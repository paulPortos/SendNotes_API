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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('notes', 'user_id')->cascadeOnDelete();
            $table->foreignId('notes_id')->constrained('notes')->cascadeOnDelete();
            $table->string('title');
            $table->string('creator_username');
            $table->string('creator_email')->index();
            $table->text('contents');
            $table->boolean('public');
            $table->boolean('to_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
