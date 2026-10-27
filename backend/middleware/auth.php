<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start();

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['id'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập trước khi thực hiện thao tác này'
    ]);

    exit;
}

// $userId = $_SESSION['id'] || '';
$userId = $_SESSION['userId'];
$role = $_SESSION['role'] ?? '';

$allowedRoles = ['patient', "doctor", "manager"];

if (!in_array($role, $allowedRoles, true)) {
    http_response_code(403);

    echo json_encode([
        'success' => false,
        'message' => 'Không có quyền truy cập'
    ]);

    exit;
}
?>