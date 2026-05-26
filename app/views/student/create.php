<div class="container mt-4" style="max-width: 600px;">
    <h2>Thêm Học sinh</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="TenHocSinh" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="NgaySinh" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="DiaChi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="SoDienThoai" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="?url=student/index" class="btn btn-secondary">Hủy</a>
    </form>
</div>