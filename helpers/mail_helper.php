<?php
// helpers/mail_helper.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Sesuaikan path autoload composer Anda
require_once __DIR__ . '/../vendor/autoload.php';

function kirimEmail($to, $subject, $message) {
    $config = include __DIR__ . '/../config/smtp.php';
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi Server
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['secure'] == 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $config['port'];
        
        // Fix untuk Localhost (Self-signed certificate)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Penerima
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($to);

        // Konten
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Jika gagal, bisa log error di sini: error_log($mail->ErrorInfo);
        return false;
    }
}
?>