<?php
require_once ROOT . '/core/Model.php';

class DiemHocTap extends Model
{
    public function getClassesByAccount($maTaiKhoan)
    {
        $sql = "
            SELECT l.*
            FROM lop_hoc l
            JOIN giao_vien gv ON gv.MaGiaoVien = l.MaGiaoVien
            WHERE gv.MaTaiKhoan = :maTaiKhoan
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':maTaiKhoan', $maTaiKhoan);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHocSinhByLop($maLop)
    {
        $sql = "
            SELECT 
                hs.MaHocSinh,
                hs.TenHocSinh,

                MAX(CASE WHEN d.LanKiemTra='TX' THEN d.Diem END) AS DTX,
                MAX(CASE WHEN d.LanKiemTra='KT' THEN d.Diem END) AS KT,
                MAX(CASE WHEN d.LanKiemTra='THI' THEN d.Diem END) AS Thi

            FROM chi_tiet_lop ctl

            INNER JOIN hoc_sinh hs
                ON hs.MaHocSinh = ctl.MaHocSinh

            LEFT JOIN diem_hoc_tap d
                ON d.MaHocSinh = hs.MaHocSinh

            WHERE ctl.MaLop = ?

            GROUP BY hs.MaHocSinh, hs.TenHocSinh
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function saveDiem($maHS, $maLop, $loai, $diem)
    // {
    //     $sql = "
    //         INSERT INTO diem_hoc_tap (MaHocSinh, MaLop, LanKiemTra, Diem)
    //         VALUES (?, ?, ?, ?)
    //         ON DUPLICATE KEY UPDATE Diem = VALUES(Diem)
    //     ";

    //     $stmt = $this->db->prepare($sql);
    //     return $stmt->execute([$maHS, $maLop, $loai, $diem]);
    // }

    public function insertDiem($maHS, $maLop, $loai, $diem)
    {
        $sql = "
            INSERT INTO diem_hoc_tap
            (MaHocSinh, MaLop, LanKiemTra, Diem)
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute([$maHS, $maLop, $loai, $diem]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function updateDiem($maHS, $maLop, $loai, $diem)
    {
        $sql = "
            UPDATE diem_hoc_tap
            SET Diem = ?
            WHERE MaHocSinh = ?
            AND MaLop = ?
            AND LanKiemTra = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $diem,
            $maHS,
            $maLop,
            $loai
        ]);
    }

    public function tinhDTB($tx, $kt, $thi)
    {
        return ($tx + $kt * 2 + $thi * 3) / 6;
    }
}
