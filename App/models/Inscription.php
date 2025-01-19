<?php
namespace App\models;
require_once __DIR__ . '/../../vendor/autoload.php';

class Inscription {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function inscrireEtudiant($studentId, $courseId) {
        // Vérifier si l'inscription existe déjà
        $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE student_id = :student_id AND course_id = :course_id");
        $stmtCheck->bindParam(':student_id', $studentId);
        $stmtCheck->bindParam(':course_id', $courseId);
        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() > 0) {
            return "Vous êtes déjà inscrit à ce cours.";
        }

        // Inscription à la base de données
        $stmt = $this->pdo->prepare("INSERT INTO inscriptions (student_id, course_id) VALUES (:student_id, :course_id)");
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':course_id', $courseId);

        if ($stmt->execute()) {
            return "Inscription réussie.";
        } else {
            return "Erreur lors de l'inscription.";
        }
    }
}
