<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $patientId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

    if ($patientId <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Thiếu ID bệnh nhân cần xóa.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Lấy taiKhoanId tương ứng với benhNhan.id
    $stmtFind = $pdo->prepare("SELECT taiKhoanId FROM benhNhan WHERE id = ?");
    $stmtFind->execute([$patientId]);
    $taiKhoanId = $stmtFind->fetchColumn();

    if (!$taiKhoanId) {
        http_response_code(404);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Không tìm thấy bệnh nhân.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Xóa ở taiKhoan sẽ tự động CASCADE xóa bản ghi bên benhNhan
    $stmtDel = $pdo->prepare("DELETE FROM taiKhoan WHERE id = ?");
    $stmtDel->execute([$taiKhoanId]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa vĩnh viễn bệnh nhân khỏi hệ thống!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Không thể xóa bệnh nhân này (có thể đang có dữ liệu liên quan): ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}