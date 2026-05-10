<?php
// FastPlay · páginas legales

class LegalController extends Controller
{
    public function terms(): void
    {
        $this->view('legal/terms', [
            'active' => '',
            'title'  => 'Términos de uso — FastPlay',
        ]);
    }

    public function privacy(): void
    {
        $this->view('legal/privacy', [
            'active' => '',
            'title'  => 'Privacidad (GDPR) — FastPlay',
        ]);
    }

    public function cookies(): void
    {
        $this->view('legal/cookies', [
            'active' => '',
            'title'  => 'Cookies — FastPlay',
        ]);
    }
}
