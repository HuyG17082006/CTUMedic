<?php

$role = 'doctor';

$tab = $_GET['tab'] ?? 'lichkham';

$tabConfigs = [
    'lichkham' => [
        'title' => 'Lịch khám',
        'description' => 'Theo dõi và quản lý lịch khám được phân công.',
    ],

    'lichsu' => [
        'title' => 'Lịch sử khám',
        'description' => 'Xem lại lịch sử các cuộc hẹn đã hoàn thành.',
    ],

    'quanly' => [
        'title' => 'Quản lý lịch nghỉ',
        'description' => 'Theo dõi lịch nghỉ và đăng ký nghỉ làm theo buổi.',
    ],

    'taikhoan' => [
        'title' => 'Thông tin tài khoản',
        'description' => 'Cập nhật thông tin cá nhân và đổi mật khẩu.',
    ],
];

if (!isset($tabConfigs[$tab])) {
    $tab = 'lichkham';
}

$pageTitle = $tabConfigs[$tab]['title'];
$pageDescription = $tabConfigs[$tab]['description'];

$sharedTabs = [
    'lichkham',
    'lichsu',
    'taikhoan',
    'quanly'
];

$sharedTabFiles = [
    'lichkham' => 'appointments',
    'lichsu' => 'appointment_history',
    'taikhoan' => 'account',
    'quanly' => 'work_calender'
];

$contentFile = in_array($tab, $sharedTabs, true)
    ? PATH_VIEWS . '/shared/' . $sharedTabFiles[$tab] . '.php'
    : '';

require PATH_COMPONENTS . '/dashboard_layout.php';
