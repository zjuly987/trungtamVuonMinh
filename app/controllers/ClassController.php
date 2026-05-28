<?php

require_once ROOT . "/app/models/LopHoc.php";
require_once ROOT . "/app/models/HocSinh.php";
require_once ROOT . "/app/models/GiaoVien.php";

class ClassController extends Controller
{
    private $lopHocModel;
    private $hocSinhModel;
    private $giaoVienModel;

    public function __construct()
    {
        $this->lopHocModel   = new LopHoc();
        $this->hocSinhModel  = new HocSinh();
        $this->giaoVienModel = new GiaoVien();
    }

    // ── Danh sách lớp học ──────────────────────────────────────────────────
    public function index()
    {
        $keyword = $_GET['q'] ?? null;
        $error   = null;

        if (isset($_GET['q']) && trim($_GET['q']) === '') {
            $error   = "Vui lòng nhập tiêu chí tìm kiếm!";
            $keyword = null;
        }

        $classes = $this->lopHocModel->getAll($keyword);

        $this->view("class/index", [
            "classes" => $classes,
            "keyword" => $keyword,
            "error"   => $error,
            "role"    => "secretary"
        ]);
    }

    // ── Tạo lớp học ────────────────────────────────────────────────────────
 public function create()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validate ngày bắt đầu
if ($_POST["NgayBatDau"] < date('Y-m-d')) {

    echo "
        <script>
            alert('Ngày bắt đầu không hợp lệ!');
            window.history.back();
        </script>
    ";

    exit;
}

// Validate số buổi
if ((int)$_POST["SoBuoi"] <= 0) {

    echo "
        <script>
            alert('Số buổi phải lớn hơn 0!');
            window.history.back();
        </script>
    ";

    exit;
}

        $siSo = (int)$_POST["SiSo"];

        $students = $_POST["students"] ?? [];

        $soLuongHocSinh = count($students);

        // Validate sĩ số
        if ($siSo <= 0) {

            echo "
                <script>
                    alert('Sĩ số lớp phải lớn hơn 0!');
                    window.history.back();
                </script>
            ";

            exit;
        }

        // Validate vượt sĩ số
        if ($soLuongHocSinh > $siSo) {

            echo "
                <script>
                    alert('Số lượng học sinh vượt quá sĩ số lớp!');
                    window.history.back();
                </script>
            ";

            exit;
        }

        // Validate chưa chọn học sinh
        if (empty($students)) {

            echo "
                <script>
                    alert('Vui lòng chọn học sinh cho lớp!');
                    window.history.back();
                </script>
            ";

            exit;
        }
// ================= VALIDATE =================

$siSo = (int)$_POST["SiSo"];

$students = $_POST["students"] ?? [];

$soLuongHocSinh = count($students);

// Validate sĩ số
if ($siSo <= 0) {

    echo "
        <script>
            alert('Sĩ số lớp phải lớn hơn 0!');
            window.history.back();
        </script>
    ";

    exit;
}

// Validate vượt sĩ số
if ($soLuongHocSinh > $siSo) {

    echo "
        <script>
            alert('Số lượng học sinh vượt quá sĩ số lớp!');
            window.history.back();
        </script>
    ";

    exit;
}

// Validate chưa chọn học sinh
if (empty($students)) {

    echo "
        <script>
            alert('Vui lòng chọn học sinh!');
            window.history.back();
        </script>
    ";

    exit;
}

// Validate giáo viên trùng lịch
$maGiaoVien = $_POST["MaGiaoVien"] ?? null;

if ($maGiaoVien && !empty($_POST["Thu"])) {

    foreach ($_POST["Thu"] as $i => $thu) {

        $ca = $_POST["Ca"][$i] ?? null;

        if (
            $this->lopHocModel->isTeacherBusy(
                $maGiaoVien,
                $thu,
                $ca
            )
        ) {

            echo "
                <script>
                    alert('Giáo viên đã có lớp ở lịch học này!');
                    window.history.back();
                </script>
            ";

            exit;
        }
    }
}

// Validate phòng học trùng lịch
if (!empty($_POST["Thu"])) {

    foreach ($_POST["Thu"] as $i => $thu) {

        $ca = $_POST["Ca"][$i] ?? null;

        $phong = $_POST["MaPhong"][$i] ?? null;

        if (
            $this->lopHocModel->isRoomBusy(
                $thu,
                $ca,
                $phong
            )
        ) {

            echo "
                <script>
                    alert('Phòng học đã được sử dụng ở lịch này!');
                    window.history.back();
                </script>
            ";

            exit;
        }
    }
}
// Validate học sinh trùng lịch
if (!empty($_POST["students"]) && !empty($_POST["Thu"])) {

    foreach ($_POST["students"] as $maHocSinh) {

        foreach ($_POST["Thu"] as $i => $thu) {

            $ca = $_POST["Ca"][$i] ?? null;

            if (
                $this->lopHocModel->isStudentBusy(
                    $maHocSinh,
                    $thu,
                    $ca
                )
            ) {

                echo "
                    <script>
                        alert('Có học sinh bị trùng lịch học!');
                        window.history.back();
                    </script>
                ";

                exit;
            }
        }
    }
}
        // Tạo lớp
        $maLop = $this->lopHocModel->create($_POST);

        // Thêm học sinh
        $this->lopHocModel->addStudents($maLop, $students);

        // Tạo lịch học
        if (!empty($_POST["Thu"])) {

            $this->lopHocModel->createSchedule(
                $maLop,
                $_POST["Thu"],
                $_POST["Ca"],
                $_POST["MaPhong"]
            );

            $this->lopHocModel->taoBuoiHoc(
                $maLop,
                $_POST["NgayBatDau"],
                $_POST["Thu"],
                $_POST["SoBuoi"]
            );
        }

        header("Location: ?url=class");
        exit;
    }

    $students = $this->hocSinhModel->getAll();
    $rooms    = $this->lopHocModel->getAllRooms();
    $teachers = $this->giaoVienModel->getAll();

    $this->view("class/create", [
        "students" => $students,
        "rooms"    => $rooms,
        "teachers" => $teachers,
        "role"     => "secretary"
    ]);
}

    // ── Chi tiết / cập nhật lớp ────────────────────────────────────────────
    public function detail($id = null)
    {
        if (!$id) $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: ?url=class"); exit; }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {

            // Xóa 1 học sinh khỏi lớp
            if ($_POST["action"] === "removeStudent") {
                $this->lopHocModel->removeStudent($id, $_POST["MaHocSinh"]);
                $this->lopHocModel->syncSiSo($id);
                header("Location: ?url=class/detail&id=$id");
                exit;
            }

            // Thêm học sinh vào lớp
           if ($_POST["action"] === "addStudents" && !empty($_POST["MaHocSinh"])) {

    $class = $this->lopHocModel->find($id);

    $studentsCurrent = count($this->lopHocModel->getStudents($id));

    $studentsAdd = count($_POST["MaHocSinh"]);

    // Validate vượt sĩ số
    if (($studentsCurrent + $studentsAdd) > $class["SiSoToiDa"]) {

        echo "
            <script>
                alert('Vượt quá sĩ số tối đa của lớp!');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Validate học sinh trùng lịch
    $schedules = $this->lopHocModel->getSchedule($id);

    foreach ($_POST["MaHocSinh"] as $maHocSinh) {

        foreach ($schedules as $schedule) {

            if (
                $this->lopHocModel->isStudentBusy(
                    $maHocSinh,
                    $schedule["Thu"],
                    $schedule["Ca"]
                )
            ) {

                echo "
                    <script>
                        alert('Có học sinh bị trùng lịch học!');
                        window.history.back();
                    </script>
                ";

                exit;
            }
        }
    }

    $this->lopHocModel->addStudents($id, $_POST["MaHocSinh"]);

    $this->lopHocModel->syncSiSo($id);

    header("Location: ?url=class/detail&id=$id&success=added");

    exit;
}
// Validate học sinh trùng lịch khi sửa lớp
$currentStudents = $this->lopHocModel->getStudents($id);

if (!empty($_POST["Thu"])) {

    foreach ($currentStudents as $student) {

        foreach ($_POST["Thu"] as $i => $thu) {

            $ca = $_POST["Ca"][$i] ?? null;

            if (
                $this->lopHocModel->isStudentBusyForUpdate(
                    $id,
                    $student["MaHocSinh"],
                    $thu,
                    $ca
                )
            ) {

                echo "
                    <script>
                        alert('Có học sinh trong lớp bị trùng lịch học!');
                        window.history.back();
                    </script>
                ";

                exit;
            }
        }
    }
}
            // Cập nhật thông tin chung của lớp
           if ($_POST["action"] === "updateClass") {

    $siSoToiDa = (int)$_POST["SiSoToiDa"];

    $studentsHienTai = count($this->lopHocModel->getStudents($id));

    // Validate sĩ số
    if ($siSoToiDa <= 0) {

        echo "
            <script>
                alert('Sĩ số lớp phải lớn hơn 0!');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Không cho sĩ số nhỏ hơn số học sinh hiện tại
    if ($siSoToiDa < $studentsHienTai) {

        echo "
            <script>
                alert('Sĩ số tối đa không được nhỏ hơn số học sinh hiện tại!');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Validate giáo viên trùng lịch
    $maGiaoVien = $_POST["MaGiaoVien"] ?? null;

    if ($maGiaoVien && !empty($_POST["Thu"])) {

        foreach ($_POST["Thu"] as $i => $thu) {

            $ca = $_POST["Ca"][$i] ?? null;

            if (
                $this->lopHocModel->isTeacherBusyForUpdate(
                    $id,
                    $maGiaoVien,
                    $thu,
                    $ca
                )
            ) {

                echo "
                    <script>
                        alert('Giáo viên đã có lớp ở lịch học này!');
                        window.history.back();
                    </script>
                ";

                exit;
            }
        }
    }

    // Validate phòng học trùng lịch
    if (!empty($_POST["Thu"])) {

        foreach ($_POST["Thu"] as $i => $thu) {

            $ca = $_POST["Ca"][$i] ?? null;

            $phong = $_POST["MaPhong"][$i] ?? null;

            if (
                $this->lopHocModel->isRoomBusyForUpdate(
                    $id,
                    $thu,
                    $ca,
                    $phong
                )
            ) {

                echo "
                    <script>
                        alert('Phòng học đã được sử dụng ở lịch này!');
                        window.history.back();
                    </script>
                ";

                exit;
            }
        }
    }

    // Update lớp
    $this->lopHocModel->update($id, [
        "TenLop"      => $_POST["TenLop"],
        "SoBuoi"      => $_POST["SoBuoi"],
        "SiSoToiDa"   => $siSoToiDa,
        "NgayBatDau"  => $_POST["NgayBatDau"],
        "MaGiaoVien"  => $maGiaoVien ?: null,
    ]);

    // Update lịch học
    $this->lopHocModel->deleteSchedule($id);

    if (!empty($_POST["Thu"])) {

        $this->lopHocModel->createSchedule(
            $id,
            $_POST["Thu"],
            $_POST["Ca"],
            $_POST["MaPhong"]
        );
    }

    header("Location: ?url=class/detail&id=$id&success=updated");
    exit;
}
        }

        // ── GET: load dữ liệu cho view ─────────────────────────────────────
        $class       = $this->lopHocModel->find($id);
        $students    = $this->lopHocModel->getStudents($id);
        $sessions    = $this->lopHocModel->getSessions($id);
        $allStudents = $this->lopHocModel->getStudentsNotInClass($id);
        $teachers    = $this->giaoVienModel->getAll();
        $rooms       = $this->lopHocModel->getAllRooms();
        $schedules   = $this->lopHocModel->getSchedule($id);
        // $schedules trả về array: [['Thu'=>..., 'Ca'=>..., 'MaPhong'=>...], ...]

        $this->view("class/detail", [
            "class"       => $class,
            "students"    => $students,
            "sessions"    => $sessions,
            "allStudents" => $allStudents,
            "teachers"    => $teachers,
            "rooms"       => $rooms,
            "schedules"   => $schedules,
            "role"        => "secretary"
        ]);
    }

    // ── Xóa lớp học ────────────────────────────────────────────────────────
    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["MaLop"])) {
            $maLop    = $_POST["MaLop"];
            $students = $this->lopHocModel->getStudents($maLop);

            if (!empty($students)) {
                header("Location: ?url=class&error=has_students");
                exit;
            }

            $this->lopHocModel->delete($maLop);
        }

        header("Location: ?url=class");
        exit;
    }
}