<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerController extends Controller
{
    public function email() {
        return view("public.contact");
    }

    public function composeEmail(Request $request) {
        // Validasi input dari Form Hubungi Kami
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug   = 0;
            $mail->isSMTP();
            
            // Mengatasi kendala IPv6 di Windows/Laragon dengan memaksa pencarian IP IPv4
            $host = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->Host        = ($host === 'smtp.gmail.com') ? gethostbyname('smtp.gmail.com') : $host;
            
            $mail->SMTPAuth    = true;
            $mail->Username    = env('MAIL_USERNAME', 'rizproject02@gmail.com');
            $mail->Password    = env('MAIL_PASSWORD', 'lxzwxctgtwzqgrlu');
            
            // Disesuaikan: Port 587 WAJIB menggunakan ENCRYPTION_STARTTLS (TLS)
            $mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port        = env('MAIL_PORT', 587);
            $mail->Timeout     = 30;

            // Bypass SSL certificate check (Khusus Localhost/Laragon)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                )
            );

            // Pengirim (Dari Sistem TEFA Hub)
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'rizproject02@gmail.com'), 'TEFA Hub System');
            
            // Penerima (Email Superadmin)
            $superadminEmail = env('SUPERADMIN_EMAIL', 'rizproject02@gmail.com');
            $mail->addAddress($superadminEmail, 'Superadmin TEFA Hub');

            // Balas langsung ke email visitor yang mengisi form
            $mail->addReplyTo($request->email, $request->name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Pesan Kontak Baru: " . $request->subject;
            
            // Menggunakan String HTML langsung (tanpa pemanggilan view Blade)
            $mail->Body = "
                <h3>Pesan Kontak Baru</h3>
                <p><b>Nama:</b> " . e($request->name) . "</p>
                <p><b>Email:</b> " . e($request->email) . "</p>
                <p><b>Subjek:</b> " . e($request->subject) . "</p>
                <p><b>Pesan:</b><br>" . nl2br(e($request->message)) . "</p>
            ";

            // Plain text alternatif untuk email client yang tidak mendukung HTML
            $mail->AltBody = "Nama: {$request->name}\nEmail: {$request->email}\nSubjek: {$request->subject}\nPesan:\n{$request->message}";

            $mail->send();
            return back()->with("success", "Pesan Anda berhasil terkirim ke Superadmin!");

        } catch (Exception $e) {
            return back()->with('failed', 'Gagal mengirim pesan. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}