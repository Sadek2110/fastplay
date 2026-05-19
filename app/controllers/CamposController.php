<?php

class CamposController extends Controller
{
    public function index(): void
    {
        $this->view('campos/index', [
            'active' => 'campos',
            'fields' => $this->model('Campo')->all(),
            'head' => '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">',
            'scripts' => '<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script src="' . asset('js/campos-map.js') . '" defer></script>',
            'title' => 'Campos - FastPlay',
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
