<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $doctorId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

    if ($doctorId <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Thiếu ID bác sĩ cần xóa.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $stmtFind = $pdo->prepare("SELECT taiKhoanId FROM bacSi WHERE id = ?");
    $stmtFind->execute([$doctorId]);
    $taiKhoanId = $stmtFind->fetchColumn();

    if (!$taiKhoanId) {
        http_response_code(404);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Không tìm thấy bác sĩ.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Xóa ở taiKhoan sẽ tự động CASCADE xóa ở bacSi
    // Nếu bác sĩ đang có lịch khám (ON DELETE RESTRICT), DB sẽ báo lỗi 23000 để bảo vệ dữ liệu
    $stmtDel = $pdo->prepare("DELETE FROM taiKhoan WHERE id = ?");
    $stmtDel->execute([$taiKhoanId]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa vĩnh viễn bác sĩ khỏi hệ thống!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    http_response_code(400);
    if ($e->getCode() == 23000) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Không thể xóa bác sĩ này vì đang có lịch khám trong hệ thống. Hãy chọn "Vô hiệu hóa" thay thế!'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}