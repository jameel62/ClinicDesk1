<?php
class CSRF {
    private static function init() {
        if (session_status() == PHP_SESSION_NONE) session_start();
    }

    // دالة توليد رمز أمان عشوائي طويل مستحيل تخمينه لزرعه داخل الـ Forms
    public static function generateToken() {
        self::init();
        // إذا لم يكن هناك توكن تم توليده مسبقاً في هذه الجلسة.. قم بتوليده الآن
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // دالة مطابقة الرمز القادم من المتصفح بالرمز المخزن في السيرفر للتأكد من شرعية الطلب
    public static function validateToken($token) {
        self::init();
        if (empty($_SESSION['csrf_token'])) return false;
        // مقارنة آمنة زمنياً تمنع ثغرات التخمين والتوقيت (Timing Attacks)
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}