
<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

if (!in_array($role, ['doctor', 'manager'], true)) {
    http_response_code(403);

    echo json_encode([
        'status' => 'failed',
        'message' => 'Bạn không có quyền xem lịch nghỉ.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$month = filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT);
$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);

if (
    !$month || $month < 1 || $month > 12 ||
    !$year || $year < 2000 || $year > 2100
) {
    http_response_code(400);

    echo json_encode([
        'status' => 'failed',
        'message' => 'Tháng hoặc năm không hợp lệ.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
var_dump(1);
$startDate = sprintf('%04d-%02d-01', $year, $month);
$endDate = date('Y-m-t', strtotime($startDate));

if ($role === 'doctor') {

    // Lấy ID bác sĩ từ tài khoản đăng nhập
    $stmt = $pdo->prepare("
        SELECT id
        FROM bacSi
        WHERE taiKhoanId = ?
    ");

    $stmt->execute([$_SESSION['userId'] ?? '']);

    $doctorId = (int) $stmt->fetchColumn();

    if ($doctorId <= 0) {
        http_response_code(404);

        echo json_encode([
            'status' => 'failed',
            'message' => 'Không tìm thấy thông tin bác sĩ.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // Bác sĩ chỉ xem lịch nghỉ cá nhân và toàn hệ thống
    $stmt = $pdo->prepare("
        SELECT
            ln.id,
            ln.bacSiId,
            bs.hoTen,
            ln.ngayNghi,
            ln.buoi,
            ln.lyDo,
            ln.nguoiTao,
            ln.createdAt
        FROM lichNghi ln
        LEFT JOIN bacSi bs ON ln.bacSiId = bs.id
        WHERE ln.ngayNghi BETWEEN ? AND ?
          AND (
                ln.bacSiId = ?
                OR ln.bacSiId IS NULL
              )
        ORDER BY ln.ngayNghi ASC
    ");

    $stmt->execute([$startDate, $endDate, $doctorId]);

} else {

    // Manager xem lịch nghỉ của tất cả bác sĩ và toàn hệ thống
    $stmt = $pdo->prepare("
        SELECT
            ln.id,
            ln.bacSiId,
            bs.hoTen,
            ln.ngayNghi,
            ln.buoi,
            ln.lyDo,
            ln.nguoiTao,
            ln.createdAt
        FROM lichNghi ln
        LEFT JOIN bacSi bs ON ln.bacSiId = bs.id
        WHERE ln.ngayNghi BETWEEN ? AND ?
        ORDER BY ln.ngayNghi ASC
    ");

    $stmt->execute([$startDate, $endDate]);
}

echo json_encode([
    'status' => 'success',
    'list' => $stmt->fetchAll()
], JSON_UNESCAPED_UNICODE);

