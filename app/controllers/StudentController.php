<?php
require_once __DIR__ . '/../models/HocSinh.php';

class StudentController extends Controller
{
    private $model;

    public function __construct() {
        $this->model = new HocSinh();
    }

    public function index() {
        $keyword = $_GET['search'] ?? '';

        if (!empty($keyword)) {
            $students = $this->model->search($keyword);
        } else {
            $students = $this->model->getAll();
        }

        $this->view('student/index', [
            'role' => 'secretary',
            'students' => $students
        ]);
    }

    public function create() {

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'TenHocSinh'  => trim($_POST['TenHocSinh']),
                'NgaySinh'    => trim($_POST['NgaySinh']),
                'DiaChi'      => trim($_POST['DiaChi']),
                'SoDienThoai' => trim($_POST['SoDienThoai']),
            ];

            // ===== VALIDATE =====

            // Họ tên
            if (empty($data['TenHocSinh'])) {
                $errors['TenHocSinh'] = 'Họ tên là trường bắt buộc';
            }
            elseif (preg_match('/[0-9]/', $data['TenHocSinh'])) {
                $errors['TenHocSinh'] = 'Họ tên không được chứa chữ số';
            }

            // Ngày sinh
            if (empty($data['NgaySinh'])) {
                $errors['NgaySinh'] = 'Ngày sinh là trường bắt buộc';
            }
            elseif (strtotime($data['NgaySinh']) > time()) {
                $errors['NgaySinh'] = 'Ngày sinh không hợp lệ';
            }

            // Địa chỉ
            if (empty($data['DiaChi'])) {
                $errors['DiaChi'] = 'Địa chỉ là trường bắt buộc';
            }

            // Số điện thoại
            if (empty($data['SoDienThoai'])) {
                $errors['SoDienThoai'] = 'Số điện thoại là trường bắt buộc';
            }
            elseif (!ctype_digit($data['SoDienThoai'])) {
                $errors['SoDienThoai'] = 'Số điện thoại chỉ được nhập số';
            }
            elseif (strlen($data['SoDienThoai']) != 10) {
                $errors['SoDienThoai'] = 'Số điện thoại phải gồm đúng 10 số';
            }

            // ===== NẾU KHÔNG CÓ LỖI =====
if (empty($errors)) {

    // kiểm tra trùng học sinh
    if ($this->model->isDuplicate($data)) {

        $errors['duplicate'] = 'Học sinh đã tồn tại trong hệ thống';

    } else {

        $this->model->create($data);

        header('Location: ?url=student/index');
        exit;
    }
}
        }

        $this->view('student/create', [
            'role' => 'secretary',
            'errors' => $errors
        ]);
    }

    public function edit() {

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: ?url=student/index');
            exit;
        }

        $student = $this->model->getById($id);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'TenHocSinh'  => trim($_POST['TenHocSinh']),
                'NgaySinh'    => trim($_POST['NgaySinh']),
                'DiaChi'      => trim($_POST['DiaChi']),
                'SoDienThoai' => trim($_POST['SoDienThoai']),
            ];

            // ===== VALIDATE =====

            // Họ tên
            if (empty($data['TenHocSinh'])) {
                $errors['TenHocSinh'] = 'Họ tên là trường bắt buộc';
            }
            elseif (preg_match('/[0-9]/', $data['TenHocSinh'])) {
                $errors['TenHocSinh'] = 'Họ tên không được chứa chữ số';
            }

            // Ngày sinh
            if (empty($data['NgaySinh'])) {
                $errors['NgaySinh'] = 'Ngày sinh là trường bắt buộc';
            }
            elseif (strtotime($data['NgaySinh']) > time()) {
                $errors['NgaySinh'] = 'Ngày sinh không hợp lệ';
            }

            // Địa chỉ
            if (empty($data['DiaChi'])) {
                $errors['DiaChi'] = 'Địa chỉ là trường bắt buộc';
            }

            // Số điện thoại
            if (empty($data['SoDienThoai'])) {
                $errors['SoDienThoai'] = 'Số điện thoại là trường bắt buộc';
            }
            elseif (!ctype_digit($data['SoDienThoai'])) {
                $errors['SoDienThoai'] = 'Số điện thoại chỉ được nhập số';
            }
            elseif (strlen($data['SoDienThoai']) != 10) {
                $errors['SoDienThoai'] = 'Số điện thoại phải gồm đúng 10 số';
            }

            // ===== UPDATE =====
            if (empty($errors)) {

                $this->model->update($id, $data);

                header('Location: ?url=student/index');
                exit;
            }
        }

        $this->view('student/edit', [
            'role' => 'secretary',
            'student' => $student,
            'errors' => $errors
        ]);
    }

  public function delete()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["MaLop"])) {
        if ($this->lopHocModel->isActive($_POST["MaLop"])) {
            header("Location: ?url=class&error=active");
            exit;
        }
        $this->lopHocModel->delete($_POST["MaLop"]);
        header("Location: ?url=class&success=deleted");
        exit;
    }
    header("Location: ?url=class");
    exit;
}
}