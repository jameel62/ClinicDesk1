<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

class DashboardController {
    // الدالة الرئيسية التي تُستدعى عند فتح لوحة التحكم
    public function index() {
        // تأمين الصفحة: السماح فقط للأدمن والطبيب والمريض بالدخول (حظر الزوار)
        Auth::requireRole('admin', 'doctor', 'patient');
        
        // جلب رتبة المستخدم الحالي وبياناته من الجلسة (Session)
        $role = Auth::role();
        $user = Auth::currentUser();
        
        // الحصول على نسخة الاتصال الموحدة بقاعدة البيانات (Singleton)
        $db = Database::getInstance();
        // مصفوفة فارغة لتخزين الأرقام والإحصائيات لعرضها في الواجهة
        $stats = [];

        // --- أولاً: إذا كان المستخدم الحالي مسؤول النظام (Admin) ---
        if ($role === 'admin') {
            // 1. حساب إجمالي عدد المرضى في النظام
            $stats['total_patients'] = $db->query("SELECT COUNT(*) as total FROM users WHERE role='patient'")->fetch_assoc()['total'];
            // 2. حساب إجمالي عدد الأطباء في النظام
            $stats['total_doctors'] = $db->query("SELECT COUNT(*) as total FROM users WHERE role='doctor'")->fetch_assoc()['total'];
            // 3. حساب عدد مواعيد الحجز المسجلة بتاريخ اليوم تلقائياً
            $stats['appts_today'] = $db->query("SELECT COUNT(*) as total FROM appointments WHERE appt_date = CURDATE()")->fetch_assoc()['total'];
            
            // استدعاء واجهة العرض الرسومية الخاصة بالأدمن (Admin View)
            require_once __DIR__ . '/../views/dashboard/admin.php';
        } 
        // --- ثانياً: إذا كان المستخدم الحالي طبيب (Doctor) ---
        elseif ($role === 'doctor') {
            // جلب معرف الطبيب (id) من جدول الأطباء بناءً على الـ user_id الخاص بحسابه
            $doc = $db->query("SELECT id FROM doctors WHERE user_id = ?", "i", [$user['id']])->fetch_assoc();
            $doc_id = $doc['id'] ?? 0;
            
            // جلب مواعيد اليوم الخاصة بهذا الطبيب فقط وعزلها عن باقي الأطباء
            $result = $db->query("SELECT * FROM appointments WHERE doctor_id = ? AND appt_date = CURDATE()", "i", [$doc_id]);
            $stats['today_appts'] = [];
            if ($result) {
                // تحويل كائن النتيجة إلى مصفوفة صفوف عادية لتجنب أخطاء العرض في الواجهة
                while ($row = $result->fetch_assoc()) {
                    $stats['today_appts'][] = $row;
                }
            }
            // استدعاء واجهة العرض الرسومية الخاصة بالطبيب (Doctor View)
            require_once __DIR__ . '/../views/dashboard/doctor.php';
        } 
        // --- ثالثاً: إذا كان المستخدم الحالي مريض (Patient) ---
        elseif ($role === 'patient') {
            // جلب المواعيد الطبية الخاصة بهذا المريض فقط بناءً على معرفه
            $result = $db->query("SELECT * FROM appointments WHERE patient_id = ?", "i", [$user['id']]);
            $stats['my_appts'] = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $stats['my_appts'][] = $row;
                }
            }
            // استدعاء واجهة العرض الرسومية الخاصة بالمريض (Patient View)
            require_once __DIR__ . '/../views/dashboard/patient.php';
        }
    }
}