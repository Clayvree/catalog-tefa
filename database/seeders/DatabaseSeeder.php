<?php

namespace Database\Seeders;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Enums\PortfolioStatus;
use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\CatalogItem;
use App\Models\Category;
use App\Models\TefaUnit;
use App\Models\User;
use App\Models\WorkerProfile;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Super Administrator',
                'password'          => Hash::make('password'),
                'role'              => UserRole::SuperAdmin,
                'email_verified_at' => now(),
            ]
        );

        $adminRpl = User::updateOrCreate(
            ['email' => 'admin.rpl@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Bpk. Hendra S.Kom (Admin RPL)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $adminDkv = User::updateOrCreate(
            ['email' => 'admin.dkv@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Ibu Maya S.Sn (Admin DKV)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::AdminJurusan,
                'email_verified_at' => now(),
            ]
        );

        $worker1 = User::updateOrCreate(
            ['email' => 'worker1@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Ahmad Rizky (Dev)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        $worker2 = User::updateOrCreate(
            ['email' => 'worker2@tefa.id'],
            [
                'id'                => (string) Str::uuid(),
                'name'              => 'Siti Nurhaliza (Designer)',
                'password'          => Hash::make('password'),
                'role'              => UserRole::Worker,
                'email_verified_at' => now(),
            ]
        );

        // 2. Skills
        $skills = [
            ['name' => 'Laravel & PHP Backend', 'slug' => 'laravel-php', 'color_hex' => '#ef4444'],
            ['name' => 'Vue.js & Frontend Dev', 'slug' => 'vue-frontend', 'color_hex' => '#10b981'],
            ['name' => 'UI/UX Design (Figma)', 'slug' => 'ui-ux-figma', 'color_hex' => '#8b5cf6'],
            ['name' => 'Desain Logo & Vektor', 'slug' => 'desain-logo-vektor', 'color_hex' => '#f59e0b'],
            ['name' => 'Video Editing & Reels', 'slug' => 'video-editing-reels', 'color_hex' => '#ec4899'],
            ['name' => 'Tune-Up Mesin Injeksi', 'slug' => 'tune-up-mesin', 'color_hex' => '#3b82f6'],
            ['name' => 'Pastry & Bakery Artisan', 'slug' => 'pastry-bakery', 'color_hex' => '#d97706'],
        ];

        $skillModels = [];
        foreach ($skills as $s) {
            $skillModels[$s['slug']] = Skill::updateOrCreate(['slug' => $s['slug']], $s);
        }

        // 3. Categories
        $catWeb = Category::updateOrCreate(['name' => 'Web & Software'], ['type' => ItemType::Jasa]);
        $catDkv = Category::updateOrCreate(['name' => 'Desain & Branding'], ['type' => ItemType::Jasa]);
        $catVideo = Category::updateOrCreate(['name' => 'Video & Animasi'], ['type' => ItemType::Jasa]);
        $catOtomotif = Category::updateOrCreate(['name' => 'Servis Otomotif'], ['type' => ItemType::Jasa]);
        $catKuliner = Category::updateOrCreate(['name' => 'Kuliner & Bakery'], ['type' => ItemType::Produk]);
        $catBusana = Category::updateOrCreate(['name' => 'Fashion & Kriya'], ['type' => ItemType::Produk]);

        // 4. TEFA Units
        $unitRpl = TefaUnit::updateOrCreate(['slug' => 'tefa-rpl-software-house'], [
            'id' => (string) Str::uuid(),
            'name' => 'TEFA RPL Software House',
            'description' => 'Unit produksi perangkat lunak terdepan dengan layanan pembuatan website kustom, aplikasi mobile Android/iOS, sistem POS kasir, automasi bisnis, dan integrasi AI.',
            'banner_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&q=80',
            'logo_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200&q=80',
            'is_active' => true,
        ]);

        $unitDkv = TefaUnit::updateOrCreate(['slug' => 'tefa-dkv-creative-studio'], [
            'id' => (string) Str::uuid(),
            'name' => 'TEFA DKV Creative Agency',
            'description' => 'Studio kreatif multimedia spesialis identitas visual, desain logo filosofis, konten reels & video promosi produk, kemasan packaging, hingga cetak offset.',
            'banner_url' => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?w=1200&q=80',
            'logo_url' => 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea?w=200&q=80',
            'is_active' => true,
        ]);

        $unitOto = TefaUnit::updateOrCreate(['slug' => 'tefa-otomotif-autocare'], [
            'id' => (string) Str::uuid(),
            'name' => 'TEFA Otomotif & AutoCare',
            'description' => 'Bengkel modern Teaching Factory berstandar APM dengan layanan tune-up injeksi, diagnosa scanner komputer ECU, carbon cleaner, dan salon detailing cat mobil.',
            'banner_url' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=1200&q=80',
            'logo_url' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=200&q=80',
            'is_active' => true,
        ]);

        $unitKuliner = TefaUnit::updateOrCreate(['slug' => 'tefa-culinary-bakery'], [
            'id' => (string) Str::uuid(),
            'name' => 'TEFA Culinary & Artisan Bakery',
            'description' => 'Produksi pastry, roti higienis butter premium, catering prasmanan event industri, dan bento box rapat dengan standar kebersihan higienis BPOM & sertifikasi Halal.',
            'banner_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1200&q=80',
            'logo_url' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=200&q=80',
            'is_active' => true,
        ]);

        // Link Admin to Unit
        $adminRpl->managedUnits()->syncWithoutDetaching([$unitRpl->id]);
        $adminDkv->managedUnits()->syncWithoutDetaching([$unitDkv->id]);

        // 5. Worker profiles with Skills
        $profile1 = WorkerProfile::updateOrCreate(['user_id' => $worker1->id], [
            'id' => (string) Str::uuid(),
            'tefa_unit_id' => $unitRpl->id,
            'nisn' => '0054321987',
            'class_name' => 'XII RPL 1',
            'bio' => 'Fullstack Web Developer spesialis Laravel, Vue, & API integration.',
        ]);

        if (isset($skillModels['laravel-php'], $skillModels['vue-frontend'])) {
            $profile1->skills()->syncWithoutDetaching([
                $skillModels['laravel-php']->id => ['proficiency_level' => 'advanced'],
                $skillModels['vue-frontend']->id => ['proficiency_level' => 'intermediate'],
            ]);
        }

        $profile2 = WorkerProfile::updateOrCreate(['user_id' => $worker2->id], [
            'id' => (string) Str::uuid(),
            'tefa_unit_id' => $unitDkv->id,
            'nisn' => '0054321988',
            'class_name' => 'XII DKV 2',
            'bio' => 'Graphic Designer & Motion Graphic artist dengan pengalaman project UMKM.',
        ]);

        if (isset($skillModels['ui-ux-figma'], $skillModels['desain-logo-vektor'])) {
            $profile2->skills()->syncWithoutDetaching([
                $skillModels['ui-ux-figma']->id => ['proficiency_level' => 'advanced'],
                $skillModels['desain-logo-vektor']->id => ['proficiency_level' => 'advanced'],
            ]);
        }

        // 6. Sample Projects & Sub-tasks
        $project1 = Project::updateOrCreate(
            ['title' => 'Sistem Informasi & Kasir Toko Sembako Berkah'],
            [
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $unitRpl->id,
                'created_by' => $adminRpl->id,
                'client_name' => 'H. Suryanto (Toko Berkah)',
                'client_contact' => '081298765432',
                'description' => 'Aplikasi POS kasir berbasis web dengan fitur cetak struk thermal, laporan laba rugi bulanan, dan stok barang multi-gudang.',
                'final_price' => 2400000,
                'status' => ProjectStatus::Active,
                'deadline' => now()->addDays(14),
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Setup Database & Autentikasi Pengguna Kasir'],
            [
                'id' => (string) Str::uuid(),
                'project_id' => $project1->id,
                'tefa_unit_id' => $unitRpl->id,
                'assigned_worker_id' => $profile1->id,
                'skill_id' => $skillModels['laravel-php']->id ?? null,
                'description' => 'Konfigurasi skema database tabel users, items, transactions dan role kasir vs admin.',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::Done,
                'ai_recommendation_notes' => 'Cocok dengan keahlian Laravel & Database Ahmad Rizky.',
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Implementasi Modul Kasir & Print Struk'],
            [
                'id' => (string) Str::uuid(),
                'project_id' => $project1->id,
                'tefa_unit_id' => $unitRpl->id,
                'assigned_worker_id' => $profile1->id,
                'skill_id' => $skillModels['vue-frontend']->id ?? null,
                'description' => 'Pembuatan tampilan kasir cepat dengan tombol keyboard shortcut dan cetak Bluetooth POS printer.',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::InProgress,
                'ai_recommendation_notes' => 'Membutuhkan kemampuan Frontend Vue.js.',
            ]
        );

        $project2 = Project::updateOrCreate(
            ['title' => 'Desain Branding & Packaging Kopi Rempah Nusantara'],
            [
                'id' => (string) Str::uuid(),
                'tefa_unit_id' => $unitDkv->id,
                'created_by' => $adminDkv->id,
                'client_name' => 'Ibu Ratna (CV Kopi Rempah)',
                'client_contact' => '081345678901',
                'description' => 'Pembuatan desain logo filosofis, kemasan pouch zipper kopi 250gr, dan label stiker toples.',
                'final_price' => 800000,
                'status' => ProjectStatus::Active,
                'deadline' => now()->addDays(7),
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Eksplorasi Konsep Logo Vektor Kopi Rempah'],
            [
                'id' => (string) Str::uuid(),
                'project_id' => $project2->id,
                'tefa_unit_id' => $unitDkv->id,
                'assigned_worker_id' => $profile2->id,
                'skill_id' => $skillModels['desain-logo-vektor']->id ?? null,
                'description' => 'Buat 3 opsi konsep logo vektor yang menggabungkan elemen biji kopi dan cengkeh tradisional.',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Todo,
                'ai_recommendation_notes' => 'Siti Nurhaliza memiliki keahlian Desain Logo Vektor tingkat lanjut.',
            ]
        );

        // 7. Catalog Items
        $items = [
            [
                'tefa_unit_id' => $unitRpl->id,
                'category_id' => $catWeb->id,
                'title' => 'Pembuatan Website Company Profile Modern & SEO Friendly',
                'slug' => 'website-company-profile-modern',
                'description' => 'Layanan pembuatan website profil perusahaan responsif, kilat, elegan dengan integrasi WhatsApp chat, Google Maps, formulir kontak, dan panel admin mandiri.',
                'price' => 1500000,
                'item_type' => ItemType::Jasa,
                'status' => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80',
            ],
            [
                'tefa_unit_id' => $unitRpl->id,
                'category_id' => $catWeb->id,
                'title' => 'Aplikasi Kasir (POS) & Inventaris Toko Siap Pakai',
                'slug' => 'aplikasi-kasir-pos-inventaris',
                'description' => 'Software kasir berbasis web/desktop dengan fitur cetak struk thermal, laporan laba rugi otomatis, barcode scanner, dan manajemen stok multi-gudang.',
                'price' => 2500000,
                'item_type' => ItemType::Jasa,
                'status' => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?w=600&q=80',
            ],
            [
                'tefa_unit_id' => $unitDkv->id,
                'category_id' => $catDkv->id,
                'title' => 'Paket Branding UMKM: Logo Filosofis + Brand Guideline',
                'slug' => 'paket-branding-umkm-logo-guideline',
                'description' => 'Pembuatan logo vektor profesional dengan filosofi mendalam, mockup 3D, palet warna, tipografi resmi, dan file master (AI, SVG, PNG transparansi tinggi).',
                'price' => 500000,
                'item_type' => ItemType::Jasa,
                'status' => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80',
            ],
            [
                'tefa_unit_id' => $unitKuliner->id,
                'category_id' => $catKuliner->id,
                'title' => 'Box Hampers Roti Artisan & Pastry Butter Premium (Isi 6)',
                'slug' => 'box-hampers-roti-artisan-pastry-butter',
                'description' => 'Roti manis lembut sourdough butter pilihan dengan varian croissant almond, choco fudge, dan smoked beef cheese. Dibuat segar setiap pagi tanpa bahan pengawet.',
                'price' => 85000,
                'item_type' => ItemType::Produk,
                'status' => ItemStatus::Published,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&q=80',
            ],
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