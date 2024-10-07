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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('notes', 'user_id')->cascadeOnDelete();
            $table->foreignId('notes_id')->constrained('notes')->cascadeOnDelete();
            $table->string('notes_title');
            $table->string('notification_type');
            $table ->string('email')->index();
            $table->String('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
