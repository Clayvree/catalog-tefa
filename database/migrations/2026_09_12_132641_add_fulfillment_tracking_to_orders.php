<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('fulfillment_status')->nullable()->after('status');
            $table->timestamp('estimated_ready_at')->nullable()->after('fulfillment_status');
            $table->string('tracking_number')->nullable()->after('estimated_ready_at');
            $table->text('fulfillment_notes')->nullable()->after('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['fulfillment_status', 'estimated_ready_at', 'tracking_number', 'fulfillment_notes']);
        });
    }
};
