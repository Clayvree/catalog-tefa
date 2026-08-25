<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_galleries', function (Blueprint $table) {
            $table->id();
            $table->uuid('catalog_item_id');
            $table->string('image_url');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('catalog_item_id')->references('id')->on('catalog_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_galleries');
    }
};
