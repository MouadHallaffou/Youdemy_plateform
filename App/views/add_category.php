<?php
require_once __DIR__ . '../../../vendor/autoload.php';
use App\Config\Database;
use App\Models\Category;
$pdo = Database::connect();
$categoryModel = new Category($pdo);
$categories = $categoryModel->getAllCategories();
$totalcategories = count($categories);
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

    <?php require_once __DIR__ . './../public/dist/shared/topbar.php';?>

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

                    <div class="modal show" id="addcategoryModal" tabindex="-1" aria-labelledby="addcategoryModalLabel" aria-hidden="true" style="display: block;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addcategoryModalLabel">Ajouter une categorys</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="../controllers/crud_categories.php" method="POST">
                                        <div class="mb-3">
                                            <label for="category_name" class="form-label">Nom de categorys</label>
                                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                        <a href="categories.php" class="btn btn-secondary">Annuler</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total categories</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $totalcategories ?? 0; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-category fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Liste des Tags
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($category['category_id']) ?></td>
                                                <td><?= htmlspecialchars($category['name']) ?></td>
                                                <td>
                                                    <a href="../views/edit_category.php?action=edit&id=<?= htmlspecialchars($category['category_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="../controllers/crud_categories.php?action=delete&id=<?= htmlspecialchars($category['category_id']) ?>" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3">Aucun category disponible.</td>
                                        </tr>
                                    <?php endif; ?>
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