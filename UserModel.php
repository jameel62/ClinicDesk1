<?php
require_once __DIR__ . '/../core/Database.php';

class UserModel {
    // متغير يحفظ كائن الاتصال بقاعدة البيانات
    private $db;

    public function __construct() {
        // ربط الموديل بنسخة قاعدة البيانات الموحدة عند إنشائه
        $this->db = Database::getInstance();
    }

    // دالة البحث عن مستخدم في قاعدة البيانات بواسطة بريده الإلكتروني (تُستخدم عند تسجيل الدخول)
    public function findByEmail($email) {
        // استعلام SQL للبحث عن الحساب المطابق للإيميل
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        
        // تنفيذ الاستعلام بشكل آمن عبر Prepared Statements (النوع s يعني نص/string)
        $result = $this->db->query($sql, "s", [$email]);
        
        // إذا وجد الحساب.. أعد البيانات على شكل مصفوفة، وإذا لم يجده أعد null
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}