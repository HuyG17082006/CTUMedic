<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Hành động không được phép'
    ]);
    exit();
}

$username       = trim($_POST['username'] ?? '');
$passwordHash   = trim($_POST['password'] ?? '');
$hoTen          = trim($_POST['hoTen'] ?? '');
$ngaySinh       = !empty($_POST['ngaySinh']) ? trim($_POST['ngaySinh']) : null;
$sdt            = trim($_POST['SDT'] ?? '');
$cccd           = !empty($_POST['CCCD']) ? trim($_POST['CCCD']) : null;
$email          = !empty($_POST['email']) ? trim($_POST['email']) : null;

if (empty($username) || empty($passwordHash) || empty($hoTen) || empty($sdt)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc (*)'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {

    $stmttaiKhoanId = $pdo->query('SELECT UUID()');
    $taiKhoanId = $stmttaiKhoanId->fetchColumn();

    $pdo->beginTransaction();

    // Thêm vào bảng taiKhoan (có truyền trực tiếp $taiKhoanId vào)
    $sqlTaiKhoan = "INSERT INTO taiKhoan (id, username, passwordHash, role) VALUES (?, ?, ?, 'patient');";
    $stmtTaiKhoan = $pdo->prepare($sqlTaiKhoan);
    $stmtTaiKhoan->execute([$taiKhoanId, $username, $passwordHash]);

    // Thêm thông tin chi tiết vào bảng benhNhan bằng đúng $taiKhoanId đó
    $sqlBenhNhan = "INSERT INTO benhNhan (taiKhoanId, hoTen, ngaySinh, email, SDT, CCCD) VALUES (?, ?, ?, ?, ?, ?);";
    $stmtBenhNhan = $pdo->prepare($sqlBenhNhan);
    $stmtBenhNhan->execute([$taiKhoanId, $hoTen, $ngaySinh, $email, $sdt, $cccd]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Đăng ký tài khoản bệnh nhân thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();
} catch (PDOException $e) {
    $pdo->rollBack();

    $message = 'Yêu cầu không thể thực hiện';
    $errorCode = $e->errorInfo[1];
    $errorData = $e->errorInfo[2];
    if ($errorCode === 1062) {

        if (str_contains($errorData, 'username')) {
            $message = 'Tên đăng nhập đã tồn tại.';
        } elseif (str_contains($errorData, 'SDT')) {
            $message = 'Số điện thoại đã tồn tại.';
        } elseif (str_contains($errorData, 'CCCD')) {
            $message = 'CCCD đã tồn tại.';
        } elseif (str_contains($errorData, 'email')) {
            $message = 'Email đã tồn tại.';
        } else {
            $message = 'Dữ liệu đã tồn tại.';
        }
    }
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi: ' . $message
    ], JSON_UNESCAPED_UNICODE);
}

exit();
