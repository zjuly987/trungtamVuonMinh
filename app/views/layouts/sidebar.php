<?php
$currentUrl = $_GET['url'] ?? 'dashboard';
$parts = explode('/', $currentUrl);

$controller = $parts[0] ?? '';
?>
<div class="sidebar">
<div>

<?php if($role === "secretary"): ?>

    <a href="?url=dashboard/secretary"
       class="<?= $currentController==="dashboard" ? "active" : "" ?>">
        Bảng điều khiển
    </a>

    <a href="?url=teacher"
       class="<?= $currentController==="teacher" ? "active" : "" ?>">
        Quản lý giáo viên
    </a>

    <a href="?url=student"
       class="<?= $currentController==="student" ? "active" : "" ?>">
        Quản lý học sinh
    </a>

    <a href="?url=class"
       class="<?= $currentController==="class" ? "active" : "" ?>">
        Quản lý lớp học
    </a>

<?php endif; ?>

<?php if($role === "teacher"): ?>

    <a href="?url=dashboard/teacher"
       class="<?= $currentController==="dashboard" ? "active" : "" ?>">
        Bảng điều khiển
    </a>

    <a href="?url=attendance"
       class="<?= $currentController==="attendance" ? "active" : "" ?>">
        Quản lý điểm danh
    </a>

    <a href="?url=grade"
    class="<?= $controller === "grade" ? "active" : "" ?>">
        Quản lý điểm
    </a>

<?php endif; ?>

</div>

<a href="?url=auth/logout" class="logout">
    <i class="bi bi-box-arrow-right"></i>
    Đăng xuất
</a>

</div>