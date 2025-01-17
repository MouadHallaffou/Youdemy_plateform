<?php
namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Config\Database;

abstract class User {
    protected $userId;
    protected $name;
    protected $email;
    protected $password;
    protected $bio;
    protected $role;
    protected $status;
    protected $imageUrl;
    protected $pdo;

    public function __construct(string $name, string $email, string $password, string $bio = '', string $status = 'active', string $imageUrl = '') {
        $this->name = $name;
        $this->email = $email;
        $this->password = $this->hashPassword($password);
        $this->bio = $bio;
        $this->status = $status;
        $this->imageUrl = filter_var($imageUrl, FILTER_VALIDATE_URL); 
        $this->pdo = Database::connect(); 
    }

    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    abstract public function save();

    public function login($email, $password): bool {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_status'] = $user['status'];

            return true;
        }

        return false;
    }

    // Méthode pour déconnecter l'utilisateur
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}

