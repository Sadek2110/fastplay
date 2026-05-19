<?php

class LoginController extends Controller
{
    public function index(): void
    {
        redirect('auth/login');
    }
}
