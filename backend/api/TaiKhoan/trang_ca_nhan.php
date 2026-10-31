<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

if (!isset($_SESSION['taiKhoanId'])) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Unauthorized: Chưa đăng nhập phiên làm việc'
    ]);
    exit();
}

$accountId = $_SESSION['taiKhoanId'];

try {
    // 1. Lấy thông tin tài khoản chung
    $stmtAcc = $pdo->prepare("SELECT id, username, role FROM taiKhoan WHERE id = ? LIMIT 1;");
    $stmtAcc->execute([$accountId]);
    $account = $stmtAcc->fetch(PDO::FETCH_ASSOC);

    if (!$account) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Không tìm thấy tài khoản trong hệ thống'
        ]);
        exit();
    }

    $roleLower = strtolower($account['role']);
    $action = $_POST['action'] ?? '';

    // Xử lý cập nhật thông tin liên lạc (Dành cho bệnh nhân)
    if ($action === 'update_contact') {
        if ($roleLower === 'doctor' || $roleLower === 'bacsi') {
            echo json_encode(['status' => 'failed', 'message' => 'Bác sĩ không có quyền chỉnh sửa thông tin này!']);
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $sdt = trim($_POST['sdt'] ?? '');
        $ngaySinh = trim($_POST['ngaySinh'] ?? '');
        $cccd = trim($_POST['cccd'] ?? '');

        $stmtUpdate = $pdo->prepare('UPDATE benhNhan SET email = ?, SDT = ?, ngaySinh = ?, CCCD = ? WHERE taiKhoanId = ?;');
        $updated = $stmtUpdate->execute([$email, $sdt, $ngaySinh, $cccd, $accountId]);

        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công!']);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Cập nhật thất bại!']);
        }
        exit();
    }

    // Xử lý đổi mật khẩu
    if ($action === 'change_password') {
        $oldPasswordHashClient = $_POST['old_password'] ?? '';
        $newPasswordHashClient = $_POST['new_password'] ?? '';

        $stmtPwd = $pdo->prepare('SELECT passwordHash FROM taiKhoan WHERE id = ?;');
        $stmtPwd->execute([$accountId]);
        $accData = $stmtPwd->fetch(PDO::FETCH_ASSOC);

        if (!$accData || $accData['passwordHash'] !== $oldPasswordHashClient) {
            echo json_encode(['status' => 'failed', 'message' => 'Mật khẩu cũ không chính xác!']);
            exit();
        }

        $stmtUpdatePwd = $pdo->prepare('UPDATE taiKhoan SET passwordHash = ? WHERE id = ?;');
        $updatedPwd = $stmtUpdatePwd->execute([$newPasswordHashClient, $accountId]);

        if ($updatedPwd) {
            echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công!']);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Không thể đổi mật khẩu!']);
        }
        exit();
    }

    // 2. Lấy thông tin chi tiết dựa theo vai trò
    $detailData = [];
    if ($roleLower === 'doctor' || $roleLower === 'bacsi') {
        $stmtDoc = $pdo->prepare("SELECT hoTen, email, chuyenKhoa FROM bacSi WHERE taiKhoanId = ? LIMIT 1;");
        $stmtDoc->execute([$accountId]);
        $detailData = $stmtDoc->fetch(PDO::FETCH_ASSOC) ?: [];
    } else {
        $stmtPat = $pdo->prepare("SELECT hoTen, ngaySinh, email, SDT, CCCD FROM benhNhan WHERE taiKhoanId = ? LIMIT 1;");
        $stmtPat->execute([$accountId]);
        $detailData = $stmtPat->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    // Gộp dữ liệu tài khoản và chi tiết lại
    $merged = array_merge($account, $detailData);
    $merged['role'] = $roleLower;

    echo json_encode([
        'status' => 'success',
        'information' => $merged
    ]);
    exit();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
    ]);
    exit();
}
?>