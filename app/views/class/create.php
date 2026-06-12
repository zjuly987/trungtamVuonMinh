<!-- app/views/class/create.php -->
<!-- Biến từ controller: $students (tất cả HS), $teachers (tất cả GV) -->
<link rel="stylesheet" href="public/css/class.css">

<div class="container-fluid mt-3 pe-4">

    <div class="student-breadcrumb">
        <a href="?url=dashboard/teacher">Trang chủ</a>
        <span>›</span>
        <a href="?url=class">Quản lý lớp học</a>
        <span>›</span>
        Tạo lớp học mới
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">

        <div class="d-flex align-items-center mb-4">
            <div style="width:4px; height:24px; background-color:#2B547E; margin-right:10px; border-radius:2px;"></div>
            <h5 class="fw-bold mb-0 text-dark">Tạo lớp học mới</h5>
        </div>

<form action="?url=class/create" method="POST">
            <!-- Hàng 1: Tên lớp, Số buổi, Sĩ số -->
            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Tên lớp <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="TenLop"
                           class="form-control rounded-pill"
                           placeholder="VD: Hóa học 12"
                           required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Số buổi <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="SoBuoi" min="1"
                           class="form-control rounded-pill text-center"
                           placeholder="VD: 10" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Sĩ số tối đa <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="SiSo" min="1" max="20"
                           class="form-control rounded-pill text-center"
                           placeholder="VD: 20" required>
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
                           min="<?= date('Y-m-d') ?>"
                           required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold text-muted" style="font-size:0.88rem;">
                        Giáo viên phụ trách
                    </label>
                    <select name="MaGiaoVien" class="form-select rounded-pill">
                        <option value="">— Chưa phân công —</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['MaGiaoVien'] ?>">
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
                        <!-- Buổi mặc định -->
                        <div class="row g-2 align-items-center mb-2 schedule-item">
                            <div class="col-auto">
                                <span class="badge bg-secondary rounded-pill">Buổi 1</span>
                            </div>
                            <div class="col-md-3">
                                <select name="Thu[]" class="form-select form-select-sm rounded-pill" required>
                                    <option value="">— Thứ —</option>
                                    <?php foreach (['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'] as $t): ?>
                                        <option value="<?= $t ?>"><?= $t ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="Ca[]" class="form-select form-select-sm rounded-pill" required>
                                    <option value="">— Ca —</option>
                                    <option value="Ca 1">Ca 1 (7h–9h)</option>
                                    <option value="Ca 2">Ca 2 (9h–11h)</option>
                                    <option value="Ca 3">Ca 3 (13h–15h)</option>
                                    <option value="Ca 4">Ca 4 (15h–17h)</option>
                                    <option value="Ca 5">Ca 5 (17h30–19h30)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="MaPhong[]" class="form-select form-select-sm rounded-pill" required>
                                    <option value="">— Phòng —</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['MaPhong'] ?>">
                                            <?= htmlspecialchars($room['TenPhong']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill btn-remove-schedule"
                                        style="display:none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
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

            <!-- Chọn học sinh -->
            <div class="mb-4">
                <label class="form-label fw-semibold text-muted d-block" style="font-size:0.88rem;">
                    Học sinh tham gia lớp
                </label>
                <div class="mb-2">
                    <input type="text" id="searchStudentCreate"
                           class="form-control form-control-sm rounded-pill"
                           style="max-width:300px;"
                           placeholder="Tìm học sinh...">
                </div>
                <div class="table-responsive rounded-3 border" style="max-height:280px; overflow-y:auto;">
                    <table class="table table-hover align-middle text-center m-0"
                           style="font-size:0.88rem;" id="studentCreateTable">
                        <thead style="background-color:#0B426B; color:white; position:sticky; top:0;">
                            <tr>
                                <th style="background-color:#0B426B; color:white; width:45px;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th style="background-color:#0B426B; color:white;">Tên học sinh</th>
                                <th style="background-color:#0B426B; color:white;">Ngày sinh</th>
                                <th style="background-color:#0B426B; color:white;">Địa chỉ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="students[]"
                                               value="<?= $s['MaHocSinh'] ?>"
                                               class="form-check-input student-check">
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
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">
                    Đã chọn: <span id="selectedCount">0</span> học sinh
                </small>
            </div>

            <!-- Nút hành động -->
            <div class="form-actions mt-4" style="display: flex; justify-content: space-between; align-items: center;">
                
                <a href="?url=class" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>

                <div class="action-right" style="display: flex; gap: 12px;">
                    <a href="?url=class" class="btn-cancel" style="padding:8px 18px; border-radius:18px; background:#f3f4f6; text-decoration:none; color:#374151;">
                        Hủy bỏ
                    </a>
                    
                    <button type="submit" class="btn-save" style="padding:8px 18px;

    border:none;

    border-radius:18px;

    background:#10b981;

    color:white;

    cursor:pointer;">
                        <i class="bi bi-plus-circle"></i> Tạo lớp học
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

<script>
// Thêm / xóa buổi học
const thuOptions = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'];
const caOptions  = [
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

    const thuOpts  = thuOptions.map(t => `<option value="${t}">${t}</option>`).join('');
    const caOpts   = caOptions.map(c => `<option value="${c.v}">${c.l}</option>`).join('');

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

// Tìm kiếm học sinh
document.getElementById('searchStudentCreate').addEventListener('input', function () {
    const kw = this.value.toLowerCase();
    document.querySelectorAll('#studentCreateTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(kw) ? '' : 'none';
    });
});

// Chọn tất cả
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.student-check').forEach(cb => cb.checked = this.checked);
    updateCount();
});

document.querySelectorAll('.student-check').forEach(cb => {
    cb.addEventListener('change', updateCount);
});

function updateCount() {
    document.getElementById('selectedCount').textContent =
        document.querySelectorAll('.student-check:checked').length;
}
</script>