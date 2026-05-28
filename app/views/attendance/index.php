<link rel="stylesheet" href="public/css/attendance/index.css">

<?php
// Giao diện: Quản lý điểm danh — Đồng bộ phong cách hệ thống
if (!empty($listLopHoc)) {
    usort($listLopHoc, function($a, $b) {
        return (int)$a['MaLop'] - (int)$b['MaLop'];
    });
}

function singleMapClassCode($id, $name) {
    return $id; 
}
?>

<div class="student-content">
  <!-- Breadcrumb -->
  <div class="student-breadcrumb">
    <a href="?url=dashboard/teacher">Trang chủ</a>
    <span>›</span>
    Quản lý điểm danh
  </div>

  <!-- Toolbar: 2 dropdowns -->
  <div class="student-toolbar">
    <form method="GET" action="" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; width:100%;">
      <input type="hidden" name="url" value="attendance/take">
      
      <!-- Dropdown Chọn lớp -->
      <div class="search-group" style="max-width: 250px; flex: 1;">
        <select name="ma_lop" id="select-lop" class="form-select" style="width: 100%; padding: 8px 14px; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 13.5px; background-color: #f9fafb;" required>
          <option value="">-- Chọn lớp học --</option>
          <?php foreach ($listLopHoc as $lop): ?>
            <option value="<?= $lop['MaLop'] ?>"><?= htmlspecialchars($lop['TenLop']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Dropdown Chọn buổi -->
      <div class="search-group" style="max-width: 250px; flex: 1;">
        <select name="ma_buoi" id="select-buoi" class="form-select" style="width: 100%; padding: 8px 14px; border: 1.5px solid #d1d5db; border-radius: 6px; font-size: 13.5px; background-color: #f9fafb;" required disabled>
          <option value="">-- Chọn buổi học --</option>
        </select>
      </div>

      <button type="submit" class="btn-search">
        <i class="bi bi-pencil-square me-1"></i> Điểm danh
      </button>
    </form>
  </div>

  <!-- Section title -->
  <div class="section-title">Danh sách lớp học</div>

  <!-- Table -->
  <div class="student-table-wrap">
    <table class="student-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã lớp</th>
          <th>Tên lớp</th>
          <th>Lịch học</th>
          <th class="text-center">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($listLopHoc)): ?>
          <tr class="empty-row">
            <td colspan="5">Không tìm thấy lớp học nào.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($listLopHoc as $i => $lop): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td class="fw-bold text-secondary"><?= singleMapClassCode($lop['MaLop'], $lop['TenLop']) ?></td>
              <td class="fw-bold text-dark"><?= htmlspecialchars($lop['TenLop']) ?></td>
              <td><?= htmlspecialchars($lop['LichHoc']) ?></td>
              <td class="text-center">
                <a href="?url=attendance/detail&ma_lop=<?= $lop['MaLop'] ?>" class="btn-action-view">
                  <i class="bi bi-calendar-check"></i> Tra cứu điểm danh
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
const allBuoiHoc = <?= json_encode($allBuoiHoc) ?>;

document.getElementById('select-lop').addEventListener('change', function() {
    const maLop = this.value;
    const selectBuoi = document.getElementById('select-buoi');
    
    // Reset và xóa các option cũ
    selectBuoi.innerHTML = '<option value="">-- Chọn buổi học --</option>';
    
    if (maLop) {
        // Lọc các buổi học của lớp này
        const filtered = allBuoiHoc.filter(b => b.MaLop == maLop);
        
        filtered.forEach((b, index) => {
            const opt = document.createElement('option');
            opt.value = b.MaBuoi;
            
            // Định dạng ngày hiển thị nếu có
            let dateStr = '';
            if (b.NgayHoc) {
                const d = new Date(b.NgayHoc);
                if (!isNaN(d.getTime())) {
                    const day = String(d.getDate()).padStart(2, '0');
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const year = d.getFullYear();
                    dateStr = ` (${day}/${month}/${year})`;
                }
            }
            
            opt.textContent = `Buổi số ${index + 1}${dateStr}`;
            selectBuoi.appendChild(opt);
        });
        
        selectBuoi.disabled = false;
    } else {
        selectBuoi.disabled = true;
    }
});
</script>