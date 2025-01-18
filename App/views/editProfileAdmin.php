<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Config\Database;
use App\Controllers\AdminHandler;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/Youdemy_plateform/App/controllers/logout.php");
    exit();
}

$pdo = Database::connect();
$adminId = $_SESSION['user_id'];

$adminHandler = new AdminHandler($pdo, $adminId);
$currentAdminData = $adminHandler->getCurrentAdminData($adminId);

if (!$currentAdminData) {
    die('Admin not found.');
}
$name = $currentAdminData['name'] ?? '';
$email = $currentAdminData['email'] ?? '';
$bio = $currentAdminData['bio'] ?? '';
$imageUrl = $currentAdminData['image_url'] ?? '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'bio' => trim($_POST['bio'] ?? ''),
        'image_url' => trim($_POST['image_url'] ?? '')
    ];

    if ($adminHandler->updateAdminProfile($data)) {
        // Update the session variable to reflect the new name
        $_SESSION['user_name'] = $data['name'];

        header("Location: http://localhost/Youdemy_plateform/App/public/dist/dashboard.php");
        exit();
    } else {
        $errorMessage = "An error occurred while updating the profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin Profile</title>
    <link rel="stylesheet" href="../public/dist/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php if (!empty($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio"><?= htmlspecialchars($bio) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="image_url" name="image_url" value="<?= htmlspecialchars($imageUrl) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
