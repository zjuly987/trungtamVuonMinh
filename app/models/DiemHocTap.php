<?php
require_once ROOT . '/core/Model.php';

class DiemHocTap extends Model
{
    public function getClassesByAccount($maTaiKhoan)
    {
        $sql = "
            SELECT
                l.MaLop,
                l.TenLop,
                GROUP_CONCAT(
                    CONCAT(lh.Thu, ' - ', lh.Ca)
                    SEPARATOR ' | '
                ) AS LichHoc,
                GROUP_CONCAT(
                    DISTINCT p.TenPhong
                    SEPARATOR ' | '
                ) AS PhongHoc
            FROM lop_hoc l
            JOIN giao_vien gv
                ON gv.MaGiaoVien = l.MaGiaoVien
            LEFT JOIN lich_hoc lh
                ON lh.MaLop = l.MaLop
            LEFT JOIN phong_hoc p
                ON p.MaPhong = lh.MaPhong
            WHERE gv.MaTaiKhoan = :maTaiKhoan
            GROUP BY l.MaLop, l.TenLop
            ORDER BY l.MaLop
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

                MAX(CASE WHEN d.LanKiemTra='TX'
                    THEN d.Diem END) AS DTX,

                MAX(CASE WHEN d.LanKiemTra='KT'
                    THEN d.Diem END) AS KT,

                MAX(CASE WHEN d.LanKiemTra='THI'
                    THEN d.Diem END) AS Thi,

                MAX(CASE WHEN d.LanKiemTra='TX'
                    THEN d.DaSua END) AS TX_DaSua,

                MAX(CASE WHEN d.LanKiemTra='KT'
                    THEN d.DaSua END) AS KT_DaSua,

                MAX(CASE WHEN d.LanKiemTra='THI'
                    THEN d.DaSua END) AS THI_DaSua

            FROM chi_tiet_lop ctl

            INNER JOIN hoc_sinh hs
                ON hs.MaHocSinh = ctl.MaHocSinh

            LEFT JOIN diem_hoc_tap d
                ON d.MaHocSinh = hs.MaHocSinh
                AND d.MaLop = ctl.MaLop

            WHERE ctl.MaLop = ?

            GROUP BY hs.MaHocSinh, hs.TenHocSinh
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveDiem($maHS, $maLop, $loai, $diem)
    {
        $sql = "
            INSERT INTO diem_hoc_tap
            (
                MaHocSinh,
                MaLop,
                LanKiemTra,
                Diem
            )
            VALUES (?, ?, ?, ?)

            ON DUPLICATE KEY UPDATE
            Diem = VALUES(Diem)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $maHS,
            $maLop,
            $loai,
            $diem
        ]);
    }
    
    public function updateDiem($maHS, $maLop, $loai, $diem)
    {
        $sql = "
            UPDATE diem_hoc_tap
            SET
                Diem = ?,
                DaSua = 1
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
