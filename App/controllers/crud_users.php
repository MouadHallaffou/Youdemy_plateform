<?php
namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Teacher;
use App\Models\Student;
use Exception;
use PDO;

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
            $name = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $bio = trim($_POST['bio']);
            $imageUrl = trim($_POST['image_url']);
            $role = trim($_POST['role']);

            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                echo "Tous les champs obligatoires doivent être remplis.";
                return;
            }

            if ($role === 'enseignant') {
                $user = new Teacher($name, $email, $password, $bio, $role, $imageUrl);
            } elseif ($role === 'etudiant') {
                $user = new Student($name, $email, $password, $bio, $role, $imageUrl);
            } else {
                echo "Rôle inconnu.";
                return;
            }

            try {
                if ($user->save()) {
                    if ($role === 'enseignant') {
                        header("Location: http://localhost/Youdemy_plateform/App/views/teacherinterface.php");
                    } else {
                        header("Location: http://localhost/Youdemy_plateform/App/views/userInterface.php");
                    }
                    exit();
                } else {
                    echo "Erreur lors de la création du compte.";
                }
            } catch (\Exception $e) {
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


    // public function loginUser() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    //         $email = trim($_POST['email']);
    //         $password = trim($_POST['password']);

    //         if (empty($email) || empty($password)) {
    //             echo "Veuillez remplir tous les champs.";
    //             return;
    //         }

    //         // Utilisateur générique pour le login
    //         $user = new class('', '', '') extends \App\Models\User {
    //             public function save() {}
    //         };

    //         if ($user->login($email, $password)) {
    //             $role = $_SESSION['user_role'];

    //             // Redirection en fonction du rôle
    //             if ($role === 'etudiant') {
    //                 header("Location: http://localhost/Youdemy_plateform/App/views/studentinterface.php");
    //             } elseif ($role === 'enseignant') {
    //                 header("Location: http://localhost/Youdemy_plateform/App/views/studentinterface.php");
    //             } elseif ($role === 'admin') {
    //                 header("Location: http://localhost/Youdemy_plateform/App/views/studentinterface.php");
    //             }
    //             exit();
    //         } else {
    //             echo "Identifiants invalides.";
    //         }
    //     }
    // }


}

$user = new UsersController();
$user->register();



if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = (int) $_GET['id'];

    try {
        $pdo = Database::connect();

        if ($action === 'accept') {
            $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
            $stmt->execute([$id]);
            header('Location: validate-teachers.php');
        }

        elseif ($action === 'change_to_student') {
            $stmt = $pdo->prepare("UPDATE users SET role = 'etudiant', status = 'pending' WHERE user_id = ?");
            $stmt->execute([$id]);
            header('Location: validate-teachers.php');
        }

        else {
            header('Location: .validate-teachers.php');
        }
    } catch (Exception $e) {
        header('Location: validate-teachers.php?error=' . urlencode($e->getMessage()));
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['image_url'] = $user['image_url'];

            if ($user['role'] === 'etudiant') {
                header('Location: ../../index.php');
            } elseif ($user['role'] === 'enseignant') {
                header('Location: ../views/teacherinterface.php');
            } elseif ($user['role'] === 'admin') {
                header('Location: http://localhost/Youdemy_plateform/App/public/dist/dashboard.php');
            }else {
                header('Location: ../../index.php');
            }
            exit();
        } else {
            echo "Identifiants incorrects.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
