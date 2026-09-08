<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE catalog_items MODIFY COLUMN item_type VARCHAR(50) NOT NULL DEFAULT 'produk'");
        DB::statement("ALTER TABLE categories MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'jasa'");
    }

    public function down(): void
    {
        // No-op
    }
};