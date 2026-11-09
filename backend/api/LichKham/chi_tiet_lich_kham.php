
<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

try {
    $appointmentId = (int) ($_GET['id'] ?? 0);

    if (!$appointmentId || $appointmentId <= 0) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => 'ID lịch khám không hợp lệ'
        ]);

        exit;
    }

    $sql = "
        SELECT
            lk.id,
            lk.ngayKham,
            lk.gioKham,
            lk.liDoKham,
            lk.trangThai,
            lk.createdAt,

            lk.cancelledBy,
            lk.cancelledAt,
            lk.cancellationType,
            lk.cancellationReason,

            lk.rescheduledFromId,

            bn.id AS benhNhanId,
            bn.hoTen AS tenBenhNhan,
            bn.ngaySinh,
            bn.email AS emailBenhNhan,
            bn.SDT,
            bn.CCCD,

            bs.id AS bacSiId,
            bs.hoTen AS tenBacSi,
            bs.email AS emailBacSi,
            bs.chuyenKhoa

        FROM lichKham lk

        LEFT JOIN benhNhan bn
            ON lk.benhNhanId = bn.id

        INNER JOIN bacSi bs
            ON lk.bacSiId = bs.id

        WHERE lk.id = :id

        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $appointmentId
    ]);

    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy lịch khám'
        ]);

        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => $appointment
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Lỗi hệ thống khi lấy chi tiết lịch khám'
    ], JSON_UNESCAPED_UNICODE);
}
