<?php

namespace Database\Seeders;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Enums\UserRole;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\TefaUnit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Database\Seeders\WorkerSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. SUPER ADMIN SYSTEM
        // ==========================================
        User::updateOrCreate(
            ['email' => 'superadmin@tefa.id'],
            [
                'name'              => 'Super Administrator',
                'password'          => Hash::make('password'),
                'phone'             => '628111111111',
                'whatsapp_number' => '628111111111',
                'role'              => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'public@tefa.id'],
            [
                'name'              => 'public',
                'password'          => Hash::make('password'),
                'phone'             => '628111111112',
                'whatsapp_number' => '628111111112',
                'role'              => UserRole::Public,
                'email_verified_at' => now(),
            ]
        );

        // ==========================================
        // 2. KATEGORI UMUM DARI SEMUA JURUSAN
        // ==========================================
        $catApp       = Category::updateOrCreate(['name' => 'Aplikasi & Website'], ['type' => ItemType::Jasa]);
        $catGame      = Category::updateOrCreate(['name' => 'Game & VR/AR'], ['type' => ItemType::Jasa]);
        $catIoT       = Category::updateOrCreate(['name' => 'Sistem Otomasi & IoT'], ['type' => ItemType::Produk]);
        $catLogistik  = Category::updateOrCreate(['name' => 'Sistem Logistik & Kargo'], ['type' => ItemType::Jasa]);
        $catDesain    = Category::updateOrCreate(['name' => 'Desain Grafis & Branding'], ['type' => ItemType::Jasa]);
        $catCetak     = Category::updateOrCreate(['name' => 'Cetak & Percetakan'], ['type' => ItemType::Produk]);
        $catMerch     = Category::updateOrCreate(['name' => 'Merchandise Custom'], ['type' => ItemType::Produk]);
        $catJaringan  = Category::updateOrCreate(['name' => 'Instalasi & Jaringan'], ['type' => ItemType::Jasa]);
        $catHardware  = Category::updateOrCreate(['name' => 'Servis & Maintenance'], ['type' => ItemType::Jasa]);
        $catModel3D   = Category::updateOrCreate(['name' => 'Aset & Pemodelan 3D'], ['type' => ItemType::Jasa]);
        $catVideoAnim = Category::updateOrCreate(['name' => 'Video Animasi & Media'], ['type' => ItemType::Jasa]);
        $catBroadcasting = Category::updateOrCreate(['name' => 'Produksi Video & Penyiaran'], ['type' => ItemType::Jasa]);

        // ==========================================
        // 3. JURUSAN PPLG / RPL (Bu Indra)
        // ==========================================
        $adminPplg = User::updateOrCreate(
            ['email' => 'bu.indra@tefa.id'],
            [
                'name'              => 'Bu Indra (Admin PPLG)',
                'password'          => Hash::make('password'),
                'phone'             => '6281268681430',
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $workerPPLG = User::updateOrCreate(
            ['email' => 'nabil@tefa.id'],
            [
                'name'              => 'Nabil (Worker PPLG)',
                'password'          => Hash::make('password'),
                'phone'             => '6281234567011',
                'role'              => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $unitPplg = TefaUnit::updateOrCreate(['slug' => 'tefa-pplg-software-house'], [
            'name'        => 'TEFA PPLG Software House',
            'description' => 'Unit produksi Pengembangan Perangkat Lunak dan GIM (PPLG) melayani pembuatan aplikasi, website, game VR, dan sistem otomasi digital.',
            'banner_url'  => asset('assets/images/units/pplg.png'),
            'logo_url'    => asset('assets/images/units/logo_pplg.jpeg'),
            'is_active'   => true,
        ]);
        $adminPplg->managedUnits()->syncWithoutDetaching([$unitPplg->id]);

        $workerProfilePPLG = \App\Models\WorkerProfile::updateOrCreate(
            ['user_id' => $workerPPLG->id],
            [
                'id'           => (string) Str::uuid(),
                'tefa_unit_id' => $unitPplg->id,
                'class_name'   => 'XII PPLG 1',
                'bio'          => 'Passionate web developer & designer.',
            ]
        );

        $skillLaravel = \App\Models\Skill::updateOrCreate(['slug' => 'laravel'], ['name' => 'Laravel', 'color_hex' => '#ef4444']);
        $skillVue = \App\Models\Skill::updateOrCreate(['slug' => 'vue-js'], ['name' => 'Vue JS', 'color_hex' => '#10b981']);
        $workerProfilePPLG->skills()->syncWithoutDetaching([$skillLaravel->id, $skillVue->id]);

        $portfoliosPplg = [
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerProfilePPLG->id,
                'title'             => 'Aplikasi Absensi Digital (Absen Silap)',
                'description'       => 'Sistem absensi siswa berbasis web yang dirancang untuk mencatat kehadiran secara real-time, dilengkapi fitur rekapitulasi data otomatis untuk memudahkan pihak sekolah.',
                'thumbnail_url'     => 'images/portofolio/silap.png',
                'status'            => 'approved',
            ],
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerProfilePPLG->id,
                'title'             => 'Platform JMB',
                'description'       => 'Portal layanan dan pusat akses informasi digital terpadu. Kunjungi platformnya di: https://jasamultiberkah.com/', 
                'thumbnail_url'     => 'images/portofolio/jmb.png',
                'status'            => 'approved',
            ],
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerProfilePPLG->id,
                'title'             => 'Game VR: Warisan di Balik Layar',
                'description'       => 'Gim berbasis Virtual Reality (VR) interaktif yang mengajak pemain menyelami cerita, petualangan, dan misteri di balik sebuah layar produksi.',
                'thumbnail_url'     => 'images/portofolio/game.jpeg',
                'status'            => 'approved',
            ],
        ];

        foreach ($portfoliosPplg as $pf) {
            Portfolio::updateOrCreate(
                ['title' => $pf['title']], 
                array_merge($pf, ['id' => (string) Str::uuid()]) 
            );
        }

        $itemsPplg = [
    [
        'tefa_unit_id'  => $unitPplg->id,
        'category_id'   => $catGame->id,
        'title'         => 'Pembuatan Gim (Game Development)',
        'slug'          => 'pembuatan-gim',
        'description'   => 'Jasa pengembangan dan pembuatan berbagai jenis gim (game development) edukatif, 2D/3D, maupun Virtual Reality (VR) secara interaktif dan imersif.',
        'price'         => 4500000,
        'item_type'     => ItemType::Jasa,
        'status'        => ItemStatus::Published,
        'thumbnail_url' => 'https://i.pinimg.com/736x/fe/86/cd/fe86cdac93f99fe0c3d352b6b15754c3.jpg',
    ],
    [
        'tefa_unit_id'  => $unitPplg->id,
        'category_id'   => $catApp->id,
        'title'         => 'Layanan Pembuatan Website',
        'slug'          => 'layanan-pembuatan-website',
        'description'   => 'Jasa pembuatan dan pengembangan website profesional, mulai dari landing page, company profile, e-commerce, hingga web-based system sesuai kebutuhan klien.',
        'price'         => 3000000,
        'item_type'     => ItemType::Jasa,
        'status'        => ItemStatus::Published,
        'thumbnail_url' => 'https://i.pinimg.com/1200x/03/79/e5/0379e54534bda2ee5836637f660f0c9b.jpg',
    ],
    [
        'tefa_unit_id'  => $unitPplg->id,
        'category_id'   => $catApp->id,
        'title'         => 'Layanan Pembuatan Aplikasi',
        'slug'          => 'layanan-pembuatan-aplikasi',
        'description'   => 'Jasa pembuatan aplikasi mobile (Android/iOS) dan sistem informasi kustom (desktop/sistem terintegrasi) untuk mendukung digitalisasi bisnis maupun instansi.',
        'price'         => 3500000,
        'item_type'     => ItemType::Jasa,
        'status'        => ItemStatus::Published,
        'thumbnail_url' => 'https://i.pinimg.com/736x/12/d9/60/12d960630b251204eb0decc4f40d4054.jpg',
    ],
];

        foreach ($itemsPplg as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        CatalogItem::updateOrCreate(
            ['slug' => 'e-book-panduan-digital'],
            [
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $unitPplg->id,
                'category_id' => $catApp->id,
                'title' => 'E‑Book Panduan Digital',
                'slug' => 'e-book-panduan-digital',
                'description' => 'Buku digital lengkap tentang strategi pemasaran dan teknologi modern.',
                'price' => 0,
                'item_type' => ItemType::Digital,
                'status' => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=600&q=80',
                'track_stock' => false,
            ]
        );

        CatalogItem::where('item_type', ItemType::Produk)->update([
            'track_stock' => true,
            'stock' => 10,
        ]);

        // ==========================================
        // 4. JURUSAN DKV (Pak Nova)
        // ==========================================
        $adminDkv = User::updateOrCreate(
            ['email' => 'pak.nova@tefa.id'],
            [
                'name'              => 'Pak Nova (Admin DKV)',
                'password'          => Hash::make('password'),
                'phone'             => '6281234567002',
                'whatsapp_number' => '6281234567002',
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitDkv = TefaUnit::updateOrCreate(['slug' => 'tefa-dkv-creative-agency'], [
            'name'        => 'TEFA DKV Creative Agency',
            'description' => 'Unit produksi Desain Komunikasi Visual (DKV) melayani jasa desain grafis, cetak outdoor/indoor, merchandise, branding kemasan, dan media visual.',
            'banner_url' => asset('assets/images/units/dkv.png'),
            'logo_url'   => asset('assets/images/units/logo_dkv.jpg'),
            'is_active'   => true,
        ]);
        $adminDkv->managedUnits()->syncWithoutDetaching([$unitDkv->id]);

        $itemsDkv = [
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Gantungan Kunci Custom', 'slug' => 'gantungan-kunci-custom', 'description' => 'Pembuatan gantungan kunci custom dengan berbagai bahan dan desain menarik.', 'price' => 15000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/15/e4/42/15e442facc4539f9c610c129bd9ab5fe.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesain->id, 'title' => 'Jasa Mockup & Desain Visual', 'slug' => 'jasa-mockup-dan-desain', 'description' => 'Layanan pembuatan mockup produk dan eksplorasi desain visual profesional.', 'price' => 150000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/00/8e/65/008e65f7e8934b4cd5b855905278d189.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesain->id, 'title' => 'Desain Kaos & Pakaian', 'slug' => 'desain-kaos-pakaian', 'description' => 'Jasa perancangan desain apparel/pakaian unik untuk komunitas maupun brand.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/59/87/73/598773cdc0c9f3e299239b547ceeb3b6.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Spanduk', 'slug' => 'desain-cetak-spanduk', 'description' => 'Layanan pembuatan desain sekaligus pencetakan spanduk promosi/event.', 'price' => 75000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/7c/f3/44/7cf344f93376625f060276c2b542a886.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Baliho', 'slug' => 'desain-cetak-baliho', 'description' => 'Jasa pembuatan desain dan cetak baliho ukuran besar (outdoor).', 'price' => 350000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/ae/7f/a2/ae7fa2086f48697a7779a94c9da379ad.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Banner', 'slug' => 'desain-cetak-banner', 'description' => 'Layanan cetak banner berdiri (X-Banner, Roll Banner) siap pakai.', 'price' => 120000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/03/5b/49/035b49bcc3b3b4af80b851be3ee06785.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Poster', 'slug' => 'design-cetak-poster', 'description' => 'Pembuatan poster promosi atau cetak seni dengan kertas presisi tinggi.', 'price' => 50000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/c2/03/82/c20382a8fa95b88b1a8eb153cef70664.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesain->id, 'title' => 'Desain Logo & Identitas Brand', 'slug' => 'desain-logo-identitas-brand', 'description' => 'Pembuatan logo vektor dengan konsep mendalam dan panduan identitas visual.', 'price' => 250000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/96/02/8b/96028bcf083ba09183f1cdf6da32941b.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Kemasan', 'slug' => 'desain-cetak-kemasan', 'description' => 'Perancangan struktur dan grafik packaging kemasan produk UMKM.', 'price' => 200000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/d9/e4/8a/d9e48a2fec9d594a705eb2e2ddb9004a.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Undangan', 'slug' => 'desain-cetak-undangan', 'description' => 'Pencetakan kartu undangan acara dengan pilihan kertas dan finishing khusus.', 'price' => 150000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/bd/94/a9/bd94a90196f28dfbca1621ed8fc2144d.jpg'],
        ];

        foreach ($itemsDkv as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // 5. JURUSAN TKJ (Bu Ayu)
        // ==========================================
        $adminTkj = User::updateOrCreate(
            ['email' => 'ibu.ayu@tefa.id'],
            [
                'name'              => 'Ibu Ayu (Admin TKJ)',
                'password'          => Hash::make('password'),
                'phone'             => '6281234567003',
                'whatsapp_number' => '6281234567003',
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitTkj = TefaUnit::updateOrCreate(['slug' => 'tefa-tkj-network-solutions'], [
            'name'        => 'TEFA TKJ Network Solutions',
            'description' => 'Unit layanan Teknik Komputer & Jaringan (TKJ) spesialis perancangan, instalasi jaringan LAN/Wireless, pemasangan internet, serta servis software.',
            'banner_url' => asset('assets/images/units/tkj.png'),
            'logo_url'   => asset('assets/images/units/logo_tkj.jpg'),
            'is_active'   => true,
        ]);
        $adminTkj->managedUnits()->syncWithoutDetaching([$unitTkj->id]);

        $itemsTkj = [
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Instalasi Jaringan LAN (Per Titik)', 'slug' => 'instalasi-jaringan-lan-pertitik', 'description' => 'Pemasangan kabel dan titik jaringan LAN dari switch ke PC. Tarif Rp50.000/titik.', 'price' => 50000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/b2/76/a0/b276a09d233b228a8ed92985ca89e8f2.jpg'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Pemasangan Jaringan Internet (SSA)', 'slug' => 'pemasangan-jaringan-internet-ssa', 'description' => 'Penyambungan dan konfigurasi jaringan internet untuk rumah atau kantor.', 'price' => 300000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/c3/fd/a7/c3fda7f507fdc12e69bdb897fcd767dd.jpg'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catHardware->id, 'title' => 'Jasa Service Software Komputer / Laptop', 'slug' => 'jasa-service-software', 'description' => 'Perbaikan OS, install ulang, pembersihan virus, dan instalasi driver.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/e6/83/68/e683680475d5a08b9ed843af2241f05b.jpg'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Instalasi Jaringan Nirkabel (Wi-Fi / Access Point)', 'slug' => 'instalasi-nirkabel-wifi', 'description' => 'Pemasangan dan konfigurasi Access Point untuk jangkauan sinyal Wi-Fi.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/50/22/e3/5022e39f9369e240c0307a1b2d7e122c.jpg'],
        ];

        foreach ($itemsTkj as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        $workerTkj = \App\Models\WorkerProfile::updateOrCreate(
            ['user_id' => $adminTkj->id],
            [
                'id'           => (string) Str::uuid(),
                'tefa_unit_id' => $unitTkj->id,
                'bio'          => 'Instruktur & Praktisi Teknik Komputer Jaringan',
            ]
        );

        $portfoliosTkj = [
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Konfigurasi Switch D-Link DGS-1100-08V2',
                'description'       => 'Praktik penggunaan switch untuk menghubungkan perangkat dalam jaringan LAN.',
                'thumbnail_url'     => 'images/portofolio/switch.jpeg',
                'status'            => 'approved',
            ],
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Konfigurasi TP-Link Wireless Router',
                'description'       => 'Praktik konfigurasi router untuk menyediakan koneksi jaringan Wi-Fi.',
                'thumbnail_url'     => 'images/portofolio/router.jpeg',
                'status'            => 'approved',
            ],
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Instalasi Kabel UTP & Switch TP-Link TL-SF1016D',
                'description'       => 'Praktik penggunaan switch dan kabel UTP untuk menghubungkan perangkat jaringan.',
                'thumbnail_url'     => 'images/portofolio/kabel.jpeg',
                'status'            => 'approved',
            ],
        ];

        foreach ($portfoliosTkj as $pf) {
            Portfolio::updateOrCreate(
                ['title' => $pf['title']], 
                array_merge($pf, ['id' => (string) Str::uuid()]) 
            );
        }

        // ==========================================
        // 6. JURUSAN ANIMASI (Bu Reka)
        // ==========================================
        $adminAnimasi = User::updateOrCreate(
            ['email' => 'bu.reka@tefa.id'],
            [
                'name'              => 'Bu Reka (Admin Animasi)',
                'password'          => Hash::make('password'),
                'phone'             => '6281234567004',
                'whatsapp_number' => '6281234567004',
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitAnimasi = TefaUnit::updateOrCreate(['slug' => 'tefa-animasi-studio'], [
            'name'        => 'TEFA Animation & Creative Studio',
            'description' => 'Unit produksi Animasi melayani perancangan karakter 2D/3D, ilustrasi digital, video edukasi, animasi promosi, hingga produksi video profil instansi.',
            'banner_url' => asset('assets/images/units/anm.jpeg'),
            'logo_url'   => asset('assets/images/units/logo_anm.jpg'),
            'is_active'   => true,
        ]);
        $adminAnimasi->managedUnits()->syncWithoutDetaching([$unitAnimasi->id]);

        $itemsAnimasi = [
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catDesain->id, 'title' => 'Desain Karakter 2D', 'slug' => 'desain-karakter-2d', 'description' => 'Pembuatan konsep dan desain karakter 2D lengkap untuk komik, game, atau animasi.', 'price' => 350000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/a9/28/4f/a9284fa242a4bd6c087ab9f056961e7b.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catModel3D->id, 'title' => 'Desain Asset 3D', 'slug' => 'desain-asset-3d', 'description' => 'Layanan pemodelan aset 3D siap pakai untuk kebutuhan game engine atau animasi.', 'price' => 500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/4e/39/a4/4e39a4bd7a659ce9435c44d5fb0f4fec.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catMerch->id, 'title' => 'Sticker Custom', 'slug' => 'sticker-custom-animasi', 'description' => 'Pembuatan ilustrasi stiker kustom, stiker digital, atau cetakan merchandise.', 'price' => 50000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/df/de/b3/dfdeb3d852ecb254e428e7cc636af249.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Video Edukasi Singkat 2D', 'slug' => 'video-edukasi-singkat-2d', 'description' => 'Pembuatan explainer video atau konten edukasi berbasis animasi 2D.', 'price' => 1500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/54/fa/a6/54faa66c7d3fd374d789b99088066547.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catDesain->id, 'title' => 'Ilustrasi Digital Custom', 'slug' => 'ilustrasi-digital-custom', 'description' => 'Gambar digital berkualitas tinggi untuk poster, buku, atau materi promosi.', 'price' => 250000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8DFXvGuEckoWAeShMJPezUWEdQeajerL5PQr5jYHr-A&s=10'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Video Promosi Animasi', 'slug' => 'video-promosi-animasi', 'description' => 'Jasa pembuatan iklan komersial atau promosi produk berbentuk video animasi.', 'price' => 2000000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/26/9b/4d/269b4d3ddb24de0200c8b3726cafb71a.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Animasi Maskot Brand', 'slug' => 'animasi-maskot-brand', 'description' => 'Pengembangan dan penjiwaan animasi dari karakter maskot perusahaan.', 'price' => 1200000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/f8/57/19/f85719a148e7a7f0d9625b0da0be25ff.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Video Profil Sekolah / Instansi', 'slug' => 'video-profil-sekolah', 'description' => 'Produksi video company profile yang menggabungkan video real dengan animasi.', 'price' => 3000000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/51/00/4a/51004a62ed8fd4d3a95787114c76eac8.jpg'],
        ];

        foreach ($itemsAnimasi as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // 7. JURUSAN PSPT (Penyiaran & Pertelevisian)
        // ==========================================
        $adminPspt = User::updateOrCreate(
            ['email' => 'admin.pspt@tefa.id'],
            [
                'name'              => 'Admin PSPT (Broadcasting)',
                'password'          => Hash::make('password'),
                'phone'             => '6281234567005',
                'whatsapp_number' => '6281234567005',
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitPspt = TefaUnit::updateOrCreate(['slug' => 'tefa-pspt-broadcasting-house'], [
            'name'        => 'TEFA PSPT Broadcasting House',
            'description' => 'Unit produksi Produksi dan Siaran Program Televisi (PSPT) melayani jasa video shooting, live streaming event, pengerjaan video klip, persewaan peralatan multicam, serta pengisian suara (Voice Over).',
            'banner_url' => asset('assets/images/units/pspt.jpeg'),
            'logo_url'   => asset('assets/images/units/logo_pspt.jpg'),
            'is_active'   => true,
        ]);
        $adminPspt->managedUnits()->syncWithoutDetaching([$unitPspt->id]);

       $itemsPspt = [
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Pembuatan Film Drama', 
        'slug' => 'pembuatan-film-drama', 
        'description' => 'Layanan produksi profesional untuk pembuatan film drama dan skenario menarik.', 
        'price' => 3000000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/drama.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Pembuatan Film Cinematic Berbasis AI', 
        'slug' => 'pembuatan-film-cinematic-berbasis-ai', 
        'description' => 'Produksi film cinematic modern dengan memanfaatkan teknologi AI terkini.', 
        'price' => 3500000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/cinematic.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Jasa Dokumenter', 
        'slug' => 'jasa-dokumenter', 
        'description' => 'Menerima publikasi media dokumenter perusahaan, kegiatan, dan profil institusi.', 
        'price' => 2500000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/dokumenter.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Pelatihan / Workshop Jurnalistik', 
        'slug' => 'pelatihan-workshop-jurnalistik', 
        'description' => 'Program pemberdayaan melalui informasi dan keterampilan bercerita / jurnalistik.', 
        'price' => 1500000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/workshop.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Jasa Talk Show', 
        'slug' => 'jasa-talk-show', 
        'description' => 'Menerima produksi acara talk show live maupun siaran tunda dengan kualitas profesional.', 
        'price' => 2000000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/talkshow.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Pembuatan Iklan Layanan Masyarakat', 
        'slug' => 'pembuatan-iklan-layanan-masyarakat', 
        'description' => 'Pembuatan media kampanye dan iklan layanan masyarakat yang kreatif dan edukatif.', 
        'price' => 2200000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/iklan.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Jasa News Magazine', 
        'slug' => 'jasa-news-magazine', 
        'description' => 'Layanan publikasi media online dan visual berbasis majalah berita profesional.', 
        'price' => 1800000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/magazine.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Jasa Podcast', 
        'slug' => 'jasa-podcast', 
        'description' => 'Menerima produksi acara podcast live dan siaran tunda (on-demand) berkualitas tinggi.', 
        'price' => 1200000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/podcast.jpeg')
    ],
    [
        'tefa_unit_id' => $unitPspt->id, 
        'category_id' => $catBroadcasting->id, 
        'title' => 'Jasa Feature', 
        'slug' => 'jasa-feature', 
        'description' => 'Menerima publikasi media feature mencakup tema sejarah, wisata, kuliner, dan lainnya.', 
        'price' => 1700000, 
        'item_type' => ItemType::Jasa, 
        'status' => ItemStatus::Published, 
        'thumbnail_url' => asset('assets/images/units/feature.jpeg')
    ],
];

        foreach ($itemsPspt as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

         // Register WorkerSeeder at the very end
        $this->call(WorkerSeeder::class);
    }
}