<?php 
namespace App\Models;

use App\models\BaseModel;

class Category extends BaseModel {
    protected string $table = 'categories';

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    public function createCategory(string $name): int {
        return $this->insertEntry($this->table, ['name' => $name]);
    }

    public function getAllCategories(): array {
        return $this->selectEntries($this->table);
    }

    public function updateCategory(int $id, string $name): int {
        return $this->updateEntry($this->table, ['name' => $name], 'category_id', $id);
    }

    public function deleteCategory(int $id): int {
        return $this->deleteEntry($this->table, 'category_id', $id);
    }

    public function getCategoryById(int $id) {
        $result = $this->selectEntries($this->table, "*", "category_id = ?", [$id]);
        return !empty($result) ? $result[0] : null; 
    }
}
