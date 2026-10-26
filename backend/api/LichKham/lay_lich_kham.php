<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

$pageNumber = $_GET['pageNumber'] ?? 1;
$specialty = $_GET['specialty'] ?? '';
$doctorId = $_GET['doctorId'] ?? '';
$date = $_GET['date'] ?? '';
$status = $_GET['status'] ?? '';
$tableType = $_GET['tableType'] ?? '';
$whereConditions = '';
$params = [];

$whereConditions = ' WHERE 1 = 1';

// Xử lý lịch cá nhân
if ($role === 'patient') {
    $whereConditions .= ' AND bn.taiKhoanId = ?';
    $params[] = $userId;
} elseif ($role === 'doctor') {
    $whereConditions .= ' AND bs.taiKhoanId = ?';
    $params[] = $userId;
}

// Xử lý loại lịch
if ($tableType === 'history') {
    $whereConditions .= " AND lk.trangThai IN ('COMPLETED', 'CANCELLED', 'REJECTED')";
} else {
    $whereConditions .= " AND lk.trangThai IN ('PENDING', 'CONFIRMED')";
}

// Xử lý chuyên khoa
if ($specialty !== '' && $role !== 'doctor') {
    $whereConditions .= ' AND bs.chuyenKhoa = ?';
    $params[] = $specialty;
}

// Xử lý bác sĩ
if ($doctorId !== '' && $role !== 'doctor') {
    $whereConditions .= ' AND bs.id = ?';
    $params[] = $doctorId;
}

// Xử lý ngày khám
if ($date !== '') {
    $whereConditions .= ' AND lk.ngayKham = ?';
    $params[] = $date;
}

// Xử lý trạng thái
if ($status !== '') {
    $whereConditions .= ' AND lk.trangThai = ?';
    $params[] = $status;
}

// Phân trang
$offset = ($pageNumber - 1) * 10;

$query = '
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

        bn.Hoten AS hoTenBenhNhan,

        COUNT(*) OVER() AS totalRecords

    FROM lichKham lk
    JOIN bacSi bs ON bs.id = lk.bacSiId
    JOIN benhNhan bn ON bn.id = lk.benhNhanId
    '.$whereConditions.'
    ORDER BY lk.ngayKham DESC, lk.gioKham DESC
    LIMIT 10 OFFSET '.$offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRecords = 0;
$totalPages = 0;

if (!empty($list)) {
    $totalRecords = (int) $list[0]['totalRecords'] || 0;
    $totalPages = (int) ceil($totalRecords / 10);
}

echo json_encode([
    'success' => true,
    'list' => $list,
    'pagination' => [
        'pageNumber' => $pageNumber || 1,
        'limit' => 10,
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages
    ]
])
?>