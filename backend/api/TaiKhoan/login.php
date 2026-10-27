<?php
session_start();
require_once(PATH_BACKEND . '/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginIdentifier = trim($_POST['loginIdentifier']); // Có thể là username hoặc CCCD
    $passwordHash = trim($_POST['password']);        // Đã được mã hóa SHA-1 từ JavaScript gửi lên

    $account = null;

    // 1. Kiểm tra xem người dùng nhập vào là username hay CCCD
    // Thử tìm theo username trước kết hợp kiểm tra mật khẩu
    $stmt = $pdo->prepare('SELECT * FROM taiKhoan WHERE username = ? AND passwordHash = ?;');
    $stmt->execute([$loginIdentifier, $passwordHash]);

    if ($stmt->rowCount() == 1) {
        $account = $stmt->fetch();
    } else {
        // Nếu không khớp username, thử tìm xem có phải là CCCD của bệnh nhân không
        $stmt = $pdo->prepare('SELECT taiKhoanId FROM benhNhan WHERE CCCD = ?;');
        $stmt->execute([$loginIdentifier]);

        if ($stmt->rowCount() == 1) {
            $benhNhanInfo = $stmt->fetch();
            $taiKhoanId = $benhNhanInfo['taiKhoanId'];

            // Kiểm tra tiếp tài khoản ứng với ID đó có đúng mật khẩu không
            $stmtAcc = $pdo->prepare('SELECT * FROM taiKhoan WHERE id = ? AND passwordHash = ?;');
            $stmtAcc->execute([$taiKhoanId, $passwordHash]);

            if ($stmtAcc->rowCount() == 1) {
                $account = $stmtAcc->fetch();
            }
        }
    }

    // 2. Nếu tìm thấy tài khoản và khớp mật khẩu
    if ($account) {
        // Lưu thông tin vào Session để dùng sau này
        $_SESSION['user_id'] = $account['id'];
        $_SESSION['role'] = $account['role'];

        // Lấy thông tin chi tiết dựa theo từng Role
        $user = null;
        if ($account['role'] == 'PATIENT') {
            $stmtUser = $pdo->prepare('SELECT * FROM benhNhan WHERE taiKhoanId = ?;');
            $stmtUser->execute([$account['id']]);
            $user = $stmtUser->fetch();
        } elseif ($account['role'] == 'DOCTOR') {
            $stmtUser = $pdo->prepare('SELECT * FROM bacSi WHERE taiKhoanId = ?;');
            $stmtUser->execute([$account['id']]);
            $user = $stmtUser->fetch();
        } elseif ($account['role'] == 'ADMIN') {
            $user = $account; // Admin có thể lấy trực tiếp bảng taiKhoan
        }

        // Trả về JSON thành công cho JavaScript xử lý
        echo json_encode([
            'status' => 'success',
            'role' => $account['role'],
            'user' => $user,
        ]);
        exit();
    } else {
        // Sai tài khoản hoặc mật khẩu
        echo json_encode([
            'status' => 'failed',
            'message' => 'Sai tên đăng nhập, CCCD hoặc mật khẩu!',
        ]);
        exit();
    }
}
exit();
?>