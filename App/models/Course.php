<?php

namespace App\Models;

use PDO;
use Exception;

class Course
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    private function insertVideo(string $title, string $description, int $categoryId, string $videoUrl): bool
    {
        $sql = "INSERT INTO courses (titre, description, contenu, category_id, video_url ,enseignant_id) 
                VALUES (:title, :description, 'video', :category_id, :video_url, :enseignant_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $categoryId,
            ':video_url' => $videoUrl,
            ':enseignant_id' => $_SESSION['user_id'],
        ]);
    }

    private function insertDocument(string $title, string $description, int $categoryId, string $documentText): bool
    {
        $sql = "INSERT INTO courses (titre, description, contenu, category_id, document_text ,enseignant_id) 
                VALUES (:title, :description, 'document', :category_id, :document_text , :enseignant_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $categoryId,
            ':document_text' => $documentText,
            ':enseignant_id' => $_SESSION['user_id'],
        ]);
    }

    public function getCourseById($id)
    {
        $sql = "SELECT * FROM courses WHERE course_id = :course_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['course_id' => $id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        return $course;
    }

    public function getTagsByCourseId(int $courseId): array
    {
        $sql = "SELECT t.tag_id, t.name 
            FROM tags t
            JOIN course_tag ct ON ct.tag_id = t.tag_id
            WHERE ct.course_id = :course_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['course_id' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function __call($name, $arguments)
    {
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
        throw new Exception("Methode $name non defini");
    }

    private function insertCourseTags(int $courseId, array $tagIds): bool
    {
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


    public function addTagsToCourse(int $courseId, array $tagIds): bool
    {
        return $this->insertCourseTags($courseId, $tagIds);
    }

    public function getLastInsertId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }


    public function updateCourse(array $data): void
    {

        $courseId = $data['course_id'] ?? null;
        $title = $data['title'] ?? null;
        $description = $data['description'] ?? null;
        $categoryId = $data['category_id'] ?? null;
        $videoUrl = $data['video_url'] ?? null;
        $documentText = $data['document_text'] ?? null;

        if (!$courseId || !$title || !$description || !$categoryId) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
        }

        $sql = "UPDATE courses SET 
                    titre = :title, 
                    description = :description, 
                    category_id = :category_id, 
                    video_url = :video_url, 
                    document_text = :document_text 
                WHERE course_id = :course_id";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute([
                ':course_id' => $courseId,
                ':title' => $title,
                ':description' => $description,
                ':category_id' => $categoryId,
                ':video_url' => $videoUrl,
                ':document_text' => $documentText
            ]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour: " . $e->getMessage());
        }
    }

    public function updateTags($courseId, $tags)
    {
        $stmt = $this->pdo->prepare("DELETE FROM course_tags WHERE course_id = ?");
        $stmt->execute([$courseId]);

        foreach ($tags as $tagId) {
            $stmt = $this->pdo->prepare("INSERT INTO course_tags (course_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$courseId, $tagId]);
        }
    }


    public function getAllCourses()
    {
        $sql = "SELECT c.course_id, c.titre, c.description,c.status,c.date_status As date,u.name AS name,
                c.contenu, c.video_url, c.document_text,cat.name AS category_name,u.image_url,
                GROUP_CONCAT(t.name SEPARATOR ' ') AS tags , COUNT(i.inscription_id) AS total_inscription
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.category_id
                LEFT JOIN course_tag ct ON c.course_id = ct.course_id
                LEFT JOIN tags t ON ct.tag_id = t.tag_id
                LEFT JOIN users u ON u.user_id = c.enseignant_id
                LEFT JOIN inscriptions i ON c.course_id = i.course_id
                GROUP BY c.course_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAcceptedCourses(): array
    {
        $sql = "SELECT c.course_id, c.titre, DATE(c.date_status) As date, c.description,c.status,
                u.image_url, u.name As user_name,c.contenu, c.video_url, c.document_text,
                cat.name AS category_name, GROUP_CONCAT(t.name SEPARATOR ' ') AS tags
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.category_id
                LEFT JOIN course_tag ct ON c.course_id = ct.course_id
                LEFT JOIN tags t ON ct.tag_id = t.tag_id
                LEFT JOIN users u ON u.user_id = c.enseignant_id
                WHERE c.status = 'accepte'
                group by c.course_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursesTeacher(string $username): array
    {
        $sql = "SELECT c.course_id, c.titre, c.date_status AS date, c.description, c.status, 
        u.name AS user_name,c.contenu, c.video_url, 
        c.document_text, cat.name AS category_name,GROUP_CONCAT(t.name SEPARATOR ' ') AS tags, 
        u.image_url, COUNT(i.inscription_id) AS total_inscription
        FROM courses c 
        LEFT JOIN categories cat ON c.category_id = cat.category_id 
        LEFT JOIN course_tag ct ON c.course_id = ct.course_id 
        LEFT JOIN tags t ON ct.tag_id = t.tag_id 
        LEFT JOIN users u ON u.user_id = c.enseignant_id 
        LEFT JOIN inscriptions i ON c.course_id = i.course_id
        WHERE u.name = ?
        GROUP BY c.course_id, c.titre, c.date_status, c.description, c.status, 
        u.name, c.contenu, c.video_url, c.document_text, cat.name";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    public function deleteCourse(int $courseId): bool
    {
        $sql = "DELETE FROM courses WHERE course_id = :courseId";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':courseId' => $courseId]);
    }

    public function updateCourseStatus(int $courseId, string $status): void
    {
        $sql = "UPDATE courses SET status = :status WHERE course_id = :course_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status, 'course_id' => $courseId]);
    }

    public static function getTopcourses(PDO $pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new Exception("La connexion PDO n'est pas valide.");
        }
        $sql = "SELECT c.course_id,c.titre AS course_title,c.description AS course_description,
            COUNT(i.inscription_id) AS total_enrollments
            FROM courses c
            LEFT JOIN inscriptions i ON c.course_id = i.course_id
            WHERE  c.status = 'accepte'
            GROUP BY  c.course_id
            ORDER BY total_enrollments DESC
            LIMIT 3";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
