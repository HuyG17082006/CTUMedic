<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));
$limit = 10;
$offset = ($pageNumber - 1) * $limit;

$specialty = trim($_GET['specialty'] ?? '');
$doctorId  = trim($_GET['doctorId'] ?? '');
$date      = trim($_GET['date'] ?? '');
$status    = strtoupper(trim($_GET['status'] ?? ''));
$tableType = trim($_GET['tableType'] ?? '');

$conditions = [];
$params = [];

if ($role === 'patient') {
    $conditions[] = 'lk.benhNhanId = ?';
    $params[] = $userId;
} elseif ($role === 'doctor') {
    $conditions[] = 'lk.bacSiId = ?';
    $params[] = $userId;
}

if ($tableType === 'history') {
    $conditions[] = "lk.trangThai IN ('COMPLETED', 'CANCELLED')";
} elseif ($tableType === 'appointments') {
    $conditions[] = "lk.trangThai = 'CONFIRMED'";
}

if ($specialty !== '' && $role !== 'doctor') {
    $conditions[] = 'bs.chuyenKhoa = ?';
    $params[] = $specialty;
}

if ($doctorId !== '' && $role !== 'doctor') {
    $conditions[] = 'bs.id = ?';
    $params[] = $doctorId;
}

if ($date !== '') {
    $conditions[] = 'lk.ngayKham = ?';
    $params[] = $date;
}

if ($status !== '') {
    $allowedStatuses = ['CONFIRMED', 'CANCELLED', 'COMPLETED'];

    if (in_array($status, $allowedStatuses, true)) {
        $conditions[] = 'lk.trangThai = ?';
        $params[] = $status;
    }
}

// Điều kiện lọc
$whereClause = $conditions
    ? 'WHERE ' . implode(' AND ', $conditions)
    : '';

// Sắp xếp theo loại bảng
$orderBy = $tableType === 'appointments'
    ? "
        ORDER BY
            lk.ngayKham ASC,
            lk.gioKham ASC,
            lk.id DESC
    "
    : "
        ORDER BY
            lk.trangThai DESC,
            lk.ngayKham DESC,
            lk.gioKham DESC,
            lk.id DESC
    ";

$countQuery = "
    SELECT COUNT(*)
    FROM lichKham lk
    INNER JOIN bacSi bs ON bs.id = lk.bacSiId
    LEFT JOIN benhNhan bn ON bn.id = lk.benhNhanId
    $whereClause
";

$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);

$totalRecords = (int) $countStmt->fetchColumn();

$query = "
    SELECT
        lk.id,
        lk.liDoKham,
        lk.ngayKham,
        lk.gioKham,
        lk.trangThai,
        lk.bacSiId,
        lk.benhNhanId,
        bs.hoTen AS tenBacSi,
        bs.chuyenKhoa,
        bn.hoTen AS tenBenhNhan

    FROM lichKham lk

    INNER JOIN bacSi bs
        ON bs.id = lk.bacSiId

    LEFT JOIN benhNhan bn
        ON bn.id = lk.benhNhanId

    $whereClause

    $orderBy

    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'status' => 'success',
    'list' => $list,
    'pagination' => [
        'pageNumber' => $pageNumber,
        'limit' => $limit,
        'totalRecords' => $totalRecords,
        'totalPages' => (int) ceil($totalRecords / $limit)
    ]
], JSON_UNESCAPED_UNICODE);
