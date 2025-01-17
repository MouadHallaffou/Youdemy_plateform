<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Course; 

$pdo = Database::connect();
$categoryModel = new Category($pdo);
$tagModel = new Tag($pdo);
$courseModel = new Course($pdo); 

$categories = $categoryModel->getAllCategories();
$tags = $tagModel->getAllTags();

$courseId = $_GET['id'] ?? null;
$course = null;
if ($courseId) {
    $course = $courseModel->getCourseById($courseId); 
    $courseTags = $courseModel->getTagsByCourseId($courseId);
    $tagsArray = array_column($courseTags, 'tag_id'); 
}

if (!$course) {
    header('Location: teacher_manage_course.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Modification du Cours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900">

    <nav class="fixed w-full top-0 z-50 px-4 py-2 flex justify-between items-center bg-white dark:bg-gray-800 border-b-2 dark:border-gray-600">
        <a class="text-2xl font-bold text-violet-600 dark:text-white" href="#">YOUDEMY</a>
        <div class="lg:flex items-center">
            <span class="text-white text-lg mr-4">Bienvenue, <?= htmlspecialchars($teacherName ?? ' ') ?></span>
        </div>
    </nav>

    <div id="add-course-form" class="flex justify-center items-center mt-20">
    <div class="relative p-4 max-w-5xl w-full bg-white rounded-lg shadow-lg">
        <div class="p-5">
            <h1 class="text-3xl font-bold text-center mb-6 text-violet-600">Modifier un Cours</h1>
            <form action="../controllers/crud_course.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['course_id']) ?>">

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Titre :</label>
                    <input type="text" id="title" name="title" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez le titre ici" value="<?= htmlspecialchars($course['titre']) ?>">
                    <?php if (isset($_GET['error']) && strpos($_GET['error'], 'title') !== false): ?>
                        <p class="text-red-500 text-xs mt-1">Le titre est obligatoire.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">Description :</label>
                    <textarea id="description" name="description" required class="border border-gray-300 rounded-lg p-2 w-full h-32 focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez la description ici"><?= htmlspecialchars($course['description']) ?></textarea>
                    <?php if (isset($_GET['error']) && strpos($_GET['error'], 'description') !== false): ?>
                        <p class="text-red-500 text-xs mt-1">La description est obligatoire.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-semibold mb-2">Catégorie :</label>
                    <select id="category" name="category_id" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="">Sélectionnez une catégorie</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['category_id']) ?>" <?= $category['category_id'] == $course['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($_GET['error']) && strpos($_GET['error'], 'category') !== false): ?>
                        <p class="text-red-500 text-xs mt-1">La catégorie est obligatoire.</p>
                    <?php endif; ?>
                </div>

                <fieldset class="mb-4">
                    <legend class="text-gray-700 font-semibold mb-2">Tags :</legend>
                    <?php foreach ($tags as $tag): ?>
                        <label class="block">
                            <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['tag_id']) ?>" class="mr-2 leading-tight" <?= in_array($tag['tag_id'], $tagsArray) ? 'checked' : '' ?>>
                            <span class="text-gray-700"><?= htmlspecialchars($tag['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                    <?php if (isset($_GET['error']) && strpos($_GET['error'], 'tags') !== false): ?>
                        <p class="text-red-500 text-xs mt-1">Veuillez sélectionner au moins un tag.</p>
                    <?php endif; ?>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="text-gray-700 font-semibold mb-2">Type de contenu :</legend>
                    <label class="block mb-1">
                        <input type="radio" name="content_type" value="video" onchange="toggleContentInput()" required class="mr-2 leading-tight" <?= $course['contenu'] == 'video' ? 'checked' : '' ?>> Vidéo
                    </label>
                    <label class="block mb-1">
                        <input type="radio" name="content_type" value="document" onchange="toggleContentInput()" class="mr-2 leading-tight" <?= $course['contenu'] == 'document' ? 'checked' : '' ?>> Document
                    </label>
                    <?php if (isset($_GET['error']) && strpos($_GET['error'], 'content_type') !== false): ?>
                        <p class="text-red-500 text-xs mt-1">Le type de contenu est obligatoire.</p>
                    <?php endif; ?>
                </fieldset>

                <div id="video_url" class="<?= $course['contenu'] == 'video' ? '' : 'hidden' ?> mb-4">
                    <label for="video_url_input" class="block text-gray-700 font-semibold mb-2">URL de la vidéo :</label>
                    <input type="url" id="video_url_input" name="video_url" class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez l'URL de la vidéo" value="<?= htmlspecialchars($course['video_url']) ?>">
                </div>

                <div id="document_file" class="<?= $course['contenu'] == 'document' ? '' : 'hidden' ?> mb-4">
                    <label for="document_file_input" class="block text-gray-700 font-semibold mb-2">Texte du document :</label>
                    <textarea id="document_file_input" name="document_text" rows="4" class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez le lien ou le contenu du document"><?= htmlspecialchars($course['document_text']) ?></textarea>
                </div>

                <div class="flex justify-between gap-4 mt-6">
                    <button type="submit" name="action" value="edit" class="bg-violet-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-violet-600 focus:outline-none focus:ring-2 focus:ring-violet-500 w-1/2">Modifier</button>
                    <a href="teacher_manage_course.php" class="bg-violet-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-violet-600 focus:outline-none focus:ring-2 focus:ring-violet-500 w-1/2 text-center">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function toggleContentInput() {
        const videoInput = document.getElementById('video_url');
        const documentInput = document.getElementById('document_file');
        const contentType = document.querySelector('input[name="content_type"]:checked').value;

        if (contentType === 'video') {
            videoInput.style.display = 'block';
            documentInput.style.display = 'none';
        } else if (contentType === 'document') {
            documentInput.style.display = 'block';
            videoInput.style.display = 'none';
        }
    }
</script>

</body>
</html>
