<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

define("ROOT", dirname(__DIR__));

require_once ROOT . "/core/App.php";
require_once ROOT . "/core/Controller.php";
require_once ROOT . "/core/Model.php";

// Bảo vệ trang — chưa đăng nhập thì về login
$publicRoutes = ['auth/login', 'auth/logout'];
$currentUrl = $_GET['url'] ?? '';

if (empty($_SESSION['user']) && !in_array($currentUrl, $publicRoutes)) {
    header('Location: ?url=auth/login');
    exit;
}

$app = new App();