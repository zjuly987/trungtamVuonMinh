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
            order by l.MaLop DESC
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

                COALESCE(SUM(CASE WHEN d.LanKiemTra = 'TX' THEN d.Diem ELSE 0 END),0) AS DTX,
                COALESCE(SUM(CASE WHEN d.LanKiemTra = 'KT' THEN d.Diem ELSE 0 END),0) AS KT,
                COALESCE(SUM(CASE WHEN d.LanKiemTra = 'THI' THEN d.Diem ELSE 0 END),0) AS Thi

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

    public function saveDiem($maHocSinh, $maLop, $lanKiemTra, $diem)
    {
        $sql = "INSERT INTO diem_hoc_tap(MaHocSinh, MaLop, LanKiemTra, Diem)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $maHocSinh,
            $maLop,
            $lanKiemTra,
            $diem
        ]);
    }

    public function tinhDTB($tx, $kt, $thi)
    {
        return ($tx + $kt * 2 + $thi * 3) / 6;
    }
}
