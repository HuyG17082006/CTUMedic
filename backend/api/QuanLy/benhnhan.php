<?php
require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $search   = trim($_GET['search'] ?? '');
    $ageGroup = trim($_GET['age_group'] ?? '');
    $sort     = trim($_GET['sort'] ?? 'id_desc');

    $pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));
    $limit = 10;
    $offset = ($pageNumber - 1) * $limit;

    $conditions = [];
    $params = [];

    // Tìm kiếm
    if ($search !== '') {
        $conditions[] = '(bn.hoTen LIKE ? OR bn.SDT LIKE ? OR bn.CCCD LIKE ? OR bn.email LIKE ? OR tk.username LIKE ?)';
        $searchTerm = "%{$search}%";
        array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }

    // Lọc độ tuổi chính xác bằng TIMESTAMPDIFF
    if ($ageGroup !== '' && $ageGroup !== 'all') {
        if ($ageGroup === 'under_18') {
            $conditions[] = 'bn.ngaySinh IS NOT NULL AND TIMESTAMPDIFF(YEAR, bn.ngaySinh, CURDATE()) < 18';
        } elseif ($ageGroup === '18_59') {
            $conditions[] = 'bn.ngaySinh IS NOT NULL AND TIMESTAMPDIFF(YEAR, bn.ngaySinh, CURDATE()) BETWEEN 18 AND 59';
        } elseif ($ageGroup === 'over_60') {
            $conditions[] = 'bn.ngaySinh IS NOT NULL AND TIMESTAMPDIFF(YEAR, bn.ngaySinh, CURDATE()) >= 60';
        }
    }

    $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

    // Đếm tổng số bản ghi
    $countQuery = "
        SELECT COUNT(*)
        FROM benhNhan bn
        LEFT JOIN taiKhoan tk ON bn.taiKhoanId = tk.id
        $whereClause
    ";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalRecords = (int) $countStmt->fetchColumn();

    // Sắp xếp
    switch ($sort) {
        case 'id_asc':    $orderBy = 'bn.id ASC'; break;
        case 'name_asc':  $orderBy = 'bn.hoTen ASC'; break;
        case 'name_desc': $orderBy = 'bn.hoTen DESC'; break;
        case 'id_desc':
        default:          $orderBy = 'bn.id DESC'; break;
    }

    // Truy vấn dữ liệu bệnh nhân và trạng thái tài khoản
    $query = "
        SELECT
            bn.id,
            bn.taiKhoanId,
            bn.hoTen,
            bn.ngaySinh,
            bn.email,
            bn.SDT,
            bn.CCCD,
            tk.username,
            IFNULL(tk.isActive, 1) AS isActive
        FROM benhNhan bn
        LEFT JOIN taiKhoan tk ON bn.taiKhoanId = tk.id
        $whereClause
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'list' => $list,
        'pagination' => [
            'pageNumber' => $pageNumber,
            'limit' => $limit,
            'totalRecords' => $totalRecords,
            'totalPages' => (int) ceil($totalRecords / $limit)
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}