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


    private function insertDocument(string $title, string $description, int $categoryId, string $documentText): bool {
        $sql = "INSERT INTO courses (titre, description, contenu, category_id, document_text) 
                VALUES (:title, :description, 'document', :category_id, :document_text)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $categoryId,
            ':document_text' => $documentText, 
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
            } elseif ($type === 'document' && count($arguments) === 4) {
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

    public function getLastInsertId(): int {
        return (int)$this->pdo->lastInsertId();
    }


    public function getAllCourses() {
        $sql = "SELECT c.course_id, c.titre, c.description,c.status,c.date_status As date, c.contenu, c.video_url, c.document_text,
                       cat.name AS category_name,
                       GROUP_CONCAT(t.name SEPARATOR ' ') AS tags
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.category_id
                LEFT JOIN course_tag ct ON c.course_id = ct.course_id
                LEFT JOIN tags t ON ct.tag_id = t.tag_id
                where status = 'soumis'
                GROUP BY c.course_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateCourseStatus(int $courseId, string $status): void {
        $sql = "UPDATE courses SET status = :status WHERE course_id = :course_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status, 'course_id' => $courseId]);
    }

    public function getAcceptedCourses(): array {
        $sql = "SELECT c.course_id, c.titre, c.date_status As date, c.description,c.status, c.contenu, c.video_url, c.document_text,
                       cat.name AS category_name,
                       GROUP_CONCAT(t.name SEPARATOR ' ') AS tags
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.category_id
                LEFT JOIN course_tag ct ON c.course_id = ct.course_id
                LEFT JOIN tags t ON ct.tag_id = t.tag_id
                WHERE status = 'accepte'
                group by c.course_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function updateCourse(int $courseId, string $title, string $description, string $contentType, ?string $videoUrl = null, ?string $documentText = null, int $categoryId): bool {
        $sql = "UPDATE courses SET titre = :title, description = :description, contenu = :contentType, category_id = :categoryId";
        
        if ($contentType === 'video') {
            $sql .= ", video_url = :videoUrl, document_text = NULL"; 
        } elseif ($contentType === 'document') {
            $sql .= ", document_text = :documentText, video_url = NULL"; 
        }
    
        $sql .= " WHERE course_id = :courseId";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':contentType' => $contentType,
            ':categoryId' => $categoryId,
            ':courseId' => $courseId,
            ':videoUrl' => $videoUrl,
            ':documentText' => $documentText,
        ]);
    }
    

    public function deleteCourse(int $courseId): bool {
        $sql = "DELETE FROM courses WHERE course_id = :courseId";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':courseId' => $courseId]);
    }
    
}
