<?php
class AttendanceController extends Controller {
    private $buoiHocModel;
    private $diemDanhModel;

    public function __construct() {
        require_once __DIR__ . '/../models/BuoiHoc.php';
        require_once __DIR__ . '/../models/DiemDanh.php';
        $this->buoiHocModel = new BuoiHoc();
        $this->diemDanhModel = new DiemDanh();
    }

    // Trang chủ Quản lý điểm danh
    public function index() {
        // Tự động đồng bộ buổi học: sinh thêm các buổi còn thiếu cho tất cả lớp
        $this->buoiHocModel->syncBuoiHocForClasses();

        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $listLopHoc = $this->diemDanhModel->getDanhSachLopHoc($search);
        $allBuoiHoc = $this->buoiHocModel->getAllBuoiHoc();

        // Xử lý nếu giáo viên bấm "Tra cứu & Điểm danh" để xem chi tiết
        $maLopMatrix = isset($_GET['view_matrix']) ? intval($_GET['view_matrix']) : null;
        $matrixData = [];
        $listBuoiHocMatrix = [];
        $listHocSinhMatrix = [];
        $listBuoiHocWithStatus = [];

        if ($maLopMatrix) {
            $listBuoiHocMatrix = $this->buoiHocModel->getBuoiHocByLop($maLopMatrix);
            $listHocSinhMatrix = $this->diemDanhModel->getHocSinhByLop($maLopMatrix);
            $rawDiemDanh = $this->diemDanhModel->getDiemDanhByLop($maLopMatrix);
            $listBuoiHocWithStatus = $this->buoiHocModel->getBuoiHocByLopWithStatus($maLopMatrix);

            foreach ($rawDiemDanh as $row) {
                $matrixData[$row['MaHocSinh']][$row['MaBuoi']] = $row['TrangThaiDiemDanh'];
            }
        }

        $this->view('attendance/index', [
            'listLopHoc' => $listLopHoc,
            'allBuoiHoc' => $allBuoiHoc,
            'maLopMatrix' => $maLopMatrix,
            'listBuoiHocMatrix' => $listBuoiHocMatrix,
            'listHocSinhMatrix' => $listHocSinhMatrix,
            'matrixData' => $matrixData,
            'listBuoiHocWithStatus' => $listBuoiHocWithStatus,
            'search' => $search,
            'role' => 'teacher'
        ]);
    }

    // Màn hình Tích chọn điểm danh (Chuyển đến khi bấm nút "Điểm danh" màu xanh lá)
    public function take() {
        $maLop = isset($_REQUEST['ma_lop']) ? intval($_REQUEST['ma_lop']) : null;
        $maBuoi = isset($_REQUEST['ma_buoi']) ? intval($_REQUEST['ma_buoi']) : null;

        if (!$maLop || !$maBuoi) {
            die("<script>alert('Vui lòng chọn đầy đủ Lớp học và Buổi học!'); window.location.href='?url=attendance';</script>");
        }

        // Lấy thông tin lớp học để hiển thị tiêu đề
        require_once __DIR__ . '/../models/LopHoc.php';
        $lopHocModel = new LopHoc();
        $classInfo = $lopHocModel->find($maLop);
        $tenLop = $classInfo ? $classInfo['TenLop'] : '';

        // Tìm thứ tự buổi học (Buổi số mấy) và Ngày học
        $listBuoiHoc = $this->buoiHocModel->getBuoiHocByLop($maLop);
        $buoiIndex = 0;
        $ngayHoc = '';
        foreach ($listBuoiHoc as $index => $b) {
            if ($b['MaBuoi'] == $maBuoi) {
                $buoiIndex = $index + 1;
                $ngayHoc = $b['NgayHoc'];
                break;
            }
        }

        $listHocSinh = $this->diemDanhModel->getHocSinhByLop($maLop);
        $rawDiemDanh = $this->diemDanhModel->getDiemDanhByLop($maLop);

        $currentStatus = [];
        foreach ($rawDiemDanh as $row) {
            if ($row['MaBuoi'] == $maBuoi) {
                $currentStatus[$row['MaHocSinh']] = $row['TrangThaiDiemDanh'];
            }
        }

        $this->view('attendance/take', [
            'maLop' => $maLop,
            'maBuoi' => $maBuoi,
            'tenLop' => $tenLop,
            'buoiIndex' => $buoiIndex,
            'ngayHoc' => $ngayHoc,
            'listHocSinh' => $listHocSinh,
            'currentStatus' => $currentStatus,
            'role' => 'teacher'
        ]);
    }

    // Thực hiện lưu dữ liệu điểm danh vào DB
    public function submitTake() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maLop = intval($_POST['ma_lop']);
            $maBuoi = intval($_POST['ma_buoi']);
            $records = isset($_POST['attendance']) ? $_POST['attendance'] : [];

            foreach ($records as $maHocSinh => $trangThai) {
                $this->diemDanhModel->updateTrangThai($maBuoi, $maHocSinh, $trangThai);
            }

            header("Location: ?url=attendance&view_matrix=" . $maLop . "#matrix-section");
            exit();
        }
    }
}