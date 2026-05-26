<?php
require_once ROOT . '/core/Model.php';

class HocSinh extends Model {

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM HOC_SINH ORDER BY TenHocSinh ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM HOC_SINH WHERE MaHocSinh = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO HOC_SINH (TenHocSinh, NgaySinh, DiaChi, SoDienThoai) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['TenHocSinh'],
            $data['NgaySinh'],
            $data['DiaChi'],
            $data['SoDienThoai']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE HOC_SINH SET TenHocSinh=?, NgaySinh=?, DiaChi=?, SoDienThoai=? WHERE MaHocSinh=?");
        return $stmt->execute([
            $data['TenHocSinh'],
            $data['NgaySinh'],
            $data['DiaChi'],
            $data['SoDienThoai'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM HOC_SINH WHERE MaHocSinh = ?");
        return $stmt->execute([$id]);
    }
    public function search($keyword) {
    $sql = "SELECT * FROM HOC_SINH
            WHERE TenHocSinh LIKE ?
            OR MaHocSinh LIKE ?
            ORDER BY MaHocSinh ASC";

    $stmt = $this->db->prepare($sql);

    $kw = "%$keyword%";

    $stmt->execute([$kw, $kw]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function isStudentInClass($id)
{
    $sql = "SELECT * 
            FROM CHI_TIET_LOP
            WHERE MaHocSinh = ?";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([$id]);

    return $stmt->rowCount() > 0;
}
}