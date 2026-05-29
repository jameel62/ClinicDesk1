<?php
require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel {
    public function updateStatus($id, $status, $notes = null) {
        $sql = "UPDATE appointments SET status = ?, doctor_notes = ? WHERE id = ?";
        return $this->execute($sql, "ssi", [$status, $notes, $id]);
    }
}