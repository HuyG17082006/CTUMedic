<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

// Xác định bác sĩ
if ($role === 'doctor') {
    // userId là ID tài khoản (UUID), cần lấy ID bác sĩ tương ứng
    $stmt = $pdo->prepare("
        SELECT id
        FROM bacSi
        WHERE taiKhoanId = ?
    ");
    $stmt->execute([$_SESSION['userId'] ?? '']);

    $doctorId = (int) $stmt->fetchColumn();
} else {
    // Bệnh nhân hoặc manager truyền doctorId qua query string
    $doctorId = (int) ($_GET['doctorId'] ?? 0);
}

$date = $_GET['date'] ?? '';

if ($doctorId <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);

    echo json_encode([
        'status' => 'failed',
        'message' => 'Bác sĩ hoặc ngày khám không hợp lệ.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 1. Lấy lịch khám của bác sĩ trong ngày
$stmt = $pdo->prepare("
    SELECT
        id,
        bacSiId,
        ngayKham,
        gioKham,
        trangThai,
        cancelledBy
    FROM lichKham
    WHERE bacSiId = ?
      AND ngayKham = ?
      AND NOT (
            trangThai = 'CANCELLED'
            AND cancelledBy = 'PATIENT'
          )
    ORDER BY gioKham ASC
");

$stmt->execute([$doctorId, $date]);
$appointmentList = $stmt->fetchAll();


// 2. Lấy lịch nghỉ cá nhân của bác sĩ và lịch nghỉ toàn hệ thống
$stmt = $pdo->prepare("
    SELECT buoi
    FROM lichNghi
    WHERE ngayNghi = ?
      AND (
            bacSiId = ?
            OR bacSiId IS NULL
          )
");

$stmt->execute([$date, $doctorId]);

$leaveSessions = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Tổng hợp trạng thái nghỉ
$leave = null;

if (in_array('FULL_DAY', $leaveSessions, true)) {
    $leave = 'FULL_DAY';
} elseif (
    in_array('MORNING', $leaveSessions, true)
    && in_array('AFTERNOON', $leaveSessions, true)
) {
    $leave = 'FULL_DAY';
} elseif (in_array('MORNING', $leaveSessions, true)) {
    $leave = 'MORNING';
} elseif (in_array('AFTERNOON', $leaveSessions, true)) {
    $leave = 'AFTERNOON';
}

// 3. Trả kết quả
echo json_encode([
    'status' => 'success',
    'list' => $appointmentList,
    'leave' => $leave
], JSON_UNESCAPED_UNICODE);