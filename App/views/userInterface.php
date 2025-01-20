<?php
require_once __DIR__ . '/../controllers/crud_course.php';

use App\controllers\CourseController;

if (!isset($coursesaccepted) || !is_array($coursesaccepted)) {
    $coursesaccepted = [];
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$searchTerm = $_GET['search'] ?? '';
if ($searchTerm) {
    $courseController = new CourseController($pdo);
    $coursesaccepted = $courseController->searchCourses($searchTerm, $coursesaccepted);
}

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
            <input class="border border-gray-200 placeholder-current h-12 px-10 pr-20 rounded-lg text-sm focus:outline-none dark:bg-gray-400 dark:border-gray-50 dark:text-gray-200"
                type="search"
                name="search"
                placeholder="Search"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                <i class="fas fa-search text-gray-600 dark:text-gray-200 h-4 w-4"></i>
            </button>
        </form>

        <div class="lg:flex items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="relative inline-block text-left">
                    <div>
                        <button type="button" class="inline-flex items-center text-gray-700 dark:text-gray-200 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true" onclick="toggleMenu()">
                            <img src="<?= htmlspecialchars($_SESSION['image_url'] ?? 'https://cdn.sofifa.net/players/209/981/25_120.png') ?>" alt="Profil" class="rounded-full" style="width: 30px; height: 30px;">
                            <span class="ml-2"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        </button>
                    </div>

                    <div id="menu" class="absolute right-0 z-10 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                        <div class="py-1" role="none">
                            <a href="http://localhost/Youdemy_plateform/App/views/editProfile.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Profile</a>
                            <a href="#!" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Settings</a>
                            <a href="#!" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Activity Log</a>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <a href="http://localhost/Youdemy_plateform/App/controllers/logout.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Logout</a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Affichage pour un utilisateur non connecté -->
                <button class="py-1.5 px-3 m-1 text-center bg-violet-700  rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="open-login-popup">
                    Login
                </button>
                <button class="py-1.5 px-3 m-1 text-center bg-violet-700  rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="open-signup-popup">
                    Sign up
                </button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16 mt-10">

        <div id="course-container" class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
            <?php foreach ($coursesaccepted as $course): ?>
                <div
                    class="course-card border border-gray-400 bg-white rounded flex flex-col justify-between leading-normal shadow-md cursor-pointer"
                    onclick="showPopup(<?= htmlspecialchars(json_encode($course)); ?>)">
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
                        <p class="text-gray-700 text-sm"><?= htmlspecialchars($course['description']); ?></p>
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
            <button id="prev" class="bg-indigo-600 text-white py-2 px-3 rounded hover:bg-indigo-700" onclick="changePage(-1)" aria-label="Page précédente">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="flex justify-center mx-4">
                <span class="page-number" id="page-number">1</span>
                <span class="mx-2">/</span>
                <span id="total-pages"></span>
            </div>

            <button id="next" class="bg-indigo-600 text-white py-2 px-3 rounded hover:bg-indigo-700" onclick="changePage(1)" aria-label="Prochaine page">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="flex justify-center mt-2">
            <div id="pagination"></div>
        </div>

        <!-- Popup de details -->
        <div id="course-popup" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full p-6 relative">
                <button class="absolute top-2 right-2 text-black text-1xl font-bold" onclick="closePopup()">✕</button>
                <div id="popup-content" class="max-h-full-screen overflow-auto">
                    <!-- Content -->
                </div>
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

    <!-- Popup Login -->
    <div id="login-popup" tabindex="-1"
        class="bg-black/50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 h-full items-center justify-center flex hidden">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow">

                <div class="p-5">
                    <h3 class="text-2xl mb-3 font-medium">Connexion</h3>
                    <p class="mb-4 text-sm font-normal text-gray-800">Entrez vos identifiants pour vous connecter.</p>

                    <form id="login-form" action="../../../Youdemy_plateform/App/controllers/crud_users.php" method="POST">

                        <button type="reset"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" id="popup-close">
                            <i class="fas fa-times"></i>
                            <span class="sr-only">Close popup</span>
                        </button>

                        <label for="login-email" class="sr-only">Email address</label>
                        <input name="email" type="email" required
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="Email Address">

                        <label for="login-password" class="sr-only">Password</label>
                        <input name="password" type="password" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="Password">

                        <button type="submit"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-black p-2 py-3 text-sm font-medium text-white outline-none focus:ring-2 focus:ring-black focus:ring-offset-1">
                            Se connecter
                        </button>
                    </form>

                    <div class="mt-6 text-center text-sm text-slate-600" id="register-link">
                        Vous n'avez pas de compte?
                        <a href="#" class="font-medium text-[#4285f4]" onclick="toggleForms(); return false;">Inscrivez-vous ici</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Popup Sign Up -->
    <div id="signup-popup" tabindex="-1"
        class="bg-black/50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 h-full items-center justify-center flex hidden">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow">
                <div class="p-5">
                    <h3 class="text-2xl mb-3 font-medium">Créer un compte</h3>
                    <form id="signup-form" action="../../../Youdemy_plateform/App/controllers/crud_users.php" method="POST">

                        <button type="reset"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" id="signup-close">
                            <i class="fas fa-times"></i>
                            <span class="sr-only">Close popup</span>
                        </button>

                        <label for="username" class="sr-only">Nom d'utilisateur</label>
                        <input name="username" type="text" required
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="Nom d'utilisateur">

                        <label for="signup-email" class="sr-only">Email</label>
                        <input name="email" type="email" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="Email">

                        <label for="signup-password" class="sr-only">Mot de passe</label>
                        <input name="password" type="password" required
                            class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="Mot de passe">

                        <label for="bio" class="sr-only">Bio</label>
                        <textarea name="bio"
                            class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="Votre Bio"></textarea>

                        <label for="image_url" class="sr-only">URL de l'image</label>
                        <input name="image_url" type="url"
                            class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
                            placeholder="https://example.com/image.jpg" required>

                        <label for="role" class="sr-only">Rôle</label>
                        <select name="role" required
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 mt-2 outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1">
                            <option value=""> Choisir un role </option>
                            <option value="etudiant">Etudiant</option>
                            <option value="enseignant">Enseignant</option>
                        </select>

                        <button type="submit" name="addUser"
                            class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-black p-2 py-3 text-sm font-medium text-white outline-none focus:ring-2 focus:ring-black focus:ring-offset-1">
                            Créer un compte
                        </button>
                    </form>

                    <div class="mt-6 text-center text-sm text-slate-600">
                        Vous avez déjà un compte?
                        <a href="#" class="font-medium text-[#4285f4]" onclick="toggleForms(); return false;">Connectez-vous ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // profile menu
        function toggleMenu() {
            const menu = document.getElementById("menu");
            menu.classList.toggle("hidden");
        }
    </script>
    <script src="./../public/dist/js/mainUserinterface.js"></script>

</body>

</html>