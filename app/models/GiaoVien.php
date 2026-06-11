<?php
require_once ROOT . '/core/Model.php';

class GiaoVien extends Model {

    public function getAll() {
        $stmt = $this->db->query("
            SELECT *
            FROM GIAO_VIEN
            ORDER BY MaGiaoVien ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {

        $stmt = $this->db->prepare("
            SELECT *
            FROM giao_vien
            WHERE MaGiaoVien = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {

            // Bắt đầu transaction
            $this->db->beginTransaction();

            // Lấy username giáo viên cuối cùng
            $stmt = $this->db->query("
                SELECT TenDangNhap
                FROM tai_khoan
                WHERE VaiTro = 'Giáo viên'
                ORDER BY MaTaiKhoan DESC
                LIMIT 1
            ");

            $lastAccount = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($lastAccount) {

                preg_match('/(\d+)$/', $lastAccount['TenDangNhap'], $match);

                $nextNumber = intval($match[1]) + 1;

            } else {

                $nextNumber = 1;
            }

            $username = 'gv' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            // Tạo tài khoản
            $stmtTK = $this->db->prepare("
                INSERT INTO tai_khoan
                (
                    TenDangNhap,
                    MatKhau,
                    VaiTro,
                    TrangThai
                )
                VALUES (?, ?, ?, ?)
            ");

            $stmtTK->execute([
                $username,
                '123456',
                'Giáo viên',
                1
            ]);

            // Lấy MaTaiKhoan vừa tạo
            $maTaiKhoan = $this->db->lastInsertId();

            // Thêm giáo viên
            $stmtGV = $this->db->prepare("
                INSERT INTO giao_vien
                (
                    TenGiaoVien,
                    GioiTinh,
                    SoDienThoai,
                    CCCD,
                    TruongDangGiangDay,
                    DiaChi,
                    ChuyenMon,
                    MaTaiKhoan
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmtGV->execute([
                $data['TenGiaoVien'],
                $data['GioiTinh'],
                $data['SoDienThoai'],
                $data['CCCD'],
                $data['TruongDangGiangDay'],
                $data['DiaChi'],
                $data['ChuyenMon'],
                $maTaiKhoan
            ]);

            $this->db->commit();

            return true;

        } catch (PDOException $e) {

            $this->db->rollBack();

            return "duplicate";
        }
    }

    public function update($id, $data) {

        $stmt = $this->db->prepare("
            UPDATE giao_vien
            SET
                TenGiaoVien=?,
                GioiTinh=?,
                SoDienThoai=?,
                CCCD=?,
                TruongDangGiangDay=?,
                DiaChi=?,
                ChuyenMon=?
            WHERE MaGiaoVien=?
        ");

        return $stmt->execute([
            $data['TenGiaoVien'],
            $data['GioiTinh'],
            $data['SoDienThoai'],
            $data['CCCD'],
            $data['TruongDangGiangDay'],
            $data['DiaChi'],
            $data['ChuyenMon'],
            $id
        ]);
    }

    public function isTeacherInClass($id)
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM lop_hoc
            WHERE MaGiaoVien = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch() ? true : false;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            SELECT MaTaiKhoan
            FROM giao_vien
            WHERE MaGiaoVien = ?
        ");

        $stmt->execute([$id]);

        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$teacher){
            return false;
        }

        $this->db->beginTransaction();

        try {

            $stmt = $this->db->prepare("
                DELETE FROM giao_vien
                WHERE MaGiaoVien = ?
            ");

            $stmt->execute([$id]);

            $stmt = $this->db->prepare("
                DELETE FROM tai_khoan
                WHERE MaTaiKhoan = ?
            ");

            $stmt->execute([$teacher['MaTaiKhoan']]);

            $this->db->commit();

            return true;

        } catch(Exception $e){

            $this->db->rollBack();

            return false;
        }
    }

    public function search($keyword) {

        $sql = "
            SELECT *
            FROM GIAO_VIEN
            WHERE TenGiaoVien LIKE ?
            OR MaGiaoVien LIKE ?
            OR ChuyenMon LIKE ?
            ORDER BY MaGiaoVien asc
        ";

        $stmt = $this->db->prepare($sql);

        $kw="%$keyword%";

        $stmt->execute([
            $kw,
            $kw,
            $kw
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function existsTeacher($cccd, $sdt)
    {
        $stmt = $this->db->prepare("
            SELECT 1 FROM giao_vien
            WHERE CCCD = ? OR SoDienThoai = ?
            LIMIT 1
        ");

        $stmt->execute([$cccd, $sdt]);

        return $stmt->fetch() ? true : false;
    }
    public function existsTeacherEdit($cccd, $sdt, $id)
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM giao_vien
            WHERE (CCCD = ? OR SoDienThoai = ?)
            AND MaGiaoVien != ?
            LIMIT 1
        ");

        $stmt->execute([$cccd, $sdt, $id]);

        return $stmt->fetch() ? true : false;
    }
}