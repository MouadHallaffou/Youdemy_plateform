<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Category;
use App\Models\Tag;


$pdo = Database::connect();
$categoryModel = new Category($pdo);
$tagModel = new Tag($pdo);

$categories = $categoryModel->getAllCategories(); 
$tags = $tagModel->getAllTags();         
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un cours</title>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Ajouter un cours</h1>
    <form action="../controllers/crud_course.php" method="POST" enctype="multipart/form-data">

        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>
        <br><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>
        <br><br>

        <label for="category">Catégorie :</label>
        <select id="category" name="category_id" required>
            <option value="">-- Sélectionnez une catégorie --</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['category_id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Aucune catégorie disponible</option>
            <?php endif; ?>
        </select>
        <br><br>

        <fieldset>
            <legend>Tags :</legend>
            <?php if (!empty($tags)): ?>
                <?php foreach ($tags as $tag): ?>
                    <label>
                        <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['tag_id']) ?>">
                        <?= htmlspecialchars($tag['name']) ?>
                    </label>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun tag disponible.</p>
            <?php endif; ?>
        </fieldset>
        <br>

        <fieldset>
            <legend>Type de contenu :</legend>
            <label><input type="radio" name="content_type" value="video" onchange="toggleContentInput()" required> Vidéo</label>
            <label><input type="radio" name="content_type" value="document" onchange="toggleContentInput()"> Document</label>
        </fieldset>
        <br>

        <div id="video_url" class="hidden">
            <label for="video_url_input">URL de la vidéo :</label>
            <input type="url" id="video_url_input" name="video_url">
        </div>

        <div id="document_file" class="hidden">
            <label for="document_file_input">Texte du document (lien ou contenu) :</label>
            <textarea id="document_file_input" name="document_text" rows="4" cols="50" placeholder="Entrez le lien ou le contenu du document"></textarea>
        </div>
        <br>

        <button type="submit" name="action" value="create">Ajouter le cours</button>
    </form>

    <script>
        function toggleContentInput() {
            const type = document.querySelector('input[name="content_type"]:checked').value;
            document.getElementById('video_url').classList.toggle('hidden', type !== 'video');
            document.getElementById('document_file').classList.toggle('hidden', type !== 'document');
        }
    </script>
</body>
</html>
