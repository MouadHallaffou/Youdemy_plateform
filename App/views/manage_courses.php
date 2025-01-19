<?php
require_once __DIR__ . '/../controllers/crud_course.php';
if (!isset($courses) || !is_array($courses)) {
    $courses = [];
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
    <link href="../public/dist/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

    <?php require_once __DIR__ . './../public/dist/shared/topbar.php'; ?>


    <div id="layoutSidenav">

        <?php
        require_once '../public/dist/shared/sidebar.php';
        ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"></li>
                    </ol>

                    <div class="row">
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


                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Enseignant</th>
                                        <th>Date</th>
                                        <th>Catégorie</th>
                                        <th>Tags</th>
                                        <th>status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Enseignant</th>
                                        <th>Date</th>
                                        <th>Catégorie</th>
                                        <th>Tags</th>
                                        <th>status</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($course['course_id']); ?></td>
                                            <td><?= htmlspecialchars($course['titre']); ?></td>
                                            <td><?= htmlspecialchars($course['name']);
                                                ?></td>
                                            <td><?= htmlspecialchars($course['date']);
                                                ?></td>
                                            <td><?= htmlspecialchars($course['category_name']); ?></td>
                                            <td><?= htmlspecialchars($course['tags'] ?: 'Aucun tag'); ?></td>
                                            <td><?= htmlspecialchars($course['status']); ?></td>
                                            <td>
                                                <a href="../controllers/crud_course.php?action=accept&id=<?= htmlspecialchars($course['course_id']) ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Accepter
                                                </a>
                                                <a href="../controllers/crud_course.php?action=refuse&id=<?= htmlspecialchars($course['course_id']) ?>" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Refuser
                                                </a>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </main>
            <?php require_once '../public/dist/shared/footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../public/dist/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../public/dist/assets/demo/chart-area-demo.js"></script>
    <script src="../public/dist/assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../public/dist/js/datatables-simple-demo.js"></script>
</body>

</html>