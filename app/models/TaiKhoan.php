<?php
require_once ROOT . '/core/Model.php';

class TaiKhoan extends Model
{
    public function findByUsername($tenDangNhap)
    {
        $stmt = $this->db->prepare("SELECT * FROM TAI_KHOAN WHERE TenDangNhap = ? AND TrangThai = 1");
        $stmt->execute([$tenDangNhap]);
        return $stmt->fetch();
    }
}