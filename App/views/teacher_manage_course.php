<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\models\Course;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: http://localhost/Youdemy_plateform/index.php');
    exit();
}

$teacherId = $_SESSION['user_id'];
$pdo = Database::connect();
$courseModel = new Course($pdo);
$courses = $courseModel->getCoursesTeacher($_SESSION['user_name']);
if (!is_array($courses)) {
    $courses = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion des Cours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <nav class="fixed w-full top-0 z-50 px-4 flex justify-between items-center bg-white dark:bg-gray-800 border-b-2 dark:border-gray-600">
        <a class="text-2xl font-bold text-violet-600 dark:text-white" href="#">
            YOUDEMY
        </a>

        <div class="lg:flex items-center relative">
            <div id="menu-items" class="lg:flex items-center hidden lg:ml-4 flex-col lg:flex-row bg-gray-800 lg:bg-transparent p-2">
                <a href="teacherinterface.php"
                    class="mb-4 text-white bg-gray-500 hover:bg-gray-700 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Retour
                </a>
            </div>
        </div>

    </nav>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-20 mb-40">
        <h2 class="text-3xl font-bold mb-10">Mes Courses</h2>
        <table id="example" class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">id</th>
                    <th class="px-4 py-2">titre</th>
                    <th class="px-4 py-2">contenu</th>
                    <th class="px-4 py-2">status</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Nombre inscription</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course) : ?>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($course['course_id']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($course['titre']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($course['contenu']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($course['status']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($course['category_name']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($course['total_inscription']) ?></td>
                        <td class="border px-4 py-2">
                            <a href="editCourse.php?id=<?= htmlspecialchars($course['course_id']) ?>"
                                class="mr-3 text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit</a>
                            <a href="../controllers/crud_course.php?action=delete&id=<?= htmlspecialchars($course['course_id']) ?>" onclick="return confirm('are you sure de suprimmer ce cour ?');"
                                class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({

            });
        });
    </script>
</body>

</html>