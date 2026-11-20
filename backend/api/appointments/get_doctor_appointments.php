<?php

require_once __DIR__ . '/../../middleware/patient.php';
require_once __DIR__ . '/../../database.php';

$doctorId = $_GET['doctorId'];
$date = $_GET['date'] ?? '';

if (!$doctorId) {
    echo json_encode([
        'status' => 'success',
        'list' => []
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        gioKham,
        cancelledBy
    FROM lichKham
    WHERE bacSiId = ?
      AND ngayKham = ?
      AND (
            trangThai IN ('CONFIRMED', 'COMPLETED', 'NO_SHOW')
            OR (
                trangThai = 'CANCELLED'
                AND cancelledBy = 'DOCTOR'
            )
          )
    ORDER BY gioKham ASC
");

$stmt->execute([$doctorId, $date]);

echo json_encode([
    'status' => 'success',
    'list' => $stmt->fetchAll()
], JSON_UNESCAPED_UNICODE);