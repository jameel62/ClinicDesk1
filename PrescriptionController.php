<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class PrescriptionController {
    public function add() {
        Auth::requireRole('doctor');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Breach detected.");
            }

            $appt_id = (int)$_POST['appointment_id'];
            $diagnosis = sanitize($_POST['diagnosis']);
            $medications = sanitize($_POST['medications']);
            $notes = sanitize($_POST['notes']);
            $file_path = null;

            if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['prescription_file']['tmp_name']);
                finfo_close($finfo);

                if ($mime === 'application/pdf') {
                    $filename = bin2hex(random_bytes(16)) . ".pdf";
                    $target = __DIR__ . '/../public/uploads/prescriptions/' . $filename;
                    if (move_uploaded_file($_FILES['prescription_file']['tmp_name'], $target)) {
                        $file_path = $filename;
                    }
                } else {
                    flash('error', 'Only PDF files are allowed.');
                    redirect('dashboard');
                }
            }

            $pm = new PrescriptionModel();
            $pm->create([
                'appointment_id' => $appt_id,
                'diagnosis' => $diagnosis,
                'medications' => $medications,
                'notes' => $notes,
                'file_path' => $file_path
            ]);

            $am = new AppointmentModel();
            $am->updateStatus($appt_id, 'completed');

            flash('success', 'Prescription Added successfully.');
            redirect('dashboard');
        }
    }

    public function download() {
        Auth::requireRole('admin', 'doctor', 'patient');
        $user = Auth::currentUser();
        $appt_id = (int)($_GET['id'] ?? 0);

        $db = Database::getInstance();
        $appt = $db->query("SELECT * FROM appointments WHERE id = ?", "i", [$appt_id])->fetch_assoc();
        $pres = $db->query("SELECT * FROM prescriptions WHERE appointment_id = ?", "i", [$appt_id])->fetch_assoc();

        if (!$appt || !$pres || empty($pres['file_path'])) {
            die("Resource not found.");
        }

        // فحص الملكية المتقاطع الصارم (Cross-Ownership Check) المطلوبة في الصفحة 30
        if ($user['role'] === 'patient' && $appt['patient_id'] != $user['id']) {
            die("Access Denied.");
        }
        if ($user['role'] === 'doctor') {
            $doc = $db->query("SELECT id FROM doctors WHERE user_id = ?", "i", [$user['id']])->fetch_assoc();
            if ($appt['doctor_id'] != $doc['id']) die("Access Denied.");
        }

        $full_path = __DIR__ . '/../public/uploads/prescriptions/' . $pres['file_path'];
        if (file_exists($full_path)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="prescription_' . $appt_id . '.pdf"');
            readfile($full_path);
            exit();
        } else {
            die("File missing.");
        }
    }
}