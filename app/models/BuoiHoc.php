<?php
class BuoiHoc extends Model {
    // Lấy danh sách toàn bộ các buổi học của hệ thống để hỗ trợ Javascript lọc nhanh
    public function getAllBuoiHoc() {
        $sql = "SELECT MaBuoi, MaLop, NgayHoc FROM BUOI_HOC ORDER BY MaLop ASC, NgayHoc ASC, MaBuoi ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy các buổi học của riêng 1 lớp phục vụ vẽ cột Ma trận
    public function getBuoiHocByLop($maLop) {
        $sql = "SELECT MaBuoi, MaLop, NgayHoc FROM BUOI_HOC WHERE MaLop = :MaLop ORDER BY MaBuoi ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':MaLop' => $maLop]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy các buổi học của lớp kèm số học sinh đã được điểm danh
    public function getBuoiHocByLopWithStatus($maLop) {
        $sql = "SELECT bh.MaBuoi, bh.MaLop, bh.NgayHoc,
                (SELECT COUNT(*) FROM DIEM_DANH dd WHERE dd.MaBuoi = bh.MaBuoi) as SoHocSinhDaDiemDanh
                FROM BUOI_HOC bh
                WHERE bh.MaLop = :MaLop
                ORDER BY bh.MaBuoi ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':MaLop' => $maLop]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tự động đồng bộ: Với mỗi lớp học có SoBuoi > số buổi thực tế trong BUOI_HOC,
     * hàm này sẽ sinh thêm các buổi học còn thiếu dựa vào lịch học (LICH_HOC) hoặc
     * áp dụng lịch học mặc định Thứ 2-4-6 nếu lớp chưa có lịch.
     */
    public function syncBuoiHocForClasses() {
        // Lấy tất cả lớp học cùng số buổi cấu hình và ngày bắt đầu
        $sqlLop = "SELECT MaLop, SoBuoi FROM LOP_HOC ORDER BY MaLop ASC";
        $stmtLop = $this->db->prepare($sqlLop);
        $stmtLop->execute();
        $classes = $stmtLop->fetchAll(PDO::FETCH_ASSOC);

        // Lấy lịch học của từng lớp (các Thứ học)
        $sqlLich = "SELECT MaLop, Thu FROM LICH_HOC";
        $stmtLich = $this->db->prepare($sqlLich);
        $stmtLich->execute();
        $allLich = $stmtLich->fetchAll(PDO::FETCH_ASSOC);

        // Nhóm lịch học theo MaLop
        $lichByLop = [];
        foreach ($allLich as $row) {
            $lichByLop[$row['MaLop']][] = $row['Thu'];
        }

        // Map tên thứ -> số ngày trong tuần (0=CN, 1=T2, ..., 6=T7)
        $mapThu = [
            'Thứ 2'    => 1,
            'Thứ 3'    => 2,
            'Thứ 4'    => 3,
            'Thứ 5'    => 4,
            'Thứ 6'    => 5,
            'Thứ 7'    => 6,
            'Chủ nhật' => 0,
        ];

        $sqlInsert = "INSERT INTO BUOI_HOC (MaLop) VALUES (?)";
        $stmtInsert = $this->db->prepare($sqlInsert);

        foreach ($classes as $class) {
            $maLop   = $class['MaLop'];
            $soBuoi  = (int)$class['SoBuoi'];

            if ($soBuoi <= 0) continue;

            // Đếm số buổi thực tế hiện có
            $stmtCount = $this->db->prepare("SELECT COUNT(*) FROM BUOI_HOC WHERE MaLop = ?");
            $stmtCount->execute([$maLop]);
            $soThucTe = (int)$stmtCount->fetchColumn();

            $conThieu = $soBuoi - $soThucTe;
            if ($conThieu <= 0) continue;

            // Lấy ngày học lớn nhất hiện có làm mốc bắt đầu sinh tiếp
            // $stmtMaxDate = $this->db->prepare("SELECT MAX(NgayHoc) FROM BUOI_HOC WHERE MaLop = ?");
            // $stmtMaxDate->execute([$maLop]);
            // $maxDate = $stmtMaxDate->fetchColumn();

            // if ($maxDate) {
            //     $startTs = strtotime('+1 day', strtotime($maxDate));
            // } elseif (!empty($class['NgayBatDau'])) {
            //     $startTs = strtotime($class['NgayBatDau']);
            // } else {
            //     $startTs = time();
            // }

            // // Xác định các thứ học hợp lệ
            // $validDays = [];
            // if (!empty($lichByLop[$maLop])) {
            //     foreach ($lichByLop[$maLop] as $thu) {
            //         if (isset($mapThu[$thu])) {
            //             $validDays[] = $mapThu[$thu];
            //         }
            //     }
            // }
            // // Nếu không có lịch học: mặc định Thứ 2, 4, 6
            // if (empty($validDays)) {
            //     $validDays = [1, 3, 5];
            // }

            // // Sinh các buổi học còn thiếu
            // $count   = 0;
            // // $current = $startTs;
            // while ($count < $conThieu) {
            //     $weekday = (int)date('w', $current);
            //     if (in_array($weekday, $validDays)) {
            //         $stmtInsert->execute([$maLop, date('Y-m-d', $current)]);
            //         $count++;
            //     }
            //     $current = strtotime('+1 day', $current);
            // }
            // Chỉ tạo buổi học, chưa có ngày học
            for ($i = 0; $i < $conThieu; $i++) {
                $stmtInsert->execute([$maLop]);
            }
        }
    }

    public function setNgayHocIfNull($maBuoi)
    {
        $sql = "UPDATE BUOI_HOC SET NgayHoc = NOW() WHERE MaBuoi = :MaBuoi AND NgayHoc IS NULL";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':MaBuoi' => $maBuoi]);
    }

    // public function isOver48Hours($maBuoi)
    // {
    //     $sql = "SELECT NgayHoc FROM BUOI_HOC WHERE MaBuoi = :MaBuoi";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([':MaBuoi' => $maBuoi]);
    //     $buoiHoc = $stmt->fetch(PDO::FETCH_ASSOC);
    //     // Không tìm thấy buổi học
    //     if (!$buoiHoc) {
    //         return true;
    //     }
    //     // Chưa điểm danh lần nào
    //     if (empty($buoiHoc['NgayHoc'])) {
    //         return false;
    //     }
    //     $ngayHoc = strtotime($buoiHoc['NgayHoc']);
    //     $hienTai = time();
    //     $chenhLech = $hienTai - $ngayHoc;
    //     // 48 giờ = 172800 giây
    //     return $chenhLech > 2;
    // }
}