<?php
// app/views/class/index.php

if (!empty($classes)) {
    usort($classes, function($a, $b) {
        return (int)$a['MaLop'] - (int)$b['MaLop'];
    });
}
?>

<style>
.student-content {
    padding: 24px 28px;
    background: #fff;
    min-height: 100%;
    font-family: 'Segoe UI', sans-serif;
}

/* Breadcrumb */
.student-breadcrumb {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 18px;
}
.student-breadcrumb a { color: #3b82f6; text-decoration: none; }
.student-breadcrumb a:hover { text-decoration: underline; }
.student-breadcrumb span { margin: 0 6px; color: #9ca3af; }

/* Toolbar */
.student-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 12px;
    flex-wrap: wrap;
}
.search-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    max-width: 420px;
}
.search-group input {
    flex: 1;
    padding: 8px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    font-size: 13.5px;
    color: #374151;
    outline: none;
    background: #f9fafb;
    transition: border-color 0.2s;
}
.search-group input:focus { border-color: #3b82f6; background: #fff; }
.search-group input::placeholder { color: #9ca3af; }

.btn-search {
    padding: 8px 20px;
    background: #22c55e;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
}
.btn-search:hover { background: #16a34a; }

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: #fff;
    color: #374151;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-add:hover { background: #f0f7ff; border-color: #3b82f6; color: #2563eb; }

/* Section title */
.section-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e40af;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 2px solid #e5e7eb;
}

/* Table */
.student-table-wrap {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}
.student-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}
.student-table thead tr { background: #1e3a5f; color: #fff; }
.student-table thead th {
    padding: 11px 14px;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.02em;
    text-align: left;
    white-space: nowrap;
}
.student-table thead th:first-child { width: 52px; text-align: center; }
.student-table thead th:last-child  { width: 130px; text-align: center; }

.student-table tbody tr { border-bottom: 1px solid #f0f0f0; transition: background 0.15s; }
.student-table tbody tr:last-child { border-bottom: none; }
.student-table tbody tr:hover { background: #f0f7ff; }
.student-table tbody tr:nth-child(even) { background: #f8fafc; }
.student-table tbody tr:nth-child(even):hover { background: #e8f2ff; }

.student-table tbody td {
    padding: 10px 14px;
    color: #374151;
    vertical-align: middle;
}
.student-table tbody td:first-child { text-align: center; color: #6b7280; font-weight: 600; }

/* Action buttons */
.action-group { display: flex; gap: 6px; justify-content: center; align-items: center; }

.btn-edit, .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0;
    border: none;
    background: transparent;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}
.btn-edit   { color: #1A365D; }
.btn-delete { color: #dc2626; }
.btn-edit:hover, .btn-delete:hover { opacity: 0.8; }

.empty-row td {
    text-align: center;
    color: #9ca3af;
    padding: 40px 0;
    font-style: italic;
}
</style>

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