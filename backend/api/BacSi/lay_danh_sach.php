<?php

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

$chuyenKhoa = trim($_GET['specialty'] ?? '');
$stmt = $pdo->prepare("SELECT id, hoTen FROM bacsi WHERE chuyenKhoa = ?");
$stmt->execute([$chuyenKhoa]);
$list = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'list' => $list ?? []
])

?>