<link rel="stylesheet" href="public/css/teacher.css">
<div class="teacher-content">

    <!-- Breadcrumb -->
    <div class="teacher-breadcrumb">

        <a href="?url=dashboard">
            Trang chủ
        </a>

        <span>›</span>

        Quản lý giáo viên

    </div>
    <?php if(
    isset($_GET['error'])
    &&
    $_GET['error']=='foreign'
    ): ?>

    <div id="teacher-modal">

    <div class="teacher-popup">

    <div class="popup-icon">

    <i class="bi bi-exclamation-triangle-fill"></i>

    </div>

    <div class="popup-title">

    Giáo viên vẫn còn tồn tại trong lớp

    </div>

    <button
    onclick="
    document
    .getElementById(
    'teacher-modal'
    )
    .style.display='none'
    ">

    Đóng

    </button>

    </div>

    </div>

    <?php endif; ?>

    <!-- Toolbar -->

    <div class="teacher-toolbar">

        <form method="GET" action="" style="display:contents;">

            <input
                type="hidden"
                name="url"
                value="teacher"
            >

            <div class="search-group">

                <input
                    type="text"
                    name="search"
                    placeholder="Nhập tên giáo viên..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                >

                <button class="btn-search">

                    Tra cứu

                </button>

            </div>

            <a
                href="?url=teacher/create"
                class="btn-add"
            >

                + Thêm giáo viên

            </a>

        </form>

    </div>

    <!-- Section -->

    <div class="section-title">

        Danh sách giáo viên

    </div>

    <div class="teacher-table-wrap">

        <table class="teacher-table">

            <thead>

                <tr>

                    <th>STT</th>

                    <th>Mã GV</th>

                    <th>Tên giáo viên</th>

                    <th>Chuyên môn</th>

                    <th>Địa chỉ</th>

                    <th>SĐT</th>

                    <th>Mã TK</th>

                    <th>Thao tác</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach($teachers as $i=>$t): ?>

            <tr>

                <td><?= $i+1 ?></td>

                <td>

                    GV<?= str_pad(
                        $t['MaGiaoVien'],
                        3,
                        '0',
                        STR_PAD_LEFT
                    ) ?>

                </td>

                <td>

                    <?= htmlspecialchars($t['TenGiaoVien']) ?>

                </td>

                <td>

                    <?= htmlspecialchars($t['ChuyenMon']) ?>

                </td>

                <td>

                    <?= htmlspecialchars($t['DiaChi']) ?>

                </td>

                <td>

                    <?= htmlspecialchars($t['SoDienThoai']) ?>

                </td>

                <td>

                    <?= $t['MaTaiKhoan'] ?>

                </td>

                <td>

                    <div class="action-group">

                        <a
                        href="?url=teacher/edit&id=<?= $t['MaGiaoVien'] ?>"
                        class="btn-edit"
                        >

                        <i class="bi bi-pencil-square"></i>

                        Sửa

                        </a>

                        <form
                        method="POST"
                        action="?url=teacher/delete&id=<?= $t['MaGiaoVien'] ?>"
                        >

                        <button class="btn-delete">

                            <i class="bi bi-trash"></i>
                        

                            Xóa

                        </button>

                        </form>

                    </div>

                </td>

            </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>