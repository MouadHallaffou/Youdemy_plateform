<?php 
namespace App\models;

use PDO;

abstract class BaseModel {
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    protected function insertEntry(string $table, array $data): int {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    protected function updateEntry(string $table, array $data, string $idColumn, int $idValue): int {
        $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $setClause WHERE $idColumn = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([...array_values($data), $idValue]);
        return $stmt->rowCount();
    }

    protected function deleteEntry(string $table, string $idColumn, int $idValue): int {
        $sql = "DELETE FROM $table WHERE $idColumn = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idValue]);
        return $stmt->rowCount();
    }

    protected function selectEntries(string $table, string $columns = "*", string $where = null, array $params = []): array {
        $sql = "SELECT $columns FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
