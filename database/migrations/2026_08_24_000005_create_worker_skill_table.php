<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_skill', function (Blueprint $table) {
            $table->id();
            $table->uuid('worker_profile_id');
            $table->unsignedBigInteger('skill_id');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->timestamps();

            $table->foreign('worker_profile_id')->references('id')->on('worker_profiles')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->unique(['worker_profile_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_skill');
    }
};
