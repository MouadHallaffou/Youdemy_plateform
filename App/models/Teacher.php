<?php
namespace App\Models;
use Exception;
use PDO;

class Teacher extends User {
    
    public function __construct(string $name, string $email, string $password, string $bio = '', string $status = 'pending', string $imageUrl = '') {
        parent::__construct($name, $email, $password, $bio, $status, $imageUrl);
        $this->role = 'enseignant';
        $this->status = 'pending';
    }

    public function save() {
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
    
    // Méthode pour récupérer tous les enseignants
    public static function getAllTeachers($pdo) {
        $query = "SELECT * FROM users WHERE role = 'enseignant'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    // Méthode pour accepter un enseignant (changer son statut à 'active')
    public static function acceptTeacher(PDO $pdo, int $teacherId): bool
    {
        $status = 'active'; // L'enseignant est maintenant actif
        $sql = "UPDATE users SET status = :status WHERE user_id = :teacherId AND role = 'enseignant'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Méthode pour changer un enseignant en étudiant
    public static function changeTeacherToStudent(PDO $pdo, int $teacherId): bool
    {
        $role = 'etudiant'; 
        $status = 'active';
        $sql = "UPDATE users SET role = :role, status = :status WHERE user_id = :teacherId AND role = 'enseignant'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);

        return $stmt->execute();
    }
    
    public function searchByTitle(array $courses, string $title): array
    {
        return array_filter($courses, function ($course) use ($title) {
            return stripos($course['titre'], $title) !== false;
        });
    }
    
}

