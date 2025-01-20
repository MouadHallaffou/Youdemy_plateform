<?php
namespace App\controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Teacher;

$pdo = Database::connect();
$action = $_GET['action'] ?? null;
$userId = $_GET['id'] ?? null;

if ($action && $userId) {
    switch ($action) {
        case 'accept':
            // Accepter enseignant (changer son statut à 'active')
            if (Teacher::acceptTeacher($pdo, $userId)) {
                header("Location: ../views/validate-teachers.php?status=success");
            } else {
                echo "Impossible de mettre à jour le statut de l'enseignant.";
            }
            break;

        case 'change_to_student':
            // Changer enseignant en étudiant (changer son rôle et son statut)
            if (Teacher::changeTeacherToStudent($pdo, $userId)) {
                header("Location: ../views/validate-teachers.php?status=changed");
            } else {
                echo "Impossible de changer l'enseignant en étudiant.";
            }
            break;

        default:
            echo "Action non autorisée.";
            break;
    }
} else {
    echo "Paramètres manquants ou méthode non autorisée.";
}
exit();
