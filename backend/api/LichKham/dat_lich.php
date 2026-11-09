<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

try {

    $doctorId = (int) ($_POST['doctorId'] ?? 0);
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    $stmt = $pdo->prepare("
        SELECT id
        FROM benhNhan
        WHERE taiKhoanId = ?
    ");

    $stmt->execute([$_SESSION['taiKhoanId']]);

    $patientId = $stmt->fetchColumn();

    if (!$patientId) {
        throw new Exception('Không tìm thấy thông tin bệnh nhân.');
    }

    /*
     * Kiểm tra bệnh nhân đã có lịch
     * tại đúng khung giờ này chưa
     */
    $stmt = $pdo->prepare("
        SELECT 1
        FROM lichKham
        WHERE benhNhanId = ?
          AND ngayKham = ?
          AND gioKham = ?
          AND trangThai IN ('CONFIRMED', 'COMPLETED')
        LIMIT 1
    ");

    $stmt->execute([
        $patientId,
        $date,
        $time
    ]);

    if ($stmt->fetchColumn()) {
        throw new Exception('Bạn đã có lịch khám vào khung giờ này.');
    }

    /*
     * Kiểm tra khung giờ của bác sĩ
     */
    $stmt = $pdo->prepare("
        SELECT 1
        FROM lichKham
        WHERE bacSiId = ?
          AND ngayKham = ?
          AND gioKham = ?
          AND (
                trangThai IN ('CONFIRMED', 'COMPLETED')
                OR (
                    trangThai = 'CANCELLED'
                    AND cancelledBy = 'DOCTOR'
                )
              )
        LIMIT 1
    ");

    $stmt->execute([
        $doctorId,
        $date,
        $time
    ]);

    if ($stmt->fetchColumn()) {
        throw new Exception('Khung giờ này đã có người đặt.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO lichKham (bacSiId, benhNhanId, ngayKham, gioKham, liDoKham, trangThai)
        SELECT id, ?, ?, ?, ?, 'CONFIRMED'
        FROM bacSi
        WHERE id = ?
    ");

    $stmt->execute([
        $patientId,
        $date,
        $time,
        $reason,
        $doctorId
    ]);

    if (!$stmt->rowCount()) {
        throw new Exception('Không tìm thấy bác sĩ.');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Đặt lịch thành công.'
    ]);
} catch (Throwable $e) {

    echo json_encode([
        'status' => 'failed',
        'message' => $e->getMessage()
    ]);
}
