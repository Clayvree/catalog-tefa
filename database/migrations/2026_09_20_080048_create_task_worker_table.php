<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_worker', function (Blueprint $table) {
            $table->id();
            $table->uuid('task_id');
            $table->uuid('worker_profile_id');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
            $table->foreign('worker_profile_id')->references('id')->on('worker_profiles')->cascadeOnDelete();
            
            $table->unique(['task_id', 'worker_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_worker');
    }
};
