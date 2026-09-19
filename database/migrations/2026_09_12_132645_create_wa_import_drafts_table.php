<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_import_drafts', function (Blueprint $table) {
            $table->id();
            $table->uuid('tefa_unit_id');
            $table->uuid('uploaded_by');
            $table->string('chat_file_path');
            $table->enum('status', ['processing', 'ready', 'confirmed', 'failed'])->default('processing');
            $table->json('ai_result')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_import_drafts');
    }
};
