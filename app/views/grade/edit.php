<link rel="stylesheet" href="/trungtamVuonMinh/public/css/grade.css">

<div class="teacher-content">

    <!-- BREADCRUMB -->
    <div class="teacher-breadcrumb">
        <a href="?url=dashboard">Trang chủ</a>
        <span>›</span>
        <a href="?url=grade">Quản lý điểm</a>
        <span>›</span>
        Sửa điểm
    </div>

    <!-- CHỌN LỚP -->
    <div class="teacher-toolbar">

        <form method="GET" style="display:contents;">
            <input type="hidden" name="url" value="grade/create">

            <select name="malop" class="form-control" onchange="this.form.submit()">
                <option value="">-- Chọn lớp --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['MaLop'] ?>"
                        <?= ($maLop ?? '') == $c['MaLop'] ? 'selected' : '' ?>>
                        <?= $c['TenLop'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </form>

    </div>

    <!-- TABLE -->
    <form method="POST">
    <div class="score-hint">
        * Nhập điểm từ 0 đến 10. Làm tròn đến số thập phân thứ nhất. Ví dụ: 8.5
        <p> * Công thức tính điểm trung bình: ĐTB = (ĐTX x 1 + KT x 2 + Thi x 3) / 6</p>
    </div>
    <div class="table-actions">
        <label class="btn-excel">
            📊 Tải file Excel
            <input
                type="file"
                accept=".xlsx,.xls"
                id="excelFile"
                hidden
            >
        </label>
    </div>
        <div class="teacher-table-wrap">

            <table class="teacher-table grade-table">

                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã HS</th>
                        <th>Tên học sinh</th>
                        <th>Thường xuyên</th>
                        <th>Kiểm tra</th>
                        <th>Thi</th>
                        <th>ĐTB</th>
                    </tr>
                </thead>

                <tbody>
                <tr class="guide-row">
                    <td>*</td>
                    <td>VD</td>
                    <td>Học sinh mẫu</td>
                    <td>8.5</td>
                    <td>7.0</td>
                    <td>9.0</td>
                    <td>8.3</td>
                </tr>
                <?php foreach ($students as $i => $s): ?>

                    <?php
                        $tx = $s['DTX'];
                        $kt = $s['KT'];
                        $thi = $s['Thi'];

                        $dtb = (($tx ?? 0) + ($kt ?? 0) * 2 + ($thi ?? 0) * 3) / 6;
                    ?>

                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= $s['MaHocSinh'] ?></td>
                        <td class="name-col"><?= $s['TenHocSinh'] ?></td>

                        <td>
                        <input
                            class="score-input tx <?= !empty($s['TX_DaSua']) ? 'edited-score' : '' ?>"
                            data-original="<?= $s['DTX'] ?? '' ?>"
                            name="data[<?= $s['MaHocSinh'] ?>][DTX]"
                            value="<?= $s['DTX'] ?? '' ?>"
                            <?= ($s['DTX'] ?? null) === null ? 'readonly disabled' : '' ?>
                        >
                        </td>

                        <td>
                        <input
                            class="score-input kt <?= !empty($s['KT_DaSua']) ? 'edited-score' : '' ?>"
                            data-original="<?= $s['KT'] ?? '' ?>"
                            name="data[<?= $s['MaHocSinh'] ?>][KT]"
                            value="<?= $s['KT'] ?? '' ?>"
                            <?= ($s['KT'] ?? null) === null ? 'readonly disabled' : '' ?>
                        >
                        </td>

                        <td>
                        <input
                            class="score-input thi <?= !empty($s['THI_DaSua']) ? 'edited-score' : '' ?>"
                            data-original="<?= $s['Thi'] ?? '' ?>"
                            name="data[<?= $s['MaHocSinh'] ?>][Thi]"
                            value="<?= $s['Thi'] ?? '' ?>"
                            <?= ($s['Thi'] ?? null) === null ? 'readonly disabled' : '' ?>
                        >
                        </td>

                        <td class="dtb"><?= number_format($dtb,1) ?></td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>

        </div>

        <!-- ACTION -->
        <div class="form-actions">

            <a href="?url=grade" class="btn-back">← Quay lại</a>

            <div class="action-right">

                <button type="button" class="btn-cancel" onclick="resetForm()">
                    Hủy bỏ
                </button>

                <button type="submit" class="btn-save">
                    💾 Lưu thay đổi
                </button>

            </div>

        </div>

    </form>
</div>
<script>
function resetForm() {

    document.querySelectorAll('.score-input').forEach(input => {
        input.value = input.dataset.original || '';
    });

    document.querySelectorAll('tbody tr').forEach(row => {

        let txVal  = row.querySelector('.tx')?.value || '';
        let ktVal  = row.querySelector('.kt')?.value || '';
        let thiVal = row.querySelector('.thi')?.value || '';

        let tx  = txVal === '' ? 0 : parseFloat(txVal);
        let kt  = ktVal === '' ? 0 : parseFloat(ktVal);
        let thi = thiVal === '' ? 0 : parseFloat(thiVal);

        row.querySelector('.dtb').innerText =
            calcDTB(tx, kt, thi).toFixed(1);
    });
}

function calcDTB(tx, kt, thi){
    return ((tx*1) + (kt*2) + (thi*3)) / 6;
}

// ===== VALIDATION 0 - 10 =====
function validateScore(value) {

    if (value === '' || value === null) return true;

    value = value.replace(',', '.');

    let num = parseFloat(value);

    if (isNaN(num)) {
        alert("Điểm phải là số hợp lệ nằm trong khoảng từ 0 đến 10!");
        return false;
    }

    if (num < 0 || num > 10) {
        alert("Điểm phải nằm trong khoảng từ 0 đến 10!");
        return false;
    }

    return true;
}

// ===== INPUT EVENT =====
document.querySelectorAll('.score-input').forEach(input => {

    input.addEventListener('input', function(){
        
        this.value = this.value.replace(',', '.');

        let value = this.value;

        // CHECK VALIDATION NGAY KHI NHẬP
        if (!validateScore(value)) {
            this.value = '';
            return;
        }

        let row = this.closest('tr');

        let txVal  = row.querySelector('.tx').value;
        let ktVal  = row.querySelector('.kt').value;
        let thiVal = row.querySelector('.thi').value;

        let tx  = txVal !== '' ? parseFloat(txVal) : 0;
        let kt  = ktVal !== '' ? parseFloat(ktVal) : 0;
        let thi = thiVal !== '' ? parseFloat(thiVal) : 0;

        let dtb = calcDTB(tx, kt, thi);

        let dtbCell = row.querySelector('.dtb');

        if (txVal === '' && ktVal === '' && thiVal === '') {
            dtbCell.innerText = '';
        } else {
            dtbCell.innerText = dtb.toFixed(1);
        }

    });

});
</script>