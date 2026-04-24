<?php
declare(strict_types=1);

abstract class Model
{
    protected PDO    $db;
    protected string $table  = '';
    protected string $pk     = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): array|false
    {
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->pk} = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function findAll(string $orderBy = 'id DESC', int $limit = 100): array
    {
        $st = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} LIMIT {$limit}");
        return $st->fetchAll();
    }

    public function findWhere(array $conditions, string $orderBy = 'id DESC', int $limit = 100): array
    {
        [$where, $vals] = $this->buildWhere($conditions);
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderBy} LIMIT {$limit}");
        $st->execute($vals);
        return $st->fetchAll();
    }

    public function findOneWhere(array $conditions): array|false
    {
        [$where, $vals] = $this->buildWhere($conditions);
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$where} LIMIT 1");
        $st->execute($vals);
        return $st->fetch();
    }

    public function insert(array $data): int
    {
        $cols = implode(', ', array_keys($data));
        $plh  = implode(', ', array_fill(0, count($data), '?'));
        $st   = $this->db->prepare("INSERT INTO {$this->table} ({$cols}) VALUES ({$plh})");
        $st->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $st  = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE {$this->pk} = ?");
        return $st->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->pk} = ?");
        return $st->execute([$id]);
    }

    public function count(array $conditions = []): int
    {
        if (empty($conditions)) {
            return (int) $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
        }
        [$where, $vals] = $this->buildWhere($conditions);
        $st = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE {$where}");
        $st->execute($vals);
        return (int) $st->fetchColumn();
    }

    private function buildWhere(array $conditions): array
    {
        $clauses = [];
        $values  = [];
        foreach ($conditions as $col => $val) {
            if ($val === null) {
                $clauses[] = "{$col} IS NULL";
            } else {
                $clauses[] = "{$col} = ?";
                $values[]  = $val;
            }
        }
        return [implode(' AND ', $clauses), $values];
    }
}
