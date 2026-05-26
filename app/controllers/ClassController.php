<?php

require_once ROOT . "/app/models/LopHoc.php";
require_once ROOT . "/app/models/HocSinh.php";
//require_once ROOT . "/app/models/GiaoVien.php";

class ClassController extends Controller
{
    private $lopHocModel;
    private $hocSinhModel;
 //   private $giaoVienModel;

    public function __construct()
    {
        $this->lopHocModel  = new LopHoc();
        $this->hocSinhModel = new HocSinh();
      //  $this->giaoVienModel = new GiaoVien();
    }

    // Danh sách lớp học
    public function index()
{
    $keyword = $_GET['q'] ?? null;
    $error = null;

    // Nếu có submit tìm kiếm mà ô trống
    if (isset($_GET['q']) && trim($_GET['q']) === '') {
        $error = "Vui lòng nhập tiêu chí tìm kiếm!";
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

    // Form tạo lớp + xử lý POST
   public function create()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $maLop = $this->lopHocModel->create($_POST);

        if (!empty($_POST["students"])) {
            $this->lopHocModel->addStudents($maLop, $_POST["students"]);
        }

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
    $teachers = []; // tạm để trống, chờ Châm làm GiaoVien xong

    $this->view("class/create", [
        "students" => $students,
        "rooms"    => $rooms,
        "teachers" => $teachers,
        "role"     => "secretary"
    ]);
}

    // Chi tiết / cập nhật lớp
   public function detail($id = null)
{
    if (!$id) $id = $_GET['id'] ?? null;
    if (!$id) { header("Location: ?url=class"); exit; }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
       if ($_POST["action"] === "removeStudent") {
    $this->lopHocModel->removeStudent($id, $_POST["MaHocSinh"]);
    $this->lopHocModel->syncSiSo($id); // thêm dòng này
    header("Location: ?url=class/detail&id=$id");
    exit;
}

if ($_POST["action"] === "addStudents" && !empty($_POST["MaHocSinh"])) {
    if ($this->lopHocModel->isFull($id)) {
        // Lớp đầy, quay lại kèm thông báo
        header("Location: ?url=class/detail&id=$id&error=full");
        exit;
    }
    $this->lopHocModel->addStudents($id, $_POST["MaHocSinh"]);
    $this->lopHocModel->syncSiSo($id);
    header("Location: ?url=class/detail&id=$id&success=added");
    exit;
}
    }

    $class       = $this->lopHocModel->find($id);
    $students    = $this->lopHocModel->getStudents($id);
    $sessions    = $this->lopHocModel->getSessions($id);
    $allStudents = $this->lopHocModel->getStudentsNotInClass($id);

    $this->view("class/detail", [
        "class"       => $class,
        "students"    => $students,
        "sessions"    => $sessions,
        "allStudents" => $allStudents,
        "role"        => "secretary"
    ]);
}

    // Xóa lớp học
    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["MaLop"])) {
            $this->lopHocModel->delete($_POST["MaLop"]);
        }
        header("Location: /trungtamVuonMinh/class");
        exit;
    }
}