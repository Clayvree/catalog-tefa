<?php

namespace Database\Seeders;

use App\Models\AiKnowledgeBase;
use Illuminate\Database\Seeder;

class AiKnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'title' => 'Cara Melakukan Pemesanan Produk',
                'description' => 'Untuk memesan produk di platform TEFA, klik tombol Beli atau Pesan pada halaman produk. Isi form data diri: nama, kontak, dan metode pengiriman (kirim ke alamat atau ambil di TEFA). Setelah mengisi form, klik Konfirmasi Pesanan dan lakukan pembayaran sesuai instruksi. Status pesanan bisa dipantau melalui menu Pesanan Saya.',
            ],
            [
                'title' => 'Cara Pembayaran dan Metode yang Tersedia',
                'description' => 'Platform TEFA menerima pembayaran melalui QRIS dan transfer bank. Untuk produk digital, pembayaran dilakukan otomatis dan file bisa langsung diunduh setelah dikonfirmasi. Untuk produk fisik atau jasa, admin TEFA mengkonfirmasi pembayaran secara manual. Bukti pembayaran bisa diunggah di halaman invoice pesanan.',
            ],
            [
                'title' => 'Apa itu TEFA (Teaching Factory)',
                'description' => 'TEFA atau Teaching Factory adalah program di SMK dimana siswa belajar sambil memproduksi barang atau jasa nyata yang dijual ke masyarakat dan industri. Platform ini memiliki beberapa unit jurusan: TEFA RPL (software & desain digital), TEFA DKV (desain kreatif & grafis), dan TEFA Culinary (bakeri & kuliner). Setiap unit memiliki produk, jasa, dan tim siswa tersendiri yang dibimbing oleh guru.',
            ],
            [
                'title' => 'Prosedur Pengajuan Jasa dan Nego Harga',
                'description' => 'Untuk layanan jasa seperti pembuatan website, desain logo, atau katering, harga bersifat negosiasi karena tergantung kompleksitas proyek. Caranya: klik Konsultasi atau Nego Harga pada halaman jasa, isi form kebutuhan (deskripsi proyek, budget, deadline). Sistem akan menghubungkan langsung ke Admin TEFA penanggung jawab via WhatsApp untuk diskusi lebih lanjut.',
            ],
            [
                'title' => 'Pengiriman dan Pengambilan Produk Fisik',
                'description' => 'Produk fisik bisa dikirim ke alamat melalui jasa ekspedisi (J&T, JNE, dll) atau diambil langsung di lokasi TEFA (gratis ongkir). Saat checkout, pilih metode: Kirim ke Alamat atau Ambil di TEFA. Harga pengiriman dihitung berdasarkan berat dan lokasi. Estimasi tiba 2-5 hari kerja.',
            ],
            [
                'title' => 'Produk Digital: Cara Unduh Setelah Pembayaran',
                'description' => 'Produk digital seperti template, source code, atau file desain dapat diunduh langsung setelah pembayaran dikonfirmasi. Buka halaman Invoice pesanan, tombol Download akan muncul otomatis. Link unduhan bersifat unik (token) dan hanya bisa diakses oleh pemesan. Jika link bermasalah, hubungi admin TEFA unit terkait.',
            ],
            [
                'title' => 'Kebijakan Garansi dan Refund',
                'description' => 'Produk fisik bergaransi kerusakan selama pengiriman - foto bukti dan laporkan ke admin dalam 1x24 jam setelah paket diterima. Produk digital tidak dapat direfund setelah berhasil diunduh. Layanan jasa dapat direvisi 1-2 kali sesuai kesepakatan awal. Untuk klaim garansi, hubungi admin TEFA unit terkait melalui kontak yang tertera di halaman jurusan.',
            ],
            [
                'title' => 'Portofolio Siswa dan Kualitas Pengerjaan',
                'description' => 'Semua produk dan jasa di platform TEFA dikerjakan langsung oleh siswa-siswi SMK yang berpengalaman di bidangnya, dibimbing oleh guru/instruktur. Portofolio karya siswa dapat dilihat di halaman Portofolio tiap unit jurusan. Kualitas pekerjaan dimonitor oleh admin/guru sebelum diserahkan ke pelanggan.',
            ],
        ];

        foreach ($entries as $entry) {
            AiKnowledgeBase::firstOrCreate(
                ['title' => $entry['title']],
                $entry
            );
        }
    }
}
