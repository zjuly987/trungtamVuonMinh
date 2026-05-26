<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập — Trung tâm Vươn Mình</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-box {
            background: #fff;
            border-radius: 16px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .login-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0B426B;
            text-align: center;
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: 0.85rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 28px;
        }
        .btn-login {
            background-color: #0B426B;
            color: #fff;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
        }
        .btn-login:hover { background-color: #2B547E; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-title">HỆ THỐNG QUẢN LÝ TRUNG TÂM VƯƠN MÌNH</div>
        <div class="login-sub">Vui lòng đăng nhập để tiếp tục</div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2" style="font-size:0.88rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:0.88rem;">Tên đăng nhập</label>
                <input type="text" name="TenDangNhap" class="form-control rounded-pill"
                       placeholder="VD: vt001" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:0.88rem;">Mật khẩu</label>
                <input type="password" name="MatKhau" class="form-control rounded-pill"
                       placeholder="Nhập mật khẩu" required>
            </div>
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
    </div>
</body>
</html>