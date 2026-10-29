<?php
define('BASE_PATH', __DIR__);

define('BASE_URL', '/dashboard/CTUMedic');
define("API_URL", BASE_URL . '/backend/api');

define('PATH_FRONTEND', BASE_PATH . '/frontend');
define('PATH_BACKEND', BASE_PATH . '/backend');
define('PATH_VIEWS', PATH_FRONTEND . '/views');
define('PATH_COMPONENTS', PATH_FRONTEND . '/components');
define('PATH_API', PATH_BACKEND . '/api');

define('WORKING_TIME_SLOTS', [
    'morning' => [
        'label' => 'Buổi sáng',
        'start' => '07:00',
        'end' => '11:00',
        'slots' => [
            ['start' => '07:00', 'end' => '07:40'],
            ['start' => '07:40', 'end' => '08:20'],
            ['start' => '08:20', 'end' => '09:00'],
            ['start' => '09:00', 'end' => '09:40'],
            ['start' => '09:40', 'end' => '10:20'],
            ['start' => '10:20', 'end' => '11:00'],
        ],
    ],
    'afternoon' => [
        'label' => 'Buổi chiều',
        'start' => '13:00',
        'end' => '17:00',
        'slots' => [
            ['start' => '13:00', 'end' => '13:40'],
            ['start' => '13:40', 'end' => '14:20'],
            ['start' => '14:20', 'end' => '15:00'],
            ['start' => '15:00', 'end' => '15:40'],
            ['start' => '15:40', 'end' => '16:20'],
            ['start' => '16:20', 'end' => '17:00'],
        ],
    ],
]);

define('SPECIALTY_LIST', [
    "Nội khoa",
    "Tim mạch",
    "Da liễu"
]);

?>
