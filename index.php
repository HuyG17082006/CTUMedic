<?php
require __DIR__ . '/BASE.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['role'] = 'doctor';
$role = strtolower(trim($_SESSION['role'] ?? ''));
$page = $_GET['page'] ?? null;

$allowedPages = [
    'login'    => 'authentication/login.php',
    'register' => 'authentication/register.php',
    'patient'  => 'patients/patients.php',
    'doctor'   => 'doctors/doctors.php',
    'manager'  => 'management/management.php',
];

$dashboardPages = ['patient', 'doctor', 'manager'];

// Chuyển trang chủ theo role hiện tại.
if ($page === null) {
    if (!empty($role) && array_key_exists($role, $allowedPages)) {
        header("Location: index.php?page=" . $role);
    } else {
        header("Location: index.php?page=login");
    }
    exit();
}

// Chỉ cho phép các trang đã khai báo.
if (!array_key_exists($page, $allowedPages)) {
    http_response_code(404);
    exit('Page not found');
}

// Người đã đăng nhập không cần quay lại trang đăng nhập/đăng ký.
if (!empty($role) && ($page === 'login' || $page === 'register')) {
    if (in_array($role, $dashboardPages, true)) {
        header("Location: index.php?page=" . $role);
        exit();
    }
}

// Chỉ cho phép truy cập dashboard đúng role.
if ($page !== 'login' && $page !== 'register') {
    if (empty($role)) {
        header("Location: index.php?page=login");
        exit();
    }
    if ($role !== $page) {
        header("Location: index.php?page=" . $role);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTUMedic</title>
    <?php
    require __DIR__ . '/frontend/assets/css/base/index_css.php';
    require __DIR__ . '/frontend/assets/css/appointment/index_css.php';
    require __DIR__ . '/frontend/assets/css/authentication/index_css.php';
    ?>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <?php
    if (in_array($page, $dashboardPages, true)):
        require __DIR__ . '/frontend/assets/css/shared/index_css.php';
    ?>
        <?php 
        if ($role === 'manager'):
            require __DIR__ . '/frontend/assets/css/management/index_css.php';
        endif;
        ?>
        <?php 
        if ($role === 'doctor'):
            require __DIR__ . '/frontend/assets/css/doctors/index_css.php';
        endif;
        ?>
        <?php 
        if ($role === 'patient'):
            require __DIR__ . '/frontend/assets/css/patients/index_css.php';
        endif;
        ?>

    <?php endif; ?>
</head>

<body>
    <?php
    // Nhúng view tương ứng vào hệ thống
    require PATH_VIEWS . '/' . $allowedPages[$page];
    ?>
</body>

</html>