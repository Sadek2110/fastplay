<?php

class RegistroController extends Controller
{
    public function index(): void
    {
        redirect('auth/register');
    }
}
