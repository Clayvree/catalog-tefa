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
            $table->boolean('track_stock')->default(true);
            $table->string('thumbnail_url')->nullable();
            $table->string('digital_file_url')->nullable();
            $table->enum('fulfillment_type', ['shipping_only', 'pickup_only', 'both', 'digital_download', 'service_booking'])->default('both');
            $table->unsignedInteger('weight_gram')->nullable()->default(500);
            $table->string('item_type', 50)->default('produk');
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

        Schema::create('catalog_item_category', function (Blueprint $table) {
            $table->uuid('catalog_item_id');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['catalog_item_id', 'category_id']);
            $table->foreign('catalog_item_id')->references('id')->on('catalog_items')->cascadeOnDelete();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('catalog_item_id')->references('id')->on('catalog_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['catalog_item_id']);
        });

        Schema::dropIfExists('catalog_item_category');
        Schema::dropIfExists('catalog_items');
    }
};
