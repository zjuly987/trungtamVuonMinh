<link rel="stylesheet" href="public/css/teacher.css">

<div class="teacher-content">

<div class="teacher-breadcrumb">
<a href="?url=dashboard">Trang chủ</a>
<span>›</span>
<a href="?url=teacher">Quản lý giáo viên</a>
<span>›</span>
Thêm giáo viên
</div>

<div class="section-title">Thêm giáo viên</div>

<?php if(!empty($errors['exists'])): ?>
<div style="color:red; margin-bottom:10px; font-weight:bold;">
    <?= $errors['exists'] ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="teacher-form">

<!-- HỌ TÊN -->
<div class="form-group">
<label>Họ và tên</label>
<input type="text" name="TenGiaoVien" class="form-control"
value="<?= htmlspecialchars($old['TenGiaoVien'] ?? '') ?>">
<small style="color:red"><?= $errors['TenGiaoVien'] ?? '' ?></small>
</div>

<!-- GIỚI TÍNH -->
<div class="form-group">
<label>Giới tính</label>
<div class="radio-group">

<label>
<input type="radio" name="GioiTinh" value="Nam"
<?= (($old['GioiTinh'] ?? '') == 'Nam') ? 'checked' : '' ?>>
Nam
</label>

<label>
<input type="radio" name="GioiTinh" value="Nữ"
<?= (($old['GioiTinh'] ?? '') == 'Nữ') ? 'checked' : '' ?>>
Nữ
</label>

</div>
<small style="color:red"><?= $errors['GioiTinh'] ?? '' ?></small>
</div>

<!-- SĐT -->
<div class="form-group">
<label>Số điện thoại</label>
<input type="text" name="SoDienThoai" class="form-control"
value="<?= htmlspecialchars($old['SoDienThoai'] ?? '') ?>">
<small style="color:red"><?= $errors['SoDienThoai'] ?? '' ?></small>
</div>

<!-- CCCD -->
<div class="form-group">
<label>CCCD</label>
<input type="text" name="CCCD" class="form-control"
value="<?= htmlspecialchars($old['CCCD'] ?? '') ?>">
<small style="color:red"><?= $errors['CCCD'] ?? '' ?></small>
</div>

<!-- TRƯỜNG -->
<div class="form-group">
<label>Trường giảng dạy</label>
<input type="text" name="TruongDangGiangDay" class="form-control"
value="<?= htmlspecialchars($old['TruongDangGiangDay'] ?? '') ?>">
<small style="color:red"><?= $errors['TruongDangGiangDay'] ?? '' ?></small>
</div>

<!-- ĐỊA CHỈ -->
<div class="form-group">
<label>Địa chỉ</label>
<input type="text" name="DiaChi" class="form-control"
value="<?= htmlspecialchars($old['DiaChi'] ?? '') ?>">
</div>

<!-- CHUYÊN MÔN -->
<div class="form-group">
<label>Chuyên môn</label>
<input type="text" name="ChuyenMon" class="form-control"
value="<?= htmlspecialchars($old['ChuyenMon'] ?? '') ?>">
</div>

<!-- TÀI KHOẢN -->
<div class="form-group">
<label>Mã tài khoản</label>
<input type="text" name="MaTaiKhoan" class="form-control"
value="<?= htmlspecialchars($old['MaTaiKhoan'] ?? '') ?>">
</div>

</div>

<div class="form-actions">
<a href="?url=teacher" class="btn-back">← Quay lại</a>

<div class="action-right">
<a href="?url=teacher" class="btn-cancel">Hủy</a>
<button type="submit" class="btn-save">💾 Lưu</button>
</div>
</div>

</form>

</div>