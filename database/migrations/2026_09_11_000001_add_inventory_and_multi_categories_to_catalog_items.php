<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->boolean('track_stock')->default(true)->after('stock');
            $table->unsignedInteger('weight_gram')->nullable()->change();
        });

        Schema::create('catalog_item_category', function (Blueprint $table) {
            $table->uuid('catalog_item_id');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['catalog_item_id', 'category_id']);
            $table->foreign('catalog_item_id')->references('id')->on('catalog_items')->cascadeOnDelete();
        });

        DB::table('catalog_items')->whereNotNull('category_id')->get(['id', 'category_id'])->each(
            fn ($item) => DB::table('catalog_item_category')->insertOrIgnore([
                'catalog_item_id' => $item->id,
                'category_id' => $item->category_id,
            ])
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_item_category');
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->dropColumn('track_stock');
        });
    }
};