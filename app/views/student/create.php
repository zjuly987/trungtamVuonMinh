<link rel="stylesheet" href="public/css/teacher.css">

<div class="teacher-content">

    <!-- Breadcrumb -->
    <div class="teacher-breadcrumb">
        <a href="?url=dashboard">Trang chủ</a>
        <span>›</span>
        <a href="?url=student">Quản lý học sinh</a>
        <span>›</span>
        Thêm học sinh
    </div>

    <div class="section-title">Thêm học sinh</div>

    <?php if (!empty($errors['duplicate'])): ?>
        <div style="color:#dc2626; margin-bottom:12px; font-size:0.9rem;">
            ⚠️ <?= htmlspecialchars($errors['duplicate']) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="teacher-form">

            <div class="form-group">
                <label>Họ và tên <span style="color:#dc2626">*</span></label>
                <input
                    type="text"
                    name="TenHocSinh"
                    class="form-control"
                    placeholder="Nhập họ và tên học sinh"
                    value="<?= htmlspecialchars($_POST['TenHocSinh'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Giới tính</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="GioiTinh" value="1"
                            <?= ($_POST['GioiTinh'] ?? '') === '1' ? 'checked' : '' ?>>
                        Nam
                    </label>
                    <label>
                        <input type="radio" name="GioiTinh" value="0"
                            <?= ($_POST['GioiTinh'] ?? '') === '0' ? 'checked' : '' ?>>
                        Nữ
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Ngày sinh <span style="color:#dc2626">*</span></label>
                <input
                    type="date"
                    name="NgaySinh"
                    class="form-control"
                    value="<?= htmlspecialchars($_POST['NgaySinh'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input
                    type="text"
                    name="SoDienThoai"
                    class="form-control"
                    placeholder="Nhập số điện thoại"
                    value="<?= htmlspecialchars($_POST['SoDienThoai'] ?? '') ?>"
                >
            </div>

            <div class="form-group full-width">
                <label>Địa chỉ thường trú</label>
                <input
                    type="text"
                    name="DiaChi"
                    class="form-control"
                    placeholder="Nhập địa chỉ"
                    value="<?= htmlspecialchars($_POST['DiaChi'] ?? '') ?>"
                >
            </div>

        </div>

        <div class="form-actions">
            <a href="?url=student" class="btn-back">← Quay lại</a>
            <div class="action-right">
                <a href="?url=student" class="btn-cancel">Hủy bỏ</a>
                <button class="btn-save" type="submit">💾 Lưu</button>
            </div>
        </div>

    </form>

</div>