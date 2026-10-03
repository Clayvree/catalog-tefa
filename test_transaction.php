<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CatalogItem;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Project;
use App\Enums\ItemType;
use App\Enums\ProjectStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

echo "=== MEMULAI TESTING TRANSAKSI ===\n";

$user = User::where('role', 'customer')->first() ?? User::first();
if(!$user) die("Tidak ada user\n");

// 1. Tipe Fisik
$fisik = CatalogItem::where('item_type', 'produk')->first();
if($fisik) {
    echo "1. Testing Produk Fisik: {$fisik->title}\n";
    $orderFisik = Order::create([
        'id' => (string) Str::uuid(),
        'tefa_unit_id' => $fisik->tefa_unit_id,
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'customer_contact' => '08123456789',
        'order_type' => 'physical',
        'fulfillment_method' => 'delivery',
        'shipping_address' => 'Jl. Test No 1',
        'shipping_city' => 'Jakarta',
        'payment_method' => 'transfer',
        'payment_status' => 'unpaid',
        'total_price' => $fisik->price ?? 0,
        'shipping_cost' => 15000,
        'status' => 'pending',
        'order_date' => now(),
    ]);
    OrderItem::create([
        'order_id' => $orderFisik->id,
        'catalog_item_id' => $fisik->id,
        'item_title' => $fisik->title,
        'unit_price' => $fisik->price ?? 0,
        'quantity' => 1,
        'subtotal' => $fisik->price ?? 0,
        'price_at_purchase' => $fisik->price ?? 0
    ]);
    
    // Simulate Admin pay
    $orderFisik->update(['payment_status' => 'paid', 'status' => 'processed']);
    echo "   -> Berhasil membuat & bayar produk fisik (ID: {$orderFisik->id})\n";
}

// 2. Tipe Digital
$digital = CatalogItem::where('item_type', 'digital')->first();
if($digital) {
    echo "2. Testing Produk Digital: {$digital->title}\n";
    $orderDigi = Order::create([
        'id' => (string) Str::uuid(),
        'tefa_unit_id' => $digital->tefa_unit_id,
        'user_id' => $user->id,
        'customer_name' => $user->name,
        'customer_contact' => '08123456789',
        'order_type' => 'digital',
        'fulfillment_method' => 'digital_download',
        'payment_method' => 'transfer',
        'payment_status' => 'paid',
        'total_price' => $digital->price ?? 0,
        'shipping_cost' => 0,
        'status' => 'completed',
        'order_date' => now(),
    ]);
    OrderItem::create([
        'order_id' => $orderDigi->id,
        'catalog_item_id' => $digital->id,
        'item_title' => $digital->title,
        'unit_price' => $digital->price ?? 0,
        'quantity' => 1,
        'subtotal' => $digital->price ?? 0,
        'price_at_purchase' => $digital->price ?? 0
    ]);
    echo "   -> Berhasil membuat & bayar produk digital (ID: {$orderDigi->id})\n";
}

// 3. Tipe Jasa
$jasa = CatalogItem::where('item_type', 'service')->first() ?? CatalogItem::where('item_type', 'jasa')->first();
if($jasa) {
    echo "3. Testing Layanan Jasa: {$jasa->title}\n";
    DB::transaction(function() use ($jasa, $user) {
        $project = Project::create([
            'id' => (string) Str::uuid(),
            'tefa_unit_id' => $jasa->tefa_unit_id,
            'created_by' => $user->id,
            'title' => "Pengajuan Jasa: {$jasa->title}",
            'client_name' => 'Klien Test',
            'client_contact' => '0812345',
            'description' => 'Test Jasa',
            'estimated_price' => $jasa->price ?? 0,
            'final_price' => $jasa->price ?? 0,
            'status' => ProjectStatus::Draft,
        ]);
        
        $orderJasa = Order::create([
            'id' => (string) Str::uuid(),
            'tefa_unit_id' => $jasa->tefa_unit_id,
            'user_id' => $user->id,
            'project_id' => $project->id,
            'customer_name' => $user->name,
            'customer_contact' => '08123456789',
            'order_type' => 'service',
            'fulfillment_method' => 'onsite_service',
            'payment_method' => 'wa',
            'payment_status' => 'unpaid',
            'total_price' => $jasa->price ?? 0,
            'shipping_cost' => 0,
            'status' => 'pending',
            'order_date' => now(),
        ]);
        
        OrderItem::create([
            'order_id' => $orderJasa->id,
            'catalog_item_id' => $jasa->id,
            'item_title' => $jasa->title,
            'unit_price' => $jasa->price ?? 0,
            'quantity' => 1,
            'subtotal' => $jasa->price ?? 0,
            'price_at_purchase' => $jasa->price ?? 0
        ]);
        echo "   -> Berhasil membuat Jasa Order (ID: {$orderJasa->id}) & Project (ID: {$project->id})\n";
    });
}

echo "=== SELESAI ===\n";
