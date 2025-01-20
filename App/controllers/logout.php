<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Models\User;

if (session_status()) {
    User::logout();
    header('Location: http://localhost/Youdemy_plateform/index.php');
    exit();
} else {
    header('Location: http://localhost/Youdemy_plateform/index.php');
    exit();
}
