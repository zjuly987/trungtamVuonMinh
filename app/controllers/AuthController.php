<?php
require_once ROOT . '/app/models/TaiKhoan.php';

class AuthController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new TaiKhoan();
    }

    public function login()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!empty($_SESSION['user'])) {
        $this->redirectByRole($_SESSION['user']['VaiTro']);
    }

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tenDangNhap = trim($_POST['TenDangNhap'] ?? '');
        $matKhau     = trim($_POST['MatKhau'] ?? '');

        $user = $this->model->findByUsername($tenDangNhap);

        if ($user && $matKhau === $user['MatKhau']) {
            $_SESSION['user'] = $user;

            // Lưu mã tài khoản đang đăng nhập
            $_SESSION['MaTaiKhoan'] = $user['MaTaiKhoan'];
            
            $this->redirectByRole($user['VaiTro']);
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    }

    // Load thẳng file login, không qua layout main.php
    require_once ROOT . '/app/views/auth/login.php';
    exit;
}

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: ?url=auth/login');
        exit;
    }

    private function redirectByRole($role)
    {
        if ($role === 'Giáo viên') {
            header('Location: ?url=dashboard/teacher');
        } else {
            header('Location: ?url=dashboard/secretary');
        }
        exit;
    }
}