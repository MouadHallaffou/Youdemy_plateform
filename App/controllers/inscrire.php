<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '..../../models/Inscription.php';

use App\Config\Database;
use App\Models\Inscription; 

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$pdo = Database::connect();

try {
    $userId = $_SESSION['user_id'];

    if (empty($_POST['course_id'])) {
        throw new Exception("L'ID du cours est manquant.");
    }
    $courseId = $_POST['course_id'];

    $inscriptionHandler = new Inscription($pdo);

    $result = $inscriptionHandler->inscrireEtudiant($userId, $courseId);

    echo $result;
    header('Location: http://localhost/Youdemy_plateform/App/views/userInterface.php');
    exit();

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

