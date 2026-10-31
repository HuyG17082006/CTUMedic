<?php
require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

// Đặt header trả về JSON chuẩn
header('Content-Type: application/json');

// Kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['taiKhoanId'])) {
    echo json_encode([
        'status' => 'failed',
        'error' => 'Unauthorized: Chưa đăng nhập',
    ]);
    exit();
}

$accountId = $_SESSION['taiKhoanId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. Xử lý cập nhật thông tin liên lạc (Email và Số điện thoại)
    if ($action === 'update_contact') {
        $email = trim($_POST['email'] ?? '');
        $sdt = trim($_POST['sdt'] ?? '');

        if (empty($email) || empty($sdt)) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Email và Số điện thoại không được để trống!',
            ]);
            exit();
        }

        try {
            $updateCommand = 'UPDATE benhNhan SET email = ?, SDT = ? WHERE taiKhoanId = ?;';
            $stmtUpdate = $pdo->prepare($updateCommand);
            $updated = $stmtUpdate->execute([$email, $sdt, $accountId]);

            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Cập nhật thông tin thành công!',
                ]);
            } else {
                echo json_encode([
                    'status' => 'failed',
                    'message' => 'Không thể cập nhật cơ sở dữ liệu.',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ]);
        }
        exit();
    }

    // 2. Xử lý đổi mật khẩu (So sánh trực tiếp chuỗi SHA-1 với DB)
    if ($action === 'change_password') {
        $oldPasswordHashClient = $_POST['old_password'] ?? '';
        $newPasswordHashClient = $_POST['new_password'] ?? '';

        if (empty($oldPasswordHashClient) || empty($newPasswordHashClient)) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Vui lòng nhập đầy đủ mật khẩu cũ và mới!',
            ]);
            exit();
        }

        try {
            // Lấy chuỗi passwordHash hiện tại từ database
            $stmtAcc = $pdo->prepare('SELECT passwordHash FROM taiKhoan WHERE id = ?;');
            $stmtAcc->execute([$accountId]);
            $account = $stmtAcc->fetch(PDO::FETCH_ASSOC);

            // So sánh trực tiếp chuỗi SHA-1 gửi lên với cột passwordHash trong database
            if (!$account || $account['passwordHash'] !== $oldPasswordHashClient) {
                echo json_encode([
                    'status' => 'failed',
                    'message' => 'Mật khẩu cũ không chính xác!',
                ]);
                exit();
            }

            // Cập nhật chuỗi SHA-1 mới vào database
            $stmtUpdatePwd = $pdo->prepare('UPDATE taiKhoan SET passwordHash = ? WHERE id = ?;');
            $updatedPwd = $stmtUpdatePwd->execute([$newPasswordHashClient, $accountId]);

            if ($updatedPwd) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đổi mật khẩu thành công!',
                ]);
            } else {
                echo json_encode([
                    'status' => 'failed',
                    'message' => 'Không thể cập nhật mật khẩu mới.',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'failed',
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ]);
        }
        exit();
    }

    // 3. Mặc định: Lấy thông tin chi tiết của người dùng
    $command = 'SELECT benhNhan.hoTen, benhNhan.ngaySinh, benhNhan.email, benhNhan.SDT, benhNhan.CCCD, taiKhoan.username, taiKhoan.role FROM benhNhan JOIN taiKhoan ON benhNhan.taiKhoanId = taiKhoan.id WHERE taiKhoanId = ?;';
    $stmt = $pdo->prepare($command);
    $stmt->execute([$accountId]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'information' => $result,
        ]);
    } else {
        echo json_encode([
            'status' => 'failed',
            'error' => 'Not found',
        ]);
    }
} else {
    echo json_encode([
        'status' => 'failed',
        'error' => 'Wrong method',
    ]);
}
