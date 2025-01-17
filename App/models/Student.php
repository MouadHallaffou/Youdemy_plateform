<?php
namespace App\Models;

use Exception;

class Student extends User {
    
    public function __construct(string $name, string $email, string $password, string $bio = '', string $status = 'active', string $imageUrl = '') {
        parent::__construct($name, $email, $password, $bio, $status, $imageUrl);
        $this->role = 'etudiant';
        $this->status = 'active';
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
    

    // Méthode pour récupérer tous les étudiants
    public static function getAllStudents($pdo) {
        $query = "SELECT * FROM users WHERE role = 'etudiant'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

