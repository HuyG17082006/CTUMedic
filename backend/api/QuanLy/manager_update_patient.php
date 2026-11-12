<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Đọc dữ liệu từ JSON body (hỗ trợ cả form-data nếu có)
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    if (!is_array($data)) {
        $data = $_POST;
    }

    $id       = (int) ($data['id'] ?? 0);
    $hoTen    = trim($data['hoTen'] ?? '');
    $ngaySinh = trim($data['ngaySinh'] ?? '');
    $SDT      = trim($data['SDT'] ?? '');
    $email    = trim($data['email'] ?? '');
    $CCCD     = trim($data['CCCD'] ?? '');

    if ($id <= 0 || $hoTen === '') {
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Thiếu ID hoặc họ tên bệnh nhân.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Chuyển chuỗi rỗng thành NULL để không vi phạm ràng buộc UNIQUE trong MySQL
    $ngaySinhVal = $ngaySinh !== '' ? $ngaySinh : null;
    $sdtVal      = $SDT !== '' ? $SDT : null;
    $emailVal    = $email !== '' ? $email : null;
    $cccdVal     = $CCCD !== '' ? $CCCD : null;

    $sql = "
        UPDATE benhNhan
        SET hoTen = ?,
            ngaySinh = ?,
            SDT = ?,
            email = ?,
            CCCD = ?
        WHERE id = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hoTen, $ngaySinhVal, $sdtVal, $emailVal, $cccdVal, $id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Cập nhật thông tin bệnh nhân thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    http_response_code(500);
    // Bắt lỗi trùng SĐT, Email hoặc CCCD (mã lỗi 23000)
    if ($e->getCode() == 23000) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Số điện thoại, Email hoặc CCCD đã tồn tại trên hệ thống!'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}