<?php

require_once __DIR__ . '/../../middleware/patient.php';
require_once __DIR__ . '/../../database.php';
require_once __DIR__ . '/../../helpers/appointment_schedule.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $appointmentId = (int) ($data['appointmentId'] ?? 0);
    $slot = normalizeAppointmentSlot(
        $data['ngayKham'] ?? null,
        $data['gioKham'] ?? null
    );
    $newDate = $slot['date'];
    $newTime = $slot['time'];

    if (!$appointmentId || !$newDate || !$newTime) {
        throw new Exception('Vui lòng cung cấp đầy đủ thông tin.');
    }

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

    $stmt = $pdo->prepare("
        SELECT 1
        FROM bacSi bs
        INNER JOIN taiKhoan tk ON tk.id = bs.taiKhoanId
        WHERE bs.id = ?
          AND tk.isActive = 1
        LIMIT 1
    ");
    $stmt->execute([$oldAppointment['bacSiId']]);

    if (!$stmt->fetchColumn()) {
        throw new Exception('Bác sĩ hiện không nhận đặt hoặc đổi lịch.');
    }

    $stmt = $pdo->prepare("
        SELECT 1
        FROM lichKham
        WHERE benhNhanId = ?
          AND ngayKham = ?
          AND gioKham = ?
          AND trangThai IN ('CONFIRMED', 'COMPLETED')
          AND id <> ?
        LIMIT 1
        FOR UPDATE
    ");
    $stmt->execute([
        $patientId,
        $newDate,
        $newTime,
        $appointmentId
    ]);

    if ($stmt->fetchColumn()) {
        throw new Exception('Bạn đã có lịch khám vào khung giờ này.');
    }

    $stmt = $pdo->prepare("
        SELECT 1
        FROM lichKham
        WHERE bacSiId = ?
          AND ngayKham = ?
          AND gioKham = ?
          AND (
                trangThai IN ('CONFIRMED', 'COMPLETED', 'NO_SHOW')
                OR (
                    trangThai = 'CANCELLED'
                    AND cancelledBy = 'DOCTOR'
                )
              )
          AND id <> ?
        FOR UPDATE
    ");
    
    $stmt->execute([
        $oldAppointment['bacSiId'],
        $newDate,
        $newTime,
        $appointmentId
    ]);

    if ($stmt->fetchColumn()) {
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

    $newAppointmentId = (int) $pdo->lastInsertId();
    if ($newAppointmentId <= 0) {
        throw new RuntimeException('Không thể xác định mã lịch khám mới.');
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Đổi lịch khám thành công.',
        'data' => [
            'oldAppointmentId' => $appointmentId,
            'newAppointmentId' => $newAppointmentId,
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
