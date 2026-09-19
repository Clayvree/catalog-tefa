<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wa_import_drafts', function (Blueprint $table) {
            $table->uuid('catalog_item_id')->nullable();
        $table->foreign('catalog_item_id')->references('id')->on('catalog_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wa_import_drafts', function (Blueprint $table) {
            //
        });
    }
};
