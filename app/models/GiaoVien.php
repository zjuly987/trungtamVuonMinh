<?php
require_once ROOT . '/core/Model.php';

class GiaoVien extends Model {

    public function getAll() {
        $stmt = $this->db->query("
            SELECT *
            FROM GIAO_VIEN
            ORDER BY TenGiaoVien ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {

        $stmt = $this->db->prepare("
            SELECT *
            FROM GIAO_VIEN
            WHERE MaGiaoVien = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {

        $stmt = $this->db->prepare("
            INSERT INTO GIAO_VIEN
            (
                TenGiaoVien,
                ChuyenMon,
                DiaChi,
                SoDienThoai,
                MaTaiKhoan
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['TenGiaoVien'],
            $data['ChuyenMon'],
            $data['DiaChi'],
            $data['SoDienThoai'],
            $data['MaTaiKhoan']
        ]);
    }

    public function update($id, $data) {

        $stmt = $this->db->prepare("
            UPDATE GIAO_VIEN
            SET
                TenGiaoVien=?,
                ChuyenMon=?,
                DiaChi=?,
                SoDienThoai=?,
                MaTaiKhoan=?
            WHERE MaGiaoVien=?
        ");

        return $stmt->execute([
            $data['TenGiaoVien'],
            $data['ChuyenMon'],
            $data['DiaChi'],
            $data['SoDienThoai'],
            $data['MaTaiKhoan'],
            $id
        ]);
    }

    public function isTeacherInClass($id)
    {
        $tables = ['lop_hoc'];

        foreach($tables as $table){

            try{

                $sql = "
                    SELECT 1
                    FROM $table
                    WHERE MaGiaoVien = ?
                    LIMIT 1
                ";

                $stmt = $this->db->prepare($sql);

                $stmt->execute([$id]);

                if($stmt->fetch()){

                    return true;
                }

            }
            catch(Exception $e){

                continue;
            }
        }

        return false;
    }
    public function delete($id)
    {
        try{

            $stmt = $this->db->prepare(
                "
                DELETE FROM giao_vien
                WHERE MaGiaoVien = ?
                "
            );

            return $stmt->execute([$id]);

        }
        catch(PDOException $e){

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
            ORDER BY MaGiaoVien ASC
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
}