<?php

define('BASE_PATH', __DIR__);

define('BASE_URL', '/dashboard/CTUMedic');
define("API_URL", BASE_URL.'/backend/api');

define('PATH_FRONTEND', BASE_PATH . '/frontend');
define('PATH_BACKEND', BASE_PATH . '/backend');
define('PATH_VIEWS', PATH_FRONTEND . '/views');
define('PATH_COMPONENTS', PATH_FRONTEND . '/components');
define('PATH_API', PATH_BACKEND . '/api');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['role'] = 'manager';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./frontend/assets/css/base.css">
    <link rel="stylesheet" href="./frontend/assets/css/register.css">
    <link rel="stylesheet" href="./frontend/assets/css/login.css">

    <?php
    $page = $_GET['page'] ?? 'login';

    $dashboardPages = ['patient', 'doctor', 'manager'];
    ?>

    <?php if (in_array($page, $dashboardPages, true)): ?>
        <link rel="stylesheet" href="./frontend/assets/css/dashboard.css">
        <link rel="stylesheet" href="./frontend/assets/css/appointment-filter.css">
        <link rel="stylesheet" href="./frontend/assets/css/appointment-page.css">
        <link rel="stylesheet" href="./frontend/assets/css/appointment-detail-modal.css">
    <?php endif; ?>
</head>

<body>

    <?php

    $role = $_SESSION['role'] ?? '';

    $allowedPages = [
        'login'    => 'DangNhap/login.php',
        'register' => 'DangNhap/register.php',
        'patient'  => 'BenhNhan/benhnhan.php',
        'doctor'   => 'BacSi/bacsi.php',
        'manager'  => 'QuanLy/quanly.php'
    ];

    if (!isset($_GET['page'])) {
        if (array_key_exists($role, $allowedPages)) {
            header("Location: index.php?page=" . $role);
        } else {
            header("Location: index.php?page=login");
        }
        exit;
    }

    $page = $_GET['page'];

    if ($page === 'login') {
        if (array_key_exists($role, $allowedPages)) {
            header("Location: index.php?page=" . $role);
            exit;
        }

        require PATH_VIEWS . '/DangNhap/login.php';
        exit;
    }

    if (!array_key_exists($page, $allowedPages)) {
        http_response_code(404);
        exit('Page not found');
    }

    if ($role !== $page) {

        if ($page === 'login' || $page === 'register') {
            require PATH_VIEWS . '/' . $allowedPages[$page];
            return;
        }

        http_response_code(403);
        exit('Access denied');
    }

    require PATH_VIEWS . '/' . $allowedPages[$page];

    ?>

</body>

</html>