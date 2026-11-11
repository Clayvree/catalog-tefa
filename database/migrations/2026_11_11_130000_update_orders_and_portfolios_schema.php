<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Tambahkan status 'dp_paid' ke enum payment_status pada tabel orders
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'dp_paid', 'paid', 'expired', 'refunded') NOT NULL DEFAULT 'unpaid'");

            // 2. Jadikan worker_profile_id pada tabel portfolios nullable agar unit tanpa siswa tetap bisa memiliki portofolio
            DB::statement("ALTER TABLE portfolios MODIFY COLUMN worker_profile_id CHAR(36) NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'expired', 'refunded') NOT NULL DEFAULT 'unpaid'");
            DB::statement("ALTER TABLE portfolios MODIFY COLUMN worker_profile_id CHAR(36) NOT NULL");
        }
    }
};
