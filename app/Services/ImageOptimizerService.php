<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageOptimizerService
{
    /**
     * Simpan file gambar yang diunggah dengan mempertahankan 100% dimensi asli,
     * rasio, resolusi, dan format file tanpa mengubah atau memotong ukuran aslinya.
     *
     * @param UploadedFile $file
     * @param string $directory (contoh: 'portfolios', 'products')
     * @return string Path relatif untuk disimpan ke database
     */
    public static function optimizeAndStore(
        UploadedFile $file,
        string $directory = 'uploads'
    ): string {
        // Simpan file asli tanpa mengubah pixel, dimensi, maupun rasio aslinya
        return $file->store($directory, 'public');
    }
}
