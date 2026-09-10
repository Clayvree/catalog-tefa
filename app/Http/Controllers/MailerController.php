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
            // Server settings (diambil dari .env)
            $mail->SMTPDebug   = 0;
            $mail->isSMTP();
            $mail->Host        = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth    = true;
            $mail->Username    = env('MAIL_USERNAME', 'rizproject02@gmail.com');
            $mail->Password    = env('MAIL_PASSWORD', 'lxzwxctgtwzqgrlu');
            $mail->SMTPSecure  = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port        = env('MAIL_PORT', 465);

            // Bypass SSL certificate check (Khusus Localhost/XAMPP)
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
            
            // Menggunakan view email.blade.php sebagai tampilan isi pesan
            $mail->Body    = view('email', [
                'name'    => $request->name,
                'email'   => $request->email,
                'subject' => $request->subject,
                'pesan'   => $request->message,
            ])->render();

            $mail->send();
            return back()->with("success", "Pesan Anda berhasil terkirim ke Superadmin!");

        } catch (Exception $e) {
            return back()->with('failed', 'Gagal mengirim pesan. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}