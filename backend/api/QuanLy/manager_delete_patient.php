<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

if (!isset($_SESSION['taiKhoanId']) || (strtolower($_SESSION['role'] ?? '') !== 'manager' && strtolower($_SESSION['role'] ?? '') !== 'admin')) {
    echo json_encode(['status' => 'failed', 'message' => 'Unauthorized: Không có quyền truy cập']);
    exit();
}

// Nhận id trực tiếp qua tham số GET
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'failed', 'message' => 'ID bệnh nhân không hợp lệ']);
    exit();
}

try {
    // 1. Tìm taiKhoanId từ bệnh nhân
    $stmtSelect = $pdo->prepare("SELECT taiKhoanId FROM benhNhan WHERE id = ?");
    $stmtSelect->execute([$id]);
    $patient = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
        echo json_encode(['status' => 'failed', 'message' => 'Không tìm thấy bệnh nhân']);
        exit();
    }

    if (!empty($patient['taiKhoanId'])) {
        // 2. Thực hiện xóa mềm bằng cách đặt isActive = 0
        $stmtUpdate = $pdo->prepare("UPDATE taiKhoan SET isActive = 0 WHERE id = ?");
        $stmtUpdate->execute([$patient['taiKhoanId']]);

        echo json_encode(['status' => 'success', 'message' => 'Đã vô hiệu hóa tài khoản bệnh nhân thành công']);
    } else {
        echo json_encode(['status' => 'failed', 'message' => 'Bệnh nhân này chưa có tài khoản liên kết']);
    }
    exit();

} catch (Exception $e) {
    echo json_encode(['status' => 'failed', 'message' => 'Lỗi DB: ' . $e->getMessage()]);
    exit();
}
?>