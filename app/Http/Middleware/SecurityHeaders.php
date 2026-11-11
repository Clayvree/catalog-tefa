<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * Inject security headers ke semua response HTTP.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Mencegah clickjacking — iframe hanya boleh dari domain yang sama
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Mencegah browser menebak-nebak content type (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Aktifkan XSS protection bawaan browser (legacy, tapi tetap berguna)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Kebijakan referrer — tidak bocorkan URL lengkap ke situs lain
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Batasi penggunaan fitur browser sensitif
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy - batasi sumber daya eksternal
        // Izinkan: self, Vite dev server, Groq API, CDN gambar, Tailwind CDN, Google Fonts, Alpine.js
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.tailwindcss.com unpkg.com cdnjs.cloudflare.com http://localhost:5173 http://127.0.0.1:5173; " .
            "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.tailwindcss.com cdnjs.cloudflare.com http://localhost:5173 http://127.0.0.1:5173; " .
            "font-src 'self' fonts.gstatic.com data:; " .
            "img-src 'self' data: blob: *; " .
            "connect-src 'self' api.groq.com http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173; " .
            "frame-ancestors 'self';"
        );

        // HSTS — paksa HTTPS (aktifkan jika sudah production dengan SSL)
        // $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        return $response;
    }
}
