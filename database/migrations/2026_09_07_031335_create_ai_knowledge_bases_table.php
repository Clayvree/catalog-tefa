<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->uuid('tefa_unit_id')->nullable(); // null = berlaku global untuk semua jurusan
            $table->string('title');                  // Judul/Index - ini yang AI baca dulu
            $table->longText('description');          // Isi detail - hanya dibaca jika AI pilih judul ini
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->nullOnDelete();
            $table->index(['tefa_unit_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_knowledge_bases');
    }
};
