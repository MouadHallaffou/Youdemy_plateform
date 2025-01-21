<?php
require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: http://localhost/Youdemy_plateform/index.php');
    exit();
}

use App\Models\Tag;
use App\Models\Course;
use App\Config\Database;
use App\Models\Category;
use App\controllers\CourseController;

$pdo = Database::connect();
$categoryModel = new Category($pdo);
$tagModel = new Tag($pdo);
$categories = $categoryModel->getAllCategories();
$tags = $tagModel->getAllTags();
$courseId = $_GET['id'] ?? null;

if (!$courseId) {
    echo "Aucun ID de cours fourni.";
    exit();
}

$courseModel = new Course($pdo);
$course = $courseModel->getCourseById($courseId);

if (!$course) {
    echo "Cours introuvable.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Cours - <?= htmlspecialchars($course['titre']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<!-- Page Container -->
<div class="max-w-4xl mx-auto p-5 sm:p-10 md:p-16 mt-20 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

    <!-- Title Section -->
    <h1 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-6"><?= htmlspecialchars($course['titre']) ?></h1>

    <!-- Description Section -->
    <p class="text-lg leading-7 mb-6"><?= htmlspecialchars($course['description']) ?></p>

    <!-- Content Section -->
    <div class="mb-8">
        <?php if ($course['contenu'] === 'video'): ?>
            <div class="relative w-full h-64 overflow-hidden rounded-lg shadow-md">
                <iframe 
                    src="<?= htmlspecialchars(CourseController::convertToEmbedUrl($course['video_url'])); ?>" 
                    class="absolute top-0 left-0 w-full h-full" 
                    frameborder="0" 
                    allowfullscreen>
                </iframe>
            </div>
        <?php elseif ($course['contenu'] === 'document'): ?>
            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-sm leading-6"><?= htmlspecialchars($course['document_text']); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Category and Tags -->
    <div class="mb-6">
        <p class="mb-2">
            <span class="font-semibold">Catégorie :</span> 
            <span class="text-blue-600 dark:text-blue-400"><?= htmlspecialchars($course['category']); ?></span>
        </p>
        <p class="mb-2">
            <span class="font-semibold">Tags :</span>
        </p>
        <div class="flex flex-wrap gap-2">
            <?php foreach (explode(',', $course['tags']) as $tag): ?>
                <span class="text-xs font-medium bg-blue-100 dark:bg-blue-500 text-blue-800 dark:text-gray-100 rounded-full px-3 py-1">
                    <?= htmlspecialchars(trim($tag)); ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8">
        <a href="teacherinterface.php" class="inline-block py-2 px-6 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg shadow-md transition">
            Retourner à mes cours
        </a>
    </div>

</div>

</body>
</html>

