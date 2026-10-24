
<?php

$role = 'doctor';

$allowedTabs = [
    'tongquan',
    'lichkham',
    'lichsu',
    'taikhoan',
];

$tab = $_GET['tab'] ?? 'tongquan';

if (!in_array($tab, $allowedTabs, true)) {
    $tab = 'tongquan';
}

$tabsDirectory = PATH_VIEWS . '/BacSi/tabs';

$tabConfigs = [
    'tongquan' => [
        'title' => 'Tổng quan',
        'description' => 'Tổng quan hoạt động khám chữa bệnh.',
    ],
    'lichkham' => [
        'title' => 'Lịch khám',
        'description' => 'Theo dõi và quản lý lịch khám được phân công.',
    ],
    'lichsu' => [
        'title' => 'Lịch sử khám',
        'description' => 'Xem lại lịch sử các cuộc hẹn đã hoàn thành.',
    ],
    'taikhoan' => [
        'title' => 'Thông tin tài khoản',
        'description' => 'Cập nhật thông tin cá nhân và đổi mật khẩu.',
    ],
];

$pageTitle = $tabConfigs[$tab]['title'];
$pageDescription = $tabConfigs[$tab]['description'];

$sharedTabs = ['lichsu', 'lichkham', 'taikhoan'];

if (in_array($tab, $sharedTabs, true)) {
    $contentFile = PATH_VIEWS . '/shared/' . $tab . '.php';
} else {
    $contentFile = $tabsDirectory . '/' . $tab . '.php';
}

require PATH_COMPONENTS . '/dashboard-layout.php';
