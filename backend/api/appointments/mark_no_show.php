
<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

try {

    $data = json_decode(file_get_contents('php://input'), true);

    $appointmentId = (int) ($data['appointmentId'] ?? 0);

    if ($role !== 'doctor' && $role !== 'manager')
        throw new Exception('Bạn không có quyền cập nhật lịch khám.');

    if ($appointmentId <= 0)
        throw new Exception('Mã lịch khám không hợp lệ.');

    $completionCutoff = (new DateTimeImmutable(
        'now',
        new DateTimeZone('Asia/Ho_Chi_Minh')
    ))->modify('-40 minutes')->format('Y-m-d H:i:s');

    $where = '';

    $params = [$appointmentId, $completionCutoff];

    if ($role === 'doctor') {
        $where = 'AND lk.bacSiId = ?';
        $params[] = $userId;
    }

    $stmt = $pdo->prepare("
        UPDATE lichKham lk
        SET trangThai = 'NO_SHOW'
        WHERE lk.id = ?
          AND lk.trangThai = 'CONFIRMED'
          AND DATE_ADD(TIMESTAMP(lk.ngayKham, lk.gioKham), INTERVAL 40 MINUTE) <= ?
          $where
    ");

    $stmt->execute($params);
    if (!$stmt->rowCount()) {
        throw new Exception('Chỉ có thể đánh dấu vắng mặt cho lịch đã xác nhận sau khi đã kết thúc ít nhất 40 phút.');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã cập nhật bệnh nhân không đến khám.'
    ]);
} catch (Throwable $e) {

    echo json_encode([
        'status' => 'failed',
        'message' => $e->getMessage()
    ]);
}
