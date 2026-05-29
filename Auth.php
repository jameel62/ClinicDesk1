<?php
class Auth {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
// دالة تسجيل دخول المستخدم وحفظ بياناته الأساسية في السيرفر
    public static function login($user) {
        self::init();
        // تخزين بيانات الهوية داخل مصفوفة الجلسة ليتذكرها الموقع في كل صفحة
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],           // صلاحية المستخدم (admin, doctor, patient)
            'email' => $user['email']
        ];
        session_regenerate_id(true);
    }

    public static function logout() {
        self::init();
        // 1. إذا كان الزائر غير مسجل دخوله أصلاً.. اطرده لصفحة تسجيل الدخول
        $_SESSION = [];
        session_destroy();
        header("Location: " . BASE_URL . "index.php?page=login");
        exit();
    }
// دالة فحص هل المستخدم الحالي مسجل دخوله أم لا (ترجع true أو false)
    public static function check() {
        self::init();
        return isset($_SESSION['user']);
    }

    public static function currentUser() {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    public static function role() {
        self::init();
        return $_SESSION['user']['role'] ?? '';
    }

    public static function requireRole(...$roles) {
        self::init();
        if (!self::check()) {
            header("Location: " . BASE_URL . "index.php?page=login");
            exit();
        }
        if (!in_array(self::role(), $roles)) {
            header("Location: " . BASE_URL . "index.php?page=403");
            exit();
        }
    }
}