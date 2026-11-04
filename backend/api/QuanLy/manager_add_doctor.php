<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

if (!isset($_SESSION['taiKhoanId']) || (strtolower($_SESSION['role'] ?? '') !== 'manager' && strtolower($_SESSION['role'] ?? '') !== 'admin')) {
    echo json_encode(['status' => 'failed', 'message' => 'Unauthorized: Không có quyền']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$username   = trim($input['username'] ?? '');
$password   = trim($input['password'] ?? '');
$hoTen      = trim($input['hoTen'] ?? '');
$chuyenKhoa = trim($input['chuyenKhoa'] ?? '');
$email      = trim($input['email'] ?? '');

if (empty($username) || empty($password) || empty($hoTen)) {
    echo json_encode(['status' => 'failed', 'message' => 'Vui lòng nhập đầy đủ Username, Mật khẩu và Họ tên bác sĩ']);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Kiểm tra username tồn tại
    $checkStmt = $pdo->prepare("SELECT id FROM taiKhoan WHERE username = ?");
    $checkStmt->execute([$username]);
    if ($checkStmt->fetch()) {
        $pdo->rollBack();
        echo json_encode(['status' => 'failed', 'message' => 'Tên đăng nhập đã tồn tại']);
        exit();
    }

    // 2. Tạo ID UUID/CHAR cho bảng taiKhoan
    $taiKhoanId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 3. Tạo tài khoản trong bảng taiKhoan
    $stmtAccount = $pdo->prepare("INSERT INTO taiKhoan (id, username, password, role, isActive) VALUES (?, ?, ?, 'bacSi', 1)");
    $stmtAccount->execute([$taiKhoanId, $username, $hashedPassword]);

    // 4. Tạo bác sĩ trong bảng bacSi
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