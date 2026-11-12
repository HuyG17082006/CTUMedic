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
    http_response_code(405);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Hành động không được phép'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$loginIdentifier = trim($_POST['loginIdentifier'] ?? '');
$passwordHash = trim($_POST['password'] ?? '');

if ($loginIdentifier === '' || $passwordHash === '') {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Vui lòng nhập đầy đủ tên đăng nhập/CCCD và mật khẩu!'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$stmt = $pdo->prepare("
    SELECT tk.id AS taiKhoanId, tk.username, tk.role, tk.isActive
    FROM taiKhoan tk
    LEFT JOIN benhNhan bn
        ON bn.taiKhoanId = tk.id
    WHERE LOWER(tk.passwordHash) = LOWER(?)
        AND (
            tk.username = ?
            OR bn.CCCD = ?
        )
    LIMIT 1
");

$stmt->execute([
    $passwordHash,
    $loginIdentifier,
    $loginIdentifier
]);

$account = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$account) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Sai tên đăng nhập, CCCD hoặc mật khẩu!'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

if (isset($account['isActive']) && (int)$account['isActive'] === 0) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Tài khoản của bạn đã bị khóa!'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$roleLower = strtolower(trim($account['role']));
if ($roleLower === 'admin') {
    $roleLower = 'manager';
}

// Reset và thiết lập Session chuẩn cho vai trò hiện tại
$_SESSION['taiKhoanId'] = $account['taiKhoanId'];
$_SESSION['role'] = $roleLower;
$_SESSION['userId'] = null;
$_SESSION['hoTen'] = $account['username'];

$user = null;
if ($roleLower === 'patient') {
    $stmtUser = $pdo->prepare('SELECT * FROM benhNhan WHERE taiKhoanId = ? LIMIT 1');
    $stmtUser->execute([$account['taiKhoanId']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC) ?: $account;
} elseif ($roleLower === 'doctor') {
    $stmtUser = $pdo->prepare('SELECT * FROM bacSi WHERE taiKhoanId = ? LIMIT 1');
    $stmtUser->execute([$account['taiKhoanId']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC) ?: $account;
} elseif ($roleLower === 'manager') {
    $user = $account;
    $_SESSION['userId'] = $account['taiKhoanId'];
}

if (is_array($user) && isset($user['id']) && $roleLower !== 'manager') {
    $_SESSION['userId'] = $user['id'];
    $_SESSION['hoTen'] = $user['hoTen'] ?? $account['username'];
}

echo json_encode([
    'status' => 'success',
    'role' => $roleLower,
    'redirect' => "/dashboard/CTUMedic/index.php?page={$roleLower}",
    'user' => $user
], JSON_UNESCAPED_UNICODE);
exit();