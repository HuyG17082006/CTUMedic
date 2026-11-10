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
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

$loginIdentifier = trim($_POST['loginIdentifier'] ?? '');
$rawPassword = $_POST['password'] ?? '';

if (empty($loginIdentifier) || empty($rawPassword)) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Vui lòng nhập tên đăng nhập/CCCD và mật khẩu!'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // 1. Tìm tài khoản theo username hoặc CCCD
    $stmt = $pdo->prepare("
        SELECT 
            tk.id AS taiKhoanId, 
            tk.username, 
            tk.passwordHash, 
            tk.role,
            IFNULL(tk.isActive, 1) AS isActive
        FROM taiKhoan tk
        LEFT JOIN benhNhan bn ON bn.taiKhoanId = tk.id
        WHERE tk.username = ? OR bn.CCCD = ?
        LIMIT 1
    ");

    $stmt->execute([$loginIdentifier, $loginIdentifier]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Sai tên đăng nhập, CCCD hoặc mật khẩu!'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 2. KIỂM TRA TRẠNG THÁI VÔ HIỆU HÓA (isActive = 0)
    if ((int)$account['isActive'] === 0) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên!'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 3. XÁC THỰC MẬT KHẨU (Hỗ trợ cả password_hash chuẩn PHP lẫn băm SHA1/Plaintext cũ)
    $isPasswordValid = false;
    if (password_verify($rawPassword, $account['passwordHash'])) {
        $isPasswordValid = true;
    } elseif ($account['passwordHash'] === sha1($rawPassword) || $account['passwordHash'] === $rawPassword) {
        $isPasswordValid = true;
    }

    if (!$isPasswordValid) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Sai tên đăng nhập, CCCD hoặc mật khẩu!'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 4. ĐĂNG NHẬP THÀNH CÔNG -> Lưu Session
    $roleLower = strtolower($account['role']);
    if ($roleLower === 'admin') {
        $roleLower = 'manager';
    }

    $_SESSION['taiKhoanId'] = $account['taiKhoanId'];
    $_SESSION['role'] = $roleLower;

    $user = null;
    if ($roleLower === 'patient') {
        $stmtUser = $pdo->prepare('SELECT * FROM benhNhan WHERE taiKhoanId = ? LIMIT 1');
        $stmtUser->execute([$account['taiKhoanId']]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    } elseif ($roleLower === 'doctor') {
        $stmtUser = $pdo->prepare('SELECT * FROM bacSi WHERE taiKhoanId = ? LIMIT 1');
        $stmtUser->execute([$account['taiKhoanId']]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    } elseif ($roleLower === 'manager') {
        $user = $account;
    }

    if ($user && isset($user['id']) && $roleLower !== 'manager') {
        $_SESSION['userId'] = $user['id'];
        $_SESSION['hoTen'] = $user['hoTen'] ?? '';
    }

    echo json_encode([
        'status' => 'success',
        'role' => $roleLower,
        'user' => $user,
        'message' => 'Đăng nhập thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi kết nối máy chủ!'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}