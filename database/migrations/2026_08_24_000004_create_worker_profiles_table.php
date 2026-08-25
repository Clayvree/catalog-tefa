<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->uuid('tefa_unit_id');
            $table->string('nisn')->nullable();
            $table->string('class_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');

            $table->index('tefa_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_profiles');
    }
};
