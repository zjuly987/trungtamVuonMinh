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
<label>Trường đang giảng dạy</label>
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
    <select name="ChuyenMon" class="form-control">
        <option value="">-- Chọn chuyên môn --</option>
        <option value="Toán cấp 2"
            <?= ($old['ChuyenMon'] ?? '') == 'Toán cấp 2' ? 'selected' : '' ?>>
            Toán cấp 2
        </option>
        <option value="Toán cấp 3"
            <?= ($old['ChuyenMon'] ?? '') == 'Toán cấp 3' ? 'selected' : '' ?>>
            Toán cấp 3
        </option>
        <option value="Vật lý cấp 2"
            <?= ($old['ChuyenMon'] ?? '') == 'Vật lý cấp 2' ? 'selected' : '' ?>>
            Vật lý cấp 2
        </option>
        <option value="Vật lý cấp 3"
            <?= ($old['ChuyenMon'] ?? '') == 'Vật lý cấp 3' ? 'selected' : '' ?>>
            Vật lý cấp 3
        </option>
        <option value="Ngữ văn cấp 2"
            <?= ($old['ChuyenMon'] ?? '') == 'Ngữ văn cấp 2' ? 'selected' : '' ?>>
            Ngữ văn cấp 2
        </option>
        <option value="Ngữ văn cấp 3"
            <?= ($old['ChuyenMon'] ?? '') == 'Ngữ văn cấp 3' ? 'selected' : '' ?>>
            Ngữ văn cấp 3
        </option>
        <option value="Hóa học cấp 2"
            <?= ($old['ChuyenMon'] ?? '') == 'Hóa học cấp 2' ? 'selected' : '' ?>>
            Hóa học cấp 2
        </option>
        <option value="Hóa học cấp 3"
            <?= ($old['ChuyenMon'] ?? '') == 'Hóa học cấp 3' ? 'selected' : '' ?>>
            Hóa học cấp 3
        </option>
        <option value="Tiếng Anh cấp 2"
            <?= ($old['ChuyenMon'] ?? '') == 'Tiếng Anh cấp 2' ? 'selected' : '' ?>>
            Tiếng Anh cấp 2
        </option>
        <option value="Tiếng Anh cấp 3"
            <?= ($old['ChuyenMon'] ?? '') == 'Tiếng Anh cấp 3' ? 'selected' : '' ?>>
            Tiếng Anh cấp 3
        </option>
        <option value="Tiền tiểu học"
            <?= ($old['ChuyenMon'] ?? '') == 'Tiền tiểu học' ? 'selected' : '' ?>>
            Tiền tiểu học
        </option>

        <option value="Toán, Tiếng việt cấp 1"
            <?= ($old['ChuyenMon'] ?? '') == 'Toán, Tiếng việt cấp 1' ? 'selected' : '' ?>>
            Toán, Tiếng việt cấp 1
        </option>
        <option value="Sinh học cấp 2, cấp 3"
            <?= ($old['ChuyenMon'] ?? '') == 'Sinh học cấp 2, cấp 3' ? 'selected' : '' ?>>
            Sinh học cấp 2, cấp 3
        </option>
    </select>

    <small style="color:red">
        <?= $errors['ChuyenMon'] ?? '' ?>
    </small>
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