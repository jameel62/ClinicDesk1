<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
        // دالة معالجة وعرض صفحة تسجيل الدخول
    public function login() {
        if (Auth::check()) redirect('dashboard');
        // إذا كان المستخدم مسجل دخوله بالفعل، انقله تلقائياً للوحة التحكم مباشرة

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // التحقق من كود الأمان لحماية النظام من ثغرات تزوير الطلبات (CSRF Token)
            if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                flash('error', 'CSRF Token validation failed.');
                redirect('login');
            }
            // تنظيف البريد الإلكتروني المدخل من أي رموز    (Sanitization)
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            //  كلمة المرور المدخلة من النموذج
            $userModel = new UserModel();
            // استدعاء الموديل (Model) للبحث عن هذا الحساب داخل قاعدة البيانات
            $user = $userModel->findByEmail($email);
            // مقارنة البيانات: هل الحساب موجود؟ هل هو نشط (active)؟ وهل الباسورد مطابق للنص العادي في قاعدة البيانات؟
            if ($user && $user['is_active'] == 1 && $password === $user['password']) {
            // إذا تطابقت البيانات.. ابدأ الجلسة وسجل دخوله ونقله للوحة التحكم بنجاح!
                Auth::login($user);
                redirect('dashboard');
            } else {
                flash('error', 'Invalid credentials or account deactivated.');
                redirect('login');
                // إذا فشل التطابق.. خزن رسالة خطأ حمراء وأعد توجيهه لصفحة الدخول مجدداً
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                Auth::logout();
            }
        }
        redirect('dashboard'); // عرض ملف الواجهة الرسومية الخاص بصفحة تسجيل الدخول 
    }
}