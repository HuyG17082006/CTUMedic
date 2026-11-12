<?php
ob_start();

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

function resolveCurrentAccount(PDO $pdo): ?array {
    if (empty($_SESSION) || !is_array($_SESSION)) {
        return null;
    }

    $candidates = [];
    $directKeys = ['taiKhoanId', 'userId', 'accountId', 'id', 'username', 'user_id', 'account_id'];

    foreach ($directKeys as $k) {
        if (!empty($_SESSION[$k]) && is_scalar($_SESSION[$k])) {
            $candidates[] = (string) $_SESSION[$k];
        }
    }

    $nestedContainers = ['user', 'account', 'taiKhoan', 'auth', 'login'];
    foreach ($nestedContainers as $container) {
        if (!empty($_SESSION[$container]) && is_array($_SESSION[$container])) {
            foreach ($directKeys as $k) {
                if (!empty($_SESSION[$container][$k]) && is_scalar($_SESSION[$container][$k])) {
                    $candidates[] = (string) $_SESSION[$container][$k];
                }
            }
        }
    }

    array_walk_recursive($_SESSION, function ($val) use (&$candidates) {
        if (is_scalar($val) && $val !== '' && !is_bool($val)) {
            $candidates[] = (string) $val;
        }
    });

    $candidates = array_values(array_unique($candidates));

    $stmt = $pdo->prepare("SELECT id, username, isActive, role FROM taiKhoan WHERE id = ? OR username = ? LIMIT 1");
    foreach ($candidates as $val) {
        $stmt->execute([$val, $val]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row;
        }
    }

    return null;
}

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $account = resolveCurrentAccount($pdo);

    if (!$account) {
        ob_clean();
        http_response_code(401);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $taiKhoanId = $account['id'];
    $role = $account['role'];

    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        $data = $_POST;
    }

    if ($role === 'patient') {
        $hoTen    = trim($data['hoTen'] ?? '');
        $ngaySinh = trim($data['ngaySinh'] ?? '');
        $SDT      = trim($data['SDT'] ?? '');
        $email    = trim($data['email'] ?? '');
        $CCCD     = trim($data['CCCD'] ?? '');

        if ($hoTen === '') {
            ob_clean();
            http_response_code(400);
            echo json_encode([
                'status' => 'failed',
                'message' => 'Họ và tên không được để trống.'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $stmt = $pdo->prepare("
            UPDATE benhNhan
            SET hoTen = ?,
                ngaySinh = ?,
                SDT = ?,
                email = ?,
                CCCD = ?
            WHERE taiKhoanId = ?
        ");
        $stmt->execute([
            $hoTen,
            $ngaySinh !== '' ? $ngaySinh : null,
            $SDT !== '' ? $SDT : null,
            $email !== '' ? $email : null,
            $CCCD !== '' ? $CCCD : null,
            $taiKhoanId
        ]);
    } elseif ($role === 'doctor') {
        $hoTen      = trim($data['hoTen'] ?? '');
        $email      = trim($data['email'] ?? '');
        $chuyenKhoa = trim($data['chuyenKhoa'] ?? '');

        if ($hoTen === '' || $email === '' || $chuyenKhoa === '') {
            ob_clean();
            http_response_code(400);
            echo json_encode([
                'status' => 'failed',
                'message' => 'Vui lòng nhập đầy đủ họ tên, email và chuyên khoa.'
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        $stmt = $pdo->prepare("
            UPDATE bacSi
            SET hoTen = ?,
                email = ?,
                chuyenKhoa = ?
            WHERE taiKhoanId = ?
        ");
        $stmt->execute([$hoTen, $email, $chuyenKhoa, $taiKhoanId]);
    }

    ob_clean();
    echo json_encode([
        'status' => 'success',
        'message' => 'Cập nhật thông tin cá nhân thành công!'
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (PDOException $e) {
    ob_clean();
    http_response_code(400);
    if ($e->getCode() == 23000) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Email, Số điện thoại hoặc CCCD đã được sử dụng bởi tài khoản khác!'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}