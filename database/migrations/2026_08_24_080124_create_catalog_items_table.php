<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tefa_unit_id');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('thumbnail_url')->nullable();
            $table->enum('item_type', ['produk', 'jasa', 'kegiatan']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->longText('embedding')->nullable()->comment('Vector data for AI Semantic Search');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->onDelete('cascade');

            // Indexes untuk performa halaman katalog publik
            $table->index('tefa_unit_id');
            $table->index(['tefa_unit_id', 'status', 'item_type']);
            $table->index('status');
            $table->unique(['tefa_unit_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_items');
    }
};
