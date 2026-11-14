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

    $stmt = $pdo->prepare("SELECT id, username, passwordHash, isActive, role FROM taiKhoan WHERE id = ? OR username = ? LIMIT 1");
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

    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        $data = $_POST;
    }

    // Nhận chuỗi hash từ frontend (hoặc raw password nếu frontend chưa băm)
    $oldInput = trim($data['oldPasswordHash'] ?? $data['oldPassword'] ?? '');
    $newInput = trim($data['newPasswordHash'] ?? $data['newPassword'] ?? $data['passwordHash'] ?? '');
    $confirmInput = trim($data['confirmPasswordHash'] ?? $data['confirmPassword'] ?? '');

    if ($oldInput === '' || $newInput === '') {
        ob_clean();
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Vui lòng nhập đầy đủ mật khẩu hiện tại và mật khẩu mới.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($confirmInput !== '' && $newInput !== $confirmInput) {
        ob_clean();
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Mật khẩu xác nhận không khớp.'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Chuẩn hóa về SHA-1 (nếu đã là chuỗi hex 40 ký tự từ frontend thì giữ nguyên, ngược lại thì sha1)
    $isSha1 = static fn(string $s): bool => (bool) preg_match('/^[a-f0-9]{40}$/i', $s);
    $oldHash = $isSha1($oldInput) ? strtolower($oldInput) : sha1($oldInput);
    $newHash = $isSha1($newInput) ? strtolower($newInput) : sha1($newInput);

    // So sánh trực tiếp với cột passwordHash trong DB
    if (empty($account['passwordHash']) || !hash_equals(strtolower($account['passwordHash']), $oldHash)) {
        ob_clean();
        http_response_code(400);
        echo json_encode([
            'status' => 'failed',
            'message' => 'Mật khẩu hiện tại không chính xác!'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // Lưu thẳng passwordHash mới vào DB (không băm lại)
    $stmtUpdate = $pdo->prepare("UPDATE taiKhoan SET passwordHash = ? WHERE id = ?");
    $stmtUpdate->execute([$newHash, $account['id']]);

    ob_clean();
    echo json_encode([
        'status' => 'success',
        'message' => 'Đổi mật khẩu thành công!'
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