<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public static function send(string $to, string $subject, string $template, array $data = []): bool
    {
        if ($to === '') {
            return false;
        }
        $body = self::render($template, $data);
        
        if (defined('FASTPLAY_TESTING') || APP_ENV !== 'production') {
            $dir = STORAGE_PATH . '/mail';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            @file_put_contents($dir . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-z0-9]+/i', '_', $template) . '.html', $body);
            return true;
        }

        if (!class_exists(PHPMailer::class)) {
            error_log('[FastPlay] PHPMailer no instalado — email no enviado a ' . $to);
            return false;
        }

        $config = require APP_ROOT . '/config/mail.php';

        $user = (string) $config['user'];
        $pass = (string) $config['pass'];
        if ($user === '' || $pass === '') {
            error_log('[FastPlay] Configuración SMTP incompleta (MAIL_USER / MAIL_PASS) — email no enviado a ' . $to);
            return false;
        }

        $fromAddress = (string) $config['from'] !== '' ? (string) $config['from'] : $user;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = (string) $config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $user;
            $mail->Password   = $pass;
            $mail->SMTPSecure = $config['encryption'] === 'ssl'
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) $config['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($fromAddress, (string) $config['from_name']);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    private static function render(string $template, array $data): string
    {
        $file = APP_PATH . '/views/emails/' . $template . '.php';
        if (is_file($file)) {
            extract($data, EXTR_SKIP);
            ob_start();
            require $file;
            return (string) ob_get_clean();
        }
        $message = e((string) ($data['message'] ?? 'Tienes una actualizacion en FastPlay.'));
        $url = (string) ($data['url'] ?? '');
        return '<h1>FastPlay</h1><p>' . $message . '</p>' . ($url !== '' ? '<p><a href="' . e($url) . '">Ver detalle</a></p>' : '');
    }
}
