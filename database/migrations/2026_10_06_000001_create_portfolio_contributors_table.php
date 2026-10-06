<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_contributors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('portfolio_id');
            $table->uuid('worker_profile_id')->nullable(); // null = tamu (tanpa akun)
            $table->string('guest_name')->nullable();       // diisi jika tidak punya akun
            $table->string('role')->nullable();             // peran spesifik, opsional
            $table->timestamps();

            $table->foreign('portfolio_id')
                  ->references('id')
                  ->on('portfolios')
                  ->onDelete('cascade');

            $table->foreign('worker_profile_id')
                  ->references('id')
                  ->on('worker_profiles')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_contributors');
    }
};
