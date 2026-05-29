<?php

require_once __DIR__ . '/../models/DiemHocTap.php';

class GradeController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new DiemHocTap();
    }

    // DANH SÁCH LỚP
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header("Location:?url=login");
            exit;
        }

        $maTaiKhoan = $_SESSION['user']['MaTaiKhoan'];
        $classes = $this->model->getClassesByAccount($maTaiKhoan);

        $this->view('grade/index', [
            'classes' => $classes,
            'role' => 'teacher'
        ]);
    }

    // NHẬP ĐIỂM (CREATE - chỉ thêm, không sửa)
    public function create()
    {
        $maTaiKhoan = $_SESSION['user']['MaTaiKhoan'];
        $classes = $this->model->getClassesByAccount($maTaiKhoan);

        $maLop = $_GET['malop'] ?? null;
        $students = $maLop ? $this->model->getHocSinhByLop($maLop) : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            foreach ($_POST['data'] as $maHS => $d) {

                // CHỈ INSERT (KHÔNG UPDATE)
                if (($d['DTX'] ?? '') === '' &&
                    ($d['KT'] ?? '') === '' &&
                    ($d['Thi'] ?? '') === '') {
                    continue;
                }

                if (($d['DTX'] ?? 0) > 0) {
                    $this->model->saveDiem($maHS, 'TX', $d['DTX']);
                }

                if (($d['KT'] ?? 0) > 0) {
                    $this->model->saveDiem($maHS, 'KT', $d['KT']);
                }

                if (($d['Thi'] ?? 0) > 0) {
                    $this->model->saveDiem($maHS, 'THI', $d['Thi']);
                }
            }

            header("Location:?url=grade/create&malop=$maLop");
            exit;
        }

        $this->view('grade/create', [
            'classes' => $classes,
            'students' => $students,
            'maLop' => $maLop,
            'role' => 'teacher'
        ]);
    }

    // SỬA ĐIỂM (UPDATE - không insert mới)
    public function edit()
    {
        $maTaiKhoan = $_SESSION['user']['MaTaiKhoan'];
        $classes = $this->model->getClassesByAccount($maTaiKhoan);

        $maLop = $_GET['malop'] ?? null;

        if (!$maLop) {
            header("Location:?url=grade");
            exit;
        }

        $students = $this->model->getHocSinhByLop($maLop);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            foreach ($_POST['data'] as $maHS => $d) {

                $this->model->saveDiem($maHS, 'TX', $d['DTX'] ?? 0);
                $this->model->saveDiem($maHS, 'KT', $d['KT'] ?? 0);
                $this->model->saveDiem($maHS, 'THI', $d['Thi'] ?? 0);
            }

            header("Location:?url=grade/edit&malop=$maLop");
            exit;
        }

        $this->view('grade/edit', [
            'classes' => $classes,
            'students' => $students,
            'maLop' => $maLop,
            'role' => 'teacher'
        ]);
    }
}