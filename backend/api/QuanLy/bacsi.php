<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $search      = trim($_GET['search'] ?? '');
    $chuyenKhoa  = trim($_GET['chuyenKhoa'] ?? '');
    $pageNumber  = max(1, (int) ($_GET['pageNumber'] ?? 1));
    $limit       = 10;
    $offset      = ($pageNumber - 1) * $limit;

    $conditions = [];
    $params     = [];

    // Tìm kiếm theo tên, chuyên khoa, email hoặc username
    if ($search !== '') {
        $conditions[] = '(bs.hoTen LIKE ? OR bs.chuyenKhoa LIKE ? OR bs.email LIKE ? OR tk.username LIKE ?)';
        $searchTerm = "%{$search}%";
        array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }

    // Lọc riêng theo chuyên khoa (nếu frontend có select box chuyên khoa)
    if ($chuyenKhoa !== '' && $chuyenKhoa !== 'all') {
        $conditions[] = 'bs.chuyenKhoa = ?';
        $params[] = $chuyenKhoa;
    }

    $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

    // Đếm tổng số bản ghi bác sĩ
    $countQuery = "
        SELECT COUNT(*)
        FROM bacSi bs
        LEFT JOIN taiKhoan tk ON bs.taiKhoanId = tk.id
        $whereClause
    ";

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $totalRecords = (int) $countStmt->fetchColumn();

    // Truy vấn thông tin bác sĩ và trạng thái tài khoản
    $query = "
        SELECT
            bs.id,
            bs.taiKhoanId,
            bs.hoTen,
            bs.email,
            bs.chuyenKhoa,
            tk.username,
            IFNULL(tk.isActive, 1) AS isActive
        FROM bacSi bs
        LEFT JOIN taiKhoan tk ON bs.taiKhoanId = tk.id
        $whereClause
        ORDER BY bs.id DESC
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