<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xóa toàn bộ các biến trong session
$_SESSION = array();

// Xóa cookie session của trình duyệt nếu có
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy hoàn toàn phiên làm việc trên server
session_destroy();

// Điều hướng về trang đăng nhập
header("Location: index.php?page=login");
exit();
?>