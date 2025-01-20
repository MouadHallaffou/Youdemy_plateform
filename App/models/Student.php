<?php

namespace App\Models;

use Exception;
use PDO;

class Student extends User
{
    protected $pdo;

    public function __construct(PDO $pdo, string $name = '', string $email = '', string $password = '', string $bio = '', string $status = 'active', string $imageUrl = '')
    {
        parent::__construct($name, $email, $password, $bio, $status, $imageUrl);
        $this->pdo = $pdo;
        $this->role = 'etudiant';
        $this->status = $status;
    }

    public function save()
    {
        if (!$this->pdo) {
            throw new Exception("La connexion PDO n'est pas valide.");
        }

        $query = "INSERT INTO users (name, email, password, bio, image_url, role, status) 
                  VALUES (:name, :email, :password, :bio, :image_url, :role, :status)";
        $stmt = $this->pdo->prepare($query);

        $params = [
            ':name' => $this->name,
            ':email' => $this->email,
            ':password' => $this->password,
            ':bio' => $this->bio,
            ':image_url' => $this->imageUrl,
            ':role' => $this->role,
            ':status' => $this->status
        ];

        try {
            $stmt->execute($params);
            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }

    // Method to retrieve all students
    public static function getAllStudents(PDO $pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new Exception("La connexion PDO n'est pas valide.");
        }

        $query = "SELECT * FROM users WHERE role = 'etudiant'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to update a student's status
    public static function updateStudentStatus(PDO $pdo, int $studentId, string $action): bool
    {
        if (!$pdo instanceof PDO) {
            throw new Exception("La connexion PDO n'est pas valide.");
        }

        $isActive = ($action === 'active') ? 1 : 0;
        $status = ($action === 'active') ? 'active' : 'suspended';

        $sql = "UPDATE users 
                SET isActive = :isActive, status = :status 
                WHERE user_id = :studentId AND role = 'etudiant'";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':isActive', $isActive, PDO::PARAM_BOOL);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function searchByTitle(array $courses, string $title): array
    {
        return array_filter($courses, function ($course) use ($title) {
            return stripos($course['titre'], $title) !== false;
        });
    }

    public function inscrireAuCours($courseId)
    {
        $inscriptionHandler = new Inscription($this->pdo);
        return $inscriptionHandler->inscrireEtudiant($this->getId(), $courseId);
    }

    // afficher les cours inscrire a l'etudinats
    public static function getAcceptedCourses(PDO $pdo, $studentId) {
        if (!$pdo instanceof PDO) {
            throw new Exception("La connexion PDO n'est pas valide.");
        }
        $sql = "SELECT c.course_id, c.titre, c.description, c.contenu, c.video_url, c.document_text, 
                u.image_url,c.date_status As date, u.name As user_name
                FROM courses c
                JOIN inscriptions i ON c.course_id = i.course_id
                JOIN users u ON u.user_id = c.enseignant_id
                WHERE i.student_id = :student_id AND c.status = 'accepte' AND i.status = 'active'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':student_id', $studentId);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $courses;
    } 
    
}
