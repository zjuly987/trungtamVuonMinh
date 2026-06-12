<link rel="stylesheet" href="public/css/attendance/take.css">

<div class="student-content">
  <!-- Breadcrumb -->
  <div class="student-breadcrumb">
    <a href="?url=dashboard">Trang chủ</a>
    <span>›</span>
    <a href="?url=attendance">Quản lý điểm danh</a>
    <span>›</span>
    Thực hiện điểm danh
  </div>

  <!-- Section title -->
  <div class="section-title">
    📝 THỰC HIỆN ĐIỂM DANH — Lớp: <?= htmlspecialchars($tenLop) ?> — Buổi <?= $buoiIndex ?> 
  </div>

  <!-- Form -->
  <form action="?url=attendance/submitTake" method="POST">
    <input type="hidden" name="ma_lop" value="<?= $maLop ?>">
    <input type="hidden" name="ma_buoi" value="<?= $maBuoi ?>">

    <!-- Table -->
    <div class="student-table-wrap">
      <table class="student-table">
        <thead>
          <tr>
            <th style="width: 10%; text-align: center;">STT</th>
            <th style="width: 40%;">Tên Học sinh</th>
            <th style="width: 50%; text-align: center;">Trạng thái điểm danh</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($listHocSinh)): ?>
            <tr class="empty-row">
              <td colspan="3">Chưa có học sinh nào trong lớp này để điểm danh.</td>
            </tr>
          <?php else: ?>
            <?php $stt = 1; foreach ($listHocSinh as $hs): ?>
              <?php $savedStatus = isset($currentStatus[$hs['MaHocSinh']]) ? $currentStatus[$hs['MaHocSinh']] : 'Có mặt'; ?>
              <tr>
                <td class="text-center"><?= $stt++ ?></td>
                <td class="fw-bold text-dark"><?= htmlspecialchars($hs['TenHocSinh']) ?></td>
                <td>
                  <div class="attendance-options">
                    <!-- Có mặt -->
                    <label class="attendance-option">
                      <input type="radio" name="attendance[<?= $hs['MaHocSinh'] ?>]" value="Có mặt" <?= $savedStatus === 'Có mặt' ? 'checked' : '' ?>>
                      <span class="attendance-label attendance-label-present">
                        <i class="bi bi-check-circle-fill me-1"></i> Có mặt
                      </span>
                    </label>
                    
                    <!-- Vắng mặt -->
                    <label class="attendance-option">
                      <input type="radio" name="attendance[<?= $hs['MaHocSinh'] ?>]" value="Vắng mặt" <?= $savedStatus === 'Vắng mặt' ? 'checked' : '' ?>>
                      <span class="attendance-label attendance-label-absent">
                        <i class="bi bi-x-circle-fill me-1"></i> Vắng mặt
                      </span>
                    </label>
                    
                    <!-- Đi muộn -->
                    <label class="attendance-option">
                      <input type="radio" name="attendance[<?= $hs['MaHocSinh'] ?>]" value="Đi muộn" <?= $savedStatus === 'Đi muộn' ? 'checked' : '' ?>>
                      <span class="attendance-label attendance-label-late">
                        <i class="bi bi-clock-fill me-1"></i> Đi muộn
                      </span>
                    </label>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end gap-2 mt-3">
      <a href="?url=attendance/detail&ma_lop=<?= $maLop ?>" class="btn-cancel">
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        💾 Lưu kết quả điểm danh
      </button>
    </div>
  </form>
</div>