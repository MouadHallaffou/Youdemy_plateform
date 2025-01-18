<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminName = 'MouadHallaffou';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $adminName = $_SESSION['user_name'] ?? $user['name'];
        $_SESSION['user_name'] = $adminName; 
    }
}
?>

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-4" href="http://localhost/Youdemy_plateform/App/public/dist/dashboard.php"> YOUDEMY</a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <!-- <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div> -->
    </form>

    <div class="d-flex align-items-center text-white me-3">
        <div class="ms-2"><?= htmlspecialchars($adminName) ?></div>
    </div>

    <!-- Navbar admin Profile -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link " id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars ($_SESSION['image_url'] ?? 'https://cdn.sofifa.net/players/209/981/25_120.png') ?>" alt="Profile" class="rounded-circle" style="width: 30px; height: 30px;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="http://localhost/Youdemy_plateform/App/views/editProfileAdmin.php">Profile</a></li>
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="http://localhost/Youdemy_plateform/App/controllers/logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
