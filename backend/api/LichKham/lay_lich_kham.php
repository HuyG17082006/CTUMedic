<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

$pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));

$specialty = $_GET['specialty'] ?? '';
$doctorId = $_GET['doctorId'] ?? '';
$date = $_GET['date'] ?? '';
$status = $_GET['status'] ?? '';
$tableType = $_GET['tableType'] ?? '';

$params = [];
$whereConditions = ' WHERE 1 = 1';

// =====================================================
// 1. XỬ LÝ TIMESLOTS
// =====================================================

if ($tableType === 'timeSlots') {

    // Bắt buộc phải có bác sĩ và ngày khám
    if ($doctorId === '' || $date === '') {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng cung cấp bác sĩ và ngày khám.'
        ]);

        exit;
    }

    $whereConditions .= ' AND lk.bacSiId = ?';
    $params[] = $doctorId;

    $whereConditions .= ' AND lk.ngayKham = ?';
    $params[] = $date;

    // Chỉ lấy lịch thực sự chiếm khung giờ
    $whereConditions .= "
        AND (
            lk.benhNhanId IS NULL
            OR lk.trangThai IN ('CONFIRMED', 'COMPLETED')
        )
    ";

    // Chuyên khoa là điều kiện bổ sung nếu được truyền
    if ($specialty !== '') {
        $whereConditions .= ' AND bs.chuyenKhoa = ?';
        $params[] = $specialty;
    }

    $query = '
        SELECT
            lk.id,
            lk.bacSiId,
            lk.benhNhanId,
            lk.ngayKham,
            lk.gioKham,
            lk.trangThai

        FROM lichKham lk
        JOIN bacSi bs ON bs.id = lk.bacSiId

        ' . $whereConditions . '

        ORDER BY lk.gioKham ASC
    ';

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'list' => $list
    ]);

    exit;
}

// =====================================================
// 2. XỬ LÝ LỊCH CÁ NHÂN
// =====================================================

if ($role === 'patient') {

    $whereConditions .= ' AND lk.benhNhanId = ?';
    $params[] = $userId;
} elseif ($role === 'doctor') {

    $whereConditions .= ' AND lk.bacSiId = ?';
    $params[] = $userId;
}

// =====================================================
// 3. XỬ LÝ LOẠI LỊCH
// =====================================================

if ($tableType === 'history') {
    $whereConditions .= "
        AND lk.trangThai IN ('COMPLETED', 'CANCELLED')
    ";
} elseif ($tableType === 'appointments') {
    $whereConditions .= "
        AND lk.trangThai = 'CONFIRMED'
    ";
}

// =====================================================
// 4. XỬ LÝ CHUYÊN KHOA
// =====================================================

if ($specialty !== '' && $role !== 'doctor') {

    $whereConditions .= ' AND bs.chuyenKhoa = ?';
    $params[] = $specialty;
}

// =====================================================
// 5. XỬ LÝ BÁC SĨ
// =====================================================

if ($doctorId !== '' && $role !== 'doctor') {

    $whereConditions .= ' AND bs.id = ?';
    $params[] = $doctorId;
}

// =====================================================
// 6. XỬ LÝ NGÀY KHÁM
// =====================================================

if ($date !== '') {

    $whereConditions .= ' AND lk.ngayKham = ?';
    $params[] = $date;
}

// =====================================================
// 7. XỬ LÝ TRẠNG THÁI
// =====================================================

if ($status !== '') {

    $whereConditions .= ' AND lk.trangThai = ?';
    $params[] = $status;
}

// =====================================================
// 8. PHÂN TRANG
// =====================================================

$limit = 10;
$offset = ($pageNumber - 1) * $limit;

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

    JOIN bacSi bs
        ON bs.id = lk.bacSiId

    LEFT JOIN benhNhan bn
        ON bn.id = lk.benhNhanId

    ' . $whereConditions . '

    ORDER BY lk.ngayKham DESC, lk.gioKham DESC

    LIMIT ' . $limit . ' OFFSET ' . $offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRecords = !empty($list)
    ? (int) $list[0]['totalRecords']
    : 0;

$totalPages = (int) ceil($totalRecords / $limit);

echo json_encode([
    'success' => true,
    'list' => $list,
    'pagination' => [
        'pageNumber' => $pageNumber,
        'limit' => $limit,
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages
    ]
]);
