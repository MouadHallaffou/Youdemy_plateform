<?php

namespace App\Models;

use PDO;
use Exception;

class Course {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }


    private function insertVideo(string $title, string $description, int $categoryId, string $videoUrl): bool {
        $sql = "INSERT INTO courses (titre, description, contenu, category_id, video_url) 
                VALUES (:title, :description, 'video', :category_id, :video_url)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $categoryId,
            ':video_url' => $videoUrl,
        ]);
    }


    private function insertDocument(string $title, string $description, int $categoryId): bool {
        $sql = "INSERT INTO courses (titre, description, contenu, category_id, document_text) 
                VALUES (:title, :description, 'document', :category_id, :document_text)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $categoryId,
            ':document_text' => $description,
        ]);
    }


    
    private function insertCourseTags(int $courseId, array $tagIds): bool {
        foreach ($tagIds as $tagId) {
            $sql = "INSERT INTO course_tag (course_id, tag_id) VALUES (:course_id, :tag_id)";
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt->execute([
                ':course_id' => $courseId,
                ':tag_id' => $tagId,
            ])) {
                return false;
            }
        }
        return true;
    }

    public function __call($name, $arguments) {
        if ($name === 'insert') {
            $type = $arguments[0]; 
            unset($arguments[0]);
            if ($type === 'video' && count($arguments) === 4) {
                return $this->insertVideo(...$arguments);
            } elseif ($type === 'document' && count($arguments) === 3) {
                return $this->insertDocument(...$arguments);
            } else {
                throw new Exception("Arguments incorrects pour la méthode $name avec le type $type.");
            }
        }
        throw new Exception("Méthode $name non définie.");
    }

    public function addTagsToCourse(int $courseId, array $tagIds): bool {
        return $this->insertCourseTags($courseId, $tagIds);
    }
}
