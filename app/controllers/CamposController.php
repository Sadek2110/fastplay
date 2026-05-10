<?php
// FastPlay · campos (catálogo)

class CamposController extends Controller
{
    public function index(): void
    {
        $campo = $this->model('Campo');
        $this->view('campos/index', [
            'active' => 'campos',
            'fields' => $campo->all(),
            'title'  => 'Campos — FastPlay',
        ]);
    }

    public function show(string $id = ''): void
    {
        $id = (int) $id;
        $campo = $this->model('Campo');
        $field = $campo->find($id);
        if (!$field) { Router::notFound(); return; }

        $this->view('campos/show', [
            'active' => 'campos',
            'field'  => $field,
            'title'  => $field['name'] . ' — FastPlay',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $campo = $this->model('Campo');
            [$field, $errors] = $campo->create($_POST);
            if ($field) {
                flash('ok', 'Campo registrado.');
                redirect('campos/show/' . $field['id']);
            }
            flash_old($_POST);
        }
        $this->view('campos/create', [
            'active' => 'campos',
            'errors' => $errors,
            'title'  => 'Nuevo campo — FastPlay',
        ]);
    }
}