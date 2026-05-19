<?php

require_once APP_PATH . '/controllers/ProfileController.php';

class PerfilController extends ProfileController
{
    public function editar(): void
    {
        $this->edit();
    }
}
