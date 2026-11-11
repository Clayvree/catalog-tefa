<?php

declare(strict_types=1);

namespace App\Support;

use Symfony\Component\Mime\MimeTypeGuesserInterface;

/**
 * Pendeteksi MIME type cadangan untuk server tanpa ekstensi PHP "fileinfo".
 *
 * Tanpa fileinfo, Symfony tidak bisa menebak tipe file sehingga validasi
 * "image"/"mimes" dan $file->store() melempar LogicException (error 500).
 * Kelas ini menebak tipe dari isi file: gambar via getimagesize() (GD),
 * lalu tanda tangan biner umum (PDF, ZIP), dan teks biasa untuk .txt.
 */
class FallbackMimeTypeGuesser implements MimeTypeGuesserInterface
{
    public function isGuesserSupported(): bool
    {
        return true;
    }

    public function guessMimeType(string $path): ?string
    {
        if (!is_file($path) || !is_readable($path)) {
            return null;
        }

        $info = @getimagesize($path);
        if (is_array($info) && !empty($info['mime'])) {
            return $info['mime'];
        }

        $head = (string) @file_get_contents($path, false, null, 0, 4096);

        if ($head === '') {
            return 'application/x-empty';
        }

        $signatures = [
            '%PDF-' => 'application/pdf',
            "PK\x03\x04" => 'application/zip',
            '<?xml' => 'text/xml',
            '<svg' => 'image/svg+xml',
        ];

        foreach ($signatures as $magic => $mime) {
            if (str_starts_with(ltrim($head, "\xEF\xBB\xBF \t\r\n"), $magic)) {
                return $mime;
            }
        }

        // Teks biasa (mis. ekspor chat WhatsApp .txt): tidak mengandung byte NUL
        if (!str_contains($head, "\0")) {
            return 'text/plain';
        }

        return 'application/octet-stream';
    }
}
