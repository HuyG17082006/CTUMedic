<?php
$role = 'manager';

$tabConfigs = [
    'quanly' => [
        'title' => 'Quản lý lịch nghỉ',
        'description' => 'Theo dõi lịch nghỉ và đăng ký nghỉ làm theo buổi.',
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
    ]
];

$tab = $_GET['tab'] ?? 'lichkham';

if (!isset($tabConfigs[$tab])) {
    $tab = 'lichkham';
}

$pageTitle = $tabConfigs[$tab]['title'];
$pageDescription = $tabConfigs[$tab]['description'];

$sharedTabs = ['lichkham', 'lichsu', 'taikhoan', 'quanly'];

$sharedTabFiles = [
    'lichkham' => 'appointments',
    'lichsu' => 'appointment_history',
    'taikhoan' => 'account',
    'quanly' => 'work_calender'
];

$managementTabFiles = [
    'benhnhan' => 'patients',
    'bacsi' => 'doctors',
];

$contentFile = in_array($tab, $sharedTabs, true)
    ? PATH_VIEWS . '/shared/' . $sharedTabFiles[$tab] . '.php'
    : PATH_VIEWS . '/management/tabs/' . $managementTabFiles[$tab] . '.php';

require PATH_COMPONENTS . '/dashboard_layout.php';