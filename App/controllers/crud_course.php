<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Course;

class CourseController {
    private $courseModel;

    public function __construct($pdo) {
        $this->courseModel = new Course($pdo);
    }

    public function createCourse(array $data): void {
        $title = $data['title'] ?? null;
        $description = $data['description'] ?? null;
        $categoryId = $data['category_id'] ?? null;
        $contentType = $data['content_type'] ?? null;
        $videoUrl = $data['video_url'] ?? null;
        $tags = $data['tags'] ?? []; 

        try {

            if (!$title || !$description || !$categoryId || !$contentType) {
                throw new \Exception("Tous les champs obligatoires doivent être remplis.");
            }

            if ($contentType === 'video' && $videoUrl) {
                $this->courseModel->insert('video', $title, $description, (int)$categoryId, $videoUrl);
            } elseif ($contentType === 'document') {
                $this->courseModel->insert('document', $title, $description, (int)$categoryId);
            } else {
                throw new \Exception("Type de contenu ou URL de vidéo manquant.");
            }

            $courseId = $this->courseModel->getLastInsertId();

            if (!empty($tags)) {
                $this->courseModel->addTagsToCourse($courseId, $tags);
            }

            header('Location: ../views/courses.php');
        } catch (\Exception $e) {
            header('Location: ../views/courses.php' . $e->getMessage());
        }
        exit;
    }

    public function handleRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'create') {
                $this->createCourse($_POST);
            }
        }
    }
}

$pdo = Database::connect();
$controller = new CourseController($pdo);
$controller->handleRequest();

