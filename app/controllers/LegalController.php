<?php
// FastPlay · páginas legales

class LegalController extends Controller
{
    private const LEGAL_LAST_UPDATED = '2026-05-10';

    public function terms(): void
    {
        $this->view('legal/terms', [
            'active'      => '',
            'lastUpdated' => self::LEGAL_LAST_UPDATED,
            'title'       => 'Términos de uso — FastPlay',
        ]);
    }

    public function privacy(): void
    {
        $this->view('legal/privacy', [
            'active'      => '',
            'lastUpdated' => self::LEGAL_LAST_UPDATED,
            'title'       => 'Privacidad (GDPR) — FastPlay',
        ]);
    }

    public function cookies(): void
    {
        $this->view('legal/cookies', [
            'active'      => '',
            'lastUpdated' => self::LEGAL_LAST_UPDATED,
            'title'       => 'Cookies — FastPlay',
        ]);
    }
}
