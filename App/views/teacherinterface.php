<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Course;

$pdo = Database::connect();
$categoryModel = new Category($pdo);
$tagModel = new Tag($pdo);

$categories = $categoryModel->getAllCategories();
$tags = $tagModel->getAllTags();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $teacherName = $_SESSION['user_name'];
    } 
} 

$courseModel = new Course($pdo);
$courses = $courseModel->getCoursesTeacher($_SESSION['user_name']);
if (!is_array($courses)) {
    $courses = []; 
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion des Cours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hidden {
            display: none;
        }

        .modal {
            position: absolute;
            top: 4rem;
            left: 50%;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900">

    <nav class="fixed w-full top-0 z-50 px-4 py-2 flex justify-between items-center bg-white dark:bg-gray-800 border-b-2 dark:border-gray-600">
        <a class="text-2xl font-bold text-violet-600 dark:text-white" href="#">
            YOUDEMY
        </a>

        <div class="lg:hidden">
            <button class="navbar-burger flex items-center text-violet-600 dark:text-gray-100 p-1" id="navbar_burger">
                <i class="fas fa-bars h-6 w-6"></i>
            </button>
        </div>

        <div class="relative mx-auto hidden lg:block">
            <input class="border border-gray-300 placeholder-current h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none dark:bg-gray-500 dark:border-gray-50 dark:text-gray-200" type="search" name="search" placeholder="Recherche...">
            <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                <i class="fas fa-search text-gray-600 dark:text-gray-200 h-4 w-4"></i>
            </button>
        </div>

        <div class="lg:flex items-center">
            <!-- le nom de enseignant -->
            <span class="text-white text-lg mr-4">Bienvenue, <?php echo htmlspecialchars($teacherName); ?></span>

            <button class="py-1.5 px-3 m-1 text-center bg-violet-700 border rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="add-course-button">
                Ajouter un Cours
            </button>

            <a href="teacher_manage_course.php" class="py-1.5 px-3 m-1 text-center bg-violet-700 border rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600">
                Mes Cours
            </a>

            <a href="../../../Youdemy_plateform/App/controllers/logout.php" class="py-1.5 px-3 m-1 text-center bg-red-700 border rounded-md text-white hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-600">
                Déconnexion
            </a>
        </div>

    </nav>

    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16 mt-20">
        <!-- Section des cartes de cours -->
        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
            <?php foreach ($courses as $course): ?>
                <div class="border border-gray-400 bg-white rounded flex flex-col justify-between leading-normal shadow-md">
                    <div class="p-4">
                        <iframe id="video_iframe" src="https://www.youtube.com/embed/VKqZNPG4o_4" class="w-full h-60 rounded" frameborder="0" allowfullscreen></iframe>
                        <a href="#" class="text-gray-900 font-bold text-lg mb-2 hover:text-indigo-600"><?= htmlspecialchars($course['titre']); ?></a>
                        <p class="text-gray-700 text-sm">Description du contenu du cours ici...</p>
                    </div>
                    <div class="flex items-center p-4 border-t border-gray-300">
                        <a href="#">
                            <img class="w-10 h-10 rounded-full mr-4" src="https://tailwindcss.com/img/jonathan.jpg" alt="Avatar de l'enseignant">
                        </a>
                        <div class="text-sm">
                            <a href="#" class="text-gray-900 font-semibold leading-none hover:text-indigo-600"><?= htmlspecialchars($course['user_name']);?></a>
                            <p class="text-gray-600">Date de création du cours: 2025-01-15</p>
                        </div>
                        <button class="py-1.5 px-3 m-1 text-center bg-violet-700 border rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600">
                            Détails
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulaire d'Ajout de Cours -->
        <div id="add-course-form" class="hidden">
            <div class="modal">
                <div class="relative p-4 w-[70%] max-w-lg bg-white rounded-lg shadow z-10">
                    <button type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" id="form-close">
                        <i class="fas fa-times"></i>
                        <span class="sr-only">Fermer le formulaire</span>
                    </button>

                    <div class="p-5">
                        <h1 class="text-3xl font-bold mb-6">Ajouter un Cours</h1>
                        <form action="../controllers/crud_course.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="title" class="block text-gray-700 font-semibold mb-2">Titre :</label>
                                <input type="text" id="title" name="title" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez le titre ici">
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-gray-700 font-semibold mb-2">Description :</label>
                                <textarea id="description" name="description" required class="border border-gray-300 rounded-lg p-2 w-full h-32 focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez la description ici"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="category" class="block text-gray-700 font-semibold mb-2">Catégorie :</label>
                                <select id="category" name="category_id" required class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500">
                                    <option value="">Sélectionnez une catégorie</option>
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
                            </div>

                            <fieldset class="mb-4">
                                <legend class="text-gray-700 font-semibold mb-2">Tags :</legend>
                                <?php if (!empty($tags)): ?>
                                    <?php foreach ($tags as $tag): ?>
                                        <label class="block">
                                            <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['tag_id']) ?>" class="mr-2 leading-tight">
                                            <span class="text-gray-700"><?= htmlspecialchars($tag['name']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Aucun tag disponible.</p>
                                <?php endif; ?>
                            </fieldset>

                            <fieldset class="mb-4">
                                <legend class="text-gray-700 font-semibold mb-2">Type de contenu :</legend>
                                <label class="block mb-1">
                                    <input type="radio" name="content_type" value="video" onchange="toggleContentInput()" required class="mr-2 leading-tight"> Vidéo
                                </label>
                                <label class="block mb-1">
                                    <input type="radio" name="content_type" value="document" onchange="toggleContentInput()" class="mr-2 leading-tight"> Document
                                </label>
                            </fieldset>

                            <div id="video_url" class="hidden mb-4">
                                <label for="video_url_input" class="block text-gray-700 font-semibold mb-2">URL de la vidéo :</label>
                                <input type="url" id="video_url_input" name="video_url" class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez l'URL de la vidéo">
                            </div>

                            <div id="document_file" class="hidden mb-4">
                                <label for="document_file_input" class="block text-gray-700 font-semibold mb-2">Texte du document (lien ou contenu) :</label>
                                <textarea id="document_file_input" name="document_text" rows="4" class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500" placeholder="Entrez le lien ou le contenu du document"></textarea>
                            </div>

                            <button type="submit" name="action" value="create" class="bg-violet-700 text-white font-semibold py-2 px-4 rounded-lg hover:bg-violet-600 focus:outline-none focus:ring-2 focus:ring-violet-500">Ajouter le cours</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-sky-800">
        <div class="mx-auto max-w-7xl py-8 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <nav class="-mx-5 -my-2 flex flex-wrap justify-center order-2" aria-label="Footer">
                <div class="px-5">
                    <a href="#" class="text-base text-white hover:text-gray-200">Conditions d'utilisation</a>
                </div>
                <div class="px-5">
                    <a href="#" class="text-base text-white hover:text-gray-200">Politique de confidentialité</a>
                </div>
            </nav>
            <div class="mt-8 md:mb-8 flex justify-center space-x-6 md:order-3">
                <a href="#" class="text-white hover:text-gray-200">
                    <i class="fab fa-facebook h-6 w-6"></i>
                </a>
                <a href="#" class="text-white hover:text-gray-200">
                    <i class="fab fa-twitter h-6 w-6"></i>
                </a>
                <a href="#" class="text-white hover:text-gray-200">
                    <i class="fab fa-github h-6 w-6"></i>
                </a>
            </div>
            <div class="mt-8 md:order-1 md:mt-0">
                <p class="text-center text-base text-white">
                    &copy; YOUDEMY PLATFORME.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function toggleContentInput() {
            const type = document.querySelector('input[name="content_type"]:checked').value;
            document.getElementById('video_url').classList.toggle('hidden', type !== 'video');
            document.getElementById('document_file').classList.toggle('hidden', type !== 'document');
        }

        document.getElementById("add-course-button").addEventListener("click", function() {
            document.getElementById("add-course-form").classList.toggle('hidden');
        });

        document.getElementById("form-close").addEventListener("click", function() {
            document.getElementById("add-course-form").classList.add('hidden');
        });
    </script>

</body>

</html>