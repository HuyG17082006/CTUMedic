<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once(PATH_BACKEND . '/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        // 1. Kiểm tra xem username đã tồn tại chưa
        $checkStmt = $pdo->prepare("SELECT username FROM taiKhoan WHERE username = ?;");
        $checkStmt->execute([$username]);

        if ($checkStmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // 2. Tự sinh mã UUID (char(36)) cho tài khoản mới
        $taiKhoanId = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // 3. Bật Transaction
        $pdo->beginTransaction();

        // Thêm vào bảng taiKhoan (có truyền trực tiếp $taiKhoanId vào)
        $sqlTaiKhoan = "INSERT INTO taiKhoan (id, username, passwordHash, role) VALUES (?, ?, ?, 'PATIENT');";
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

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi hệ thống: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }
}
exit();
?>