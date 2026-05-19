<?php

class CamposController extends Controller
{
    public function index(): void
    {
        $googleMapsKey = getenv('GOOGLE_MAPS_API_KEY') ?: '';
        $mapScripts = '<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>';
        if ($googleMapsKey !== '') {
            $mapScripts .= '<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . rawurlencode($googleMapsKey) . '&callback=initCeutaMap"></script>';
        }
        $mapScripts .= '<script src="' . asset('js/campos-map.js') . '" defer></script>';

        $this->view('campos/index', [
            'active' => 'campos',
            'fields' => $this->model('Campo')->ceuta(),
            'googleMapsEnabled' => $googleMapsKey !== '',
            'head' => '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">',
            'scripts' => $mapScripts,
            'title' => 'Campos de Ceuta - FastPlay',
        ]);
    }

    public function show(string $id = ''): void
    {
        $field = $this->model('Campo')->find((int) $id);
        if (!$field) { Router::notFound(); return; }
        $this->view('campos/show', [
            'active' => 'campos',
            'field' => $field,
            'title' => $field['name'] . ' - FastPlay',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            [$field, $errors] = $this->model('Campo')->create($_POST);
            if ($field) {
                flash('ok', 'Campo registrado.');
                redirect('campos/show/' . $field['id']);
            }
            flash_old($_POST);
        }
        $this->view('campos/create', [
            'active' => 'campos',
            'errors' => $errors,
            'title' => 'Nuevo campo - FastPlay',
        ]);
    }
}
