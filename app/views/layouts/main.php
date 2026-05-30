<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Trung tâm Vươn Mình</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>

<?php $role = $role ?? ''; ?>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand">
            HỆ THỐNG QUẢN LÝ TRUNG TÂM GIÁO DỤC VƯƠN MÌNH
        </a>
       <div class="text-white d-flex align-items-center gap-2">
    <i class="bi bi-person-circle fs-4"></i>
    <span><?= $_SESSION['user']['VaiTro'] ?? '' ?></span>
</div>
    </div>
</nav>

<div class="row">
    <div class="col-md-3">
        <?php require_once ROOT . "/app/views/layouts/sidebar.php"; ?>
    </div>
    <div class="col-md-9">
        <div class="content-box">
            <?php require_once $content; ?>
        </div>
    </div>
</div>

</body>
</html>