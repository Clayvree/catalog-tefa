<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\TefaUnit;
use App\Enums\ItemStatus;
use App\Enums\ItemType;
use Illuminate\Support\Str;

class DigitalProductSeeder extends Seeder
{
    public function run(): void
    {
        $unitRpl = TefaUnit::where('slug', 'tefa-rpl-software-house')->first() ?? TefaUnit::first();
        $unitDkv = TefaUnit::where('slug', 'tefa-dkv-creative-studio')->first() ?? TefaUnit::skip(1)->first() ?? $unitRpl;
        $unitKuliner = TefaUnit::where('slug', 'tefa-culinary-bakery')->first() ?? TefaUnit::latest()->first() ?? $unitRpl;

        $catDigital = Category::firstOrCreate(['name' => 'Template & Aset Digital'], ['type' => ItemType::Produk]);
        $catKuliner = Category::firstOrCreate(['name' => 'Kuliner & Bakery'], ['type' => ItemType::Produk]);

        $items = [
            [
                'tefa_unit_id' => $unitRpl->id,
                'category_id' => $catDigital->id,
                'title' => 'Source Code E-Commerce Starter Kit Laravel 11 & Vue.js (Full Docs)',
                'slug' => 'source-code-ecommerce-starter-kit-laravel-11',
                'description' => 'Fullstack source code toko online siap pakai berbasis Laravel 11 + Vue.js SPA. Dilengkapi integrasi Payment Gateway QRIS, multi-admin role, manajemen stok produk, dan dokumentasi instalasi lengkap.',
                'price' => 125000,
                'stock' => 999,
                'item_type' => ItemType::Digital,
                'fulfillment_type' => 'digital_download',
                'digital_file_url' => 'https://github.com/tefa-rpl-vokasi/ecommerce-starter-kit-laravel11',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&q=80',
                'status' => ItemStatus::Published,
            ],
            [
                'tefa_unit_id' => $unitRpl->id,
                'category_id' => $catDigital->id,
                'title' => 'UI/UX Design System Dashboard Admin Multi-Unit (Figma File .fig)',
                'slug' => 'ui-ux-design-system-dashboard-admin-figma',
                'description' => 'Koleksi 120+ komponen UI, autolayout, dark & light mode, chart analytics, dan template dashboard responsif siap pakai untuk Figma.',
                'price' => 49000,
                'stock' => 999,
                'item_type' => ItemType::Digital,
                'fulfillment_type' => 'digital_download',
                'digital_file_url' => 'https://www.figma.com/community/file/sample-tefa-design-system',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=600&q=80',
                'status' => ItemStatus::Published,
            ],
            [
                'tefa_unit_id' => $unitDkv->id,
                'category_id' => $catDigital->id,
                'title' => 'Bundle 50+ Template Feed & Reels Instagram Promosi UMKM (Canva & PSD)',
                'slug' => 'bundle-50-template-feed-reels-instagram-canva-psd',
                'description' => 'Paket template desain postingan promosi, flash sale, testimoni, dan video reels 9:16 untuk Canva & Photoshop. Mudah diedit teks dan warna.',
                'price' => 35000,
                'stock' => 999,
                'item_type' => ItemType::Digital,
                'fulfillment_type' => 'digital_download',
                'digital_file_url' => 'https://drive.google.com/drive/folders/sample-tefa-canva-pack',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?w=600&q=80',
                'status' => ItemStatus::Published,
            ],
            [
                'tefa_unit_id' => $unitDkv->id,
                'category_id' => $catDigital->id,
                'title' => 'Paket 100+ Ilustrasi Vektor Maskot & Karakter 3D (SVG & PNG HD)',
                'slug' => 'paket-100-ilustrasi-vektor-maskot-karakter-3d',
                'description' => 'Koleksi ilustrasi karakter 3D modern dengan resolusi tinggi (PNG transparansi 4000px & vektor SVG) untuk kebutuhan presentasi, website, dan brosur.',
                'price' => 59000,
                'stock' => 999,
                'item_type' => ItemType::Digital,
                'fulfillment_type' => 'digital_download',
                'digital_file_url' => 'https://drive.google.com/drive/folders/sample-tefa-3d-character-pack',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&q=80',
                'status' => ItemStatus::Published,
            ],
            [
                'tefa_unit_id' => $unitKuliner->id,
                'category_id' => $catKuliner->id,
                'title' => 'Kopi Bubuk Robusta Dampit Single Origin 250gr (Kemasan Valve)',
                'slug' => 'kopi-bubuk-robusta-dampit-single-origin-250gr',
                'description' => 'Kopi robusta asli lereng Gunung Semeru Dampit dengan aroma coklat karamel khas, di-roasting medium dark. Kemasan food grade dengan one-way degassing valve.',
                'price' => 45000,
                'stock' => 35,
                'item_type' => ItemType::Produk,
                'fulfillment_type' => 'both',
                'weight_gram' => 280,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600&q=80',
                'status' => ItemStatus::Published,
            ]
        ];

        foreach ($items as $itemData) {
            CatalogItem::updateOrCreate(
                ['slug' => $itemData['slug']],
                array_merge($itemData, [
                    'id' => (string) Str::uuid(),
                ])
            );
        }
    }
}