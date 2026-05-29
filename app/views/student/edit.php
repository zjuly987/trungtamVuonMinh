<?php
// View: Sửa học sinh
?>

<style>
  .student-content {
    padding: 24px 28px;
    background: #fff;
    min-height: 100%;
    font-family: 'Segoe UI', sans-serif;
  }

  /* Breadcrumb */
  .student-breadcrumb {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 20px;
  }

  .student-breadcrumb a {
    color: #3b82f6;
    text-decoration: none;
  }

  .student-breadcrumb a:hover {
    text-decoration: underline;
  }

  .student-breadcrumb span {
    margin: 0 6px;
    color: #9ca3af;
  }

  /* Title */
  .section-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e40af;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 20px;
    padding-bottom: 6px;
    border-bottom: 2px solid #e5e7eb;
  }

  /* Form */
  .student-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px 24px;
  }

  .form-group {
    display: flex;
    flex-direction: column;
  }

  .form-group.full-width {
    grid-column: span 2;
  }

  .form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-control {
    padding: 10px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    font-size: 13.5px;
    color: #374151;
    outline: none;
    background: #f9fafb;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: #3b82f6;
    background: #fff;
  }

  /* Buttons */
  .form-actions {
    margin-top: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Nút quay lại */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 8px 18px;
    background: #fff;

    border: 2px solid #2196f3;
    border-radius: 4px;

    color: #374151;
    font-size: 13px;
    font-weight: 500;

    text-decoration: none;
    transition: 0.2s;
}

.btn-back:hover {
    background: #eff6ff;
}

/* Nhóm bên phải */
.action-right {
    display: flex;
    gap: 12px;
}

/* Nút hủy */
.btn-cancel {
    padding: 8px 18px;
    border: none;
    border-radius: 18px;

    background: #f3f4f6;
    color: #374151;

    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
}

/* Nút lưu */
.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 8px 18px;
    border: none;
    border-radius: 18px;

    background: #10b981;
    color: white;

    font-size: 13px;
    cursor: pointer;
    transition: 0.2s;
}

.btn-save:hover {
    background: #059669;
}

  @media (max-width: 768px) {
    .student-form {
      grid-template-columns: 1fr;
    }

    .form-group.full-width {
      grid-column: span 1;
    }
  }
</style>

<div class="student-content">

  <!-- Breadcrumb -->
  <div class="student-breadcrumb">
    <a href="?url=dashboard">Trang chủ</a>
    <span>›</span>
    <a href="?url=student">Quản lý học sinh</a>
    <span>›</span>
    Sửa thông tin học sinh
  </div>

  <!-- Title -->
  <div class="section-title">
    Chỉnh sửa thông tin học sinh
  </div>

  <!-- Form -->
  <form method="POST">
    <?php if (!empty($errors['duplicate'])): ?>
    <div style="color:red; margin-bottom:15px;">
        <?= $errors['duplicate'] ?>
    </div>
<?php endif; ?>

    <div class="student-form">

      <!-- Tên học sinh -->
      <div class="form-group">
        <label>Họ và tên</label>

        <input
          type="text"
          name="TenHocSinh"
          class="form-control"
          value="<?= htmlspecialchars($student['TenHocSinh']) ?>"
          required
        >
      </div>

      <!-- Ngày sinh -->
      <div class="form-group">
        <label>Ngày sinh</label>

        <input
          type="date"
          name="NgaySinh"
          class="form-control"
          value="<?= $student['NgaySinh'] ?>"
          required
        >
      </div>

      <!-- Số điện thoại -->
      <div class="form-group">
        <label>Số điện thoại</label>

        <input
          type="text"
          name="SoDienThoai"
          class="form-control"
          value="<?= htmlspecialchars($student['SoDienThoai']) ?>"
          required
        >
      </div>

      <!-- Địa chỉ -->
      <div class="form-group full-width">
        <label>Địa chỉ</label>

        <input
          type="text"
          name="DiaChi"
          class="form-control"
          value="<?= htmlspecialchars($student['DiaChi']) ?>"
          required
        >
      </div>

    </div>

    <!-- Buttons -->
   <div class="form-actions">

    <a href="?url=student" class="btn-back">
        ↩ Quay lại
    </a>

    <div class="action-right">

        <a href="?url=student" class="btn-cancel">
            Hủy bỏ
        </a>

        <button type="submit" class="btn-save">
            💾 Lưu thay đổi
        </button>

    </div>

</div>
  </form>

</div>