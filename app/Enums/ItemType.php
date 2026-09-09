<?php

declare(strict_types=1);

namespace App\Enums;

enum ItemType: string
{
    case Produk    = 'produk';    // Produk Fisik
    case Digital   = 'digital';   // Produk Digital
    case Jasa      = 'jasa';      // Layanan Jasa (Nego Proyek)
    case Kegiatan  = 'kegiatan';  // Event / Pelatihan

    public function label(): string
    {
        return match($this) {
            self::Produk   => 'Produk Fisik',
            self::Digital  => 'Produk Digital',
            self::Jasa     => 'Layanan Jasa',
            self::Kegiatan => 'Kegiatan & Event',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Produk   => 'bg-blue-50 text-blue-700 border-blue-200',
            self::Digital  => 'bg-purple-50 text-purple-700 border-purple-200',
            self::Jasa     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Kegiatan => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }
}