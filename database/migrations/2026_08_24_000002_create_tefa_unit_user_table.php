<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tefa_unit_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('tefa_unit_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['tefa_unit_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tefa_unit_user');
    }
};
