<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

try {

    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $doctorId = (int) ($data['doctorId'] ?? 0);
    $date = trim($data['date'] ?? '');
    $time = trim($data['time'] ?? '');
    $reason = trim($data['reason'] ?? '');

    if (
        !$doctorId ||
        !$date ||
        !$time
    ) {
        throw new Exception('Thiếu thông tin đặt lịch.');
    }

    $taiKhoanId = $_SESSION['taiKhoanId'] ?? null;

    if (!$taiKhoanId) {
        throw new Exception('Vui lòng đăng nhập.');
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM benhNhan
        WHERE taiKhoanId = ?
        LIMIT 1
    ");

    $stmt->execute([
        $taiKhoanId
    ]);

    $benhNhan = $stmt->fetch();

    if (!$benhNhan) {
        throw new Exception('Không tìm thấy thông tin bệnh nhân.');
    }

    $stmt = $pdo->prepare("
        SELECT 1
        FROM lichKham
        WHERE benhNhanId = ? AND ngayKham = ? AND gioKham = ?
        LIMIT 1
    ");

    $stmt->execute([$benhNhan['id'], $date, $time]);

    $isBookingTimeDuplicate = $stmt->fetch();

    if ($isBookingTimeDuplicate) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Bạn đã có lịch khám vào khung giờ này.'
        ]);
        exit();
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM bacSi
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([
        $doctorId
    ]);

    $bacSi = $stmt->fetch();

    if (!$bacSi) {
        throw new Exception('Không tìm thấy thông tin bác sĩ.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO lichKham (
            bacSiId,
            benhNhanId,
            ngayKham,
            gioKham,
            liDoKham,
            trangThai
        )
        VALUES (
            ?, ?, ?, ?, ?, 'CONFIRMED'
        )
    ");

    $stmt->execute([
        $doctorId,
        $benhNhan['id'],
        $date,
        $time,
        $reason
    ]);

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
