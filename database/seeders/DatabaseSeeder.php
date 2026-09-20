<?php

namespace Database\Seeders;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Enums\UserRole;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Task;
use App\Models\TefaUnit;
use App\Models\User;
use App\Models\WorkerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. SUPER ADMIN
        // ==========================================
        User::updateOrCreate(
            ['email' => 'superadmin@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Super Admin TEFA',
                'password'          => Hash::make('password'),
                'role'              => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]
        );

        // ==========================================
        // 2. KATEGORI UTAMA
        // ==========================================
        $catWeb      = Category::updateOrCreate(['name' => 'Web Development']);
        $catMobile   = Category::updateOrCreate(['name' => 'Mobile App']);
        $catDesign   = Category::updateOrCreate(['name' => 'Desain Grafis']);
        $catVideo    = Category::updateOrCreate(['name' => 'Video Editing']);
        $catJaringan = Category::updateOrCreate(['name' => 'Jaringan Komputer']);
        $catHardware = Category::updateOrCreate(['name' => 'Servis Hardware']);
        $catMerch    = Category::updateOrCreate(['name' => 'Merchandise & Cetak']);
        $cat3d       = Category::updateOrCreate(['name' => '3D & Animasi']);

        // ==========================================
        // 3. JURUSAN PPLG
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

        $unitPplg = TefaUnit::updateOrCreate(['slug' => 'tefa-pplg-software-house'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA PPLG Software House',
            'description' => 'Unit layanan Pengembangan Perangkat Lunak dan Gim (PPLG) SMKN 4 Tanjungpinang yang melayani pembuatan website, aplikasi mobile, dan software kustom.',
            'banner_url'  => asset('assets/images/units/pplg.png'),
            'logo_url'    => asset('assets/images/units/logo_pplg.jpeg'),
            'is_active'   => true,
        ]);
        $adminPplg->managedUnits()->syncWithoutDetaching([$unitPplg->id]);

        $itemsPplg = [
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Jasa Pembuatan Landing Page Company Profile', 'slug' => 'jasa-landing-page-company-profile', 'description' => 'Landing page profesional responsif, modern, dan cepat menggunakan Laravel / Vue.js.', 'price' => 1500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80'],
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catMobile->id, 'title' => 'Pembuatan Aplikasi Mobile Android / iOS Custom', 'slug' => 'aplikasi-mobile-custom', 'description' => 'Aplikasi Android/iOS berbasis Flutter untuk kebutuhan bisnis, sekolah, atau UMKM.', 'price' => 4500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&q=80'],
        ];

        foreach ($itemsPplg as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // PORTOFOLIO PPLG (KARYA UNGGULAN)
        // ==========================================
        $workerPplg = WorkerProfile::updateOrCreate(
            ['user_id' => $adminPplg->id],
            [
                'id'           => (string) Str::uuid(),
                'tefa_unit_id' => $unitPplg->id,
                'bio'          => 'Tim Pengembang Perangkat Lunak & Gim TEFA PPLG SMKN 4 Tanjungpinang',
            ]
        );

        $portfoliosPplg = [
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerPplg->id,
                'title'             => 'Aplikasi Absensi Digital (Absen Silap)',
                'description'       => 'Sistem absensi siswa berbasis web yang dirancang untuk mencatat kehadiran secara real-time, dilengkapi fitur rekapitulasi data otomatis untuk memudahkan pihak sekolah.',
                'thumbnail_url'     => 'images/portofolio/silap.png',
                'status'            => 'approved',
            ],
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerPplg->id,
                'title'             => 'Platform JMB',
                'description'       => 'Portal layanan dan pusat akses informasi digital terpadu. Kunjungi platformnya di: https://jasamultiberkah.com/', 
                'thumbnail_url'     => 'images/portofolio/jmb.png',
                'status'            => 'approved',
            ],
            [
                'tefa_unit_id'      => $unitPplg->id,
                'worker_profile_id' => $workerPplg->id,
                'title'             => 'Game VR: Warisan di Balik Layar',
                'description'       => 'Gim berbasis Virtual Reality (VR) interaktif yang mengajak pemain menyelami cerita, petualangan, dan misteri di balik sebuah layar produksi. Dikembangkan sebagai media hiburan sekaligus sarana eksplorasi imersif.',
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

        // ==========================================
        // 4. JURUSAN DKV
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

        $unitDkv = TefaUnit::updateOrCreate(['slug' => 'tefa-dkv-creative-studio'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA DKV Creative Studio',
            'description' => 'Studio kreatif Desain Komunikasi Visual (DKV) SMKN 4 Tanjungpinang melayani branding, desain grafis, ilustrasi, merchandise, dan percetakan.',
            'banner_url'  => asset('assets/images/units/dkv.png'),
            'logo_url'    =>  asset('assets/images/units/logo_dkv.jpg'),
            'is_active'   => true,
        ]);
        $adminDkv->managedUnits()->syncWithoutDetaching([$unitDkv->id]);

        $itemsDkv = [
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesign->id, 'title' => 'Desain Logo & Brand Identity Package', 'slug' => 'desain-logo-brand-identity', 'description' => 'Paket branding lengkap termasuk konsep logo, filosofi, color palette, dan brand guideline PDF.', 'price' => 750000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Cetak Kaos Sablon DTF Custom', 'slug' => 'cetak-kaos-sablon-dtf', 'description' => 'Cetak kaos sablon DTF berkualitas tinggi, bahan Cotton Combed 30s halus dan adem.', 'price' => 85000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&q=80'],
        ];

        foreach ($itemsDkv as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // 5. JURUSAN TJKT / TKJ
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
            'name'        => 'TEFA TJKT Network Solutions',
            'description' => 'Unit layanan Teknik Jaringan Komputer dan Telekomunikasi (TJKT/TKJ) SMKN 4 Tanjungpinang spesialis instalasi jaringan, server, fiber optic, serta servis komputer.',
            'banner_url'  => asset('assets/images/units/tkj.png'),
            'logo_url'    => asset('assets/images/units/logo_tkj.jpg'),
            'is_active'   => true,
        ]);
        $adminTkj->managedUnits()->syncWithoutDetaching([$unitTkj->id]);

        $itemsTkj = [
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Instalasi Jaringan LAN (Per Titik)', 'slug' => 'instalasi-jaringan-lan-pertitik', 'description' => 'Pemasangan kabel dan titik jaringan LAN dari switch ke PC. Tarif Rp50.000/titik.', 'price' => 50000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&q=80'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catHardware->id, 'title' => 'Jasa Service Software Komputer / Laptop', 'slug' => 'jasa-service-software', 'description' => 'Perbaikan OS, install ulang, pembersihan virus, dan instalasi driver.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1588702547919-26089e690ecc?w=600&q=80'],
        ];

        foreach ($itemsTkj as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // Worker Profile & Portofolio TKJ (Diperbaiki tanpa field cover_image)
        $workerTkj = WorkerProfile::updateOrCreate(
            ['user_id' => $adminTkj->id],
            [
                'id'           => (string) Str::uuid(),
                'tefa_unit_id' => $unitTkj->id,
                'bio'          => 'Instruktur & Praktisi Teknik Komputer Jaringan SMKN 4 Tanjungpinang',
            ]
        );

        $portfoliosTkj = [
            [
                'tefa_unit_id'      => $unitTkj->id,
                'worker_profile_id' => $workerTkj->id,
                'title'             => 'Konfigurasi Switch D-Link DGS-1100-08V2',
                'description'       => 'Praktik penggunaan switch untuk menghubungkan perangkat dalam jaringan LAN.',
                'thumbnail_url'     => 'images/portofolio/switch.jpeg', // Pastikan pakai thumbnail_url
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
        // 6. JURUSAN ANIMASI
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
            'name'        => 'TEFA Animasi Studio',
            'description' => 'Unit produksi Animasi SMKN 4 Tanjungpinang untuk animasi 2D/3D, 3D modeling, asset game, serta efek visual.',
            'banner_url'  => asset('assets/images/units/anm.png'),
            'logo_url'    => asset('assets/images/units/logo_anm.jpg'),
            'is_active'   => true,
        ]);
        $adminAnimasi->managedUnits()->syncWithoutDetaching([$unitAnimasi->id]);

        $itemsAnimasi = [
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $cat3d->id, 'title' => 'Jasa Pembuatan 3D Asset & Character Modeling', 'slug' => '3d-asset-character-modeling', 'description' => 'Pembuatan karakter 3D high-poly/low-poly untuk game dan animasi.', 'price' => 2000000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=600&q=80'],
        ];

        foreach ($itemsAnimasi as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // 7. JURUSAN PSPT
        // ==========================================
        $adminPspt = User::updateOrCreate(
            ['email' => 'admin.pspt@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Admin PSPT',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $unitPspt = TefaUnit::updateOrCreate(['slug' => 'tefa-pspt-broadcasting'], [
            'id'          => (string) Str::uuid(),
            'name'        => 'TEFA PSPT Media & Broadcasting',
            'description' => 'Unit layanan Produksi dan Siaran Program Televisi (PSPT) SMKN 4 Tanjungpinang untuk perfilman, dokumentasi acara, live streaming, dan video profil.',
            'banner_url'  => asset('assets/images/units/pspt.png'),
            'logo_url'    => asset('assets/images/units/logo_pspt.jpg'),
            'is_active'   => true,
        ]);
        $adminPspt->managedUnits()->syncWithoutDetaching([$unitPspt->id]);

        $itemsPspt = [
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catVideo->id, 'title' => 'Jasa Videografi & Dokumentasi Event', 'slug' => 'videografi-dokumentasi-event', 'description' => 'Layanan perekaman video profesional untuk acara, perpisahan, dan company profile.', 'price' => 2500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80'],
        ];

        foreach ($itemsPspt as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

        // ==========================================
        // 8. PROJECT, SKILL, & TASK
        // ==========================================
        $skillPplg = Skill::updateOrCreate(
            ['name' => 'Laravel & Web Development'],
            [
                'slug' => Str::slug('Laravel & Web Development'),
            ]
        );

        $projectDemo = Project::updateOrCreate(
            ['title' => 'Pengembangan Sistem Informasi TEFA SMKN 4'],
            [
                'tefa_unit_id' => $unitPplg->id,
                'client_name'  => 'Dinas Pendidikan Kepri',
                'deadline'     => now()->addMonths(2),
                'created_by'   => $adminPplg->id,
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Selesaikan Fitur Dashboard Worker & Pengumpulan Tugas'],
            [
                'tefa_unit_id'            => $unitPplg->id, // Menyertakan tefa_unit_id
                'project_id'              => $projectDemo->id,
                'skill_id'                => $skillPplg->id,
                'description'             => "1. Selesaikan kodingan Blade untuk halaman detail tugas.\n2. Pastikan form upload file dan ganti status berfungsi.\n3. Lakukan pengujian form dengan input dummy.",
                'priority'                => 'high',
                'status'                  => 'in_progress',
                'ai_recommendation_notes' => 'Siswa memiliki tingkat kesesuaian keahlian 95% pada stack Laravel & Tailwind CSS.',
                'proof_notes'             => 'Sudah menyelesaikan tampilan UI dan integrasi route backend.',
                'proof_file_url'          => 'https://github.com/username/project-tefa',
            ]
        );
    }
}