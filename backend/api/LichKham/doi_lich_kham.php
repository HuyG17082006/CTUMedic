<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $appointmentId = (int) ($data['appointmentId'] ?? 0);
    $newDate = trim($data['ngayKham'] ?? '');
    $newTime = trim($data['gioKham'] ?? '');

    if ($role !== 'patient') {
        throw new Exception('Bạn không có quyền đổi lịch khám.');
    }
    
    if (!$appointmentId || !$newDate || !$newTime) {
        throw new Exception('Vui lòng cung cấp đầy đủ thông tin.');
    }

    $newTime = strlen($newTime) === 5 ? "{$newTime}:00" : $newTime;

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT id
        FROM benhNhan
        WHERE id = ?
    ");
    $stmt->execute([$userId]);

    $patientId = $stmt->fetchColumn();

    if (!$patientId) {
        throw new Exception('Không tìm thấy thông tin bệnh nhân.');
    }

    $stmt = $pdo->prepare("
        SELECT id, bacSiId, benhNhanId, ngayKham, gioKham, liDoKham, trangThai
        FROM lichKham
        WHERE id = ?
          AND benhNhanId = ?
        FOR UPDATE
    ");
    $stmt->execute([$appointmentId, $userId]);

    $oldAppointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$oldAppointment) {
        throw new Exception('Không tìm thấy lịch khám.');
    }

    if ($oldAppointment['trangThai'] !== 'CONFIRMED') {
        throw new Exception('Lịch khám này không thể thay đổi.');
    }

    $stmt = $pdo->prepare(
        "SELECT TIMESTAMP(?, ?) <= NOW()"
    );
    $stmt->execute([$newDate, $newTime]);

    if ($stmt->fetchColumn()) {
        throw new Exception('Không thể chọn khung giờ đã qua.');
    }

    $stmt = $pdo->prepare("
        SELECT 1
        FROM lichKham
        WHERE bacSiId = ?
          AND ngayKham = ?
          AND gioKham = ?
          AND trangThai = 'CONFIRMED'
          AND id <> ?
        FOR UPDATE
    ");
    
    $stmt->execute([
        $oldAppointment['bacSiId'],
        $newDate,
        $newTime,
        $appointmentId
    ]);

    if ($stmt->fetch()) {
        throw new Exception('Khung giờ này đã có bệnh nhân đặt.');
    }

    $stmt = $pdo->prepare("
        UPDATE lichKham
        SET
            trangThai = 'CANCELLED',
            cancelledBy = 'PATIENT',
            cancelledAt = NOW(),
            cancellationType = 'RESCHEDULE',
            cancellationReason = 'Bệnh nhân đổi lịch khám'
        WHERE id = ?
          AND benhNhanId = ?
          AND trangThai = 'CONFIRMED'
    ");
    $stmt->execute([$appointmentId, $patientId]);

    if (!$stmt->rowCount()) {
        throw new Exception('Không thể cập nhật lịch khám cũ.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO lichKham (
            bacSiId,
            benhNhanId,
            ngayKham,
            gioKham,
            liDoKham,
            trangThai,
            rescheduledFromId
        )
        VALUES (?, ?, ?, ?, ?, 'CONFIRMED', ?)
    ");

    $stmt->execute([
        $oldAppointment['bacSiId'],
        $patientId,
        $newDate,
        $newTime,
        $oldAppointment['liDoKham'],
        $appointmentId
    ]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Đổi lịch khám thành công.',
        'data' => [
            'oldAppointmentId' => $appointmentId,
            'newAppointmentId' => $pdo->lastInsertId(),
            'ngayKham' => $newDate,
            'gioKham' => $newTime
        ]
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'status' => 'failed',
        'message' => $e->getMessage()
    ]);
}
