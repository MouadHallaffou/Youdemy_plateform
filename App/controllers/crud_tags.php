<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Tag;

class TagController {
    private Tag $tagModel;

    public function __construct($pdo) {
        $this->tagModel = new Tag($pdo);
    }

    public function displayTags(): array {
        return $this->tagModel->getAllTags();
    }

    public function createTag(string $tagName): void {
        // Assuming there is a dedicated method to handle the creation from the request
        if (!empty($tagName)) {
            $this->tagModel->createTag($tagName);
            header("Location: ../views/tags.php");
            exit;
        }
    }

    public function updateTag(int $tagId, string $tagName): void {
        if (!empty($tagName)) {
            $this->tagModel->updateTag($tagId, $tagName);
            header("Location: ../views/tags.php");
            exit;
        }
    }

    public function deleteTag(int $id): void {
        if (is_numeric($id)) {
            $this->tagModel->deleteTag($id);
            header("Location: ../views/tags.php");
            exit;
        }
    }

    // A method to handle incoming requests
    public function handleRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['tag_name'])) {
                $this->createTag(trim($_POST['tag_name']));
            } elseif (isset($_POST['tag_id'], $_POST['tagEdit_name'])) {
                $this->updateTag((int)$_POST['tag_id'], trim($_POST['tagEdit_name']));
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
                $this->deleteTag((int)$_GET['id']);
            }
        }
    }

    public function getTagById(int $id) {
        return $this->tagModel->getTagById($id);
    }

}

// Connect to the database and create an instance of the controller
$pdo = Database::connect();
$tagController = new TagController($pdo);

// Handle the incoming request
$tagController->handleRequest();

// Display tags after handling the request
$tags = $tagController->displayTags();
