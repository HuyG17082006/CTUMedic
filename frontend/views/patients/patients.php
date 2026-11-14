<?php

$role = 'patient';

$tab = $_GET['tab'] ?? 'lichkham';

$tabsDirectory = PATH_VIEWS . '/patients/tabs';

$tabConfigs = [
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

if (!isset($tabConfigs[$tab])) {
    $tab = 'lichkham';
}

$pageTitle = $tabConfigs[$tab]['title'];
$pageDescription = $tabConfigs[$tab]['description'];

$sharedTabs = ['lichkham', 'lichsu', 'taikhoan'];

$sharedTabFiles = [
    'lichkham' => 'appointments',
    'lichsu' => 'appointment_history',
    'taikhoan' => 'account',
];

$contentFile = in_array($tab, $sharedTabs, true)
    ? PATH_VIEWS . '/shared/' . $sharedTabFiles[$tab] . '.php'
    : $tabsDirectory . '/book_appointment.php';

require PATH_COMPONENTS . '/dashboard_layout.php';