<?php
require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$taiKhoanId = $_GET['id'] ?? '';
$status     = (int)($_GET['status'] ?? 0); // 1: Kích hoạt, 0: Vô hiệu hóa

if (empty($taiKhoanId)) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu ID tài khoản'], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE taiKhoan SET isActive = ? WHERE id = ?");
    $stmt->execute([$status, $taiKhoanId]);

    echo json_encode([
        'status' => 'success',
        'message' => $status === 1 ? 'Đã kích hoạt tài khoản thành công!' : 'Đã vô hiệu hóa tài khoản thành công!'
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái tài khoản'], JSON_UNESCAPED_UNICODE);
}