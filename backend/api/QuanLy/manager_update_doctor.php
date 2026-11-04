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

$id         = intval($input['id'] ?? 0);
$hoTen      = trim($input['hoTen'] ?? '');
$chuyenKhoa = trim($input['chuyenKhoa'] ?? '');
$email      = trim($input['email'] ?? '');

if ($id <= 0 || empty($hoTen)) {
    echo json_encode(['status' => 'failed', 'message' => 'Họ tên bác sĩ không được để trống']);
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE bacSi SET hoTen = ?, chuyenKhoa = ?, email = ? WHERE id = ?");
    $stmt->execute([$hoTen, $chuyenKhoa, $email, $id]);

    echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin bác sĩ thành công']);
    exit();
} catch (Exception $e) {
    echo json_encode(['status' => 'failed', 'message' => 'Lỗi DB: ' . $e->getMessage()]);
    exit();
}
?>