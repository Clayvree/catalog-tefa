<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerController extends Controller
{
    public function email() {
        return view("layouts.email");
    }

    public function composeEmail(Request $request) {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rizproject02@gmail.com';
            
            // GANTI DENGAN 16 KARAKTER APP PASSWORD DARI GOOGLE (TANPA SPASI)
            $mail->Password   = 'lxzwxctgtwzqgrlu'; 

            // Pengaturan Enkripsi SSL Port 465
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port       = 465;

            // Optional: Jika masih error SSL sertifikat di local (XAMPP/Laragon)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Recipients
            $mail->setFrom('rizproject02@gmail.com', 'Mailer System');
            $mail->addAddress($request->emailRecipient);
            
            if ($request->emailCc) {
                $mail->addCC($request->emailCc);
            }
            if ($request->emailBcc) {
                $mail->addBCC($request->emailBcc);
            }

            // Attachments
            if ($request->hasFile('emailAttachments')) {
                foreach ($request->file('emailAttachments') as $file) {
                    $mail->addAttachment($file->getPathname(), $file->getClientOriginalName());
                }
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $request->emailSubject;
            $mail->Body    = nl2br($request->emailBody);

            $mail->send();
            return back()->with("success", "Email has been sent");

        } catch (Exception $e) {
            // Menampilkan error asli dari PHPMailer jika gagal
            return back()->with('failed', 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}