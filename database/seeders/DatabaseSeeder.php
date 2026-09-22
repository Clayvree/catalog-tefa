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
                'id'                => (string) Str::uuid(),
                'name'              => 'Super Administrator',
                'password'          => Hash::make('password'),
                'role'              => UserRole::SuperAdmin,
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
                'id'                => (string) Str::uuid(),
                'name'              => 'Bu Indra (Admin PPLG)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $workerPPLG = User::updateOrCreate(
            ['email' => 'nabil@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Nabil (Worker PPLG)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $unitPplg = TefaUnit::updateOrCreate(['slug' => 'tefa-pplg-software-house'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA PPLG Software House',
            'description' => 'Unit produksi Pengembangan Perangkat Lunak dan GIM (PPLG) melayani pembuatan aplikasi, website, game VR, dan sistem otomasi digital.',
            'banner_url'  => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&q=80',
            'logo_url'    => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&q=80',
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
                'title'             => 'Aplikasi Point of Sales (POS)',
                'description'       => 'Sistem kasir berbasis web menggunakan Laravel & Vue.js.',
                'status'            => 'approved',
                'review_notes'      => 'Sangat memuaskan! Kode bersih dan fitur kasir berfungsi 100% tanpa bug.',
                'reviewed_by'       => $adminPplg->id,
                'reviewed_at'       => now(),
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&q=80',
            ],
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerProfilePPLG->id,
                'title'             => 'Landing Page Sekolah',
                'description'       => 'Website company profile interaktif untuk sekolah.',
                'status'            => 'pending',
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&q=80',
            ],
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerProfilePPLG->id,
                'title'             => 'Sistem E-Voting OSIS',
                'description'       => 'Aplikasi voting dengan keamanan ganda.',
                'status'            => 'rejected',
                'review_notes'      => 'Tolong perbaiki UI di bagian hasil voting, masih tumpang tindih di layar HP.',
                'reviewed_by'       => $adminPplg->id,
                'reviewed_at'       => now(),
                'thumbnail_url'     => 'https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=600&q=80',
            ]
        ];

        foreach ($portfoliosPplg as $pf) {
            if (class_exists('App\Models\Portfolio')) {
                Portfolio::updateOrCreate(
                    ['title' => $pf['title']],
                    array_merge($pf, ['id' => (string) Str::uuid()])
                );
            }
        }

        $itemsPplg = [
            [
                'tefa_unit_id'  => $unitPplg->id,
                'category_id'   => $catGame->id,
                'title'         => 'Game VR Museum Virtual',
                'slug'          => 'game-vr-museum',
                'description'   => 'Aplikasi permainan Virtual Reality (VR) edukatif yang mensimulasikan tur museum secara interaktif dan imersif 3D.',
                'price'         => 4500000,
                'item_type'     => ItemType::Jasa,
                'status'        => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1592478411213-6153e4ebc07d?w=600&q=80',
            ],
            [
                'tefa_unit_id'  => $unitPplg->id,
                'category_id'   => $catLogistik->id,
                'title'         => 'Jasa Multi Berkah (Sistem Ekspedisi Kargo)',
                'slug'          => 'jasa-multi-berkah-ekspedisi-kargo',
                'description'   => 'Sistem manajemen dan pelacakan pengiriman barang kargo/ekspedisi lengkap dengan cetak resi dan laporan transaksi.',
                'price'         => 3500000,
                'item_type'     => ItemType::Jasa,
                'status'        => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&q=80',
            ],
            [
                'tefa_unit_id'  => $unitPplg->id,
                'category_id'   => $catApp->id,
                'title'         => 'Aplikasi Absen Silap (Sistem Informasi Lapangan)',
                'slug'          => 'aplikasi-absen-silap',
                'description'   => 'Aplikasi presensi dan absensi digital real-time berbasis GPS/QR code yang memudahkan pendataan kehadiran secara akurat.',
                'price'         => 2500000,
                'item_type'     => ItemType::Jasa,
                'status'        => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=600&q=80',
            ],
            [
                'tefa_unit_id'  => $unitPplg->id,
                'category_id'   => $catIoT->id,
                'title'         => 'Sistem Display Jadwal Masjid Digital',
                'slug'          => 'jadwal-masjid-digital',
                'description'   => 'Aplikasi display jadwal sholat otomatis berbasis layar TV/LED lengkap dengan pengingat azan, iqomah, dan pengumuman masjid.',
                'price'         => 1500000,
                'item_type'     => ItemType::Produk,
                'status'        => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=600&q=80',
            ],
            [
                'tefa_unit_id'  => $unitPplg->id,
                'category_id'   => $catIoT->id,
                'title'         => 'Sistem Bel Sekolah Otomatis',
                'slug'          => 'bel-sekolah-otomatis',
                'description'   => 'Perangkat dan perangkat lunak bel sekolah otomatis berbasis jadwal terprogram dengan mp3 suara nada bel dua bahasa.',
                'price'         => 1200000,
                'item_type'     => ItemType::Produk,
                'status'        => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80',
            ],
            [
                'tefa_unit_id'  => $unitPplg->id,
                'category_id'   => $catApp->id,
                'title'         => 'Layanan Pembuatan Aplikasi Custom & Website',
                'slug'          => 'layanan-pembuatan-aplikasi-website',
                'description'   => 'Jasa kustomisasi dan pembuatan software, landing page, company profile, maupun aplikasi mobile/web sesuai kebutuhan klien.',
                'price'         => 3000000,
                'item_type'     => ItemType::Jasa,
                'status'        => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80',
            ],
        ];

        foreach ($itemsPplg as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // 4. JURUSAN DKV (Pak Nova)
        // ==========================================
        $adminDkv = User::updateOrCreate(
            ['email' => 'pak.nova@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Pak Nova (Admin DKV)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitDkv = TefaUnit::updateOrCreate(['slug' => 'tefa-dkv-creative-agency'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA DKV Creative Agency',
            'description' => 'Unit produksi Desain Komunikasi Visual (DKV) melayani jasa desain grafis, cetak outdoor/indoor, merchandise, branding kemasan, dan media visual.',
            'banner_url'  => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?w=1200&q=80',
            'logo_url'    => 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea?w=200&q=80',
            'is_active'   => true,
        ]);
        $adminDkv->managedUnits()->syncWithoutDetaching([$unitDkv->id]);

        $itemsDkv = [
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Gantungan Kunci Custom', 'slug' => 'gantungan-kunci-custom', 'description' => 'Pembuatan gantungan kunci custom dengan berbagai bahan dan desain menarik.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesain->id, 'title' => 'Jasa Mockup & Desain Visual', 'slug' => 'jasa-mockup-dan-desain', 'description' => 'Layanan pembuatan mockup produk dan eksplorasi desain visual profesional.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesain->id, 'title' => 'Desain Kaos & Pakaian', 'slug' => 'desain-kaos-pakaian', 'description' => 'Jasa perancangan desain apparel/pakaian unik untuk komunitas maupun brand.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Spanduk', 'slug' => 'desain-cetak-spanduk', 'description' => 'Layanan pembuatan desain sekaligus pencetakan spanduk promosi/event.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1563770660941-20978e870e26?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Baliho', 'slug' => 'desain-cetak-baliho', 'description' => 'Jasa pembuatan desain dan cetak baliho ukuran besar (outdoor).', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Banner', 'slug' => 'desain-cetak-banner', 'description' => 'Layanan cetak banner berdiri (X-Banner, Roll Banner) siap pakai.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Poster', 'slug' => 'desain-cetak-poster', 'description' => 'Pembuatan poster promosi atau cetak seni dengan kertas presisi tinggi.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesain->id, 'title' => 'Desain Logo & Identitas Brand', 'slug' => 'desain-logo-identitas-brand', 'description' => 'Pembuatan logo vektor dengan konsep mendalam dan panduan identitas visual.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Kemasan', 'slug' => 'desain-cetak-kemasan', 'description' => 'Perancangan struktur dan grafik packaging kemasan produk UMKM.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catCetak->id, 'title' => 'Desain & Cetak Undangan', 'slug' => 'desain-cetak-undangan', 'description' => 'Pencetakan kartu undangan acara dengan pilihan kertas dan finishing khusus.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600&q=80'],
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
                'id'                => (string) Str::uuid(),
                'name'              => 'Ibu Ayu (Admin TKJ)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitTkj = TefaUnit::updateOrCreate(['slug' => 'tefa-tkj-network-solutions'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA TKJ Network Solutions',
            'description' => 'Unit layanan Teknik Komputer & Jaringan (TKJ) spesialis perancangan, instalasi jaringan LAN/Wireless, pemasangan internet, serta servis software.',
            'banner_url'  => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=1200&q=80',
            'logo_url'    => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=200&q=80',
            'is_active'   => true,
        ]);
        $adminTkj->managedUnits()->syncWithoutDetaching([$unitTkj->id]);

        $itemsTkj = [
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Instalasi Jaringan LAN (Per Titik)', 'slug' => 'instalasi-jaringan-lan-pertitik', 'description' => 'Pemasangan kabel dan titik jaringan LAN dari switch ke PC. Tarif Rp50.000/titik.', 'price' => 50000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&q=80'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Pemasangan Jaringan Internet (SSA)', 'slug' => 'pemasangan-jaringan-internet-ssa', 'description' => 'Penyambungan dan konfigurasi jaringan internet untuk rumah atau kantor.', 'price' => 300000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&q=80'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catHardware->id, 'title' => 'Jasa Service Software Komputer / Laptop', 'slug' => 'jasa-service-software', 'description' => 'Perbaikan OS, install ulang, pembersihan virus, dan instalasi driver.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1588702547919-26089e690ecc?w=600&q=80'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Instalasi Jaringan Nirkabel (Wi-Fi / Access Point)', 'slug' => 'instalasi-nirkabel-wifi', 'description' => 'Pemasangan dan konfigurasi Access Point untuk jangkauan sinyal Wi-Fi.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1563770660941-20978e870e26?w=600&q=80'],
        ];

        foreach ($itemsTkj as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // --- BUAT WORKER PROFILE UNTUK BU AYU (TKJ) ---
        $workerTkj = \App\Models\WorkerProfile::updateOrCreate(
            ['user_id' => $adminTkj->id],
            [
                'id'           => (string) Str::uuid(),
                'tefa_unit_id' => $unitTkj->id,
                'bio'          => 'Instruktur & Praktisi Teknik Komputer Jaringan',
            ]
        );

        // --- PORTOFOLIO TKJ ---
        $portfoliosTkj = [
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Konfigurasi Switch D-Link DGS-1100-08V2',
                'description'       => 'Praktik penggunaan switch untuk menghubungkan perangkat dalam jaringan LAN.',
            ],
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Konfigurasi TP-Link Wireless Router',
                'description'       => 'Praktik konfigurasi router untuk menyediakan koneksi jaringan Wi-Fi.',
            ],
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Instalasi Kabel UTP & Switch TP-Link TL-SF1016D',
                'description'       => 'Praktik penggunaan switch dan kabel UTP untuk menghubungkan perangkat jaringan.',
            ],
        ];

        foreach ($portfoliosTkj as $pf) {
            if (class_exists('App\Models\Portfolio')) {
                Portfolio::updateOrCreate(
                    ['title' => $pf['title']],
                    array_merge($pf, ['id' => (string) Str::uuid()])
                );
            }
        }

        // ==========================================
        // 6. JURUSAN ANIMASI (Bu Reka)
        // ==========================================
        $adminAnimasi = User::updateOrCreate(
            ['email' => 'bu.reka@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Bu Reka (Admin Animasi)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitAnimasi = TefaUnit::updateOrCreate(['slug' => 'tefa-animasi-studio'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA Animation & Creative Studio',
            'description' => 'Unit produksi Animasi melayani perancangan karakter 2D/3D, ilustrasi digital, video edukasi, animasi promosi, hingga produksi video profil instansi.',
            'banner_url'  => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1200&q=80',
            'logo_url'    => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&q=80',
            'is_active'   => true,
        ]);
        $adminAnimasi->managedUnits()->syncWithoutDetaching([$unitAnimasi->id]);

        $itemsAnimasi = [
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catDesain->id, 'title' => 'Desain Karakter 2D', 'slug' => 'desain-karakter-2d', 'description' => 'Pembuatan konsep dan desain karakter 2D lengkap untuk komik, game, atau animasi.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catModel3D->id, 'title' => 'Desain Asset 3D', 'slug' => 'desain-asset-3d', 'description' => 'Layanan pemodelan aset 3D siap pakai untuk kebutuhan game engine atau animasi.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catMerch->id, 'title' => 'Sticker Custom', 'slug' => 'sticker-custom-animasi', 'description' => 'Pembuatan ilustrasi stiker kustom, stiker digital, atau cetakan merchandise.', 'price' => 0, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1572375992501-4b0892d50c69?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Video Edukasi Singkat 2D', 'slug' => 'video-edukasi-singkat-2d', 'description' => 'Pembuatan explainer video atau konten edukasi berbasis animasi 2D.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catDesain->id, 'title' => 'Ilustrasi Digital Custom', 'slug' => 'ilustrasi-digital-custom', 'description' => 'Gambar digital berkualitas tinggi untuk poster, buku, atau materi promosi.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Video Promosi Animasi', 'slug' => 'video-promosi-animasi', 'description' => 'Jasa pembuatan iklan komersial atau promosi produk berbentuk video animasi.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1536240478700-b869070f9279?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Animasi Maskot Brand', 'slug' => 'animasi-maskot-brand', 'description' => 'Pengembangan dan penjiwaan animasi dari karakter maskot perusahaan.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideoAnim->id, 'title' => 'Video Profil Sekolah / Instansi', 'slug' => 'video-profil-sekolah', 'description' => 'Produksi video company profile yang menggabungkan video real dengan animasi.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&q=80'],
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
                'id'                => (string) Str::uuid(),
                'name'              => 'Admin PSPT (Broadcasting)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitPspt = TefaUnit::updateOrCreate(['slug' => 'tefa-pspt-broadcasting-house'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA PSPT Broadcasting House',
            'description' => 'Unit produksi Produksi dan Siaran Program Televisi (PSPT) melayani jasa video shooting, live streaming event, pengerjaan video klip, persewaan peralatan multicam, serta pengisian suara (Voice Over).',
            'banner_url'  => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=1200&q=80',
            'logo_url'    => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=200&q=80',
            'is_active'   => true,
        ]);
        $adminPspt->managedUnits()->syncWithoutDetaching([$unitPspt->id]);

        $itemsPspt = [
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catBroadcasting->id, 'title' => 'Jasa Production Video Commercial / Iklan', 'slug' => 'jasa-production-video-commercial', 'description' => 'Produksi video iklan komersial dari penulisan naskah, pengambilan gambar sinematik, hingga editing akhir.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80'],
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catBroadcasting->id, 'title' => 'Layanan Live Streaming Multicam Event', 'slug' => 'layanan-live-streaming-multicam', 'description' => 'Jasa liputan siaran langsung (live streaming) multi-kamera untuk seminar, wisuda, atau event besar di YouTube/Zoom.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&q=80'],
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catBroadcasting->id, 'title' => 'Jasa Video Editing & Post-Production', 'slug' => 'jasa-video-editing-post-production', 'description' => 'Pengeditan video profesional, color grading, penambahan efek suara, dan sound mixing.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1535016120720-40c646be5580?w=600&q=80'],
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catBroadcasting->id, 'title' => 'Jasa Voice Over & Sound Recording', 'slug' => 'jasa-voice-over-sound-recording', 'description' => 'Perekaman isi suara (dubbing/VO) untuk video profil, iklan, atau narasi film menggunakan studio kedap suara.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?w=600&q=80'],
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catBroadcasting->id, 'title' => 'Sewa Equipment Broadcast & Shooting Kit', 'slug' => 'sewa-equipment-broadcast', 'description' => 'Penyewaan alat produksi video seperti kamera sinema, lighting studio, microphone wireless, dan stabilizer.', 'price' => 0, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=600&q=80'],
        ];

        foreach ($itemsPspt as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // Register WorkerSeeder at the very end
        $this->call(WorkerSeeder::class);
    }
}