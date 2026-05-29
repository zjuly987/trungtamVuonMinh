<link rel="stylesheet" href="/trungtamVuonMinh/public/css/grade.css">

<div class="teacher-content">

    <!-- Breadcrumb -->
    <div class="teacher-breadcrumb">

        <a href="?url=dashboard">Trang chủ</a>

        <span>›</span>

        Quản lý điểm

    </div>

    <!-- Toolbar -->
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

    <!-- Section -->
    <div class="section-title">
        Danh sách lớp được phân công
    </div>

    <div class="teacher-table-wrap">

        <table class="teacher-table">

            <thead>
                <tr>
                    <th>Mã lớp</th>
                    <th>Tên lớp</th>
                    <th>Lịch học</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($classes)): ?>

                <?php foreach ($classes as $i => $c): ?>

                    <tr>

                        <td><?= $c['MaLop'] ?></td>

                        <td><?= htmlspecialchars($c['TenLop']) ?></td>

                        <td><?= htmlspecialchars($c['LichHoc'] ?? 'Chưa cập nhật') ?></td>

                        <td>

                            <div class="action-group">

                            <!-- Nhập điểm --><a href="?url=grade/create&malop=<?= $c['MaLop'] ?>" class="btn-edit">
                                <i class="bi bi-journal-plus"></i>
                                Nhập điểm
                            </a>

                            <!-- Sửa điểm -->
                            <a href="?url=grade/edit&malop=<?= $c['MaLop'] ?>" class="btn-delete">
                                <i class="bi bi-pencil-square"></i>
                                Sửa điểm
                            </a>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" style="text-align:center;">
                        Không có lớp được phân công
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>