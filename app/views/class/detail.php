<!-- app/views/class/detail.php -->
<!-- Biến từ controller: $class, $students, $allStudents, $teachers, $rooms, $schedules -->
<!-- $schedules: array of ['Thu'=>..., 'Ca'=>..., 'MaPhong'=>...] cho lớp này -->

<?php if (($_GET['error'] ?? '') === 'full'): ?>
    <div class="alert alert-danger">Lớp học đã đạt sĩ số tối đa. Không thể thêm học sinh mới!</div>
<?php endif; ?>

<?php if (($_GET['success'] ?? '') === 'added'): ?>
    <div class="alert alert-success">Thêm học sinh vào lớp thành công!</div>
<?php endif; ?>

<div class="container-fluid mt-3 pe-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3" style="font-size:0.9rem;">
            <li class="breadcrumb-item fw-bold">Trang chủ</li>
            <li class="breadcrumb-item text-muted">
                <a href="?url=class" class="text-decoration-none text-muted">Quản lý lớp học</a>
            </li>
            <li class="breadcrumb-item active text-muted fst-italic">Cập nhật thông tin lớp</li>
        </ol>
    </nav>

    <!-- Thông tin chung (có thể sửa) -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex align-items-center mb-4">
            <div style="width:4px; height:24px; background-color:#2B547E; margin-right:10px; border-radius:2px;"></div>
            <h5 class="fw-bold mb-0 text-dark">Thông tin chung</h5>
        </div>

        <form action="?url=class/detail&id=<?= $class['MaLop'] ?>" method="POST">
            <input type="hidden" name="action" value="updateClass">

            <!-- Hàng 1: Tên lớp, Số buổi, Sĩ số -->
            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Tên lớp <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="TenLop"
                           class="form-control rounded-pill"
                           value="<?= htmlspecialchars($class['TenLop']) ?>"
                           required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Số buổi <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="SoBuoi" min="1"
                           class="form-control rounded-pill text-center"
                           value="<?= $class['SoBuoi'] ?>"
                           required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Sĩ số tối đa <span class="text-danger">*</span>
                        <span class="fw-normal text-muted" style="font-size:0.80rem;">
                            (hiện: <strong><?= $class['SiSo'] ?></strong> học sinh)
                        </span>
                    </label>
                    <input type="number" name="SiSoToiDa" min="1"
                           class="form-control rounded-pill text-center"
                           value="<?= $class['SiSoToiDa'] ?? $class['SiSo'] ?>"
                           required>
                </div>
            </div>

            <!-- Hàng 2: Ngày bắt đầu, Giáo viên -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Ngày bắt đầu <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="NgayBatDau"
                           class="form-control rounded-pill"
                           value="<?= $class['NgayBatDau'] ?>"
                           required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Giáo viên phụ trách
                    </label>
                    <select name="MaGiaoVien" class="form-select rounded-pill">
                        <option value="">— Chưa phân công —</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['MaGiaoVien'] ?>"
                                <?= ($class['MaGiaoVien'] == $teacher['MaGiaoVien']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($teacher['TenGiaoVien']) ?>
                                — <?= htmlspecialchars($teacher['ChuyenMon']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Lịch học động -->
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted d-block" style="font-size:0.88rem;">
                    Lịch học <span class="text-danger">*</span>
                    <span class="fw-normal">(mỗi hàng = 1 buổi/tuần)</span>
                </label>
                <div class="card border rounded-3 p-3" style="background:#f8fafc;">
                    <div id="schedule-rows">
                        <?php
                        // Nếu có lịch cũ thì hiển thị sẵn, nếu không thì tạo 1 hàng trống
                        $existingSchedules = !empty($schedules) ? $schedules : [[]];
                        foreach ($existingSchedules as $idx => $sch):
                        ?>
                        <div class="row g-2 align-items-center mb-2 schedule-item">
                            <div class="col-auto">
                                <span class="badge bg-secondary rounded-pill">Buổi <?= $idx + 1 ?></span>
                            </div>
                            <div class="col-md-3">
                                <select name="Thu[]" class="form-select form-select-sm rounded-pill" required>
                                    <option value="">— Thứ —</option>
                                    <?php foreach (['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'] as $t): ?>
                                        <option value="<?= $t ?>"
                                            <?= (($sch['Thu'] ?? '') === $t) ? 'selected' : '' ?>>
                                            <?= $t ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="Ca[]" class="form-select form-select-sm rounded-pill" required>
                                    <option value="">— Ca —</option>
                                    <?php
                                    $caList = [
                                        'Ca 1' => 'Ca 1 (7h–9h)',
                                        'Ca 2' => 'Ca 2 (9h–11h)',
                                        'Ca 3' => 'Ca 3 (13h–15h)',
                                        'Ca 4' => 'Ca 4 (15h–17h)',
                                        'Ca 5' => 'Ca 5 (17h30–19h30)',
                                    ];
                                    foreach ($caList as $val => $label): ?>
                                        <option value="<?= $val ?>"
                                            <?= (($sch['Ca'] ?? '') === $val) ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="MaPhong[]" class="form-select form-select-sm rounded-pill" required>
                                    <option value="">— Phòng —</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['MaPhong'] ?>"
                                            <?= (($sch['MaPhong'] ?? '') == $room['MaPhong']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($room['TenPhong']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill btn-remove-schedule"
                                        style="display:<?= count($existingSchedules) > 1 ? 'inline-block' : 'none' ?>;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-1">
                        <button type="button" id="btn-add-schedule"
                                class="btn btn-sm rounded-pill px-3"
                                style="background-color:#E2E8F0; color:#1A365D; border:1px solid #cbd5e1; font-size:0.83rem;">
                            + Thêm buổi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Nút lưu -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn text-white rounded-3 px-4 shadow-sm"
                        style="background-color:#2B547E;">
                    💾 Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    <!-- Danh sách học sinh -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold m-0 text-dark text-uppercase" style="font-size:0.88rem; letter-spacing:0.04em;">
            Danh sách học sinh (<span id="soHocSinh"><?= count($students) ?></span> người)
        </h6>
        <button class="btn btn-sm rounded-pill px-3 fw-semibold"
                style="background-color:#E2E8F0; color:#1A365D; border:1px solid #cbd5e1;"
                data-bs-toggle="modal" data-bs-target="#modalAddStudent">
            + Thêm học sinh vào lớp
        </button>
    </div>

    <div class="table-responsive rounded-3 shadow-sm mb-4">
        <table class="table table-bordered table-hover align-middle text-center m-0"
               style="font-size:0.88rem;">
            <thead style="background-color:#0B426B; color:white;">
                <tr>
                    <th style="background-color:#0B426B; color:white; width:55px;">STT</th>
                    <th style="background-color:#0B426B; color:white;">Tên học sinh</th>
                    <th style="background-color:#0B426B; color:white;">Ngày sinh</th>
                    <th style="background-color:#0B426B; color:white;">Địa chỉ</th>
                    <th style="background-color:#0B426B; color:white;">Số điện thoại</th>
                    <th style="background-color:#0B426B; color:white; width:140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="6" class="text-muted fst-italic py-4">
                            Chưa có học sinh nào trong lớp.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $stt = 1; foreach ($students as $student): ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td class="text-start ps-4 fw-semibold">
                                <?= htmlspecialchars($student['TenHocSinh']) ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($student['NgaySinh'])) ?></td>
                            <td class="text-start" style="font-size:0.82rem; color:#555;">
                                <?= htmlspecialchars($student['DiaChi']) ?>
                            </td>
                            <td><?= htmlspecialchars($student['SoDienThoai']) ?></td>
                            <td>
                                <form action="?url=class/detail&id=<?= $class['MaLop'] ?>"
                                      method="POST"
                                      onsubmit="return confirm('Xóa học sinh này khỏi lớp?')">
                                    <input type="hidden" name="action" value="removeStudent">
                                    <input type="hidden" name="MaHocSinh" value="<?= $student['MaHocSinh'] ?>">
                                    <button type="submit"
                                            class="btn btn-link btn-sm text-decoration-none p-0"
                                            style="color:#dc2626; font-size:0.82rem;">
                                        🗑️ Xóa khỏi lớp
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Nút hành động -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="?url=class" class="btn btn-light rounded-pill px-4 shadow-sm border">
            ↩ Quay lại
        </a>
        <form action="?url=class/delete" method="POST"
              onsubmit="return confirm('Xóa lớp này? Hành động không thể hoàn tác.')">
            <input type="hidden" name="MaLop" value="<?= $class['MaLop'] ?>">
            <button type="submit" class="btn rounded-3 px-4 shadow-sm text-dark border"
                    style="background-color:#CBD5E1;">
                🗑️ Xóa lớp
            </button>
        </form>
    </div>
</div>

<!-- Modal thêm học sinh -->
<div class="modal fade" id="modalAddStudent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header" style="background-color:#0B426B;">
                <h5 class="modal-title text-white fw-bold">Thêm học sinh vào lớp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="text" id="searchStudentModal"
                       class="form-control rounded-pill mb-3"
                       placeholder="Tìm theo tên...">

                <form action="?url=class/detail&id=<?= $class['MaLop'] ?>" method="POST">
                    <input type="hidden" name="action" value="addStudents">

                    <div class="table-responsive rounded-3" style="max-height:320px; overflow-y:auto;">
                        <table class="table table-hover align-middle text-center m-0"
                               style="font-size:0.88rem;" id="modalStudentTable">
                            <thead style="background-color:#0B426B; color:white; position:sticky; top:0;">
                                <tr>
                                    <th style="background-color:#0B426B; color:white; width:45px;">
                                        <input type="checkbox" id="selectAllModal" class="form-check-input">
                                    </th>
                                    <th style="background-color:#0B426B; color:white;">Tên học sinh</th>
                                    <th style="background-color:#0B426B; color:white;">Ngày sinh</th>
                                    <th style="background-color:#0B426B; color:white;">Địa chỉ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($allStudents)): ?>
                                    <tr>
                                        <td colspan="4" class="text-muted fst-italic py-3">
                                            Không còn học sinh nào để thêm.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allStudents as $s): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="MaHocSinh[]"
                                                       value="<?= $s['MaHocSinh'] ?>"
                                                       class="form-check-input modal-check">
                                            </td>
                                            <td class="text-start ps-3 fw-semibold">
                                                <?= htmlspecialchars($s['TenHocSinh']) ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($s['NgaySinh'])) ?></td>
                                            <td class="text-start" style="font-size:0.82rem; color:#666;">
                                                <?= htmlspecialchars($s['DiaChi']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Đã chọn: <span id="modalSelectedCount">0</span> học sinh
                        </small>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4 border"
                                    data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn text-white rounded-3 px-4"
                                    style="background-color:#00A854;">
                                <i class="bi bi-person-plus me-1"></i> Thêm vào lớp
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Thêm / xóa hàng lịch học ──────────────────────────────────────────────
const thuOptions  = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'];
const caOptions   = [
    {v:'Ca 1', l:'Ca 1 (7h–9h)'},
    {v:'Ca 2', l:'Ca 2 (9h–11h)'},
    {v:'Ca 3', l:'Ca 3 (13h–15h)'},
    {v:'Ca 4', l:'Ca 4 (15h–17h)'},
    {v:'Ca 5', l:'Ca 5 (17h30–19h30)'}
];
const roomOptions = `<?php foreach ($rooms as $room): ?><option value="<?= $room['MaPhong'] ?>"><?= htmlspecialchars($room['TenPhong']) ?></option><?php endforeach; ?>`;

document.getElementById('btn-add-schedule').addEventListener('click', function () {
    const container = document.getElementById('schedule-rows');
    const idx = container.querySelectorAll('.schedule-item').length + 1;

    const thuOpts = thuOptions.map(t => `<option value="${t}">${t}</option>`).join('');
    const caOpts  = caOptions.map(c => `<option value="${c.v}">${c.l}</option>`).join('');

    container.insertAdjacentHTML('beforeend', `
        <div class="row g-2 align-items-center mb-2 schedule-item">
            <div class="col-auto">
                <span class="badge bg-secondary rounded-pill">Buổi ${idx}</span>
            </div>
            <div class="col-md-3">
                <select name="Thu[]" class="form-select form-select-sm rounded-pill" required>
                    <option value="">— Thứ —</option>${thuOpts}
                </select>
            </div>
            <div class="col-md-3">
                <select name="Ca[]" class="form-select form-select-sm rounded-pill" required>
                    <option value="">— Ca —</option>${caOpts}
                </select>
            </div>
            <div class="col-md-3">
                <select name="MaPhong[]" class="form-select form-select-sm rounded-pill" required>
                    <option value="">— Phòng —</option>${roomOptions}
                </select>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill btn-remove-schedule">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`);
    toggleRemove();
});

document.getElementById('schedule-rows').addEventListener('click', function (e) {
    if (e.target.closest('.btn-remove-schedule')) {
        e.target.closest('.schedule-item').remove();
        document.querySelectorAll('.schedule-item .badge').forEach((b, i) => {
            b.textContent = `Buổi ${i + 1}`;
        });
        toggleRemove();
    }
});

function toggleRemove() {
    const items = document.querySelectorAll('.schedule-item');
    items.forEach(item => {
        item.querySelector('.btn-remove-schedule').style.display =
            items.length > 1 ? 'inline-block' : 'none';
    });
}

// ── Modal tìm kiếm & chọn học sinh ────────────────────────────────────────
document.getElementById('searchStudentModal').addEventListener('input', function () {
    const kw = this.value.toLowerCase();
    document.querySelectorAll('#modalStudentTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(kw) ? '' : 'none';
    });
});

document.getElementById('selectAllModal').addEventListener('change', function () {
    document.querySelectorAll('.modal-check').forEach(cb => cb.checked = this.checked);
    updateModalCount();
});
document.querySelectorAll('.modal-check').forEach(cb => {
    cb.addEventListener('change', updateModalCount);
});
function updateModalCount() {
    document.getElementById('modalSelectedCount').textContent =
        document.querySelectorAll('.modal-check:checked').length;
}
</script>