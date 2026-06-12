<?php

class LopHoc extends Model
{
    // Danh sách lớp học (JOIN giáo viên + lịch học + phòng)
    public function getAll($keyword = null)
    {
        $sql = "
            SELECT
                l.MaLop,
                l.TenLop,
                l.SoBuoi,
                l.SiSo,
                l.SiSoToiDa,
                l.NgayBatDau,
                g.TenGiaoVien,
                GROUP_CONCAT(
                    CONCAT(lh.Thu, ' ', lh.Ca)
                    ORDER BY lh.MaLich
                    SEPARATOR ' | '
                ) AS LichHoc,
                GROUP_CONCAT(
                    DISTINCT p.TenPhong
                    ORDER BY p.TenPhong
                    SEPARATOR ', '
                ) AS DanhSachPhong
            FROM LOP_HOC l
            LEFT JOIN GIAO_VIEN g ON l.MaGiaoVien = g.MaGiaoVien
            LEFT JOIN LICH_HOC lh ON l.MaLop = lh.MaLop
            LEFT JOIN PHONG_HOC p ON lh.MaPhong = p.MaPhong
        ";

        $params = [];

        if ($keyword) {
            $sql .= " WHERE l.TenLop LIKE ? OR g.TenGiaoVien LIKE ?";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $sql .= " GROUP BY l.MaLop ORDER BY l.MaLop ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
    public function getAllRooms()
{
    $stmt = $this->db->query("SELECT * FROM PHONG_HOC ORDER BY TenPhong ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Tạo lớp học
    public function create($data)
    {
        $sql = "
            INSERT INTO LOP_HOC (TenLop, SoBuoi, SiSo, SiSoToiDa, NgayBatDau, MaGiaoVien)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);

        // SiSo = 0 ban đầu (sẽ cập nhật khi thêm học sinh)
        // SiSoToiDa = sĩ số tối đa từ form
        $stmt->execute([
            $data["TenLop"],
            $data["SoBuoi"],
            0,  // SiSo ban đầu = 0
            $data["SiSo"],  // SiSoToiDa = giá trị từ form
            $data["NgayBatDau"],
            $data["MaGiaoVien"] ?: null
        ]);

        return $this->db->lastInsertId();
    }

    // Thêm học sinh vào lớp
    public function addStudents($maLop, $students)
    {
        $sql = "INSERT INTO CHI_TIET_LOP (MaLop, MaHocSinh) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($students as $studentId) {
            $stmt->execute([$maLop, $studentId]);
        }
    }

    // Xóa học sinh khỏi lớp
    public function removeStudent($maLop, $maHocSinh)
    {
        $sql = "DELETE FROM CHI_TIET_LOP WHERE MaLop = ? AND MaHocSinh = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maLop, $maHocSinh]);
    }

    // Tạo lịch học
    public function createSchedule($maLop, $thuHoc, $ca, $maPhong)
    {
        $sql = "INSERT INTO LICH_HOC (MaLop, Thu, Ca, MaPhong) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        foreach ($thuHoc as $i => $thu) {
            $stmt->execute([
                $maLop,
                $thu,
                $ca[$i] ?? $ca[0],
                $maPhong[$i] ?? $maPhong[0]
            ]);
        }
    }
    public function syncSiSo($maLop)
{
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM CHI_TIET_LOP WHERE MaLop = ?");
    $stmt->execute([$maLop]);
    $count = $stmt->fetchColumn();

    $stmt = $this->db->prepare("UPDATE LOP_HOC SET SiSo = ? WHERE MaLop = ?");
    $stmt->execute([$count, $maLop]);
}
public function isFull($maLop)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(MaHocSinh) as SoHienTai
        FROM CHI_TIET_LOP
        WHERE MaLop = ?
    ");
    $stmt->execute([$maLop]);
    $row = $stmt->fetch();
    return $row && $row['SoHienTai'] >= 20;
}
public function isActive($maLop)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM BUOI_HOC 
        WHERE MaLop = ? AND NgayHoc >= CURDATE()
    ");
    $stmt->execute([$maLop]);
    return $stmt->fetchColumn() > 0;
}

public function getSchedule($id)
{
    $sql = "
        SELECT lh.*, p.TenPhong
        FROM LICH_HOC lh
        LEFT JOIN PHONG_HOC p
        ON lh.MaPhong = p.MaPhong
        WHERE lh.MaLop = ?
        ORDER BY lh.MaLich ASC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteSchedule($id)
{
    $sql = "DELETE FROM LICH_HOC WHERE MaLop = ?";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([$id]);
}
public function update($id, $data)
{
    $sql = "UPDATE LOP_HOC SET TenLop=?, SoBuoi=?, NgayBatDau=?, MaGiaoVien=?, SiSoToiDa=?
            WHERE MaLop=?";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute([
        $data['TenLop'],
        $data['SoBuoi'],
        $data['NgayBatDau'],
        $data['MaGiaoVien'] ?: null,
        $data['SiSoToiDa'],
        $id
    ]);
    // Không sync vì SiSo là số học sinh hiện tại
    return $result;
}
    // Tạo buổi học tự động
    public function taoBuoiHoc($maLop, $ngayBatDau, $thuHoc, $soBuoi)
    {
        $mapThu = [
            'Thứ 2' => 1,
            'Thứ 3' => 2,
            'Thứ 4' => 3,
            'Thứ 5' => 4,
            'Thứ 6' => 5,
            'Thứ 7' => 6,
            'Chủ nhật' => 0
        ];

        $validDays = [];
        foreach ($thuHoc as $thu) {
            if (isset($mapThu[$thu])) {
                $validDays[] = $mapThu[$thu];
            }
        }

        $current = strtotime($ngayBatDau);
        $count = 0;
        $sql = "INSERT INTO BUOI_HOC (MaLop, NgayHoc) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        while ($count < $soBuoi) {
            $weekday = (int) date("w", $current);
            if (in_array($weekday, $validDays)) {
                $stmt->execute([$maLop, date("Y-m-d", $current)]);
                $count++;
            }
            $current = strtotime("+1 day", $current);
        }
    }

    // Chi tiết lớp học (JOIN giáo viên + lịch học + phòng)
    public function find($id)
    {
        $sql = "
            SELECT
                l.MaLop,
                l.TenLop,
                l.SoBuoi,
                l.SiSo,
                l.SiSoToiDa,
                l.NgayBatDau,
                l.MaGiaoVien,
                g.TenGiaoVien,
                GROUP_CONCAT(
                    CONCAT(lh.Thu, ' ', lh.Ca)
                    ORDER BY lh.MaLich
                    SEPARATOR ' | '
                ) AS LichHoc,
                GROUP_CONCAT(
                    DISTINCT p.TenPhong
                    ORDER BY p.TenPhong
                    SEPARATOR ', '
                ) AS DanhSachPhong
            FROM LOP_HOC l
            LEFT JOIN GIAO_VIEN g ON l.MaGiaoVien = g.MaGiaoVien
            LEFT JOIN LICH_HOC lh ON l.MaLop = lh.MaLop
            LEFT JOIN PHONG_HOC p ON lh.MaPhong = p.MaPhong
            WHERE l.MaLop = ?
            GROUP BY l.MaLop
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    // Danh sách học sinh trong lớp
    public function getStudents($id)
    {
        $sql = "
            SELECT hs.*
            FROM CHI_TIET_LOP ctl
            JOIN HOC_SINH hs ON ctl.MaHocSinh = hs.MaHocSinh
            WHERE ctl.MaLop = ?
ORDER BY hs.MaHocSinh ASC        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    // Học sinh chưa có trong lớp (dùng cho modal thêm)
    public function getStudentsNotInClass($id)
    {
        $sql = "
            SELECT *
            FROM HOC_SINH
            WHERE MaHocSinh NOT IN (
                SELECT MaHocSinh FROM CHI_TIET_LOP WHERE MaLop = ?
            )
            ORDER BY TenHocSinh ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    // Danh sách buổi học
    public function getSessions($id)
    {
        $sql = "
            SELECT * FROM BUOI_HOC
            WHERE MaLop = ?
            ORDER BY NgayHoc ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }

    // Xóa lớp học (và các bản ghi liên quan)
    public function delete($id)
    {
        // Xóa theo thứ tự để không vi phạm FK
        foreach ([
            "DELETE FROM DIEM_DANH WHERE MaBuoi IN (SELECT MaBuoi FROM BUOI_HOC WHERE MaLop = ?)",
            "DELETE FROM DIEM_HOC_TAP WHERE MaLop = ?",
            "DELETE FROM BUOI_HOC WHERE MaLop = ?",
            "DELETE FROM LICH_HOC WHERE MaLop = ?",
            "DELETE FROM CHI_TIET_LOP WHERE MaLop = ?",
            "DELETE FROM LOP_HOC WHERE MaLop = ?"
        ] as $sql) {
            $this->db->prepare($sql)->execute([$id]);
        }
    }
    public function isTeacherBusy($maGiaoVien, $thuHoc, $caHoc)
{
    $sql = "
        SELECT COUNT(*) 
        FROM LOP_HOC l
        JOIN LICH_HOC lh ON l.MaLop = lh.MaLop
        WHERE l.MaGiaoVien = ?
        AND lh.Thu = ?
        AND lh.Ca = ?
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $maGiaoVien,
        $thuHoc,
        $caHoc
    ]);

    return $stmt->fetchColumn() > 0;
}

public function isRoomBusy($thuHoc, $caHoc, $maPhong)
{
    $sql = "
        SELECT COUNT(*)
        FROM LICH_HOC
        WHERE Thu = ?
        AND Ca = ?
        AND MaPhong = ?
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $thuHoc,
        $caHoc,
        $maPhong
    ]);

    return $stmt->fetchColumn() > 0;
}
public function isTeacherBusyForUpdate($maLop, $maGiaoVien, $thuHoc, $caHoc)
{
    $sql = "
        SELECT COUNT(*)
        FROM LOP_HOC l
        JOIN LICH_HOC lh ON l.MaLop = lh.MaLop
        WHERE l.MaGiaoVien = ?
        AND lh.Thu = ?
        AND lh.Ca = ?
        AND l.MaLop != ?
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $maGiaoVien,
        $thuHoc,
        $caHoc,
        $maLop
    ]);

    return $stmt->fetchColumn() > 0;
}

public function isRoomBusyForUpdate($maLop, $thuHoc, $caHoc, $maPhong)
{
    $sql = "
        SELECT COUNT(*)
        FROM LICH_HOC
        WHERE Thu = ?
        AND Ca = ?
        AND MaPhong = ?
        AND MaLop != ?
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $thuHoc,
        $caHoc,
        $maPhong,
        $maLop
    ]);

    return $stmt->fetchColumn() > 0;
}
public function isStudentBusy($maHocSinh, $thuHoc, $caHoc)
{
    $sql = "
        SELECT COUNT(*)
        FROM CHI_TIET_LOP ctl
        JOIN LICH_HOC lh ON ctl.MaLop = lh.MaLop
        WHERE ctl.MaHocSinh = ?
        AND lh.Thu = ?
        AND lh.Ca = ?
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $maHocSinh,
        $thuHoc,
        $caHoc
    ]);

    return $stmt->fetchColumn() > 0;
}
public function isStudentBusyForUpdate($maLop, $maHocSinh, $thuHoc, $caHoc)
{
    $sql = "
        SELECT COUNT(*)
        FROM CHI_TIET_LOP ctl
        JOIN LICH_HOC lh ON ctl.MaLop = lh.MaLop
        WHERE ctl.MaHocSinh = ?
        AND lh.Thu = ?
        AND lh.Ca = ?
        AND ctl.MaLop != ?
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $maHocSinh,
        $thuHoc,
        $caHoc,
        $maLop
    ]);

    return $stmt->fetchColumn() > 0;
}
}