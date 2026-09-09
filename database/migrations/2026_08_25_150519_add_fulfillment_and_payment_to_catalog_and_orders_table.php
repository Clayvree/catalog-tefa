<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->string('digital_file_url')->nullable()->after('thumbnail_url');
            $table->enum('fulfillment_type', ['shipping_only', 'pickup_only', 'both', 'digital_download', 'service_booking'])->default('both')->after('digital_file_url');
            $table->integer('weight_gram')->default(500)->after('fulfillment_type');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['physical', 'digital', 'service'])->default('physical')->after('project_id');
            $table->enum('fulfillment_method', ['delivery', 'pickup_at_tefa', 'digital_download', 'onsite_service'])->default('delivery')->after('order_type');
            $table->text('shipping_address')->nullable()->after('fulfillment_method');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_courier')->nullable()->after('shipping_city');
            $table->string('payment_method')->default('qris')->after('shipping_courier');
            $table->enum('payment_status', ['unpaid', 'paid', 'expired', 'refunded'])->default('unpaid')->after('payment_method');
            $table->string('payment_proof_url')->nullable()->after('payment_status');
            $table->string('digital_access_token')->nullable()->after('payment_proof_url');
        });
    }

    public function down(): void
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->dropColumn(['digital_file_url', 'fulfillment_type', 'weight_gram']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'fulfillment_method',
                'shipping_address',
                'shipping_city',
                'shipping_courier',
                'payment_method',
                'payment_status',
                'payment_proof_url',
                'digital_access_token',
            ]);
        });
    }
};