<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

try {

    $data = json_decode(file_get_contents("php://input"), true);

    $appointmentId = (int)($data['appointmentId'] ?? 0);
    $reason = trim($data['cancellationReason'] ?? '');

    $where = match ($role) {
        'patient' => 'AND lk.benhNhanId = ?',
        'doctor' => 'AND lk.bacSiId = ?',
        'manager' => '',
    };

    $params = [
        strtoupper($role),
        $reason,
        $appointmentId
    ];

    if ($role !== 'manager') {
        $params[] = $userId;
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
