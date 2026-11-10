
<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

try {
    $appointmentId = (int) ($_GET['id'] ?? 0);

    if ($appointmentId <= 0) {
        throw new Exception('ID lịch khám không hợp lệ');
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

    if (!$appointment) 
        throw new Exception('Không tìm thấy lịch khám');

    echo json_encode([
        'success' => true,
        'data' => $appointment
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
