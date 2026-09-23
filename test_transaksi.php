<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CatalogItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TefaUnit;
use App\Models\Category;
use Illuminate\Support\Str;

$unit = TefaUnit::first();
$cat = Category::first();

// 1. Ensure we have one of each type
$fisik = CatalogItem::where('item_type', 'produk')->first();
$jasa = CatalogItem::where('item_type', 'jasa')->first();
$digital = CatalogItem::where('item_type', 'digital')->first();

if (!$digital) {
    $digital = CatalogItem::create([
        'id' => Str::uuid(),
        'tefa_unit_id' => $unit->id,
        'category_id' => $cat->id,
        'title' => 'E-Book Panduan Digital V2',
        'slug' => 'ebook-panduan-digital-v2',
        'description' => 'Test digital',
        'price' => 50000,
        'item_type' => 'digital',
        'fulfillment_type' => 'digital_download',
        'status' => 'published',
        'digital_file_url' => 'https://example.com/download'
    ]);
}

// 2. Set stock for physical just in case
$fisik->update(['track_stock' => true, 'stock' => 50]);

echo "=== MEMBUAT PESANAN FISIK ===\n";
$o1 = Order::create([
    'id' => Str::uuid(),
    'tefa_unit_id' => $fisik->tefa_unit_id,
    'customer_name' => 'Tester Fisik',
    'customer_contact' => '08123',
    'order_type' => 'physical',
    'fulfillment_method' => 'delivery',
    'payment_method' => 'wa',
    'payment_status' => 'unpaid',
    'status' => 'pending',
    'total_price' => $fisik->price,
    'shipping_cost' => 0,
]);
OrderItem::create(['order_id' => $o1->id, 'catalog_item_id' => $fisik->id, 'item_title' => $fisik->title, 'unit_price' => $fisik->price, 'quantity' => 1, 'subtotal' => $fisik->price, 'price_at_purchase' => $fisik->price]);

echo "=== MEMBUAT PESANAN DIGITAL ===\n";
$o2 = Order::create([
    'id' => Str::uuid(),
    'tefa_unit_id' => $digital->tefa_unit_id,
    'customer_name' => 'Tester Digital',
    'customer_contact' => '08123',
    'order_type' => 'digital',
    'fulfillment_method' => 'digital_download',
    'payment_method' => 'wa',
    'payment_status' => 'unpaid',
    'digital_access_token' => Str::random(32),
    'status' => 'pending',
    'total_price' => $digital->price,
    'shipping_cost' => 0,
]);
OrderItem::create(['order_id' => $o2->id, 'catalog_item_id' => $digital->id, 'item_title' => $digital->title, 'unit_price' => $digital->price, 'quantity' => 1, 'subtotal' => $digital->price, 'price_at_purchase' => $digital->price]);

echo "=== MEMBUAT PESANAN JASA ===\n";
$o3 = Order::create([
    'id' => Str::uuid(),
    'tefa_unit_id' => $jasa->tefa_unit_id,
    'customer_name' => 'Tester Jasa',
    'customer_contact' => '08123',
    'order_type' => 'service',
    'fulfillment_method' => 'onsite_service',
    'payment_method' => 'wa',
    'payment_status' => 'unpaid',
    'status' => 'pending',
    'total_price' => $jasa->price,
    'shipping_cost' => 0,
]);
OrderItem::create(['order_id' => $o3->id, 'catalog_item_id' => $jasa->id, 'item_title' => $jasa->title, 'unit_price' => $jasa->price, 'quantity' => 1, 'subtotal' => $jasa->price, 'price_at_purchase' => $jasa->price]);


// 3. ADMIN UPDATES
echo "\n=== ADMIN MENGATUR ONGKIR UNTUK FISIK ===\n";
$o1->update(['shipping_cost' => 25000]);
echo "Ongkir Fisik sekarang: " . $o1->shipping_cost . ", Grand Total: " . $o1->grand_total . "\n";

echo "\n=== ADMIN KONFIRMASI PEMBAYARAN LUNAS (SEMUA) ===\n";
foreach ([$o1, $o2, $o3] as $order) {
    $updates = ['payment_status' => 'paid'];
    if ($order->isDigital()) {
        $updates['status'] = 'completed';
        $updates['fulfillment_status'] = 'download_ready';
    } elseif ($order->isService()) {
        $updates['status'] = 'processed';
        $updates['fulfillment_status'] = 'in_progress';
    } else {
        $updates['status'] = 'processed';
        $updates['fulfillment_status'] = 'packing';
    }
    $order->update($updates);
    echo "Pesanan " . $order->order_type . " dibayar! Status: " . $order->status . " | Fulfillment: " . $order->fulfillment_status->value . "\n";
}

echo "\n=== ADMIN KONFIRMASI SELESAI (FISIK & JASA) ===\n";
$o1->update(['fulfillment_status' => 'delivered', 'status' => 'completed']);
echo "Pesanan Fisik diantar! Status: " . $o1->status . " | Fulfillment: " . $o1->fulfillment_status->value . "\n";

$o3->update(['fulfillment_status' => 'completed', 'status' => 'completed']);
echo "Pesanan Jasa selesai! Status: " . $o3->status . " | Fulfillment: " . $o3->fulfillment_status->value . "\n";

echo "\nTESTING SELESAI DENGAN SUKSES!\n";
