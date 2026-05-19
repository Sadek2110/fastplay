<?php
// FastPlay · modelo Campo (pista/cancha)

class Campo
{
    public function all(): array
    {
        return Database::all("SELECT * FROM fields ORDER BY CASE WHEN city = 'Ceuta' THEN 0 ELSE 1 END, city, name");
    }

    public function ceuta(): array
    {
        return Database::all(
            "SELECT * FROM fields
             WHERE city = 'Ceuta'
               AND id IN (SELECT MIN(id) FROM fields WHERE city = 'Ceuta' GROUP BY name)
             ORDER BY name"
        );
    }

    public function find(int $id): ?array
    {
        return Database::one('SELECT * FROM fields WHERE id = ?', [$id]);
    }

    public function create(array $data): array
    {
        $errors = [];
        $name = trim((string) ($data['name'] ?? ''));
        $city = trim((string) ($data['city'] ?? ''));
        $address = trim((string) ($data['address'] ?? ''));
        $surface = trim((string) ($data['surface'] ?? 'césped'));
        $cap = (int) ($data['capacity'] ?? 22);
        $rate = (float) ($data['hourly_rate'] ?? 0);

        if (!v_required($name)) $errors['name'] = 'Nombre obligatorio.';
        if (!v_required($city)) $errors['city'] = 'Ciudad obligatoria.';
        if (!in_array($surface, ['césped','sintético','tierra','cemento'], true)) {
            $errors['surface'] = 'Superficie no válida.';
        }
        if (!v_int_range($cap, 4, 50)) $errors['capacity'] = 'Capacidad entre 4 y 50.';
        if ($rate < 0 || $rate > 1000)  $errors['hourly_rate'] = 'Tarifa fuera de rango.';
        if ($errors) return [null, $errors];

        Database::run(
            'INSERT INTO fields (name,city,address,surface,capacity,hourly_rate) VALUES (?,?,?,?,?,?)',
            [$name, $city, $address ?: null, $surface, $cap, $rate]
        );
        return [$this->find(Database::insertId()), []];
    }

    public function delete(int $id): void
    {
        Database::run('DELETE FROM fields WHERE id=?', [$id]);
    }
}
