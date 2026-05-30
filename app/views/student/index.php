<?php if (($_GET['error'] ?? '') === 'in_class'): ?>
<script>
    alert('⚠️ Không thể xóa!\nHọc sinh này đang có trong lớp học.\nVui lòng xóa khỏi lớp trước.');
</script>
<?php endif; ?>

<?php
// Sắp xếp theo MaHocSinh từ nhỏ đến lớn
if (!empty($students)) {
    usort($students, function($a, $b) {
        return (int)$a['MaHocSinh'] - (int)$b['MaHocSinh'];
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
  .student-breadcrumb {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 18px;
  }
  .student-breadcrumb a { color: #3b82f6; text-decoration: none; }
  .student-breadcrumb a:hover { text-decoration: underline; }
  .student-breadcrumb span { margin: 0 6px; color: #9ca3af; }
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
  .student-table thead th:nth-child(1) { width: 100px; }
  .student-table thead th:nth-child(2) { min-width: 200px; }
  .student-table thead th:nth-child(3) { width: 110px; }
  .student-table thead th:nth-child(4) { min-width: 160px; }
  .student-table thead th:nth-child(5) { width: 130px; }
  .student-table thead th:last-child   { width: 120px; text-align: center; }
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
  .action-group { display: flex; gap: 6px; justify-content: center; }
  .btn-edit, .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 13px;
    border-radius: 5px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.15s;
    background: transparent;
  }
  .btn-edit   { color: #1A365D; }
  .btn-delete { color: #dc2626; }
  .btn-edit:hover, .btn-delete:hover { opacity: 0.82; }
  .empty-row td {
    text-align: center;
    color: #9ca3af;
    padding: 40px 0;
    font-style: italic;
  }
</style>

<div class="student-content">

  <div class="student-breadcrumb">
    <a href="?url=dashboard">Trang chủ</a>
    <span>›</span>
    Quản lý học sinh
  </div>

  <div class="student-toolbar">
    <form method="GET" action="" style="display:contents;">
      <input type="hidden" name="url" value="student">
      <div class="search-group">
        <input
          type="text"
          name="search"
          placeholder="Nhập tên học sinh hoặc mã học sinh..."
          value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        >
        <button type="submit" class="btn-search">Tra cứu</button>
      </div>
      <a href="?url=student/create" class="btn-add">+ Thêm học sinh</a>
    </form>
  </div>

  <div class="section-title">Danh sách học sinh</div>

  <div class="student-table-wrap">
    <table class="student-table">
      <thead>
        <tr>
          <th>Mã học sinh</th>
          <th>Họ và Tên</th>
          <th>Ngày sinh</th>
          <th>Địa chỉ</th>
          <th>Số điện thoại liên hệ</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($students)): ?>
          <tr class="empty-row">
            <td colspan="6">Chưa có học sinh nào.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($students as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['MaHocSinh']) ?></td>
            <td><?= htmlspecialchars($s['TenHocSinh']) ?></td>
            <td><?= !empty($s['NgaySinh']) ? date('d/m/Y', strtotime($s['NgaySinh'])) : '' ?></td>
            <td><?= htmlspecialchars($s['DiaChi'] ?? '') ?></td>
            <td><?= htmlspecialchars($s['SoDienThoai'] ?? '') ?></td>
            <td>
              <div class="action-group">
                <a href="?url=student/edit&id=<?= $s['MaHocSinh'] ?>" class="btn-edit">
                  <i class="bi bi-pencil-square"></i> Sửa
                </a>
                <!-- ✅ id nằm trong POST, không phải URL -->
                <form method="POST" action="?url=student/delete"
                      onsubmit="return confirm('Bạn có chắc muốn xóa học sinh này?')">
                  <input type="hidden" name="id" value="<?= $s['MaHocSinh'] ?>">
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