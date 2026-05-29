<?php
require_once __DIR__ . '/BaseModel.php';

class DoctorModel extends BaseModel {
    public function findByUserId($userId) {
        $sql = "SELECT d.*, u.name, u.email FROM doctors d JOIN users u ON d.user_id = u.id WHERE d.user_id = ?";
        $res = $this->execute($sql, "i", [$userId]);
        return $res ? $res->fetch_assoc() : null;
    }
}