<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));

$specialty = trim($_GET['specialty'] ?? '');
$doctorId  = trim($_GET['doctorId'] ?? '');
$date      = trim($_GET['date'] ?? '');
$status    = trim($_GET['status'] ?? '');
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

if ($tableType === 'history') 
    $conditions[] = "lk.trangThai IN ('COMPLETED', 'CANCELLED')";
elseif ($tableType === 'appointments') 
    $conditions[] = "lk.trangThai = 'CONFIRMED'";

if ($specialty && $role !== 'doctor') {
    $conditions[] = 'bs.chuyenKhoa = ?';
    $params[] = $specialty;
}

if ($doctorId && $role !== 'doctor') {
    $conditions[] = 'bs.id = ?';
    $params[] = $doctorId;
}

if ($date) {
    $conditions[] = 'lk.ngayKham = ?';
    $params[] = $date;
}

if ($status) {
    $conditions[] = 'lk.trangThai = ?';
    $params[] = $status;
}

$whereClause = '';

if ($conditions) {
    $whereClause = 'WHERE ' . implode(' AND ', $conditions);
}

$limit = 10;
$offset = ($pageNumber - 1) * $limit;

$query = "
    SELECT
        lk.id,
        lk.liDoKham,
        lk.ngayKham,
        lk.gioKham,
        lk.trangThai,
        lk.bacSiId,
        lk.benhNhanId,

        bs.hoTen AS hoTenBacSi,
        bs.chuyenKhoa,

        bn.hoTen AS hoTenBenhNhan,

        COUNT(*) OVER() AS totalRecords

    FROM lichKham lk

    JOIN bacSi bs
        ON bs.id = lk.bacSiId

    LEFT JOIN benhNhan bn
        ON bn.id = lk.benhNhanId

    $whereClause

    ORDER BY
        lk.ngayKham ASC,
        lk.gioKham ASC

    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRecords = $list
    ? (int) $list[0]['totalRecords']
    : 0;

echo json_encode([
    'status' => true,
    'list' => $list,
    'pagination' => [
        'pageNumber'   => $pageNumber,
        'limit'        => $limit,
        'totalRecords' => $totalRecords,
        'totalPages'   => (int) ceil($totalRecords / $limit)
    ]
]);