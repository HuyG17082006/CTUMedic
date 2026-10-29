<?php
require __DIR__ . '/BASE.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Xử lý các request gọi API trước tiên
if (isset($_GET['api'])) {
    $allowedApis = [
        "register"  => PATH_API . '/TaiKhoan/register.php',
        "login"     => PATH_API . '/TaiKhoan/login.php',
        "logout"    => PATH_API . '/TaiKhoan/logout.php',
        // Thêm các API khác ở đây nếu sau này có (ví dụ: login, update, ...)
    ];

    $apiName = $_GET['api'];

    if (array_key_exists($apiName, $allowedApis)) {
        require_once $allowedApis[$apiName];
    } else {
        http_response_code(404);
        exit('API not found');
    }
}

$role = $_SESSION['role'] ?? '';
$page = $_GET['page'] ?? null;

$allowedPages = [
    'login'     => 'DangNhap/login.php',
    'register'  => 'DangNhap/register.php',
    'patient'   => 'BenhNhan/benhnhan.php',
    'doctor'    => 'BacSi/bacsi.php',
    'manager'   => 'QuanLy/quanly.php',
];

$dashboardPages = ['patient', 'doctor', 'manager'];

// 2. Nếu người dùng truy cập vào trang chủ không có tham số ?page
if ($page === null) {
    if (!empty($role) && array_key_exists($role, $allowedPages)) {
        header("Location: index.php?page=" . $role);
    } else {
        header("Location: index.php?page=login");
    }
    exit;
}

// 3. Kiểm tra trang yêu cầu có tồn tại trong hệ thống không
if (!array_key_exists($page, $allowedPages)) {
    http_response_code(404);
    exit('Page not found');
}

// 4. Kiểm tra phân quyền truy cập (Authorization)
// Cho phép truy cập tự do trang login và register
if ($page !== 'login' && $page !== 'register') {
    if (empty($role) || $role !== $page) {
        http_response_code(403);
        exit('Access denied');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTUMedic</title>
    <link rel="icon" href="./frontend/assets/images/IconCTUMedic.png">
    <link rel="stylesheet" href="./frontend/assets/css/base.css">
    <link rel="stylesheet" href="./frontend/assets/css/register.css">
    <link rel="stylesheet" href="./frontend/assets/css/login.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <?php if (in_array($page, $dashboardPages, true)): ?>
        <link rel="stylesheet" href="./frontend/assets/css/dashboard.css">
        <!-- <link rel="stylesheet" href="./frontend/assets/css/appointment-filter.css">
        <link rel="stylesheet" href="./frontend/assets/css/appointment-page.css"> -->
        <link rel="stylesheet" href="./frontend/assets/css/appointment-table-filter-test.css">
        <link rel="stylesheet" href="./frontend/assets/css/appointment-detail-modal.css">
        <link rel="stylesheet" href="./frontend/assets/css/popupLogout.css">
        <?php if ($role && $role == 'doctor'): ?>
            <link rel="stylesheet" href="./frontend/assets/css/doctor-schedule.css">
        <?php endif; ?>
        <?php if ($role && $role == 'patient'): ?>
            <link rel="stylesheet" href="./frontend/assets/css/patient-booking.css">
        <?php endif; ?>
    <?php endif; ?>
</head>

<body>
    <?php
    // Nhúng view tương ứng vào hệ thống
    require PATH_VIEWS . '/' . $allowedPages[$page];
    ?>
</body>

</html>