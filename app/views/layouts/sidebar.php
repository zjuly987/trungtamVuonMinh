<div class="sidebar">

<div>

<?php if($role=="secretary"): ?>

<a href="/trungtamVuonMinh/dashboard/secretary"
class="<?= $currentController=="dashboard" ? "active" : "" ?>">
Bảng điều khiển
</a>

<a href="/trungtamVuonMinh/teacher"
class="<?= $currentController=="teacher" ? "active" : "" ?>">
Quản lý giáo viên
</a>

<a href="/trungtamVuonMinh/student"
class="<?= $currentController=="student" ? "active" : "" ?>">
Quản lý học sinh
</a>

<a href="/trungtamVuonMinh/class"
class="<?= $currentController=="class" ? "active" : "" ?>">
Quản lý lớp học
</a>

<?php endif; ?>


<?php if($role=="teacher"): ?>

<a href="/trungtamVuonMinh/dashboard/teacher"
class="<?= $currentController=="dashboard" ? "active" : "" ?>">
Bảng điều khiển
</a>

<a href="/trungtamVuonMinh/attendance"
class="<?= $currentController=="attendance" ? "active" : "" ?>">
Quản lý điểm danh
</a>

<a href="/trungtamVuonMinh/grade"
class="<?= $currentController=="grade" ? "active" : "" ?>">
Quản lý điểm
</a>

<?php endif; ?>

</div>

<a class="logout">
<i class="bi bi-box-arrow-right"></i>
Đăng xuất
</a>

</div>