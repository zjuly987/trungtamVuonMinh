<link rel="stylesheet" href="public/css/attendance.css">

<?php
// Giao diện: Quản lý điểm danh — Đồng bộ phong cách hệ thống
if (!empty($listLopHoc)) {
    usort($listLopHoc, function($a, $b) {
        return (int)$a['MaLop'] - (int)$b['MaLop'];
    });
}

function singleMapClassCode($id, $name) {
    if (strpos($name, 'Hóa học') !== false) return 'HOA' . filter_var($name, FILTER_SANITIZE_NUMBER_INT);
    if (strpos($name, 'Ngữ văn') !== false) {
        $num = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
        $char = (strpos($name, '(A)') !== false) ? 'A' : ((strpos($name, '(B)') !== false) ? 'B' : '');
        return 'VAN' . $num . $char;
    }
    if (strpos($name, 'Toán') !== false) return 'TOAN' . filter_var($name, FILTER_SANITIZE_NUMBER_INT);
    if (strpos($name, 'Tiếng Anh') !== false) return 'ANH' . filter_var($name, FILTER_SANITIZE_NUMBER_INT);
    if (strpos($name, 'Vật lý') !== false) return 'LY' . filter_var($name, FILTER_SANITIZE_NUMBER_INT);
    if (strpos($name, 'Sinh học') !== false) return 'SINH' . filter_var($name, FILTER_SANITIZE_NUMBER_INT);
    if (strpos($name, 'Tiền tiểu học') !== false) return 'TTH' . filter_var($name, FILTER_SANITIZE_NUMBER_INT);
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
                <a href="?url=attendance&view_matrix=<?= $lop['MaLop'] ?>#matrix-section" class="btn-action-view">
                  <i class="bi bi-calendar-check"></i> Tra cứu điểm danh
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- PHẦN XEM CHI TIẾT ĐIỂM DANH LỚP HỌC -->
  <?php if ($maLopMatrix): ?>
    <?php 
      $currentLopIndex = array_search($maLopMatrix, array_column($listLopHoc, 'MaLop'));
      $selectedClassName = $currentLopIndex !== false ? $listLopHoc[$currentLopIndex]['TenLop'] : '';
    ?>
    <div id="matrix-section">
      <!-- 1. BẢNG DANH SÁCH CÁC BUỔI HỌC CỦA LỚP -->
      <div class="detail-card mb-4">
        <div class="detail-card-header">
          <span>📅 DANH SÁCH BUỔI HỌC: <?= htmlspecialchars($selectedClassName) ?></span>
          <span style="font-size: 12.5px; font-weight: normal; opacity: 0.9;">Chọn buổi học để thực hiện hoặc chỉnh sửa điểm danh</span>
        </div>
        <div class="detail-card-body">
          <div class="student-table-wrap" style="margin-bottom:0px;">
            <table class="student-table">
              <thead>
                <tr>
                  <th style="width: 10%;">STT</th>
                  <th style="width: 25%;">Buổi số</th>
                  <th style="width: 25%;">Ngày học</th>
                  <th style="width: 25%;">Trạng thái điểm danh</th>
                  <th style="width: 15%; text-align: center;">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($listBuoiHocWithStatus)): ?>
                  <tr class="empty-row">
                    <td colspan="5">Lớp chưa được lập lịch buổi học.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($listBuoiHocWithStatus as $index => $buoi): ?>
                    <?php 
                      $daDiemDanh = $buoi['SoHocSinhDaDiemDanh'] > 0;
                    ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td class="fw-bold text-dark">Buổi số <?= $index + 1 ?></td>
                      <td class="text-secondary"><?= date('d/m/Y', strtotime($buoi['NgayHoc'])) ?></td>
                      <td>
                        <?php if ($daDiemDanh): ?>
                          <span class="badge-session badge-session-done">
                            <i class="bi bi-check-circle-fill me-1"></i> Đã điểm danh (<?= $buoi['SoHocSinhDaDiemDanh'] ?> học sinh)
                          </span>
                        <?php else: ?>
                          <span class="badge-session badge-session-pending">
                            <i class="bi bi-x-circle-fill me-1"></i> Chưa điểm danh
                          </span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <a href="?url=attendance/take&ma_lop=<?= $maLopMatrix ?>&ma_buoi=<?= $buoi['MaBuoi'] ?>" class="btn-action-take">
                          <i class="bi bi-pencil-square"></i> <?= $daDiemDanh ? 'Sửa' : 'Điểm danh' ?>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- 2. MA TRẬN ĐIỂM DANH LỚP HỌC -->
      <div class="detail-card">
        <div class="detail-card-header">
          <span>📊 MA TRẬN ĐIỂM DANH: <?= htmlspecialchars($selectedClassName) ?></span>
          <div class="d-flex gap-3" style="font-size: 12.5px; font-weight: normal; opacity: 0.95;">
            <div><span class="dot-status dot-present me-1"></span> Có mặt</div>
            <div><span class="dot-status dot-absent me-1"></span> Vắng mặt</div>
            <div><span class="dot-status dot-late me-1"></span> Đi muộn</div>
            <div><span class="dot-status dot-future me-1"></span> Chưa học</div>
          </div>
        </div>
        <div class="detail-card-body">
          <div class="student-table-wrap" style="margin-bottom:0px;">
            <table class="student-table text-center">
              <thead>
                <tr>
                  <th style="width: 8%; text-align: center;">STT</th>
                  <th style="width: 25%; text-align: left;">Họ và tên Học sinh</th>
                  <?php foreach ($listBuoiHocMatrix as $index => $buoi): ?>
                    <th class="text-center" style="font-size: 12px; font-weight: 600;">Buổi <?= $index + 1 ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($listHocSinhMatrix)): ?>
                  <tr class="empty-row">
                    <td colspan="<?= count($listBuoiHocMatrix) + 2 ?>">Chưa có học sinh nào trong lớp.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($listHocSinhMatrix as $iHS => $hs): ?>
                    <tr>
                      <td class="text-center"><?= $iHS + 1 ?></td>
                      <td class="text-start fw-bold text-dark"><?= htmlspecialchars($hs['TenHocSinh']) ?></td>
                      <?php foreach ($listBuoiHocMatrix as $buoi): ?>
                        <td class="text-center">
                          <?php 
                            $status = isset($matrixData[$hs['MaHocSinh']][$buoi['MaBuoi']]) ? $matrixData[$hs['MaHocSinh']][$buoi['MaBuoi']] : 'Chưa học';
                            if ($status === 'Có mặt') {
                                echo '<span class="dot-status dot-present" title="Có mặt"></span>';
                            } elseif ($status === 'Vắng mặt') {
                                echo '<span class="dot-status dot-absent" title="Vắng mặt"></span>';
                            } elseif ($status === 'Đi muộn') {
                                echo '<span class="dot-status dot-late" title="Đi muộn"></span>';
                            } else {
                                echo '<span class="dot-status dot-future" title="Chưa học"></span>';
                            }
                          ?>
                        </td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
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