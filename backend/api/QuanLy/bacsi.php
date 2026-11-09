<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $search = trim($_GET['search'] ?? '');

    $pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));
    $limit = 10;
    $offset = ($pageNumber - 1) * $limit;

    $conditions = ['tk.isActive = 1'];
    $params = [];

    if ($search !== '') {
        $conditions[] = '(bs.hoTen LIKE ? OR bs.chuyenKhoa LIKE ? OR bs.email LIKE ? OR tk.username LIKE ?)';

        $searchTerm = "%{$search}%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
    }

    $whereClause = 'WHERE ' . implode(' AND ', $conditions);

    $countQuery = "
        SELECT COUNT(*)
        FROM bacSi bs
        JOIN taiKhoan tk ON bs.taiKhoanId = tk.id
        $whereClause
    ";

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);

    $totalRecords = (int) $countStmt->fetchColumn();

    $query = "
        SELECT
            bs.id,
            bs.hoTen,
            bs.email,
            bs.chuyenKhoa,
            tk.username

        FROM bacSi bs

        JOIN taiKhoan tk
            ON bs.taiKhoanId = tk.id

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
        'message' => 'Lỗi truy vấn cơ sở dữ liệu.'
    ], JSON_UNESCAPED_UNICODE);

    exit();
}