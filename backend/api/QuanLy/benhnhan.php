<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $search   = trim($_GET['search'] ?? '');
    $status   = trim($_GET['status'] ?? '');     // '1' (Hoạt động) | '0' (Vô hiệu hóa) | '' (Tất cả)
    $ageGroup = trim($_GET['age_group'] ?? '');  // 'under_18' | '18_59' | 'over_60'
    $sort     = trim($_GET['sort'] ?? 'id_desc');

    $pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));
    $limit = 10;
    $offset = ($pageNumber - 1) * $limit;

    $conditions = [];
    $params = [];

    // 1. Tìm kiếm theo họ tên, SĐT, CCCD, email, username
    if ($search !== '') {
        $conditions[] = '(bn.hoTen LIKE ? OR bn.SDT LIKE ? OR bn.CCCD LIKE ? OR bn.email LIKE ? OR tk.username LIKE ?)';
        $searchTerm = "%{$search}%";
        array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }

    // 2. Lọc theo trạng thái tài khoản (isActive)
    if ($status !== '') {
        if ($status === '1') {
            $conditions[] = 'tk.isActive = 1';
        } elseif ($status === '0') {
            $conditions[] = 'tk.isActive = 0';
        }
    }

    // 3. Lọc theo độ tuổi
    if ($ageGroup !== '' && $ageGroup !== 'all') {
        if ($ageGroup === 'under_18') {
            $conditions[] = 'bn.ngaySinh IS NOT NULL AND TIMESTAMPDIFF(YEAR, bn.ngaySinh, CURDATE()) < 18';
        } elseif ($ageGroup === '18_59') {
            $conditions[] = 'bn.ngaySinh IS NOT NULL AND TIMESTAMPDIFF(YEAR, bn.ngaySinh, CURDATE()) BETWEEN 18 AND 59';
        } elseif ($ageGroup === 'over_60') {
            $conditions[] = 'bn.ngaySinh IS NOT NULL AND TIMESTAMPDIFF(YEAR, bn.ngaySinh, CURDATE()) >= 60';
        }
    }

    $whereClause = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

    // Đếm tổng số bản ghi
    $countQuery = "
        SELECT COUNT(*)
        FROM benhNhan bn
        INNER JOIN taiKhoan tk ON bn.taiKhoanId = tk.id
        $whereClause
    ";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalRecords = (int) $countStmt->fetchColumn();

    // Sắp xếp
    switch ($sort) {
        case 'id_asc':
            $orderBy = 'bn.id ASC';
            break;
        case 'name_asc':
            $orderBy = 'bn.hoTen ASC';
            break;
        case 'name_desc':
            $orderBy = 'bn.hoTen DESC';
            break;
        case 'id_desc':
        default:
            $orderBy = 'bn.id DESC';
            break;
    }

    // Truy vấn đúng các cột có trong bảng benhNhan & taiKhoan
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
            tk.isActive
        FROM benhNhan bn
        INNER JOIN taiKhoan tk ON bn.taiKhoanId = tk.id
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
        'message' => 'Lỗi truy vấn cơ sở dữ liệu.'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}