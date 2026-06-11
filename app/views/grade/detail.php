<link rel="stylesheet" href="/trungtamVuonMinh/public/css/grade.css">

<div class="teacher-content">

    <!-- BREADCRUMB -->
    <div class="teacher-breadcrumb">
        <a href="?url=dashboard">Trang chủ</a>
        <span>›</span>
        <a href="?url=grade">Quản lý điểm</a>
        <span>›</span>
        Tra cứu điểm
    </div>

    <!-- FORM TRA CỨU -->
    <div class="teacher-toolbar">

        <form method="GET" action="" style="display:contents;">
            <input type="hidden" name="url" value="grade/detail">

            <div class="search-group">

                <select name="malop" class="form-control" required>
                    <option value="">-- Chọn lớp --</option>

                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['MaLop'] ?>"
                            <?= ($maLop ?? '') == $c['MaLop'] ? 'selected' : '' ?>>
                            <?= $c['MaLop'] ?> - <?= htmlspecialchars($c['TenLop']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

                <button class="btn-search">
                    Tra cứu điểm
                </button>

            </div>
        </form>

    </div>

    <!-- TABLE HIỂN THỊ ĐIỂM -->
    <div class="teacher-table-wrap">

        <table class="teacher-table">

            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã HS</th>
                    <th>Tên học sinh</th>
                    <th>TX</th>
                    <th>KT</th>
                    <th>Thi</th>
                    <th>ĐTB</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($students)): ?>

                <?php foreach ($students as $i => $s): ?>

                    <?php
                        $tx = $s['DTX'] ?? 0;
                        $kt = $s['KT'] ?? 0;
                        $thi = $s['Thi'] ?? 0;

                        $dtb = ($tx + $kt*2 + $thi*3) / 6;
                    ?>

                    <tr>
                        <td><?= $i + 1 ?></td>

                        <td><?= $s['MaHocSinh'] ?></td>

                        <td>
                            <?= htmlspecialchars($s['TenHocSinh']) ?>
                        </td>

                        <td class="<?= !empty($s['TX_DaSua']) ? 'edited-score' : '' ?>">
                            <?= $tx ?>
                        </td>

                        <td class="<?= !empty($s['KT_DaSua']) ? 'edited-score' : '' ?>">
                            <?= $kt ?>
                        </td>

                        <td class="<?= !empty($s['THI_DaSua']) ? 'edited-score' : '' ?>">
                            <?= $thi ?>
                        </td>

                        <td class="<?= !empty($s['DTB_DaSua']) ? 'edited-score' : '' ?>">
                            <strong>
                                <?= number_format($dtb, 1) ?>
                            </strong>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center; padding:20px; color:#999;">
                        <?php if (!empty($maLop)): ?>
                            Không tìm thấy dữ liệu lớp này
                        <?php else: ?>
                            Nhập mã lớp để tra cứu điểm
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- BUTTON QUAY LẠI -->
    <div class="form-actions">
        <a href="?url=grade" class="btn-back">← Quay lại</a>
    </div>

</div>