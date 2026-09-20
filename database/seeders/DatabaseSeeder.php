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
    public function run(): void
    {
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

        $catWeb      = Category::updateOrCreate(['name' => 'Web Development']);
        $catMobile   = Category::updateOrCreate(['name' => 'Mobile App']);
        $catDesign   = Category::updateOrCreate(['name' => 'Desain Grafis']);
        $catVideo    = Category::updateOrCreate(['name' => 'Video Editing']);
        $catJaringan = Category::updateOrCreate(['name' => 'Jaringan Komputer']);
        $catHardware = Category::updateOrCreate(['name' => 'Servis Hardware']);
        $catMerch    = Category::updateOrCreate(['name' => 'Merchandise & Cetak']);
        $cat3d       = Category::updateOrCreate(['name' => '3D & Animasi']);

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
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Game VR Museum', 'slug' => 'game-vr-museum', 'description' => 'Aplikasi permainan Virtual Reality interaktif bertema museum untuk edukasi dan hiburan.', 'price' => 4500000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?w=600&q=80'],
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Jasa Multi Berkah (Ekspedisi Kargo)', 'slug' => 'jasa-multi-berkah-kargo', 'description' => 'Sistem manajemen logistik dan ekspedisi kargo berbasis digital yang handal.', 'price' => 3500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&q=80'],
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Aplikasi Absen Silap', 'slug' => 'aplikasi-absen-silap', 'description' => 'Aplikasi absensi digital praktis dan akurat untuk instansi atau sekolah.', 'price' => 2500000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&q=80'],
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Jadwal Masjid', 'slug' => 'jadwal-masjid', 'description' => 'Sistem informasi jadwal sholat digital otomatis untuk kebutuhan masjid.', 'price' => 1500000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/83/2d/f7/832df70e1648edcdf3b2f07d6e1e2459.jpg'],
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Bel Sekolah Otomatis', 'slug' => 'bel-sekolah-otomatis', 'description' => 'Perangkat lunak pengatur bel sekolah otomatis berbasis waktu secara presisi.', 'price' => 1200000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/9a/40/46/9a40461ad36f6886a971de2c1a8020b4.jpg'],
            ['tefa_unit_id' => $unitPplg->id, 'category_id' => $catWeb->id, 'title' => 'Layanan Pembuatan Aplikasi & Website', 'slug' => 'layanan-pembuatan-aplikasi-website', 'description' => 'Jasa kustom pembuatan website profil, e-commerce, maupun sistem informasi.', 'price' => 4000000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&q=80'],
        ];

        foreach ($itemsPplg as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

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
            'logo_url'    => asset('assets/images/units/logo_dkv.jpg'),
            'is_active'   => true,
        ]);
        $adminDkv->managedUnits()->syncWithoutDetaching([$unitDkv->id]);

        $itemsDkv = [
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Gantungan Kunci', 'slug' => 'gantungan-kunci', 'description' => 'Desain dan cetak gantungan kunci custom berbagai bentuk.', 'price' => 15000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/15/e4/42/15e442facc4539f9c610c129bd9ab5fe.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesign->id, 'title' => 'Mockup & Design', 'slug' => 'mockup-design', 'description' => 'Jasa pembuatan mockup produk dan konsep desain kreatif.', 'price' => 250000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesign->id, 'title' => 'Design Kaos / Pakaian', 'slug' => 'design-kaos-pakaian', 'description' => 'Jasa desain grafis khusus sablon kaos, jersey, atau seragam.', 'price' => 150000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=600&q=80'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Design & Cetak Spanduk', 'slug' => 'design-cetak-spanduk', 'description' => 'Layanan desain dan percetakan spanduk berkualitas tinggi.', 'price' => 75000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/7c/f3/44/7cf344f93376625f060276c2b542a886.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Design & Cetak Baliho', 'slug' => 'design-cetak-baliho', 'description' => 'Cetak baliho promosi ukuran besar tahan cuaca luar ruangan.', 'price' => 350000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/ae/7f/a2/ae7fa2086f48697a7779a94c9da379ad.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Design & Cetak Banner', 'slug' => 'design-cetak-banner', 'description' => 'Pembuatan roll-up banner atau mini banner promosi bisnis.', 'price' => 120000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/03/5b/49/035b49bcc3b3b4af80b851be3ee06785.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catMerch->id, 'title' => 'Design & Cetak Poster', 'slug' => 'design-cetak-poster', 'description' => 'Desain poster artistik dan cetak high-resolution.', 'price' => 50000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/c2/03/82/c20382a8fa95b88b1a8eb153cef70664.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesign->id, 'title' => 'Design & Cetak Logo', 'slug' => 'design-cetak-logo', 'description' => 'Jasa pembuatan identitas merek (logo) profesional beserta file master.', 'price' => 500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/96/02/8b/96028bcf083ba09183f1cdf6da32941b.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesign->id, 'title' => 'Design & Cetak Kemasan', 'slug' => 'design-cetak-kemasan', 'description' => 'Desain packaging/kemasan produk makanan maupun barang.', 'price' => 300000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/ab/82/3a/ab823aa9b51dca312ccef2e73deef3ae.jpg'],
            ['tefa_unit_id' => $unitDkv->id, 'category_id' => $catDesign->id, 'title' => 'Design & Cetak Undangan', 'slug' => 'design-cetak-undangan', 'description' => 'Desain undangan pernikahan, acara resmi, atau ulang tahun eksklusif.', 'price' => 200000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600&q=80'],
        ];

        foreach ($itemsDkv as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

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
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Pemasangan Jaringan Internet (SSA)', 'slug' => 'pemasangan-jaringan-internet-ssa', 'description' => 'Jasa setting dan instalasi perangkat akses internet (300K-450K).', 'price' => 400000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&q=80'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catHardware->id, 'title' => 'Jasa Service Software Komputer / Laptop', 'slug' => 'jasa-service-software', 'description' => 'Perbaikan OS, install ulang, pembersihan virus, dan instalasi driver.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1588702547919-26089e690ecc?w=600&q=80'],
            ['tefa_unit_id' => $unitTkj->id, 'category_id' => $catJaringan->id, 'title' => 'Instalasi Nirkabel (Wireless)', 'slug' => 'instalasi-nirkabel-wireless', 'description' => 'Pengaturan dan pemasangan perangkat jaringan nirkabel/access point.', 'price' => 100000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1563770660941-20978e870e26?w=600&q=80'],
        ];

        foreach ($itemsTkj as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

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
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $cat3d->id, 'title' => 'Desain Karakter 2D', 'slug' => 'desain-karakter-2d', 'description' => 'Pembuatan karakter 2D orisinil lengkap dengan berbagai ekspresi.', 'price' => 350000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/a9/28/4f/a9284fa242a4bd6c087ab9f056961e7b.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $cat3d->id, 'title' => 'Desain Asset 3D', 'slug' => 'desain-asset-3d', 'description' => 'Pembuatan model objek 3D siap pakai untuk game atau animasi.', 'price' => 500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/4e/39/a4/4e39a4bd7a659ce9435c44d5fb0f4fec.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catMerch->id, 'title' => 'Sticker', 'slug' => 'sticker', 'description' => 'Desain stiker tematik lucu atau maskot brand.', 'price' => 50000, 'item_type' => ItemType::Produk, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/1200x/df/de/b3/dfdeb3d852ecb254e428e7cc636af249.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideo->id, 'title' => 'Video Edukasi Singkat 2D', 'slug' => 'video-edukasi-singkat-2d', 'description' => 'Pembuatan video animasi 2D berdurasi pendek untuk media pembelajaran.', 'price' => 1500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catDesign->id, 'title' => 'Ilustrasi Digital', 'slug' => 'ilustrasi-digital', 'description' => 'Gambar digital kustom untuk poster, sampul buku, media sosial, atau promosi.', 'price' => 250000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8DFXvGuEckoWAeShMJPezUWEdQeajerL5PQr5jYHr-A&s=10'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideo->id, 'title' => 'Video Promosi Animasi', 'slug' => 'video-promosi-animasi', 'description' => 'Video iklan komersial berbasis animasi gerak untuk produk/jasa.', 'price' => 2000000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1626544827763-d516dce335e2?w=600&q=80'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $cat3d->id, 'title' => 'Animasi Maskot', 'slug' => 'animasi-maskot', 'description' => 'Pembuatan animasi pendek untuk maskot perusahaan atau instansi.', 'price' => 1200000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/f8/57/19/f85719a148e7a7f0d9625b0da0be25ff.jpg'],
            ['tefa_unit_id' => $unitAnimasi->id, 'category_id' => $catVideo->id, 'title' => 'Video Profil Sekolah / Instansi', 'slug' => 'video-profil-sekolah-instansi', 'description' => 'Pembuatan video profil sinematik dan animasi untuk instansi.', 'price' => 3000000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://i.pinimg.com/736x/51/00/4a/51004a62ed8fd4d3a95787114c76eac8.jpg'],
        ];

        foreach ($itemsAnimasi as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

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
            ['tefa_unit_id' => $unitPspt->id, 'category_id' => $catVideo->id, 'title' => 'Jasa Videografi & Dokumentasi Event', 'slug' => 'videografi-dokumentasi-event', 'description' => 'Layanan perekaman video profesional untuk acara, perpisahan, dan company profile.', 'price' => 2500000, 'item_type' => ItemType::Jasa, 'status' => ItemStatus::Published, 'thumbnail_url' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=600&q=80'],
        ];

        foreach ($itemsPspt as $item) {
            CatalogItem::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['id' => (string) Str::uuid()]));
        }

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
                'tefa_unit_id'            => $unitPplg->id,
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