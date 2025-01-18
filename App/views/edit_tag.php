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
                    <h1 class="mt-4">Modifier un Tag</h1>

                    <?php
                    require_once '../controllers/crud_tags.php';
                    $tagId = $_GET['id'] ?? null;
                    $tag = $tagController->getTagById($tagId);
                    if ($tag):
                    ?>

                        <div class="modal show" id="updatetagModal" tabindex="-1" aria-labelledby="updatetagModalLabel" aria-hidden="true" style="display: block;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updatetagModalLabel">Modifier Tags</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="../controllers/crud_tags.php" method="POST">
                                            <input type="hidden" name="tag_id" value="<?= htmlspecialchars($tag['tag_id']) ?>">
                                            <div class="mb-3">
                                                <label for="tag_name" class="form-label">Nom de tag</label>
                                                <input type="text" class="form-control" id="tag_name" name="tagEdit_name" value="<?= htmlspecialchars($tag['name']) ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                            <a href="../views/tags.php" class="btn btn-secondary">Annuler</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Display Total Tags -->
                    <div class="row mt-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tags</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($tags) ?? 0; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- List of Tags -->
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
                                    <?php if (!empty($tags)): ?>
                                        <?php foreach ($tags as $tag): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($tag['tag_id']) ?></td>
                                                <td><?= htmlspecialchars($tag['name']) ?></td>
                                                <td>
                                                    <a href="../controllers/crud_tags.php?action=edit&id=<?= htmlspecialchars($tag['tag_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="../controllers/crud_tags.php?action=delete&id=<?= htmlspecialchars($tag['tag_id']) ?>" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3">Aucun Tag disponible.</td>
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