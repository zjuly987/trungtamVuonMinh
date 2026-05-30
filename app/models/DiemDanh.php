<?php
class DiemDanh extends Model {
    // 1. Lấy danh sách lớp học và gộp lịch học phục vụ bảng hiển thị chính
    public function getDanhSachLopHoc($search = null) {
        $sql = "SELECT MaLop, TenLop FROM LOP_HOC";
        $params = [];
        if ($search) {
            $sql .= " WHERE TenLop LIKE ?";
            $params[] = "%" . $search . "%";
        }
        $sql .= " ORDER BY MaLop ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy toàn bộ lịch học để xử lý ghép chuỗi ca học trong PHP (tránh lỗi cú pháp giữa các hệ CSDL)
        $sqlLich = "SELECT MaLop, Thu, Ca FROM LICH_HOC";
        $stmtLich = $this->db->prepare($sqlLich);
        $stmtLich->execute();
        $schedules = $stmtLich->fetchAll(PDO::FETCH_ASSOC);

        // Ghép lịch học vào từng lớp
        foreach ($classes as &$class) {
            $lichArray = [];
            foreach ($schedules as $s) {
                if ($s['MaLop'] == $class['MaLop']) {
                    $lichArray[] = $s['Thu'] . " " . $s['Ca'];
                }
            }
            $class['LichHoc'] = !empty($lichArray) ? implode(' | ', $lichArray) : 'Chưa xếp lịch';
        }
        return $classes;
    }

    // 2. Lấy danh sách học sinh thuộc lớp
    public function getHocSinhByLop($maLop) {
        $sql = "SELECT hs.MaHocSinh, hs.TenHocSinh 
                FROM HOC_SINH hs
                JOIN CHI_TIET_LOP ctl ON hs.MaHocSinh = ctl.MaHocSinh
                WHERE ctl.MaLop = :MaLop ORDER BY hs.TenHocSinh ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':MaLop' => $maLop]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Lấy dữ liệu điểm danh để vẽ ma trận chấm tròn
    public function getDiemDanhByLop($maLop) {
        $sql = "SELECT dd.MaBuoi, dd.MaHocSinh, dd.TrangThaiDiemDanh 
                FROM DIEM_DANH dd
                JOIN BUOI_HOC bh ON dd.MaBuoi = bh.MaBuoi
                WHERE bh.MaLop = :MaLop";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':MaLop' => $maLop]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Lưu hoặc Cập nhật điểm danh
    public function updateTrangThai($maBuoi, $maHocSinh, $trangThai) {
        $checkSql = "SELECT COUNT(*) FROM DIEM_DANH WHERE MaBuoi = :MaBuoi AND MaHocSinh = :MaHocSinh";
        $stmt = $this->db->prepare($checkSql);
        $stmt->execute([':MaBuoi' => $maBuoi, ':MaHocSinh' => $maHocSinh]);
        
        if ($stmt->fetchColumn() > 0) {
            $sql = "UPDATE DIEM_DANH SET TrangThaiDiemDanh = :TrangThaiDiemDanh WHERE MaBuoi = :MaBuoi AND MaHocSinh = :MaHocSinh";
        } else {
            $sql = "INSERT INTO DIEM_DANH (MaBuoi, MaHocSinh, TrangThaiDiemDanh) VALUES (:MaBuoi, :MaHocSinh, :TrangThaiDiemDanh)";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':MaBuoi' => $maBuoi, ':MaHocSinh' => $maHocSinh, ':TrangThaiDiemDanh' => $trangThai]);
    }

    public function getDanhSachLopHocByTeacher($maTaiKhoan, $search = null)
    {
        $sql = "SELECT lh.MaLop, lh.TenLop
                FROM LOP_HOC lh
                INNER JOIN GIAO_VIEN gv 
                    ON lh.MaGiaoVien = gv.MaGiaoVien
                WHERE gv.MaTaiKhoan = ?";
        $params = [$maTaiKhoan];
        if (!empty($search)) { $sql .= " AND lh.TenLop LIKE ?"; $params[] = "%" . $search . "%"; }
        $sql .= " ORDER BY lh.MaLop ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Lấy lịch học
        $sqlLich = "SELECT MaLop, Thu, Ca, TenPhong FROM LICH_HOC JOIN PHONG_HOC 
        ON LICH_HOC.MaPhong = PHONG_HOC.MaPhong";
        $stmtLich = $this->db->prepare($sqlLich);
        $stmtLich->execute();
        $schedules = $stmtLich->fetchAll(PDO::FETCH_ASSOC);
        // Ghép lịch học vào từng lớp
        foreach ($classes as &$class) {
            $lichArray = [];
            $phongArray = [];
            foreach ($schedules as $s) {
                if ($s['MaLop'] == $class['MaLop']) {
                    $lichArray[] = $s['Thu'] . " " . $s['Ca'];
                    $phongArray[] = $s['TenPhong'];
                }
            }
            $class['LichHoc'] = !empty($lichArray)
                ? implode(' | ', $lichArray)
                : 'Chưa xếp lịch';
            $class['PhongHoc'] = !empty($phongArray)
                ? implode(' | ', array_unique($phongArray))
                : 'Chưa có phòng';
        }
        return $classes;
    }

    public function isBuoiBeforeCompleted($maLop, $maBuoi)
    {
        $sql = " SELECT MaBuoi FROM BUOI_HOC WHERE MaLop = :MaLop ORDER BY MaBuoi ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':MaLop' => $maLop]);
        $listBuoi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Nếu không có buổi học
        if (empty($listBuoi)) {
            return true;
        }
        $currentIndex = null;
        foreach ($listBuoi as $index => $buoi) {
            if ((int)$buoi['MaBuoi'] === (int)$maBuoi) {
                $currentIndex = $index;
                break;
            }
        }
        // Không tìm thấy buổi hiện tại
        if ($currentIndex === null) {
            return false;
        }
        // Nếu là buổi đầu tiên -> luôn cho phép
        if ($currentIndex === 0) {
            return true;
        }
        // Lấy mã buổi trước
        $maBuoiTruoc = $listBuoi[$currentIndex - 1]['MaBuoi'];
        // Kiểm tra buổi trước đã điểm danh chưa
        $sqlCheck = " SELECT NgayHoc FROM BUOI_HOC WHERE MaBuoi = :MaBuoi";
        $stmt = $this->db->prepare($sqlCheck);
        $stmt->execute([':MaBuoi' => $maBuoiTruoc]);
        $prevBuoi = $stmt->fetch(PDO::FETCH_ASSOC);
        // Có ngày học => đã điểm danh
        return !empty($prevBuoi['NgayHoc']);
    }
}