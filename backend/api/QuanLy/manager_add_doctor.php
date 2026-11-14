<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Phương thức không được phép.'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// Kiểm tra quyền quản lý
if (strtolower($_SESSION['role'] ?? '') !== 'manager') {
    http_response_code(403);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Bạn không có quyền thực hiện thao tác này.'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        $data = $_POST;
    }

    $username   = trim($data['username'] ?? '');
    $password   = trim($data['password'] ?? '');
    $hoTen      = trim($data['hoTen'] ?? '');
    $email      = trim($data['email'] ?? '');
    $chuyenKhoa = trim($data['chuyenKhoa'] ?? '');

    if ($username === '' || $password === '' || $hoTen === '' || $email === '' || $chuyenKhoa === '') {
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Vui lòng nhập đầy đủ tất cả các trường thông tin.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Đồng bộ chuẩn SHA-1: Nếu client đã băm SHA-1 (40 ký tự hex) thì dùng luôn, nếu chưa thì băm sha1()
    $passwordHash = preg_match('/^[a-f0-9]{40}$/i', $password)
        ? strtolower($password)
        : sha1($password);

    $pdo->beginTransaction();

    // Tạo UUID v4 cho taiKhoan.id
    $uuid = sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $stmtTk = $pdo->prepare("
        INSERT INTO taiKhoan (id, username, passwordHash, isActive, role)
        VALUES (?, ?, ?, 1, 'doctor')
    ");
    $stmtTk->execute([$uuid, $username, $passwordHash]);

    $stmtBs = $pdo->prepare("
        INSERT INTO bacSi (taiKhoanId, hoTen, email, chuyenKhoa)
        VALUES (?, ?, ?, ?)
    ");
    $stmtBs->execute([$uuid, $hoTen, $email, $chuyenKhoa]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Thêm bác sĩ mới thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    if ($e->getCode() == 23000) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Tên đăng nhập hoặc Email đã tồn tại trên hệ thống!'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}