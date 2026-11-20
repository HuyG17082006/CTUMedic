<?php

require_once __DIR__ . '/../../middleware/auth.php';
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
    $sql = 'SELECT id, hoTen FROM bacSi';
    $params = [];

    if ($specialty !== '') {
        $sql .= ' WHERE chuyenKhoa = ?';
        $params[] = $specialty;
    }

    $sql .= ' ORDER BY hoTen ASC, id ASC';

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
        'message' => 'Không thể tải danh sách bác sĩ.'
    ], JSON_UNESCAPED_UNICODE);
}
