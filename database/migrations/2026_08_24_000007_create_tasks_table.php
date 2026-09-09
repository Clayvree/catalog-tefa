<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->uuid('tefa_unit_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->uuid('assigned_worker_id')->nullable();
            $table->unsignedBigInteger('skill_id')->nullable();
            $table->text('ai_recommendation_notes')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'review', 'done'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('proof_file_url')->nullable();
            $table->text('proof_notes')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');
            $table->foreign('assigned_worker_id')->references('id')->on('worker_profiles')->nullOnDelete();
            $table->foreign('skill_id')->references('id')->on('skills')->nullOnDelete();

            $table->index(['project_id', 'status']);
            $table->index(['assigned_worker_id', 'status']);
            $table->index('tefa_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
