<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

try {

    $appointmentId = (int) ($_POST['appointmentId'] ?? 0);
    $role = $_SESSION['role'];

    if ($role !== 'doctor' && $role !== 'manager')
        throw new Exception('Bạn không có quyền hoàn thành lịch khám.');
    
    $where = '';
    
    if ($role === 'doctor')
        $where = 'AND lk.bacSiId = (
            SELECT id FROM bacSi WHERE taiKhoanId = ?
        )';

    $params = [$appointmentId];

    if ($role !== 'manager') {
        $params[] = $_SESSION['taiKhoanId'];
    }

    $stmt = $pdo->prepare("
        UPDATE lichKham lk
        SET trangThai = 'COMPLETED'
        WHERE lk.id = ?
          AND lk.trangThai = 'CONFIRMED'
          $where
    ");

    $stmt->execute($params);

    if (!$stmt->rowCount()) {
        throw new Exception('Không thể hoàn thành lịch khám.');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã hoàn thành lịch khám.'
    ]);

} catch (Throwable $e) {

    echo json_encode([
        'status' => 'failed',
        'message' => $e->getMessage()
    ]);

}