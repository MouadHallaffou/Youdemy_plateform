<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Models\User;

if (session_status()) {
    User::logout();
    header('Location: ../views/userInterface.php');
    exit();
} else {
    header('Location: ../views/userInterface.php');
    exit();
}
