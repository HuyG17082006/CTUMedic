<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

// Kiểm tra quyền Manager
if (!isset($_SESSION['taiKhoanId']) || (strtolower($_SESSION['role'] ?? '') !== 'manager' && strtolower($_SESSION['role'] ?? '') !== 'admin')) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Unauthorized: Không có quyền truy cập'
    ]);
    exit();
}

try {
    $search = trim($_GET['search'] ?? '');
    
    $query = "
        SELECT bs.id, bs.hoTen, bs.email, bs.chuyenKhoa, tk.username
        FROM bacSi bs
        JOIN taiKhoan tk ON bs.taiKhoanId = tk.id
    ";

    $params = [];
    if (!empty($search)) {
        $query .= " WHERE bs.hoTen LIKE ? OR bs.chuyenKhoa LIKE ? OR bs.email LIKE ? OR tk.username LIKE ?";
        $searchTerm = "%{$search}%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
    }

    $query .= " ORDER BY bs.id DESC;";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'status',
        'data' => $doctors
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