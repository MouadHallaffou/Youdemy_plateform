<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

        <div class="lg:hidden">
            <button class="navbar-burger flex items-center text-violet-600 dark:text-gray-100 p-1" id="navbar_burger">
                <i class="fas fa-bars h-6 w-6"></i>
            </button>
        </div>

        <div class="relative mx-auto hidden lg:block">
            <input class="border border-gray-300 placeholder-current h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none dark:bg-gray-500 dark:border-gray-50 dark:text-gray-200" type="search" name="search" placeholder="Search">
            <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                <i class="fas fa-search text-gray-600 dark:text-gray-200 h-4 w-4"></i>
            </button>
        </div>

        <div class="lg:flex items-center">
            <button class="py-1.5 px-3 m-1 text-center bg-violet-700 border rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="open-login-popup">
                Login
            </button>
            <button class="py-1.5 px-3 m-1 text-center bg-violet-700 border rounded-md text-white hover:bg-violet-600 dark:bg-violet-700 dark:hover:bg-violet-600" id="open-signup-popup">
                Sign up
            </button>
        </div>
    </nav>

    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16 mt-20">
        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
            <div class="border border-gray-400 bg-white rounded flex flex-col justify-between leading-normal shadow-md">
                <div class="p-4">
                    <iframe id="video_iframe" src="https://www.youtube.com/embed/cRsgRrsS-v0"  class="w-full h-60 rounded" frameborder="0" allowfullscreen></iframe>
                    <a href="#" class="text-gray-900 font-bold text-lg mb-2 hover:text-indigo-600">Titre du cours</a>
                    <p class="text-gray-700 text-sm">Description du contenu du cours ici...</p>
                </div>
                <div class="flex items-center p-4 border-t border-gray-300">
                    <a href="#">
                        <img class="w-10 h-10 rounded-full mr-4" src="https://tailwindcss.com/img/jonathan.jpg" alt="Avatar de l'enseignant">
                    </a>
                    <div class="text-sm">
                        <a href="#" class="text-gray-900 font-semibold leading-none hover:text-indigo-600">Nom de l'enseignant</a>
                        <p class="text-gray-600">Date de création du cours: 2025-01-15</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-sky-800">
        <div class="mx-auto max-w-7xl py-8 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <nav class="-mx-5 -my-2 flex flex-wrap justify-center order-2" aria-label="Footer">
                <div class="px-5">
                    <a href="#" class="text-base text-white hover:text-gray-200">Terms of Service</a>
                </div>
                <div class="px-5">
                    <a href="#" class="text-base text-white hover:text-gray-200">Privacy Policy</a>
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

    <!-- Popup Login -->
    <div id="login-popup" tabindex="-1"
        class="bg-black/50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 h-full items-center justify-center flex hidden">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" id="popup-close">
                    <i class="fas fa-times"></i>
                    <span class="sr-only">Close popup</span>
                </button>

                <div class="p-5">
                    <h3 class="text-2xl mb-3 font-medium">Connexion</h3>
                    <p class="mb-4 text-sm font-normal text-gray-800">Entrez vos identifiants pour vous connecter.</p>

                    <form id="login-form">
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
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" id="signup-close">
                    <i class="fas fa-times"></i>
                    <span class="sr-only">Close popup</span>
                </button>

                <div class="p-5">
                    <h3 class="text-2xl mb-3 font-medium">Créer un compte</h3>

                    <form id="signup-form">
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

                        <label for="role" class="sr-only">Rôle</label>
                        <select name="role" required
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 mt-2 outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1">
                            <option value="">-- Choisir un rôle --</option>
                            <option value="etudiant">Étudiant</option>
                            <option value="enseignant">Enseignant</option>
                        </select>

                        <button type="submit"
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
        document.getElementById("open-login-popup").addEventListener("click", function () {
            document.getElementById("login-popup").classList.remove('hidden');
            document.getElementById("signup-popup").classList.add('hidden');
        });

        document.getElementById("open-signup-popup").addEventListener("click", function () {
            document.getElementById("signup-popup").classList.remove('hidden');
            document.getElementById("login-popup").classList.add('hidden');
        });

        document.getElementById("popup-close").addEventListener("click", function () {
            document.getElementById("login-popup").classList.add('hidden');
        });

        document.getElementById("signup-close").addEventListener("click", function () {
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
    </script>

</body>

</html>
