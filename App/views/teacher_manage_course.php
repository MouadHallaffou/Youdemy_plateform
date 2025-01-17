<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Config\Database;
use App\models\Course;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold mb-4">All Courses</h2>
        <table id="example" class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">id</th>
                    <th class="px-4 py-2">titre</th>
                    <th class="px-4 py-2">contenu</th>
                    <th class="px-4 py-2">status</th>
                    <th class="px-4 py-2">Agecategory</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($courses as $course) :?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['course_id'])?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['titre'])?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['contenu'])?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['status'])?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['category_name'])?></td>
                    <td class="border px-4 py-2">
                    <a href="editCourse.php?id=<?= htmlspecialchars($course['course_id']) ?>"
                    class="mr-3 text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit</a>
                        <a href="../controllers/crud_course.php?action=delete&id=<?= htmlspecialchars($course['course_id']) ?>" onclick = "return confirm('are you sure de suprimmer ce cour ?');"
                        class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</a>
                    </td>
                </tr>
                <?php endforeach ;?>
            </tbody>
        </table>
        <a href="teacherinterface.php" 
            class="mb-4 text-white bg-gray-500 hover:bg-gray-700 py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Retour à la page précédente
        </a>
    </div>
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