<?php

require_once __DIR__ . '/../../middleware/patient.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Phương thức không được phép.'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $specialty = trim($_GET['specialty'] ?? '');
    $sql = "
        SELECT bs.id, bs.hoTen
        FROM bacSi bs
        INNER JOIN taiKhoan tk ON tk.id = bs.taiKhoanId
        WHERE tk.isActive = 1
    ";
    $params = [];

    if ($specialty !== '') {
        $sql .= ' AND bs.chuyenKhoa = ?';
        $params[] = $specialty;
    }

    $sql .= ' ORDER BY bs.hoTen ASC, bs.id ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode([
        'status' => 'success',
        'list' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Không thể tải danh sách bác sĩ đang hoạt động.'
    ], JSON_UNESCAPED_UNICODE);
}
