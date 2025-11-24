<?php
// $title, $guide (mảng) do controller cung cấp
$isEdit = !empty($guide['id']);
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><?= htmlspecialchars($title) ?></h5>
  <a href="index.php?c=Guide&a=index" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left"></i> Quay lại
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="index.php?c=Guide&a=<?= $isEdit ? 'update' : 'store' ?>" method="post" class="row g-3">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$guide['id'] ?>">
      <?php endif; ?>

      <div class="col-md-6">
        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($guide['name'] ?? '') ?>" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Điện thoại</label>
        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($guide['phone'] ?? '') ?>">
      </div>

      <div class="col-md-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($guide['email'] ?? '') ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Ngôn ngữ (vd: Vietnamese, English, Chinese)</label>
        <input type="text" class="form-control" name="languages" value="<?= htmlspecialchars($guide['languages'] ?? '') ?>">
      </div>

      <div class="col-md-3">
        <label class="form-label">Trạng thái</label>
        <?php $cur = $guide['status'] ?? 'active'; ?>
        <select class="form-select" name="status">
          <option value="active"   <?= $cur==='active'?'selected':''; ?>>Đang hoạt động</option>
          <option value="inactive" <?= $cur==='inactive'?'selected':''; ?>>Ngừng hoạt động</option>
        </select>
      </div>

      <div class="col-md-12">
        <label class="form-label">Ghi chú</label>
        <textarea class="form-control" name="note" rows="3"><?= htmlspecialchars($guide['note'] ?? '') ?></textarea>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">
          <i class="bi bi-save"></i> <?= $isEdit ? 'Cập nhật' : 'Lưu' ?>
        </button>
        <a href="index.php?c=Guide&a=index" class="btn btn-outline-secondary">Hủy</a>
      </div>
    </form>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
