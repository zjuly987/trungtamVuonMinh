<link rel="stylesheet" href="public/css/class.css">

<?php
// app/views/class/index.php

if (!empty($classes)) {
    usort($classes, function($a, $b) {
        return (int)$a['MaLop'] - (int)$b['MaLop'];
    });
}
?>

<!-- ✅ JS alert: bật popup ngay khi redirect về kèm lỗi has_students -->
<?php if (($_GET['error'] ?? '') === 'has_students'): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('⚠️ Không thể xóa lớp này!\nLớp hiện vẫn còn học sinh. Vui lòng chuyển hoặc xóa hết học sinh trước khi xóa lớp.');
    });
</script>
<?php endif; ?>

<?php if (($_GET['error'] ?? '') === 'active'): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('⚠️ Lớp đang mở, không thể xóa!');
    });
</script>
<?php endif; ?>

<?php if (($_GET['success'] ?? '') === 'deleted'): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('✅ Xóa lớp thành công!');
    });
</script>
<?php endif; ?>

<div class="student-content">

    <!-- Breadcrumb -->
    <div class="student-breadcrumb">
        <a href="?url=dashboard">Trang chủ</a>
        <span>›</span>
        Quản lý lớp học
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-warning" style="font-size:0.88rem;">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <!-- Toolbar -->
    <div class="student-toolbar">
        <form method="GET" action="" style="display:contents;">
            <input type="hidden" name="url" value="class">
            <div class="search-group">
                <input
                    type="text"
                    name="q"
                    placeholder="Nhập tên lớp hoặc giáo viên..."
                    value="<?= htmlspecialchars($keyword ?? '') ?>"
                >
                <button type="submit" class="btn-search">Tra cứu</button>
            </div>
            <a href="?url=class/create" class="btn-add">+ Tạo lớp học</a>
        </form>
    </div>

    <!-- Section title -->
    <div class="section-title">Danh sách lớp học</div>

    <!-- Table -->
    <div class="student-table-wrap">
        <table class="student-table">
            <thead>
                <tr>
                    <th>Mã lớp</th>
                    <th>Tên lớp</th>
                    <th>Số buổi</th>
                    <th>Sĩ số</th>
                    <th>Ngày bắt đầu</th>
                    <th>Giáo viên</th>
                    <th>Lịch học</th>
                    <th>Phòng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($classes)): ?>
                    <tr class="empty-row">
                        <td colspan="9">Không có lớp học nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= htmlspecialchars($class['MaLop']) ?></td>
                        <td><?= htmlspecialchars($class['TenLop']) ?></td>
                        <td><?= htmlspecialchars($class['SoBuoi']) ?></td>
                        <td><?= htmlspecialchars($class['SiSo']) ?></td>
                        <td><?= date('d/m/Y', strtotime($class['NgayBatDau'])) ?></td>
                        <td><?= htmlspecialchars($class['TenGiaoVien'] ?? 'Chưa phân công') ?></td>
                        <td style="font-size:12.5px;"><?= htmlspecialchars($class['LichHoc'] ?? '') ?></td>
                        <td><?= htmlspecialchars($class['DanhSachPhong'] ?? '') ?></td>
                        <td>
                            <div class="action-group">
                                <a href="?url=class/detail&id=<?= $class['MaLop'] ?>" class="btn-edit">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>

                                <form method="POST"
                                      action="?url=class/delete"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa lớp này?')">
                                    <input type="hidden" name="MaLop" value="<?= $class['MaLop'] ?>">
                                    <button type="submit" class="btn-delete">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>