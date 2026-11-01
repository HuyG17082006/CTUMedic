<?php

require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$doctorId = trim($_GET['doctorId'] ?? '');
$date     = trim($_GET['date'] ?? '');

if (!$doctorId || !$date) {
    http_response_code(400);

    echo json_encode([
        'status' => false,
        'message' => 'Vui lòng cung cấp bác sĩ và ngày khám.'
    ]);

    exit;
}

$params = [$doctorId, $date];

$query = '
    SELECT
        lk.id,
        lk.bacSiId,
        lk.benhNhanId,
        lk.ngayKham,
        lk.gioKham,
        lk.trangThai

    FROM lichKham lk

    WHERE
        lk.bacSiId = ?
        AND lk.ngayKham = ?
        AND (
            lk.benhNhanId IS NULL
            OR lk.trangThai IN (
                "CONFIRMED",
                "COMPLETED",
                "CANCELLED"
            )
        )

    ORDER BY lk.gioKham
';

$stmt = $pdo->prepare($query);
$stmt->execute($params);

echo json_encode([
    'status' => true,
    'list' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);