<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tefa_unit_id');
            $table->string('title');
            $table->string('client_name');
            $table->string('client_contact')->nullable();
            $table->text('description')->nullable();
            $table->string('source_chat_file_url')->nullable();
            $table->json('ai_extraction_data')->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->date('deadline')->nullable();
            $table->uuid('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');

            $table->index('tefa_unit_id');
            $table->index(['tefa_unit_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
