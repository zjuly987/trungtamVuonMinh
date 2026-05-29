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

    <!-- TABLE -->
    <form method="POST">

        <div class="teacher-table-wrap">

            <table class="teacher-table grade-table">

                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã HS</th>
                        <th>Tên học sinh</th>
                        <th>TX</th>
                        <th>KT</th>
                        <th>Thi</th>
                        <th>DTB</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($students as $i => $s): ?>

                    <?php
                        $tx = $s['DTX'] ?? 0;
                        $kt = $s['KT'] ?? 0;
                        $thi = $s['Thi'] ?? 0;
                        $dtb = ($tx + $kt*2 + $thi*3) / 6;
                    ?>

                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= $s['MaHocSinh'] ?></td>
                        <td class="name-col"><?= $s['TenHocSinh'] ?></td>

                        <td>
                            <input class="score-input tx"
                                   data-original="<?= $tx ?>"
                                   name="data[<?= $s['MaHocSinh'] ?>][DTX]"
                                   value="<?= $tx ?>">
                        </td>

                        <td>
                            <input class="score-input kt"
                                   data-original="<?= $kt ?>"
                                   name="data[<?= $s['MaHocSinh'] ?>][KT]"
                                   value="<?= $kt ?>">
                        </td>

                        <td>
                            <input class="score-input thi"
                                   data-original="<?= $thi ?>"
                                   name="data[<?= $s['MaHocSinh'] ?>][Thi]"
                                   value="<?= $thi ?>">
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

        let original = input.getAttribute('data-original');

        input.value = (original !== null && original !== '')
            ? original
            : 0;
    });

    document.querySelectorAll('tr').forEach(row => {

        let tx  = parseFloat(row.querySelector('.tx')?.value || 0);
        let kt  = parseFloat(row.querySelector('.kt')?.value || 0);
        let thi = parseFloat(row.querySelector('.thi')?.value || 0);

        let dtb = (tx + kt*2 + thi*3) / 6;

        let cell = row.querySelector('.dtb');
        if (cell) cell.innerText = dtb.toFixed(1);
    });
}
function calcDTB(tx, kt, thi){
    return ((tx*1) + (kt*2) + (thi*3)) / 6;
}

document.querySelectorAll('.score-input').forEach(input => {
    input.addEventListener('input', function(){

        let row = this.closest('tr');

        let tx  = parseFloat(row.querySelector('.tx').value) || 0;
        let kt  = parseFloat(row.querySelector('.kt').value) || 0;
        let thi = parseFloat(row.querySelector('.thi').value) || 0;

        let dtb = calcDTB(tx, kt, thi);

        let dtbCell = row.querySelector('.dtb');
        dtbCell.innerText = dtb.toFixed(1);

    });
});
</script>