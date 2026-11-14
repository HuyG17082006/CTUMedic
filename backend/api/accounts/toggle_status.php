<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $id     = trim($_GET['id'] ?? $_POST['id'] ?? '');
    $status = $_GET['status'] ?? $_POST['status'] ?? null;

    if ($id === '' || ($status !== '0' && $status !== '1' && $status !== 0 && $status !== 1)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Thiếu ID tài khoản hoặc trạng thái không hợp lệ.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $isActive = (int) $status;

    $stmt = $pdo->prepare("UPDATE taiKhoan SET isActive = ? WHERE id = ?");
    $stmt->execute([$isActive, $id]);

    $msg = $isActive === 1 ? 'Đã kích hoạt tài khoản thành công!' : 'Đã vô hiệu hóa tài khoản thành công!';

    echo json_encode([
        'status' => 'success',
        'message' => $msg
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}