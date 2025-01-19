<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Config\Database;
use App\controllers\CourseController;
use App\Models\Teacher;
use App\Models\Student;
use Exception;
use PDO;

$pdo = Database::connect();

class UsersController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])) {
            // Récupérer les données du formulaire
            $name = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $bio = trim($_POST['bio']);
            $imageUrl = trim($_POST['image_url']);
            $role = trim($_POST['role']);

            // Validation des champs obligatoires
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                echo "Tous les champs obligatoires doivent être remplis.";
                return;
            }

            // Création d'un objet utilisateur basé sur le rôle
            $user = null;
            if ($role === 'enseignant') {
                $user = new Teacher($name, $email, $password, $bio, 'active', $imageUrl);
            } elseif ($role === 'etudiant') {
                $user = new Student($this->pdo, $name, $email, $password, $bio, 'active', $imageUrl);
            } else {
                echo "Rôle inconnu.";
                return;
            }

            // Sauvegarde de l'utilisateur
            try {
                if ($user->save()) {
                    // Redirection basée sur le rôle
                    if ($role === 'enseignant') {
                        header("Location: http://localhost/Youdemy_plateform/App/views/userInterface.php");
                    } else {
                        header("Location: http://localhost/Youdemy_plateform/App/views/userInterface.php");
                    }
                    exit();
                } else {
                    echo "Erreur lors de la création du compte.";
                }
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    }

    // Récupérer tous les enseignants
    public function getTeachers()
    {
        return Teacher::getAllTeachers($this->pdo);
    }

    // Récupérer tous les étudiants
    public function getStudents()
    {
        return Student::getAllStudents($this->pdo);
    }

    public function getTroisTopTeachers($pdo){
        return Teacher::getTopTeachers($pdo);
    }
}


//instance sign in
$user = new UsersController();
$user->register();
$Teachers = new UsersController();
$allTeachers = $Teachers-> getTeachers();
$AllStudent = Student::getAllStudents($pdo);
$TopTeachers = $Teachers->getTroisTopTeachers($pdo);



// update status etudiant (action formulaire)
$action = $_GET['action'] ?? null;
$studentId = $_GET['id'] ?? null;
if ($action && $studentId) {
    if (Student::updateStudentStatus($pdo, $studentId, $action)) {
        header("Location: ../views/manage-students.php?status=success");
    } else {
        echo "Impossible de mettre à jour le statut.";
    }
    exit();
} else {
    echo "Impossible de mettre à jour le statut.";
}

// login -> méthode de traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifier si l'utilisateur a un statut "suspended"
            if ($user['status'] === 'suspended') {
                echo "Votre compte  suspendu. contacter l'administrateur.";
                exit();
            }

            // Vérifier les identifiants
            if (password_verify($password, $user['password'])) {

                // Créer les sessions après une connexion réussie
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['image_url'] = $user['image_url'];
                $_SESSION['status'] = $user['status'];

                // Rediriger l'utilisateur en fonction de son rôle
                if ($user['role'] === 'etudiant') {
                    header('Location: http://localhost/Youdemy_plateform/App/views/userInterface.php');
                } elseif ($user['role'] === 'enseignant' && $user['status'] === 'pending') {
                    header('Location: http://localhost/Youdemy_plateform/App/views/userInterface.php');
                } elseif ($user['role'] === 'enseignant' && $user['status'] === 'active') {
                    header('Location: ../views/teacherinterface.php');
                } elseif ($user['role'] === 'admin') {
                    header('Location: http://localhost/Youdemy_plateform/App/public/dist/dashboard.php');
                } else {
                    header('Location: ../../index.php');
                }
                exit();
            } else {
                echo "Identifiants incorrects.";
            }
        } else {
            echo "Identifiants incorrects.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

