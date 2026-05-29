<?php
// 1. استدعاء ملف الإعدادات العامة للمشروع    
require_once __DIR__ . '/config/app.php';
// 2. استدعاء دوال المساعدة العامة ( دالة التنظيف sanitize ودالة التوجيه redirect)
require_once __DIR__ . '/core/helpers.php';

// قراءة الصفحة المطلوبة من الرابط (مثلاً: ?page=login)، وإذا كان الرابط فارغاً افتح اللوحة (dashboard) تلقائياً
$page = $_GET['page'] ?? 'dashboard';
// قراءة الأكشن المطلوب تنفيذه داخل الصفحة (  index)
$action = $_GET['action'] ?? 'index';

// نظام التوجيه المركزي (Routing Switch): يفحص الكلمة المكتوبة في متغير $page يستدعي متحكمها
switch ($page) {
    
    // أولاً: إذا كان الرابط يطلب صفحة تسجيل الدخول
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->login();
        break;

    // ثانياً: إذا كان الرابط يطلب لوحة التحكم الرئيسية (Dashboard)
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        $ctrl = new DashboardController();
        $ctrl->index();
        break;

    // ثالثاً: إذا حاول المستخدم الدخول لصفحة غير موجودة في النظام
    default:
        // عرض صفحة الخطأ 404 (الصفحة غير موجودة) للحفاظ على احترافية النظام
        http_response_code(404);
        require_once __DIR__ . '/views/errors/404.php';
        break;
}