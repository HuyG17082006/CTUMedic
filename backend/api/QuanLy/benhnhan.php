<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(PATH_BACKEND . '/database.php');

if (!isset($_SESSION['taiKhoanId']) || (strtolower($_SESSION['role'] ?? '') !== 'manager' && strtolower($_SESSION['role'] ?? '') !== 'admin')) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Unauthorized: Không có quyền truy cập'
    ]);
    exit();
}

try {
    $search   = trim($_GET['search'] ?? '');
    $ageGroup = trim($_GET['age_group'] ?? '');
    $sort     = trim($_GET['sort'] ?? 'id_desc');

    // Chỉ lấy bệnh nhân có tk.isActive = 1 hoặc chưa có tài khoản
    $query = "
        SELECT 
            bn.id, 
            bn.hoTen, 
            bn.ngaySinh, 
            bn.email, 
            bn.SDT, 
            bn.CCCD, 
            tk.username
        FROM benhNhan bn
        LEFT JOIN taiKhoan tk ON bn.taiKhoanId = tk.id
        WHERE (tk.isActive = 1 OR tk.id IS NULL)
    ";

    $params = [];

    if (!empty($search)) {
        $query .= " AND (bn.hoTen LIKE ? OR bn.SDT LIKE ? OR bn.CCCD LIKE ? OR bn.email LIKE ? OR tk.username LIKE ?)";
        $searchTerm = "%{$search}%";
        array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }

    if (!empty($ageGroup) && $ageGroup !== 'all') {
        $currentYear = date('Y');
        if ($ageGroup === 'under_18') {
            $query .= " AND bn.ngaySinh IS NOT NULL AND YEAR(bn.ngaySinh) > ?";
            $params[] = $currentYear - 18;
        } elseif ($ageGroup === '18_59') {
            $query .= " AND bn.ngaySinh IS NOT NULL AND YEAR(bn.ngaySinh) BETWEEN ? AND ?";
            $params[] = $currentYear - 59;
            $params[] = $currentYear - 18;
        } elseif ($ageGroup === 'over_60') {
            $query .= " AND bn.ngaySinh IS NOT NULL AND YEAR(bn.ngaySinh) <= ?";
            $params[] = $currentYear - 60;
        }
    }

    switch ($sort) {
        case 'id_asc':
            $query .= " ORDER BY bn.id ASC";
            break;
        case 'name_asc':
            $query .= " ORDER BY bn.hoTen ASC";
            break;
        case 'name_desc':
            $query .= " ORDER BY bn.hoTen DESC";
            break;
        case 'id_desc':
        default:
            $query .= " ORDER BY bn.id DESC";
            break;
    }

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