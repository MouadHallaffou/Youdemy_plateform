<?php
require_once __DIR__ . './vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: http://localhost/Youdemy_plateform/App/views/userInterface.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOUDEMY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hidden {
            display: none;
        }

        .modal {
            position: absolute;
            top: 4rem;
            left: 50%;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    
    <?php require_once __DIR__ .'./App/views/userInterface.php';?>

    <script src="./App/public/dist/js/mainUserinterface.js"></script>
</body>
</html>
