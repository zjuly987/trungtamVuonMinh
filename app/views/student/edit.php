<link rel="stylesheet" href="public/css/teacher.css">

<div class="teacher-content">

    <div class="teacher-breadcrumb">
        <a href="?url=dashboard">Trang chủ</a>
        <span>›</span>
        <a href="?url=student">Quản lý học sinh</a>
        <span>›</span>
        Sửa học sinh
    </div>

    <div class="section-title">Sửa thông tin học sinh</div>

    <form method="POST">

        <div class="teacher-form">

            <!-- Họ tên -->
            <div class="form-group">
                <label>Họ và tên <span style="color:#dc2626">*</span></label>
                <input type="text" name="TenHocSinh"
                    class="form-control <?= !empty($errors['TenHocSinh']) ? 'is-invalid' : '' ?>"
                    placeholder="Nhập họ và tên học sinh"
                    value="<?= htmlspecialchars($_POST['TenHocSinh'] ?? $student['TenHocSinh'] ?? '') ?>">
                <?php if (!empty($errors['TenHocSinh'])): ?>
                    <span class="error-msg">⚠ <?= $errors['TenHocSinh'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Giới tính -->
            <div class="form-group">
                <label>Giới tính</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="GioiTinh" value="1"
                            <?= (($_POST['GioiTinh'] ?? $student['GioiTinh'] ?? '1')) == '1' ? 'checked' : '' ?>> Nam
                    </label>
                    <label>
                        <input type="radio" name="GioiTinh" value="0"
                            <?= (($_POST['GioiTinh'] ?? $student['GioiTinh'] ?? '')) == '0' ? 'checked' : '' ?>> Nữ
                    </label>
                </div>
            </div>

            <!-- Ngày sinh -->
            <div class="form-group">
                <label>Ngày sinh <span style="color:#dc2626">*</span></label>
                <input type="date" name="NgaySinh"
                    class="form-control <?= !empty($errors['NgaySinh']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($_POST['NgaySinh'] ?? $student['NgaySinh'] ?? '') ?>">
                <?php if (!empty($errors['NgaySinh'])): ?>
                    <span class="error-msg">⚠ <?= $errors['NgaySinh'] ?></span>
                <?php endif; ?>
            </div>

            <!-- SĐT học sinh -->
            <div class="form-group">
                <label>Số điện thoại liên hệ</label>
                <input type="text" name="SoDienThoai"
                    class="form-control <?= !empty($errors['SoDienThoai']) ? 'is-invalid' : '' ?>"
                    placeholder="Nhập số điện thoại"
                    value="<?= htmlspecialchars($_POST['SoDienThoai'] ?? $student['SoDienThoai'] ?? '') ?>">
                <?php if (!empty($errors['SoDienThoai'])): ?>
                    <span class="error-msg">⚠ <?= $errors['SoDienThoai'] ?></span>
                <?php endif; ?>
            </div>

            <!-- CCCD -->
            <div class="form-group">
                <label>CCCD</label>
                <input type="text" name="CCCD"
                    class="form-control <?= !empty($errors['CCCD']) ? 'is-invalid' : '' ?>"
                    placeholder="Nhập số CCCD"
                    value="<?= htmlspecialchars($_POST['CCCD'] ?? $student['CCCD'] ?? '') ?>">
                <?php if (!empty($errors['CCCD'])): ?>
                    <span class="error-msg">⚠ <?= $errors['CCCD'] ?></span>
                <?php endif; ?>
            </div>

          

            <!-- Trường đang học -->
            <div class="form-group">
                <label>Trường đang học</label>
                <input type="text" name="Truong"
                    class="form-control"
                    placeholder="Nhập tên trường"
                    value="<?= htmlspecialchars($_POST['Truong'] ?? $student['Truong'] ?? '') ?>">
            </div>

            <!-- Lớp tại trường -->
            <div class="form-group">
                <label>Học sinh lớp</label>
                <input type="text" name="LopTruong"
                    class="form-control"
                    placeholder="VD: 10A1, 11B2..."
                    value="<?= htmlspecialchars($_POST['LopTruong'] ?? $student['LopTruong'] ?? '') ?>">
            </div>

            <!-- Địa chỉ -->
            <div class="form-group full-width">
                <label>Địa chỉ thường trú</label>
                <input type="text" name="DiaChi"
                    class="form-control <?= !empty($errors['DiaChi']) ? 'is-invalid' : '' ?>"
                    placeholder="Nhập địa chỉ"
                    value="<?= htmlspecialchars($_POST['DiaChi'] ?? $student['DiaChi'] ?? '') ?>">
                <?php if (!empty($errors['DiaChi'])): ?>
                    <span class="error-msg">⚠ <?= $errors['DiaChi'] ?></span>
                <?php endif; ?>
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

<style>
.error-msg {
    display: block;
    color: #dc2626;
    font-size: 0.82rem;
    margin-top: 4px;
}
.form-control.is-invalid {
    border-color: #dc2626 !important;
    background-color: #fff8f8;
}
</style>