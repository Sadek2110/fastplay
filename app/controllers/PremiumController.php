<?php

require_once APP_PATH . '/models/Usuario.php';

class PremiumController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->view('subscription/index', [
            'active' => 'subscription',
            'isPremium' => Usuario::isPremium((int) current_user()['id']),
            'title' => 'Premium - FastPlay',
        ]);
    }
}
