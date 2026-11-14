<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $search     = trim($_GET['search'] ?? '');
    $specialty  = trim($_GET['specialty'] ?? '');
    $status     = trim($_GET['status'] ?? ''); // '1' | '0' | ''
    $sort       = trim($_GET['sort'] ?? 'id_desc');

    $pageNumber = max(1, (int) ($_GET['pageNumber'] ?? 1));
    $limit = 10;
    $offset = ($pageNumber - 1) * $limit;

    $conditions = [];
    $params = [];

    if ($search !== '') {
        $conditions[] = '(bs.hoTen LIKE ? OR bs.email LIKE ? OR tk.username LIKE ?)';
        $term = "%{$search}%";
        array_push($params, $term, $term, $term);
    }

    if ($specialty !== '') {
        $conditions[] = 'bs.chuyenKhoa = ?';
        $params[] = $specialty;
    }

    if ($status === '1') {
        $conditions[] = 'tk.isActive = 1';
    } elseif ($status === '0') {
        $conditions[] = 'tk.isActive = 0';
    }

    $whereClause = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $countStmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM bacSi bs
        INNER JOIN taiKhoan tk ON bs.taiKhoanId = tk.id
        $whereClause
    ");
    $countStmt->execute($params);
    $totalRecords = (int) $countStmt->fetchColumn();

    switch ($sort) {
        case 'id_asc':
            $orderBy = 'bs.id ASC';
            break;
        case 'name_asc':
            $orderBy = 'bs.hoTen ASC';
            break;
        case 'name_desc':
            $orderBy = 'bs.hoTen DESC';
            break;
        case 'id_desc':
        default:
            $orderBy = 'bs.id DESC';
            break;
    }

    $stmt = $pdo->prepare("
        SELECT
            bs.id,
            bs.taiKhoanId,
            bs.hoTen,
            bs.email,
            bs.chuyenKhoa,
            tk.username,
            tk.isActive
        FROM bacSi bs
        INNER JOIN taiKhoan tk ON bs.taiKhoanId = tk.id
        $whereClause
        ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
    ");
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
        'message' => 'Lỗi truy vấn danh sách bác sĩ: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}