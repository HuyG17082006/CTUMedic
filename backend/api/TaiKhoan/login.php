<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Hành động không được phép'
    ]);
    exit();
}

$loginIdentifier = trim($_POST['loginIdentifier'] ?? '');
$passwordHash = trim($_POST['password'] ?? '');

// Kiểm tra tài khoản
$stmt = $pdo->prepare("
        SELECT tk.id AS taiKhoanId, tk.username, tk.role
        FROM taiKhoan tk
        LEFT JOIN benhNhan bn
            ON bn.taiKhoanId = tk.id
        WHERE tk.passwordHash = ?
            AND (
                tk.username = ?
                OR bn.CCCD = ?
            )
        LIMIT 1");

$stmt->execute([
    $passwordHash,
    $loginIdentifier,
    $loginIdentifier
]);

$account = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$account) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Sai tên đăng nhập, CCCD hoặc mật khẩu!',
    ]);
    exit();
}

$roleLower = strtolower($account['role']);

// Lưu thông tin vào Session chuẩn xác
$_SESSION['taiKhoanId'] = $account['taiKhoanId'];
$_SESSION['role'] = $roleLower;

// Lấy thông tin chi tiết dựa theo từng Role
$user = null;
if ($roleLower === 'patient') {
    $stmtUser = $pdo->prepare('SELECT * FROM benhNhan WHERE taiKhoanId = ?;');
    $stmtUser->execute([$account['taiKhoanId']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
} elseif ($roleLower === 'doctor') {
    $stmtUser = $pdo->prepare('SELECT * FROM bacSi WHERE taiKhoanId = ?;');
    $stmtUser->execute([$account['taiKhoanId']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
} elseif ($roleLower === 'manager') {
    $user = $account;
}

$_SESSION['userId'] = 'manager';

if ($user && isset($user['id']) && $roleLower !== 'manager') {
    $_SESSION['userId'] = $user['id'];
    $_SESSION['hoTen'] = $user['hoTen'];
}

echo json_encode([
    'status' => 'success',
    'role' => $roleLower,
    'user' => $user,
]);

exit();
