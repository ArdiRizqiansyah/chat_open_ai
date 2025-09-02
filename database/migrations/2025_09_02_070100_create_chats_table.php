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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->longText('message');
            $table->enum('side', ['user', 'assistant']);
            $table->text('message_open_ai_id')->nullable();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete(); // Add this line to create the foreign key relationship
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
