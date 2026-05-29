<?php
function redirect($page, $action = "", $params = []) {
    $url = BASE_URL . "index.php?page=" . urlencode($page);
    if (!empty($action)) $url .= "&action=" . urlencode($action);
    foreach ($params as $key => $val) {
        $url .= "&" . urlencode($key) . "=" . urlencode($val);
    }
    header("Location: " . $url);
    exit();
}

function sanitize($data) {
    if (is_object($data) || is_array($data)) {
        return ''; // إذا كانت البيانات كائن أو مصفوفة لا يتم تحويلها لنص لمنع الخطأ
    }
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

function flash($key, $message = null) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
    } else {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
    }
    return null;
}