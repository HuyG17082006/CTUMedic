<?php
require __DIR__ . '/BASE.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Xử lý các request gọi API trước tiên
if (isset($_GET['api'])) {
    $allowedApis = [
        "register"                  => PATH_API . '/TaiKhoan/register.php',
        "login"                     => PATH_API . '/TaiKhoan/login.php',
        "logout"                    => PATH_API . '/TaiKhoan/logout.php',
        "profile"                   => PATH_API . '/TaiKhoan/trang_ca_nhan.php',

        "manager_get_patients"      => PATH_API . '/QuanLy/benhnhan.php',
        "manager_update_patient"    => PATH_API . '/QuanLy/manager_update_patient.php',
        "manager_delete_patient"    => PATH_API . '/QuanLy/manager_delete_patient.php',

        "manager_get_doctors"       => PATH_API   . '/QuanLy/bacsi.php',
        "manager_add_doctor"        => PATH_API . '/QuanLy/manager_add_doctor.php',
        "manager_update_doctor"     => PATH_API . '/QuanLy/manager_update_doctor.php',
        "manager_delete_doctor"     => PATH_API . '/QuanLy/manager_delete_doctor.php',
    ];

    $apiName = $_GET['api'];

    if (array_key_exists($apiName, $allowedApis)) {
        require_once $allowedApis[$apiName];
        exit(); // <-- Thêm dòng này để dừng lại, không chạy xuống code bên dưới nữa!
    } else {
        http_response_code(404);
        exit('API not found');
    }
}

$_SESSION['role'] = 'manager';
$role = $_SESSION['role'] ?? "";
$page = $_GET['page'] ?? null;


$allowedPages = [
    'login'                 => 'DangNhap/login.php',
    'register'              => 'DangNhap/register.php',
    'patient'               => 'BenhNhan/benhnhan.php',
    'doctor'                => 'BacSi/bacsi.php',
    'manager'               => 'QuanLy/quanly.php',
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
    <?php
    require __DIR__ . '/frontend/assets/css/base/index-css.php';
    require __DIR__ . '/frontend/assets/css/appointment/index-css.php';
    require __DIR__ . '/frontend/assets/css/DangNhap/index-css.php';
    ?>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <?php
    if (in_array($page, $dashboardPages, true)):
        require __DIR__ . '/frontend/assets/css/shared/index-css.php';
    ?>

        <script>
            window.SPECIALTY_LIST = <?= json_encode(
                                        SPECIALTY_LIST,
                                        JSON_UNESCAPED_UNICODE
                                    ) ?>;
            window.WORKING_TIME_SLOTS = <?= json_encode(
                                            WORKING_TIME_SLOTS,
                                            JSON_UNESCAPED_UNICODE
                                        ) ?>;
        </script>
        <?php 
        if ($role && $role == 'manager'):
            require __DIR__ . '/frontend/assets/css/QuanLy/index-css.php';
        endif;
        ?>
        <?php 
        if ($role && $role == 'doctor'):
            require __DIR__ . '/frontend/assets/css/BacSi/index-css.php';
        endif;
        ?>
        <?php 
        if ($role && $role == 'patient'):
            require __DIR__ . '/frontend/assets/css/BenhNhan/index-css.php';
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