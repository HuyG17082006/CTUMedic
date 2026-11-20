<?php
require_once __DIR__ . '/auth.php';

if ($role !== 'manager') {
    http_response_code(403);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Bạn không có quyền thực hiện thao tác này.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
