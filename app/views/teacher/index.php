<link rel="stylesheet" href="/trungtamVuonMinh/public/css/teacher.css">
<div class="teacher-content">

    <!-- Breadcrumb -->
    <div class="teacher-breadcrumb">

        <a href="?url=dashboard">
            Trang chủ
        </a>

        <span>›</span>

        Quản lý giáo viên

    </div>

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

                <td>
                    <?= htmlspecialchars($t['MaGiaoVien']) ?>
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

                        <a href="#"
                        class="btn-delete"
                        onclick="checkDelete(event, <?= $t['MaGiaoVien'] ?>)">
                            
                            <i class="bi bi-trash"></i> Xóa

                        </a>

                    </div>

                </td>

            </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>
<script>
function checkDelete(event, id){
    event.preventDefault();

    fetch("?url=teacher/checkDelete&id=" + id)
        .then(res => res.text())
        .then(text => {

            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.log("NOT JSON:", text);
                data = { inClass: false };
            }

            if(data.inClass === true){
                alert("Giáo viên vẫn còn tồn tại trong lớp");
                return;
            }

            if(confirm("Bạn có chắc chắn muốn xóa giáo viên này không?")){
                window.location.href = "?url=teacher/delete&id=" + id;
            }
        });
}
</script>