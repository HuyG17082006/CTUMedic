<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

// Kiểm tra quyền Manager (hoặc Admin)
if (!isset($_SESSION['taiKhoanId']) || (strtolower($_SESSION['role'] ?? '') !== 'manager' && strtolower($_SESSION['role'] ?? '') !== 'admin')) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Unauthorized: Không có quyền truy cập hệ thống quản lý'
    ]);
    exit();
}

try {
    $search = trim($_GET['search'] ?? '');
    
    $query = "
        SELECT bn.id, bn.hoTen, bn.ngaySinh, bn.SDT, bn.email, bn.CCCD, tk.username
        FROM benhNhan bn
        JOIN taiKhoan tk ON bn.taiKhoanId = tk.id
    ";

    $params = [];
    if (!empty($search)) {
        $query .= " WHERE bn.hoTen LIKE ? OR bn.SDT LIKE ? OR bn.CCCD LIKE ? OR tk.username LIKE ?";
        $searchTerm = "%{$search}%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
    }

    $query .= " ORDER BY bn.id DESC;";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $patients
    ]);
    exit();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage()
    ]);
    exit();
}
?>