<?php

class MailService
{
    public static function send(string $to, string $subject, string $template, array $data = []): bool
    {
        if ($to === '') {
            return false;
        }
        $body = self::render($template, $data);
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . (getenv('MAIL_FROM') ?: 'FastPlay <no-reply@fastplay.local>'),
        ];
        if (defined('FASTPLAY_TESTING') || APP_ENV !== 'production') {
            $dir = STORAGE_PATH . '/mail';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            @file_put_contents($dir . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-z0-9]+/i', '_', $template) . '.html', $body);
            return true;
        }
        return @mail($to, $subject, $body, implode("\r\n", $headers));
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
