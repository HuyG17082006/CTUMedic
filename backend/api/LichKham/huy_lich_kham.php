<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

try {

    $appointmentId = (int)($_POST['appointmentId'] ?? 0);
    $reason = trim($_POST['cancellationReason'] ?? '');

    $where = match ($role) {
        'patient' => 'AND lk.benhNhanId = (
            SELECT id FROM benhNhan WHERE taiKhoanId = ?
        )',
        'doctor' => 'AND lk.bacSiId = (
            SELECT id FROM bacSi WHERE taiKhoanId = ?
        )',
        'manager' => '',
    };

    $params = [
        strtoupper($role),
        $reason,
        $appointmentId
    ];

    if ($role !== 'manager') {
        $params[] = $_SESSION['taiKhoanId'];
    }

    $stmt = $pdo->prepare("
        UPDATE lichKham lk
        SET
            trangThai = 'CANCELLED',
            cancelledBy = ?,
            cancelledAt = NOW(),
            cancellationType = 'CANCELLATION',
            cancellationReason = ?
        WHERE lk.id = ?
          AND lk.trangThai = 'CONFIRMED'
          $where
    ");

    $stmt->execute($params);

    if (!$stmt->rowCount()) {
        throw new Exception('Không thể hủy lịch khám.');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Hủy lịch khám thành công.'
    ]);
} catch (Throwable $e) {

    echo json_encode([
        'status' => 'failed',
        'message' => $e->getMessage()
    ]);
}
