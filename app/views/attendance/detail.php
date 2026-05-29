<link rel="stylesheet" href="public/css/attendance/index.css">

<?php
// Giao diện: Chi tiết điểm danh lớp học — Trang riêng

function detailMapClassCode($id, $name) {
    return $id;
}

$selectedClassName = isset($tenLop) ? $tenLop : '';
?>

<div class="student-content">
  <!-- Breadcrumb -->
  <div class="student-breadcrumb">
    <a href="?url=dashboard/teacher">Trang chủ</a>
    <span>›</span>
    <a href="?url=attendance">Quản lý điểm danh</a>
    <span>›</span>
    Chi tiết điểm danh: <?= htmlspecialchars($selectedClassName) ?>
  </div>

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
                <!-- <th style="width: 25%;">Ngày học</th> -->
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
                    <!-- <td class="text-secondary"><?= date('d/m/Y', strtotime($buoi['NgayHoc'])) ?></td> -->
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
                      <a href="?url=attendance/take&ma_lop=<?= $maLop ?>&ma_buoi=<?= $buoi['MaBuoi'] ?>" class="btn-action-take">
                        <?= $daDiemDanh ? 'Sửa' : 'Điểm danh' ?>
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

    <!-- 2. CHI TIẾT ĐIỂM DANH LỚP HỌC -->
    <div class="detail-card">
      <div class="detail-card-header">
        <span>📊 CHI TIẾT ĐIỂM DANH LỚP HỌC: <?= htmlspecialchars($selectedClassName) ?></span>
        <div class="d-flex gap-3" style="font-size: 12.5px; font-weight: normal; opacity: 0.95;">
          <div><span class="dot-status dot-present me-1"></span> Có mặt</div>
          <div><span class="dot-status dot-absent me-1"></span> Vắng mặt</div>
          <div><span class="dot-status dot-late me-1"></span> Đi muộn</div>
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

  <!-- Nút quay lại -->
  <div class="d-flex justify-content-start mt-3">
    <a href="?url=attendance" class="btn-action-view" style="padding: 8px 18px; font-size: 13.5px;">
      <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách lớp
    </a>
  </div>
</div>
