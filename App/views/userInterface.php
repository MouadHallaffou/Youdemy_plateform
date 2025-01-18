<?php
require_once __DIR__ . '/../controllers/crud_course.php';

use App\controllers\CourseController;

if (!isset($coursesaccepted) || !is_array($coursesaccepted)) {
    $coursesaccepted = [];
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

        <form method="GET" action="" class="relative mx-auto hidden lg:block">
            <input class="border border-gray-300 placeholder-current h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none dark:bg-gray-500 dark:border-gray-50 dark:text-gray-200"
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
                <!-- Affichage pour un utilisateur connecté -->
                <div class="flex items-center gap-4">
                    <span class="text-gray-700 dark:text-gray-200 font-medium">
                        Bienvenue, <a href="/profile" class="text-white"><?= htmlspecialchars($_SESSION['user_name']) ?></a>
                    </span>
                    <a href="../../../Youdemy_plateform/App/controllers/logout.php" class="py-1.5 px-3 text-center bg-red-600 rounded-md text-white hover:bg-red-500 dark:bg-red-600 dark:hover:bg-red-500">
                        Logout
                    </a>
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

    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16 mt-20">
        <div id="course-container" class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
            <?php foreach ($coursesaccepted as $index => $course): ?>
                <div
                    class="course-card border border-gray-400 bg-white rounded flex flex-col justify-between leading-normal shadow-md cursor-pointer"
                    onclick="showPopup(<?= htmlspecialchars(json_encode($course)); ?>)">
                    <div class="p-4">
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
                        <a href="#">
                            <img class="w-10 h-10 rounded-full mr-4" src="<?= htmlspecialchars($course['image_url']); ?>" alt="enseignant">
                        </a>
                        <div class="text-sm">
                            <a href="#" class="text-gray-900 font-semibold leading-none hover:text-indigo-600"><?= htmlspecialchars($course['user_name']); ?></a>
                            <p class="text-gray-600">Date de création du cours: <?= htmlspecialchars($course['date']); ?></p>
                        </div>
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
    </div>

    <!-- Popup de details -->
    <div id="course-popup" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full p-6 relative">
            <button class="absolute top-2 right-2 text-black text-1xl font-bold" onclick="closePopup()">✕</button>
            <div id="popup-content" class="max-h-full-screen overflow-auto">
                <!-- Content dynamically injected -->
            </div>
        </div>
    </div>


    <footer class="bg-sky-800 rounded-lg">
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
                    &copy; YOUDEMY PLATFORME.
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
        document.getElementById("open-login-popup").addEventListener("click", function() {
            document.getElementById("login-popup").classList.remove('hidden');
            document.getElementById("signup-popup").classList.add('hidden');
        });

        document.getElementById("open-signup-popup").addEventListener("click", function() {
            document.getElementById("signup-popup").classList.remove('hidden');
            document.getElementById("login-popup").classList.add('hidden');
        });

        document.getElementById("popup-close").addEventListener("click", function() {
            document.getElementById("login-popup").classList.add('hidden');
        });

        document.getElementById("signup-close").addEventListener("click", function() {
            document.getElementById("signup-popup").classList.add('hidden');
        });

        function toggleForms() {
            const loginPopup = document.getElementById("login-popup");
            const signupPopup = document.getElementById("signup-popup");

            if (loginPopup.classList.contains('hidden')) {
                loginPopup.classList.remove('hidden');
                signupPopup.classList.add('hidden');
            } else {
                signupPopup.classList.remove('hidden');
                loginPopup.classList.add('hidden');
            }
        }

        let currentPage = 0;
        const cardsPerPage = 3;
        const cards = document.querySelectorAll('.course-card');
        const totalCards = cards.length;
        const totalPages = Math.ceil(totalCards / cardsPerPage);

        function showPage(page) {
            // Hide all cards
            cards.forEach(card => card.classList.add('hidden'));
            // Calculate start and end index
            const start = page * cardsPerPage;
            const end = start + cardsPerPage;

            // Show cards for the current page
            for (let i = start; i < end && i < totalCards; i++) {
                cards[i].classList.remove('hidden');
            }

            // Update page number display
            document.getElementById('page-number').textContent = page + 1;
            document.getElementById('total-pages').textContent = totalPages;

            // Update pagination links
            updatePagination(page);

            // Disable buttons if on first or last page
            document.getElementById('prev').disabled = page === 0;
            document.getElementById('next').disabled = page === totalPages - 1;
        }

        function changePage(direction) {
            currentPage += direction;
            showPage(currentPage);
        }

        function updatePagination(page) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = ''; // Clear previous links

            for (let i = 0; i < totalPages; i++) {
                const pageLink = document.createElement('button');
                pageLink.textContent = i + 1;
                pageLink.className = 'bg-gray-200 text-gray-800 rounded px-2 py-1 mx-1' + (i === page ? ' font-bold' : '');
                pageLink.onclick = () => {
                    currentPage = i;
                    showPage(currentPage);
                };
                paginationContainer.appendChild(pageLink);
            }
        }

        // Initial page
        showPage(currentPage);
        updatePagination(currentPage);

        
        // Function to show the popup with course details
        function showPopup(course) {
            const popup = document.getElementById('course-popup');
            const popupContent = document.getElementById('popup-content');

            let content = '';
            if (course.contenu === 'video') {
                const embedUrl = course.video_url ? course.video_url.replace('watch?v=', 'embed/') : null;
                content = embedUrl ?
                    `<iframe src="${embedUrl}" class="w-full h-96 rounded" frameborder="0" allowfullscreen></iframe>` :
                    '<p class="text-red-600">URL de vidéo invalide.</p>';
            } else if (course.contenu === 'document') {
                content = `
                <textarea class="w-full h-96 p-4 border border-gray-300 rounded" readonly>${course.document_text}</textarea>
            `;
            }

            content += `
            <h2 class="text-xl font-bold mt-4">${course.titre}</h2>
            <p class="text-gray-700 mt-2">${course.description}</p>
        `;

            popupContent.innerHTML = content;
            popup.classList.remove('hidden');
        }

        // Function to close the popup
        function closePopup() {
            const popup = document.getElementById('course-popup');
            popup.classList.add('hidden');
        }
    </script>

</body>

</html>