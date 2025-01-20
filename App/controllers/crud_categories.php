<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Category;
$pdo = Database::connect();

class CatyroryController {
    private Category $categoryModel;

    public function __construct($pdo) {
        $this->categoryModel = new Category($pdo);
    }

    public function displayCategories(): array {
        return $this->categoryModel->getAllCategories();
    }

    public function createCategory(string $categoryNames): void {
        if (!empty($categoryNames)) {
            $categoriesArray = explode(',', $categoryNames);
            foreach ($categoriesArray as $categoryName) {
                $categoryName = trim($categoryName); 
                if (!empty($categoryName)) {
                    $this->categoryModel->createCategory($categoryName);
                }
            }
            header("Location: ../views/categories.php");
            exit;
        }
    }
    
    public function updateCategory(int $categoryId, string $categoryName): void {
        if (!empty($categoryName)) {
            $this->categoryModel->updateCategory($categoryId, $categoryName);
            header("Location: ../views/categories.php");
            exit;
        }
    }

    public function deleteCategory(int $id): void {
        if (is_numeric($id)) {
            $this->categoryModel->deleteCategory($id);
            header("Location: ../views/categories.php");
            exit;
        }
    }

    // A method to handle incoming requests
    public function handleRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['category_name'])) {
                $this->createCategory(trim($_POST['category_name']));
            } elseif (isset($_POST['category_id'], $_POST['categoryEdit_name'])) {
                $this->updatecategory((int)$_POST['category_id'], trim($_POST['categoryEdit_name']));
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
                $this->deleteCategory((int)$_GET['id']);
            }
        }
    }

    public function getcategoryById(int $id) {
        return $this->categoryModel->getcategoryById($id);
    }

}

$categoryController = new CatyroryController($pdo);

$categoryController->handleRequest();

$categorys = $categoryController->displayCategories();
