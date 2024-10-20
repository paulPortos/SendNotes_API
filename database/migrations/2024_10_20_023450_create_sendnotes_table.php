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
        Schema::create('send_notes', function (Blueprint $table) {
            $table->id();
            $table->string('sent_by');
            $table->string('send_to');
            $table->foreignId('user_id')->constrained('notes', 'user_id')->cascadeOnDelete();
            $table->foreignId('notes_id')->constrained('notes')->cascadeOnDelete();
            $table->string('title');
            $table->text('contents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sendnotes');
    }
};
