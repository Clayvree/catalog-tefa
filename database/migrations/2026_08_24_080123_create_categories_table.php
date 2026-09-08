<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('tefa_unit_id')->nullable(); // null = kategori global
            $table->string('name');
            $table->enum('type', ['produk', 'jasa', 'kegiatan']);
            $table->timestamps();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->nullOnDelete();
            $table->index(['tefa_unit_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
