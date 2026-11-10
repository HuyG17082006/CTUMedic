<?php
require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$patientId = $_GET['id'] ?? '';

if (empty($patientId)) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Thiếu ID bệnh nhân'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Lấy taiKhoanId từ bệnh nhân trước khi xóa
    $stmtGet = $pdo->prepare("SELECT taiKhoanId FROM benhNhan WHERE id = ?");
    $stmtGet->execute([$patientId]);
    $taiKhoanId = $stmtGet->fetchColumn();

    // 2. Xóa toàn bộ lịch khám liên quan đến bệnh nhân này trước
    $stmtDeleteLich = $pdo->prepare("DELETE FROM lichKham WHERE benhNhanId = ?");
    $stmtDeleteLich->execute([$patientId]);

    // 3. Xóa thông tin bệnh nhân trong bảng benhNhan
    $stmtDeleteBN = $pdo->prepare("DELETE FROM benhNhan WHERE id = ?");
    $stmtDeleteBN->execute([$patientId]);

    // 4. Xóa tài khoản liên quan trong bảng taiKhoan (nếu có)
    if (!empty($taiKhoanId)) {
        $stmtDeleteTK = $pdo->prepare("DELETE FROM taiKhoan WHERE id = ?");
        $stmtDeleteTK->execute([$taiKhoanId]);
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa vĩnh viễn bệnh nhân và toàn bộ lịch khám liên quan thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi khi xóa dữ liệu: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}