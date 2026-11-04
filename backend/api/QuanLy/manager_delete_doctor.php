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

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['status' => 'failed', 'message' => 'ID bác sĩ không hợp lệ']);
    exit();
}

try {
    // 1. Lấy taiKhoanId của bác sĩ
    $stmtSelect = $pdo->prepare("SELECT taiKhoanId FROM bacSi WHERE id = ?");
    $stmtSelect->execute([$id]);
    $doctor = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    if (!$doctor) {
        echo json_encode(['status' => 'failed', 'message' => 'Không tìm thấy thông tin bác sĩ']);
        exit();
    }

    if (!empty($doctor['taiKhoanId'])) {
        // 2. Đổi isActive = 0 (Xóa mềm)
        $stmtUpdate = $pdo->prepare("UPDATE taiKhoan SET isActive = 0 WHERE id = ?");
        $stmtUpdate->execute([$doctor['taiKhoanId']]);

        echo json_encode(['status' => 'success', 'message' => 'Đã vô hiệu hóa tài khoản bác sĩ thành công']);
    } else {
        echo json_encode(['status' => 'failed', 'message' => 'Bác sĩ chưa gắn tài khoản']);
    }
    exit();

} catch (Exception $e) {
    echo json_encode(['status' => 'failed', 'message' => 'Lỗi DB: ' . $e->getMessage()]);
    exit();
}
?>