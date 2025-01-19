<?php
namespace App\views;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../controllers/crud_users.php';

use App\Controllers\UsersController;

$controller = new UsersController();
$teachers = $controller->getTeachers();

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
                        <!-- Total Teachers -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Enseignants</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $totalTeachers ?? 0; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Profile</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Profile</th>
                                        <th>Email</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($teacher['user_id']); ?></td>
                                            <td><?= htmlspecialchars($teacher['name']); ?></td>
                                            <td><?= htmlspecialchars($teacher['role']); ?></td>
                                            <td><?= htmlspecialchars($teacher['status']); ?></td>
                                            <td>
                                                <img src="<?= htmlspecialchars($teacher['image_url']); ?>" alt="Profile" width="30" height="30">
                                            </td>
                                            <td><?= htmlspecialchars($teacher['email']); ?></td>
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