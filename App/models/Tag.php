<?php 
namespace App\Models;

use App\models\BaseModel;

class Tag extends BaseModel {
    protected string $table = 'tags';

    public function __construct($pdo) {
        parent::__construct($pdo);
    }

    public function createTag(string $name): int {
        return $this->insertEntry($this->table, ['name' => $name]);
    }

    public function getAllTags(): array {
        return $this->selectEntries($this->table);
    }

    public function updateTag(int $id, string $name): int {
        return $this->updateEntry($this->table, ['name' => $name], 'tag_id', $id);
    }

    public function deleteTag(int $id): int {
        return $this->deleteEntry($this->table, 'tag_id', $id);
    }

    public function getTagById(int $id) {
        $result = $this->selectEntries($this->table, "*", "tag_id = ?", [$id]);
        return !empty($result) ? $result[0] : null; 
    }
}
