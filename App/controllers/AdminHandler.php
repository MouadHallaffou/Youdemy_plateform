<?php
namespace App\Controllers;
namespace App\Controllers;

use App\Models\Admin;
use PDO;

class AdminHandler {
    private $pdo;
    private $admin;

    public function __construct(PDO $pdo, int $adminId) {
        $this->pdo = $pdo;

        // Fetch admin data to initialize
        $currentAdminData = $this->getCurrentAdminData($adminId);
        $this->admin = new Admin($adminId, $currentAdminData['name'], $currentAdminData['email'], ''); // Password is not set here
    }

    public function getCurrentAdminData(int $adminId): array {
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $adminId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAdminProfile(array $data): bool {
        return $this->admin->updateProfile($this->pdo, $data['name'], $data['email'], $data['bio'], $data['image_url']);
    }
}
