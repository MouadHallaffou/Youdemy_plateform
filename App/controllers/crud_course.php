<?php
namespace App\controllers;

require_once __DIR__ . '/../../vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Config\Database;
use App\Models\Course;

class CourseController
{
    private $courseModel;
    public $courses;

    public function __construct($pdo)
    {
        $this->courseModel = new Course($pdo);
    }

    public function createCourse(array $data): void
    {
        $title = $data['title'] ?? null;
        $description = $data['description'] ?? null;
        $categoryId = $data['category_id'] ?? null;
        $contentType = $data['content_type'] ?? null;
        $videoUrl = $data['video_url'] ?? null;
        $documentText = $data['document_text'] ?? null;
        $tags = $data['tags'] ?? [];

        try {
            if (!$title || !$description || !$categoryId || !$contentType) {
                throw new \Exception("Tous les champs obligatoires doivent être remplis.");
            }

            if ($contentType === 'video' && $videoUrl) {
                $this->courseModel->insert('video', $title, $description, (int)$categoryId, $videoUrl);
            } elseif ($contentType === 'document') {

                $this->courseModel->insert('document', $title, $description, (int)$categoryId, $documentText);
            } else {
                throw new \Exception("Type de contenu ou URL de vidéo manquant.");
            }

            $courseId = $this->courseModel->getLastInsertId();

            if (!empty($tags)) {
                $this->courseModel->addTagsToCourse($courseId, $tags);
            }

            header('Location: ../views/teacherinterface.php');
        } catch (\Exception $e) {
            header('Location: ../views/teacherinterface.php?error=' . urlencode($e->getMessage()));
        }
        exit;
    }

    public function editCourse(array $data): void
    {
        $courseId = $data['course_id'] ?? null;
        $title = $data['title'] ?? null;
        $description = $data['description'] ?? null;
        $categoryId = $data['category_id'] ?? null;
        $contentType = $data['content_type'] ?? null;
        $videoUrl = $data['video_url'] ?? null;
        $documentText = $data['document_text'] ?? null;

        try {
            if (!$courseId || !$title || !$description || !$categoryId || !$contentType) {
                throw new \Exception("Tous les champs obligatoires doivent être remplis.");
            }

            $dataToUpdate = [
                'course_id' => $courseId,
                'title' => $title,
                'description' => $description,
                'category_id' => $categoryId,
                'content_type' => $contentType,
                'video_url' => $videoUrl ?? null,
                'document_text' => $documentText ?? null
            ];

            $this->courseModel->updateCourse($dataToUpdate);

            if (!$this->courseModel->updateCourse($dataToUpdate)) {
                echo "La mise à jour a échoué.";
            }
            header('Location: ../views/editCourse.php');
        } catch (\Exception $e) {
            header('Location: ../views/editCourse.php?error=' . urlencode($e->getMessage()));
        }
        exit;
    }

    public function handleRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'create') {
                $this->createCourse($_POST);
            } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
                $this->editCourse($_POST);
            }
        }

        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'accept' && isset($_GET['id'])) {
                $this->courseModel->updateCourseStatus((int)$_GET['id'], 'accepte');
                header('Location: http://localhost/Youdemy_plateform/App/views/manage_courses.php');
                exit;
            } elseif ($_GET['action'] === 'refuse' && isset($_GET['id'])) {
                $this->courseModel->updateCourseStatus((int)$_GET['id'], 'refuse');
                header('Location: http://localhost/Youdemy_plateform/App/views/manage_courses.php');
                exit;
            } elseif ($_GET['action'] === 'delete' && isset($_GET['id'])) {
                $this->courseModel->deleteCourse((int)$_GET['id']);
                header('Location: http://localhost/Youdemy_plateform/App/views/teacher_manage_course.php');
                exit;
            }
        }
    }

    public function getCourses(): array
    {
        return $this->courseModel->getAllCourses();
    }

    public function getCoursesAcetpted(): array
    {
        return $this->courseModel->getAcceptedCourses();
    }


    public static function truncateText($text, $maxLength = 300)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . '...';
        }
        return $text;
    }

    public static function convertToEmbedUrl($url)
    {
        $videoId = null;

        if (preg_match('/[?&]v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId ? "https://www.youtube.com/embed/$videoId" : null;
    }
}


$pdo = Database::connect();
$controller = new CourseController($pdo);
$controller->handleRequest();
$courses = $controller->getCourses();
$coursesaccepted = $controller->getCoursesAcetpted();
