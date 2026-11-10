<?php
require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$doctorId = $_GET['id'] ?? '';

if (empty($doctorId)) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu ID Bác sĩ'], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Lấy taiKhoanId từ bác sĩ
    $stmtGet = $pdo->prepare("SELECT taiKhoanId FROM bacSi WHERE id = ?");
    $stmtGet->execute([$doctorId]);
    $taiKhoanId = $stmtGet->fetchColumn();

    // 2. Xóa các lịch khám gắn liền với Bác sĩ này
    $stmtDeleteLich = $pdo->prepare("DELETE FROM lichKham WHERE bacSiId = ?");
    $stmtDeleteLich->execute([$doctorId]);

    // 3. Xóa thông tin bác sĩ
    $stmtDeleteBS = $pdo->prepare("DELETE FROM bacSi WHERE id = ?");
    $stmtDeleteBS->execute([$doctorId]);

    // 4. Xóa tài khoản đăng nhập
    if (!empty($taiKhoanId)) {
        $stmtDeleteTK = $pdo->prepare("DELETE FROM taiKhoan WHERE id = ?");
        $stmtDeleteTK->execute([$taiKhoanId]);
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa vĩnh viễn bác sĩ và các lịch khám liên quan!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'status' => 'error',
        'message' => 'Không thể xóa bác sĩ do ràng buộc dữ liệu: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}