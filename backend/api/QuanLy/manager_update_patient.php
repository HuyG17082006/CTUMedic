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

$input = json_decode(file_get_contents('php://input'), true);

$id       = intval($input['id'] ?? 0);
$hoTen    = trim($input['hoTen'] ?? '');
$ngaySinh = !empty($input['ngaySinh']) ? $input['ngaySinh'] : null;
$SDT      = trim($input['SDT'] ?? '');
$email    = trim($input['email'] ?? '');
$CCCD     = trim($input['CCCD'] ?? '');

if ($id <= 0 || empty($hoTen)) {
    echo json_encode(['status' => 'failed', 'message' => 'Họ tên không được để trống']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        UPDATE benhNhan 
        SET hoTen = ?, ngaySinh = ?, SDT = ?, email = ?, CCCD = ?
        WHERE id = ?
    ");
    $stmt->execute([$hoTen, $ngaySinh, $SDT, $email, $CCCD, $id]);

    echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin bệnh nhân thành công']);
    exit();

} catch (Exception $e) {
    echo json_encode(['status' => 'failed', 'message' => 'Lỗi DB: ' . $e->getMessage()]);
    exit();
}
?>