<?php
require_once __DIR__ . '/../../../App/controllers/crud_course.php';
require_once __DIR__ . '/../../../App/controllers/crud_users.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: http://localhost/Youdemy_plateform/index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

    <!-- includes navbar -->

    <?php require_once __DIR__ . './shared/topbar.php'; ?>

    <div id="layoutSidenav">

        <?php
        require_once 'shared/sidebar.php';
        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>

                    <div class="row">
                        <!-- Total Users -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Utilisateurs</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= count($allTeachers) + count($AllStudent) ?? 0; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Students -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Étudiants</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= count($AllStudent) ?? 0; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Teachers -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Enseignants</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= count($allTeachers) ?? 0; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Courses -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Cours</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= count($courses) ?? 0; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Area Chart
                                </div>
                                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Bar Chart
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <!-- Carte des Top Teachers -->
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Top Teachers</h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($TopTeachers as $teacher): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <?php if ($teacher['teacher_image']): ?>
                                                    <img src="<?= htmlspecialchars($teacher['teacher_image']) ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="<?= htmlspecialchars($teacher['teacher_name']) ?>">
                                                <?php else: ?>
                                                    <div class="icon-circle bg-primary text-white" style="width: 30px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="font-weight-bold"><?= htmlspecialchars($teacher['teacher_name']) ?></div>
                                                <div class="text-gray-800"><?= $teacher['total_courses'] ?> courses</div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Carte des Top Courses -->
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Top Courses</h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($TopCourses as $course): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3 px-2 py-1">
                                                <div class="icon-circle bg-success text-white" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="font-weight-bold"><?= htmlspecialchars($course['course_title']) ?></div>
                                                <!-- <div class="text-gray-800"><?= htmlspecialchars($course['course_description']) ?></div> -->
                                                <div class="small text-gray-500"><?= $course['total_enrollments'] ?> students enrolled</div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            All Courses
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>titre</th>
                                        <th>enseignant</th>
                                        <th>profile</th>
                                        <th>categorie</th>
                                        <th>date de creation</th>
                                        <th>total inscription</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>id</th>
                                        <th>titre</th>
                                        <th>enseignant</th>
                                        <th>profile</th>
                                        <th>categorie</th>
                                        <th>date de creation</th>
                                        <th>total inscription</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php if (!empty($courses)) : ?>
                                        <?php foreach ($courses as $course) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($course['course_id']) ?></td>
                                                <td><?= htmlspecialchars($course['titre']) ?></td>
                                                <td><?= htmlspecialchars($course['name']) ?></td>
                                                <td><img src="<?= htmlspecialchars($course['image_url']) ?>" alt="Profile" width="20" height="20"></td>
                                                <td><?= htmlspecialchars($course['category_name']) ?></td>
                                                <td><?= htmlspecialchars($course['date']) ?></td>
                                                <td><?= htmlspecialchars($course['total_inscription']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7">Aucun cours trouvé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>


                </div>
            </main>
            <?php require_once 'shared/footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>