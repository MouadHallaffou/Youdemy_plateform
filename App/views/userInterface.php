<?php
require_once __DIR__ . '/../controllers/crud_course.php';

use App\controllers\CourseController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: http://localhost/Youdemy_plateform/index.php');
    exit();
}

$searchTerm = $_GET['search'] ?? '';
$courses = [];

$courseController = new CourseController($pdo);
$courses = $courseController->getCoursesAcetpted();

if ($searchTerm) {
    $courses = $courseController->searchCourses($searchTerm, $courses);
}

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 3;

$totalCourses = count($courses);
$totalPages = ceil($totalCourses / $itemsPerPage);
$startIndex = ($currentPage - 1) * $itemsPerPage;
$pagedCourses = array_slice($courses, $startIndex, $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Popup styling */
        #course-popup {
            z-index: 1000;
        }

        #course-popup .rounded-lg {
            max-height: 90vh;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900">

    <nav class="fixed w-full top-0 z-50 px-4 py-2 flex justify-between items-center bg-white dark:bg-gray-800 border-b-2 dark:border-gray-600">
        <a class="text-2xl font-bold text-violet-600 dark:text-white" href="#">
            YOUDEMY
        </a>

        <form method="GET" action="" class="relative mx-auto lg:block">
            <input
                class="border border-gray-200 placeholder-current h-8 px-10 pr-20 rounded-lg text-sm focus:outline-none dark:bg-gray-800 dark:border-gray-50 dark:text-gray-100"
                type="search"
                name="search"
                placeholder="Search ..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="absolute right-0 top-0 mt-1 mr-4">
                <i class="fas fa-search text-gray-600 dark:text-gray-100 h-4 w-4"></i>
            </button>
        </form>

        <div class="lg:flex items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="relative inline-block text-left">
                    <div>
                        <button type="button" class="inline-flex items-center text-gray-700 dark:text-gray-200 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true" onclick="toggleMenu()">
                            <span class="ml-2 text-lg">Bienvenu, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                            <img src="<?= htmlspecialchars($_SESSION['image_url'] ?? 'https://cdn.sofifa.net/players/209/981/25_120.png') ?>" alt="Profil" class="rounded-full" style="width: 30px; height: 30px;">
                        </button>
                    </div>
                    <div id="menu" class="absolute right-0 z-10 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                        <div class="py-1" role="none">
                            <a href="http://localhost/Youdemy_plateform/App/views/editProfile.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Profile</a>
                            <a href="studentMesCours.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Mes Cours</a>
                            <a href="http://localhost/Youdemy_plateform/App/views/userInterface.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">All Cours</a>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <a href="http://localhost/Youdemy_plateform/App/controllers/logout.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Logout</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <button class="py-1.5 px-3 m-1 text-center bg-violet-700 rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="open-login-popup">
                    Login
                </button>
                <button class="py-1.5 px-3 m-1 text-center bg-violet-700 rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="open-signup-popup">
                    Sign up
                </button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16 mt-10">
        <h2 class="text-2xl font-bold mb-5 text-white">Tous les Cours</h2>

        <div id="all-courses" class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
            <?php foreach ($pagedCourses as $course): ?>
                <div class="course-card border border-gray-400 bg-white rounded flex flex-col justify-between leading-normal shadow-md cursor-pointer" onclick="showPopup(<?= htmlspecialchars(json_encode($course)); ?>)">
                    <div class="p-2">
                        <?php if ($course['contenu'] === 'video'): ?>
                            <?php $embedUrl = CourseController::convertToEmbedUrl($course['video_url']); ?>
                            <?php if ($embedUrl): ?>
                                <iframe src="<?= htmlspecialchars($embedUrl); ?>" class="w-full h-60 rounded" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <p>URL de vidéo invalide.</p>
                            <?php endif; ?>
                        <?php elseif ($course['contenu'] === 'document'): ?>
                            <div class="document-text">
                                <p><?= htmlspecialchars(CourseController::truncateText($course['document_text'], 300)); ?></p>
                                <button class="text-blue-500 hover:underline text-sm">Afficher plus</button>
                            </div>
                        <?php endif; ?>
                        <a href="#" class="text-gray-900 font-bold text-lg mb-2 hover:text-indigo-600"><?= htmlspecialchars($course['titre']); ?></a>
                        <p class="text-gray-700 text-sm"><?= htmlspecialchars(CourseController::truncateText($course['description'], 100)); ?></p>

                        <?php if (!empty($course['tags'])): ?>
                            <div class="tags flex flex-wrap gap-2 mt-2">
                                <?php foreach (explode(',', $course['tags']) as $tag): ?>
                                    <span class="text-xs font-medium bg-blue-300 text-gray-800 rounded-full px-3 py-1"><?= htmlspecialchars(trim($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center p-4 border-t border-gray-300">
                        <img class="w-10 h-10 rounded-full mr-4" src="<?= htmlspecialchars($course['image_url']); ?>" alt="enseignant">
                        <div class="text-sm">
                            <a href="#" class="text-gray-900 font-semibold leading-none hover:text-indigo-600"><?= htmlspecialchars($course['user_name']); ?></a>
                            <p class="text-gray-600">Date de création du cours: <?= htmlspecialchars($course['date']); ?></p>
                        </div>
                    </div>
                    <div class="p-2">
                        <form method="POST" action="../controllers/inscrire.php">
                            <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['course_id']); ?>">
                            <button type="submit" class="py-1 px-3 m-1 text-center bg-violet-700 border rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600">S'inscrire</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


        <!-- Pagination -->
        <div class="flex items-center justify-center mt-5">
            <a href="?page=<?= max(1, $currentPage - 1) ?>" class="bg-indigo-600 text-white py-2 px-3 rounded hover:bg-indigo-700 <?= $currentPage === 1 ? 'disabled' : '' ?>" aria-label="Page précédente">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div class="flex justify-center mx-4">
                <span class="page-number"><?= $currentPage; ?></span>
                <span class="mx-2">/</span>
                <span><?= $totalPages; ?></span>
            </div>
            <a href="?page=<?= min($totalPages, $currentPage + 1) ?>" class="bg-indigo-600 text-white py-2 px-3 rounded hover:bg-indigo-700 <?= $currentPage === $totalPages ? 'disabled' : '' ?>" aria-label="Prochaine page">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Popup de détails -->
        <div id="course-popup" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full p-6 relative">
                <button class="absolute top-2 right-2 text-black text-1xl font-bold" onclick="closePopup()">✕</button>
                <div id="popup-content" class="max-h-full-screen overflow-auto"></div>
            </div>
        </div>
    </div>

    <footer class="bg-sky-800 fixed bottom-0 w-full">
        <div class="mx-auto max-w-7xl py-4 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <nav class="-mx-5 -my-2 flex flex-wrap justify-center order-2" aria-label="Footer">
                <div class="px-5">
                    <a href="#" class="text-base text-white hover:text-gray-200">Terms of Service</a>
                </div>
                <div class="px-5">
                    <a href="#" class="text-base text-white hover:text-gray-200">Privacy Policy</a>
                </div>
            </nav>
            <div class="mt-4 md:mb-0 flex justify-center space-x-6 md:order-3">
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
            <div class="mt-4 md:order-1 md:mt-0">
                <p class="text-center text-base text-white">
                    &copy; 2025 YOUDEMY PLATFORME.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function showPopup(course) {
            const popup = document.getElementById("course-popup");
            const popupContent = document.getElementById("popup-content");

            let content = "";
            if (course.contenu === "video") {
                const embedUrl = course.video_url ? course.video_url.replace("watch?v=", "embed/") : null;
                content = embedUrl ? `<iframe src="${embedUrl}" class="w-full h-96 rounded" frameborder="0" allowfullscreen></iframe>` : '<p class="text-red-600">URL de vidéo invalide.</p>';
            } else if (course.contenu === "document") {
                content = `<textarea class="w-full h-96 p-4 border border-gray-300 rounded" readonly>${course.document_text}</textarea>`;
            }

            content += `<h2 class="text-xl font-bold mt-4">${course.titre}</h2><p class="text-gray-700 mt-2">${course.description}</p>`;

            popupContent.innerHTML = content;
            popup.classList.remove("hidden");
        }

        // Function close popup
        function closePopup() {
            const popup = document.getElementById("course-popup");
            popup.classList.add("hidden");
        }
        // profile menu
        function toggleMenu() {
            const menu = document.getElementById("menu");
            menu.classList.toggle("hidden");
        }
    </script>

</body>

</html>