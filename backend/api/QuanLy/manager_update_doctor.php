<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        $data = $_POST;
    }

    $id         = (int) ($data['id'] ?? 0);
    $hoTen      = trim($data['hoTen'] ?? '');
    $email      = trim($data['email'] ?? '');
    $chuyenKhoa = trim($data['chuyenKhoa'] ?? '');

    if ($id <= 0 || $hoTen === '' || $email === '' || $chuyenKhoa === '') {
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Vui lòng nhập đầy đủ họ tên, email và chuyên khoa.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $stmt = $pdo->prepare("
        UPDATE bacSi
        SET hoTen = ?,
            email = ?,
            chuyenKhoa = ?
        WHERE id = ?
    ");
    $stmt->execute([$hoTen, $email, $chuyenKhoa, $id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Cập nhật thông tin bác sĩ thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    http_response_code(400);
    if ($e->getCode() == 23000) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Email này đã được sử dụng bởi bác sĩ khác!'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}