<?php
ob_start();
function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><?= h($title) ?></h5>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-<?= h($flash['type'] ?? 'info') ?>">
    <?= h($flash['msg'] ?? '') ?>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-sm mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Tour</th>
            <th>Khởi hành</th>
            <th>Kết thúc</th>
            <th>Chỗ</th>
            <th>Trạng thái</th>
            <th class="text-end" style="width:160px;">Điểm danh</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($schedules)): foreach ($schedules as $sc): ?>
            <tr>
              <td>#<?= (int)$sc['id'] ?></td>
              <td><?= h($sc['tour_name'] ?? '') ?></td>
              <td><?= h($sc['start_date']) ?></td>
              <td><?= h($sc['end_date'] ?? '-') ?></td>
              <td><?= (int)($sc['booked_count'] ?? 0) ?>/<?= (int)($sc['capacity'] ?? 0) ?></td>
              <td><span class="badge text-bg-secondary"><?= h($sc['status']) ?></span></td>
              <td class="text-end">
                <a class="btn btn-sm btn-primary"
                   href="index.php?c=Attendance&a=show&schedule_id=<?= (int)$sc['id'] ?>">
                  <i class="bi bi-clipboard-check"></i> Điểm danh
                </a>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Chưa có lịch khởi hành.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
