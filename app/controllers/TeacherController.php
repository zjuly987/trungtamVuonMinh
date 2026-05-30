<?php

require_once __DIR__.'/../models/GiaoVien.php';

class TeacherController extends Controller {

    private $model;

    public function __construct() {
        $this->model = new GiaoVien();
    }

    public function index() {

        $keyword=$_GET['search'] ?? '';

        if(!empty($keyword)){
            $teachers=$this->model->search($keyword);
        }
        else{
            $teachers=$this->model->getAll();
        }

        $this->view(
            'teacher/index',
            [
                'role'=>'secretary',
                'teachers'=>$teachers
            ]
        );
    }

    public function create(){

        $errors = [];
        $old = [];

        if($_SERVER['REQUEST_METHOD'] == "POST"){

            $data = [
                'TenGiaoVien' => trim($_POST['TenGiaoVien'] ?? ''),
                'GioiTinh' => $_POST['GioiTinh'] ?? '',
                'SoDienThoai' => trim($_POST['SoDienThoai'] ?? ''),
                'CCCD' => trim($_POST['CCCD'] ?? ''),
                'TruongDangGiangDay' => trim($_POST['TruongDangGiangDay'] ?? ''),
                'DiaChi' => trim($_POST['DiaChi'] ?? ''),
                'ChuyenMon' => trim($_POST['ChuyenMon'] ?? ''),
                'MaTaiKhoan' => trim($_POST['MaTaiKhoan'] ?? '')
            ];

            $old = $data;

            // VALIDATE
            if(empty($data['TenGiaoVien'])){
                $errors['TenGiaoVien'] = "Họ tên là bắt buộc";
            } elseif (preg_match('/\d/', $data['TenGiaoVien'])) {
                $errors['TenGiaoVien'] = "Họ tên không được chứa số";
            }

            if(empty($data['GioiTinh'])){
                $errors['GioiTinh'] = "Giới tính là bắt buộc";
            }

            if(empty($data['SoDienThoai'])){
                $errors['SoDienThoai'] = "SĐT là bắt buộc";
            } elseif (!preg_match('/^\d+$/', $data['SoDienThoai'])) {
                $errors['SoDienThoai'] = "SĐT chỉ được nhập số";
            } elseif (strlen($data['SoDienThoai']) != 10) {
                $errors['SoDienThoai'] = "SĐT phải đủ 10 số";
            }

            if(empty($data['CCCD'])){
                $errors['CCCD'] = "CCCD là bắt buộc";
            }

            if(empty($data['TruongDangGiangDay'])){
                $errors['TruongDangGiangDay'] = "Trường là bắt buộc";
            }

            // CHECK TRÙNG
            if($this->model->existsTeacher($data['CCCD'], $data['SoDienThoai'])){
                $errors['exists'] = "Giáo viên đã tồn tại";
            }

            if(empty($errors)){

                $result = $this->model->create($data);

                if($result === "duplicate"){
                    $errors['exists'] = "Giáo viên đã tồn tại";
                } else {
                    header("Location:?url=teacher&success=1");
                    exit;
                }
            }
        }

        $this->view('teacher/create', [
            'role' => 'secretary',
            'errors' => $errors,
            'old' => $old
        ]);
    }

    public function edit(){

        $id = $_GET['id'] ?? null;

        if(!$id){
            header("Location:?url=teacher");
            exit;
        }

        $teacher = $this->model->getById($id);

        $errors = [];
        $old = $teacher;

        if($_SERVER['REQUEST_METHOD'] == "POST"){

        $data = [
            'TenGiaoVien' => trim($_POST['TenGiaoVien'] ?? ''),
            'GioiTinh' => $_POST['GioiTinh'] ?? '',
            'SoDienThoai' => trim($_POST['SoDienThoai'] ?? ''),
            'CCCD' => trim($_POST['CCCD'] ?? ''),
            'TruongDangGiangDay' => trim($_POST['TruongDangGiangDay'] ?? ''),
            'DiaChi' => trim($_POST['DiaChi'] ?? ''),
            'ChuyenMon' => trim($_POST['ChuyenMon'] ?? ''),
            // KHÔNG NHẬN MaTaiKhoan TỪ FORM
        ];

            $old = $data;

            // VALIDATE
            if(empty($data['TenGiaoVien'])){
                $errors['TenGiaoVien'] = "Họ tên là bắt buộc";
            } elseif (preg_match('/\d/', $data['TenGiaoVien'])){
                $errors['TenGiaoVien'] = "Họ tên không được chứa số";
            }

            if(empty($data['GioiTinh'])){
                $errors['GioiTinh'] = "Giới tính là bắt buộc";
            }

            if(empty($data['SoDienThoai'])){
                $errors['SoDienThoai'] = "SĐT là bắt buộc";
            } elseif (!preg_match('/^\d+$/', $data['SoDienThoai'])){
                $errors['SoDienThoai'] = "SĐT chỉ được nhập số";
            } elseif (strlen($data['SoDienThoai']) != 10){
                $errors['SoDienThoai'] = "SĐT phải đủ 10 số";
            }

            if(empty($data['CCCD'])){
                $errors['CCCD'] = "CCCD là bắt buộc";
            }

            if(empty($data['TruongDangGiangDay'])){
                $errors['TruongDangGiangDay'] = "Trường là bắt buộc";
            }

            // CHECK TRÙNG (TRỪ CHÍNH NÓ)
            if($this->model->existsTeacherEdit($data['CCCD'], $data['SoDienThoai'], $id)){
                $errors['exists'] = "Giáo viên đã tồn tại";
            }

            if(empty($errors)){

                $this->model->update($id, $data);

                header("Location:?url=teacher&success=updated");
                exit;
            }
        }

        $this->view('teacher/edit', [
            'role' => 'secretary',
            'teacher' => $teacher,
            'old' => $old,
            'errors' => $errors
        ]);
    }

    // ✔ CHECK TRƯỚC KHI XÓA (AJAX)
    public function checkDelete()
    {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        $inClass = $this->model->isTeacherInClass($id);

        echo json_encode(['inClass' => $inClass]);
        exit;
    }

    // ✔ XÓA THẬT
    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if(!$id){
            header("Location:?url=teacher");
            exit;
        }

        $this->model->delete($id);

        header("Location:?url=teacher&success=deleted");
        exit;
    }
}