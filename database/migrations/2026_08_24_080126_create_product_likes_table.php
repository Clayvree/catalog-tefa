<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_likes', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->uuid('catalog_item_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('catalog_item_id')->references('id')->on('catalog_items')->onDelete('cascade');
            $table->unique(['user_id', 'catalog_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_likes');
    }
};
