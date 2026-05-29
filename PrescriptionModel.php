<?php
require_once __DIR__ . '/BaseModel.php';

class PrescriptionModel extends BaseModel {
    public function create($data) {
        $sql = "INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path) VALUES (?, ?, ?, ?, ?)";
        return $this->execute($sql, "issss", [$data['appointment_id'], $data['diagnosis'], $data['medications'], $data['notes'], $data['file_path']]);
    }
}