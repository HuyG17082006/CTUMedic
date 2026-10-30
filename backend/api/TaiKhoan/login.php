<?php
header('Content-Type: application/json; charset=utf-8');

require_once(PATH_BACKEND . '/database.php');

if (session_status() === PHP_SESSION_NONE)
    session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginIdentifier = trim($_POST['loginIdentifier'] ?? ''); 
    $passwordHash = trim($_POST['password'] ?? ''); 

    $stmt = $pdo->prepare("
        SELECT tk.id AS taiKhoanId, tk.username, tk.role, bn.*
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

    if ($account) {
        $roleLower = strtolower($account['role']);

        // Lưu thông tin vào Session chuẩn xác
        $_SESSION['taiKhoanId'] = $account['taiKhoanId'];
        $_SESSION['role'] = $roleLower;
        $_SESSION['user_name'] = $account['username'];

        // Lấy thông tin chi tiết dựa theo từng Role
        $user = null;
        if ($roleLower == 'patient') {
            $stmtUser = $pdo->prepare('SELECT * FROM benhNhan WHERE taiKhoanId = ?;');
            $stmtUser->execute([$account['taiKhoanId']]);
            $user = $stmtUser->fetch();
        } elseif ($roleLower == 'doctor') {
            $stmtUser = $pdo->prepare('SELECT * FROM bacSi WHERE taiKhoanId = ?;');
            $stmtUser->execute([$account['taiKhoanId']]);
            $user = $stmtUser->fetch();
        } elseif ($roleLower == 'manager') {
            $user = $account; 
        }

        // Trả về JSON thành công cho JavaScript xử lý
        echo json_encode([
            'status' => 'success',
            'role' => $roleLower,
            'user' => $user,
        ]);
        exit();
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Sai tên đăng nhập, CCCD hoặc mật khẩu!',
        ]);
        exit();
    }
}
exit();