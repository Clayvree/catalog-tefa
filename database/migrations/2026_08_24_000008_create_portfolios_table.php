<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('worker_profile_id');
            $table->uuid('tefa_unit_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('external_link')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->uuid('reviewed_by')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('worker_profile_id')->references('id')->on('worker_profiles')->onDelete('cascade');
            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['tefa_unit_id', 'status']);
            $table->index('worker_profile_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
