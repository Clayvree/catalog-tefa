<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_id');
            $table->enum('sender', ['user', 'ai']);
            $table->text('message');
            $table->json('metadata')->nullable(); // untuk menyimpan context RAG
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('ai_chat_sessions')->onDelete('cascade');
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_messages');
    }
};
