<?php
ob_start();

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json; charset=utf-8');

/**
 * Hàm tự động trích xuất ID hoặc username từ mọi cấu trúc $_SESSION
 */
function resolveCurrentAccount(PDO $pdo): ?array {
    if (empty($_SESSION) || !is_array($_SESSION)) {
        return null;
    }

    $candidates = [];

    // 1. Lấy các key phổ biến ở cấp 1
    $directKeys = ['taiKhoanId', 'userId', 'accountId', 'id', 'username', 'user_id', 'account_id'];
    foreach ($directKeys as $k) {
        if (!empty($_SESSION[$k]) && is_scalar($_SESSION[$k])) {
            $candidates[] = (string) $_SESSION[$k];
        }
    }

    // 2. Lấy các key phổ biến nếu lưu dạng mảng (vd: $_SESSION['user']['id'])
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

    // 3. Quét toàn bộ chuỗi/số trong $_SESSION (phòng trường hợp đặt tên key đặc thù)
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
            'message' => 'Không xác định được tài khoản đang đăng nhập từ Session.',
            'debug_session_keys' => array_keys($_SESSION ?? [])
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $taiKhoanId = $account['id'];
    $profile = null;

    if ($account['role'] === 'patient') {
        $stmtBn = $pdo->prepare("SELECT id, hoTen, ngaySinh, email, SDT, CCCD FROM benhNhan WHERE taiKhoanId = ? LIMIT 1");
        $stmtBn->execute([$taiKhoanId]);
        $profile = $stmtBn->fetch(PDO::FETCH_ASSOC) ?: [];
    } elseif ($account['role'] === 'doctor') {
        $stmtBs = $pdo->prepare("SELECT id, hoTen, email, chuyenKhoa FROM bacSi WHERE taiKhoanId = ? LIMIT 1");
        $stmtBs->execute([$taiKhoanId]);
        $profile = $stmtBs->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    ob_clean();
    echo json_encode([
        'status' => 'success',
        'account' => $account,
        'profile' => $profile
    ], JSON_UNESCAPED_UNICODE);
    exit();

} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'status' => 'failed',
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit();
}