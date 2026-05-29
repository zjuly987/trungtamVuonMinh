<link rel="stylesheet" href="/trungtamVuonMinh/public/css/teacher.css">

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

            <input type="hidden" name="url" value="grade">

            <div class="search-group">

                <input
                    type="text"
                    name="search"
                    placeholder="Tìm mã lớp..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                >

                <button class="btn-search">
                    Tra cứu
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
                    <th>STT</th>
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

                        <td><?= $i + 1 ?></td>

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