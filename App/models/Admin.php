<?php
namespace App\Models;

use PDO;

class Admin extends User {
    public function __construct(int $userId, string $name, string $email, string $password) {
        parent::__construct($name, $email, $password);
        $this->userId = $userId;
    }

    public function getAdminData(PDO $pdo): array {
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $this->userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile(PDO $pdo, string $name, string $email, string $bio, string $imageUrl): bool {
        $sql = "UPDATE users SET name = :name, email = :email, bio = :bio, image_url = :image_url WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':bio' => $bio,
            ':image_url' => $imageUrl,
            ':user_id' => $this->userId
        ]);
    }

    public function save(){

    }
    
    public function searchByTitle(array $courses, string $title): array
    {
        return array_filter($courses, function ($course) use ($title) {
            return stripos($course['titre'], $title) !== false;
        });
    }
}



