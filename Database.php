<?php
class Database {
    private static $instance = null;
        // متغير استاتيكي يحفظ نسخة الاتصال لمنع تكراره في السيرفر (نمط الـ Singleton)

    private $conn;
// دالة البناء جعلناها private (خاصة) لمنع إنشاء كائنات عشوائية من خارج الكلاس
    private function __construct() {
        // استدعاء ملف الإعدادات الخاص بقاعدة البيانات    
        $config = require __DIR__ . '/../config/database.php';
        $this->conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);
        if ($this->conn->connect_error) {
            // فحص إذا كان هناك خطأ في الاتصال بالسيرفر
            die("Database Connection Failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }
// الدالة العامة الوحيدة للحصول على نسخة الاتصال الموحدة في النظام
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
// دالة تنفيذ الاستعلامات الآمنة لحماية النظام من ثغرات حقن قواعد البيانات (SQL Injection)
    public function query($sql, $types = "", $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
// إذا كان الاستعلام يحتوي على متغيرات (Parameters)، قم بربطها بالأنواع المناسبة لها
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if ($stmt->execute()) {
            if (strpos(strtoupper($sql), 'SELECT') === 0) {
                return $stmt->get_result();
            }
            return $stmt;
        }
        return false;
    }
}