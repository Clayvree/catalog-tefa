<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('label');
            $table->longText('value')->nullable();
            $table->enum('type', ['text', 'html', 'image', 'json'])->default('text');
            $table->uuid('tefa_unit_id')->nullable();
            $table->timestamps();

            $table->foreign('tefa_unit_id')->references('id')->on('tefa_units')->nullOnDelete();
            // key unik per scope (global atau per unit)
            $table->unique(['key', 'tefa_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_contents');
    }
};
