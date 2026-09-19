<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('item_title')->after('catalog_item_id');
            $table->decimal('unit_price', 15, 2)->after('item_title');
            $table->decimal('subtotal', 15, 2)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['item_title', 'unit_price', 'subtotal']);
        });
    }
};
