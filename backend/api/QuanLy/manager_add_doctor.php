<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

// 1. Kiểm tra quyền
if (!isset($_SESSION['taiKhoanId']) || (strtoupper($_SESSION['role'] ?? '') !== 'MANAGER' && strtoupper($_SESSION['role'] ?? '') !== 'ADMIN')) {
    echo json_encode(['status' => 'failed', 'message' => 'Unauthorized: Không có quyền truy cập']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$username       = trim($input['username'] ?? '');
$passwordHash   = trim($input['password'] ?? ''); // Chuỗi hash SHA-1 từ Client
$hoTen          = trim($input['hoTen'] ?? '');
$chuyenKhoa     = trim($input['chuyenKhoa'] ?? '');
$email          = trim($input['email'] ?? '');

if (empty($username) || empty($passwordHash) || empty($hoTen)) {
    echo json_encode(['status' => 'failed', 'message' => 'Vui lòng nhập đầy đủ Tên đăng nhập, Mật khẩu và Họ tên bác sĩ']);
    exit();
}

try {
    $pdo->beginTransaction();

    // 2. Kiểm tra trùng username
    $checkStmt = $pdo->prepare("SELECT id FROM taiKhoan WHERE username = ?");
    $checkStmt->execute([$username]);
    if ($checkStmt->fetch()) {
        $pdo->rollBack();
        echo json_encode(['status' => 'failed', 'message' => 'Tên đăng nhập đã tồn tại trong hệ thống']);
        exit();
    }

    // 3. Tạo ID char(36) UUID
    $taiKhoanId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    // 4. Insert vào taiKhoan (Đúng tên cột passwordHash và role = 'DOCTOR')
    $stmtAccount = $pdo->prepare("INSERT INTO taiKhoan (id, username, passwordHash, role, isActive) VALUES (?, ?, ?, 'DOCTOR', 1)");
    $stmtAccount->execute([$taiKhoanId, $username, $passwordHash]);

    // 5. Insert vào bacSi (Tái sử dụng taiKhoanId vừa tạo)
    $stmtDoctor = $pdo->prepare("INSERT INTO bacSi (taiKhoanId, hoTen, chuyenKhoa, email) VALUES (?, ?, ?, ?)");
    $stmtDoctor->execute([$taiKhoanId, $hoTen, $chuyenKhoa, $email]);

    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Thêm bác sĩ thành công']);
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['status' => 'failed', 'message' => 'Lỗi DB: ' . $e->getMessage()]);
    exit();
}
?>