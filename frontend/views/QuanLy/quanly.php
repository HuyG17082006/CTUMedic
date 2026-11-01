<?php
$role = 'manager';

$tabConfigs = [
    'tongquan' => [
        'title' => 'Tổng quan',
        'description' => 'Tổng quan hoạt động của hệ thống CTUMedic.',
    ],
    'lichkham' => [
        'title' => 'Quản lý lịch khám',
        'description' => 'Theo dõi và quản lý lịch khám toàn hệ thống.',
    ],
    'lichsu' => [
        'title' => 'Lịch sử khám',
        'description' => 'Tra cứu lịch sử các cuộc hẹn khám.',
    ],
    'benhnhan' => [
        'title' => 'Quản lý bệnh nhân',
        'description' => 'Quản lý thông tin và hồ sơ bệnh nhân.',
    ],
    'bacsi' => [
        'title' => 'Quản lý bác sĩ',
        'description' => 'Quản lý thông tin và danh sách bác sĩ.',
    ],
    'taikhoan' => [
        'title' => 'Thông tin tài khoản',
        'description' => 'Cập nhật thông tin cá nhân và đổi mật khẩu.',
    ],
    'nguoidung' => [
        'title' => 'Quản lý người dùng',
        'description' => 'Quản lý tài khoản và phân quyền.',
    ],
];

$tab = $_GET['tab'] ?? 'lichkham';

if (!isset($tabConfigs[$tab])) {
    $tab = 'tongquan';
}

$pageTitle = $tabConfigs[$tab]['title'];
$pageDescription = $tabConfigs[$tab]['description'];

$sharedTabs = ['lichkham', 'lichsu', 'taikhoan'];

$contentFile = in_array($tab, $sharedTabs, true)
    ? PATH_VIEWS . '/shared/' . $tab . '.php'
    : PATH_VIEWS . '/QuanLy/tabs/' . $tab . '.php';

require PATH_COMPONENTS . '/dashboard-layout.php';