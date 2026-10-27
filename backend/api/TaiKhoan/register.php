<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once PATH_BACKEND . '/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Lấy và làm sạch dữ liệu từ form gửi lên
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? ''); // Đã được băm SHA-256 từ phía Client
    $hoten = trim($_POST['hoTen'] ?? '');
    
    // Xử lý các trường có thể để trống thành NULL
    $ngaysinh = !empty($_POST['ngaySinh']) ? $_POST['ngaySinh'] : null;
    $phone = trim($_POST['SDT'] ?? '');
    $CCCD = !empty($_POST['CCCD']) ? trim($_POST['CCCD']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    
    try {
        // 2. Kiểm tra xem tên đăng nhập hoặc số điện thoại đã tồn tại chưa
        $checkStmt = $pdo->prepare("SELECT id FROM taiKhoan WHERE username = ?");
        $checkStmt->execute([$username]);
        if ($checkStmt->rowCount() > 0) {
            $_SESSION['error_message'] = "Tên đăng nhập '$username' đã tồn tại trong hệ thống!";
            header("Location: index.php?page=register");
            exit();
        }

        $checkPhoneStmt = $pdo->prepare("SELECT id FROM benhNhan WHERE SDT = ?");
        $checkPhoneStmt->execute([$phone]);
        if ($checkPhoneStmt->rowCount() > 0) {
            $_SESSION['error_message'] = "Số điện thoại '$phone' đã được đăng ký cho tài khoản khác!";
            header("Location: index.php?page=register");
            exit();
        }

        // 3. Bắt đầu Transaction đảm bảo tính toàn vẹn dữ liệu ở cả 2 bảng
        $pdo->beginTransaction();

        // 4. Tạo mã UUID (char(36)) thủ công cho bảng taiKhoan
        $taiKhoanId = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        // 5. Thêm bản ghi vào bảng taiKhoan (với role là PATIENT)
        $insertTaiKhoan = "INSERT INTO taiKhoan(id, username, passwordHash, role, isActive) VALUES (?, ?, ?, 'PATIENT', 1);";
        $stmtTK = $pdo->prepare($insertTaiKhoan);
        $stmtTK->execute([$taiKhoanId, $username, $password]);

        // 6. Thêm bản ghi vào bảng benhNhan (liên kết qua taiKhoanId)
        $insertBenhNhan = "INSERT INTO benhNhan(taiKhoanId, hoTen, ngaySinh, email, SDT, CCCD) VALUES (?, ?, ?, ?, ?, ?);";
        $stmtBN = $pdo->prepare($insertBenhNhan);
        $stmtBN->execute([$taiKhoanId, $hoten, $ngaysinh, $email, $phone, $CCCD]);

        // 7. Hoàn tất Transaction khi mọi thứ thành công
        $pdo->commit();

        // Đăng ký thành công, lưu thông báo thành công và chuyển hướng về trang đăng nhập
        $_SESSION['success_message'] = "Đăng ký tài khoản thành công! Vui lòng đăng nhập.";
        header("Location: index.php?page=login");
        exit();

    } catch (PDOException $e) {
        // Nếu có lỗi CSDL xảy ra, hủy bỏ toàn bộ câu lệnh đã thực hiện (Rollback)
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log($e->getMessage());
        
        // Bắt lỗi trùng lặp khóa duy nhất (Duplicate entry - mã lỗi 23000)
        if ($e->getCode() == 23000) {
            $_SESSION['error_message'] = "Thông tin đăng ký (Tên đăng nhập, SĐT hoặc CCCD) đã tồn tại trong hệ thống.";
        } else {
            $_SESSION['error_message'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
        }
        
        header("Location: index.php?page=register");
        exit();
    }
} else {
    // Nếu truy cập trực tiếp file không qua POST
    header("Location: index.php?page=register");
    exit();
}
?>