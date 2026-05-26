<link rel="stylesheet" href="public/css/teacher.css">

<div class="teacher-content">

<div class="teacher-breadcrumb">

<a href="?url=dashboard">Trang chủ</a>

<span>›</span>

<a href="?url=teacher">
Quản lý giáo viên
</a>

<span>›</span>

Sửa thông tin giáo viên

</div>

<div class="section-title">

Chỉnh sửa thông tin giáo viên

</div>

<form method="POST">

<div class="teacher-form">

<div class="form-group">

<label>Họ và tên giáo viên</label>

<input
type="text"
name="TenGiaoVien"
class="form-control"

value="<?= htmlspecialchars($teacher['TenGiaoVien']) ?>"

required>

</div>

<div class="form-group">

<label>Chuyên môn</label>

<input
type="text"

name="ChuyenMon"

class="form-control"

value="<?= htmlspecialchars($teacher['ChuyenMon']) ?>"

required>

</div>

<div class="form-group">

<label>Số điện thoại</label>

<input
type="text"

name="SoDienThoai"

class="form-control"

value="<?= htmlspecialchars($teacher['SoDienThoai']) ?>"

required>

</div>

<div class="form-group">

<label>Mã tài khoản</label>

<input
type="number"

name="MaTaiKhoan"

class="form-control"

value="<?= htmlspecialchars($teacher['MaTaiKhoan']) ?>"

required>

</div>

<div class="form-group full-width">

<label>Địa chỉ</label>

<input
type="text"

name="DiaChi"

class="form-control"

value="<?= htmlspecialchars($teacher['DiaChi']) ?>"

required>

</div>

</div>

<div class="form-actions">

<a
href="?url=teacher"

class="btn-back">

↩ Quay lại

</a>

<div class="action-right">

<a
href="?url=teacher"

class="btn-cancel">

Hủy bỏ

</a>

<button
type="submit"

class="btn-save">

💾 Lưu thay đổi

</button>

</div>

</div>

</form>

</div>