
<?php


$role = 'patient';

$allowedTabs = [
    'tongquan',
    'datlich',
    'lichkham',
    'lichsu',
    'taikhoan',
];

$tab = $_GET['tab'] ?? 'tongquan';

if (!in_array($tab, $allowedTabs, true)) {
    $tab = 'tongquan';
}

$tabsDirectory = PATH_VIEWS . '/BenhNhan/tabs';

$tabConfigs = [
    'tongquan' => [
        'title' => 'Tổng quan',
        'description' => 'Tổng quan hoạt động khám chữa bệnh của bạn.',
    ],
    'datlich' => [
        'title' => 'Đặt lịch khám',
        'description' => 'Đặt lịch khám với bác sĩ và chuyên khoa phù hợp.',
    ],
    'lichkham' => [
        'title' => 'Lịch khám',
        'description' => 'Theo dõi và quản lý các lịch khám của bạn.',
    ],
    'lichsu' => [
        'title' => 'Lịch sử khám',
        'description' => 'Xem lại lịch sử khám chữa bệnh của bạn.',
    ],
    'taikhoan' => [
        'title' => 'Thông tin tài khoản',
        'description' => 'Cập nhật thông tin cá nhân và đổi mật khẩu.',
    ],
];

$pageTitle = $tabConfigs[$tab]['title'];
$pageDescription = $tabConfigs[$tab]['description'];

// Các trang dùng chung giữa các vai trò
$sharedTabs = ['lichkham', 'lichsu', 'taikhoan'];

if (in_array($tab, $sharedTabs, true)) {
    $contentFile = PATH_VIEWS . '/shared/' . $tab . '.php';
} else {
    $contentFile = $tabsDirectory . '/' . $tab . '.php';
}

require PATH_COMPONENTS . '/dashboard-layout.php';
