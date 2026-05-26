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

        $errors=[];

        if($_SERVER['REQUEST_METHOD']=="POST"){

            $data=[

                'TenGiaoVien'=>trim($_POST['TenGiaoVien']),
                'ChuyenMon'=>trim($_POST['ChuyenMon']),
                'DiaChi'=>trim($_POST['DiaChi']),
                'SoDienThoai'=>trim($_POST['SoDienThoai']),
                'MaTaiKhoan'=>trim($_POST['MaTaiKhoan'])

            ];

            if(empty($data['TenGiaoVien'])){
                $errors['TenGiaoVien']="Không được để trống";
            }

            if(empty($data['ChuyenMon'])){
                $errors['ChuyenMon']="Không được để trống";
            }

            if(strlen($data['SoDienThoai'])!=10){
                $errors['SoDienThoai']="SĐT phải đủ 10 số";
            }

            if(empty($errors)){

                $this->model->create($data);

                header("Location:?url=teacher");

                exit;
            }
        }

        $this->view(
            'teacher/create',
            [
                'role'=>'secretary',
                'errors'=>$errors
            ]
        );
    }

    public function edit(){

        $id = $_GET['id'] ?? null;

        if(!$id){

            header("Location:?url=teacher");

            exit;
        }

        $teacher = $this->model->getById($id);

        $errors=[];

        if($_SERVER['REQUEST_METHOD']=="POST"){

            $data=[

                'TenGiaoVien'=>trim($_POST['TenGiaoVien']),

                'ChuyenMon'=>trim($_POST['ChuyenMon']),

                'DiaChi'=>trim($_POST['DiaChi']),

                'SoDienThoai'=>trim($_POST['SoDienThoai']),

                'MaTaiKhoan'=>trim($_POST['MaTaiKhoan'])

            ];

            if(empty($errors)){

                $this->model->update(
                    $id,
                    $data
                );

                header("Location:?url=teacher");

                exit;
            }
        }

        $this->view(

            'teacher/edit',

            [

                'role'=>'secretary',

                'activeMenu'=>'teacher',

                'teacher'=>$teacher,

                'errors'=>$errors

            ]

        );
    }

    public function delete()
    {

        $id = $_GET['id'] ?? null;

        if(!$id){

            header(
                "Location:?url=teacher"
            );

            exit;
        }

        if(
            $this->model
            ->isTeacherInClass($id)
        ){

            header(
                "Location:?url=teacher&error=foreign"
            );

            exit;
        }

        $deleted =
            $this->model
            ->delete($id);

        if(!$deleted){

            header(
                "Location:?url=teacher&error=foreign"
            );

            exit;
        }

        header(
            "Location:?url=teacher"
        );

        exit;
    }
}