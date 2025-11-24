<?php
ob_start();
function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

$stLabels = [
  'present' => 'Có mặt',
  'late'    => 'Đi muộn',
  'absent'  => 'Vắng'
];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="mb-0"><?= h($title) ?></h5>
    <div class="small text-muted">
      Tour: <strong><?= h($schedule['tour_name'] ?? '') ?></strong>
      | Khởi hành: <?= h($schedule['start_date']) ?>
      | HDV: <?= h($schedule['guide_name'] ?? 'Chưa gán') ?>
    </div>
  </div>
  <a href="index.php?c=Attendance&a=index" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left"></i> Quay lại
  </a>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-<?= h($flash['type'] ?? 'info') ?>">
    <?= h($flash['msg'] ?? '') ?>
  </div>
<?php endif; ?>

<div class="mb-2 small">
  Thống kê: 
  Có mặt <?= (int)($stats['present'] ?? 0) ?> |
  Đi muộn <?= (int)($stats['late'] ?? 0) ?> |
  Vắng <?= (int)($stats['absent'] ?? 0) ?>
</div>

<form method="post" action="index.php?c=Attendance&a=store">
  <input type="hidden" name="schedule_id" value="<?= (int)$schedule['id'] ?>">

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-sm mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>#Booking</th>
              <th>Khách hàng</th>
              <th>Liên hệ</th>
              <th style="width:240px;">Trạng thái</th>
              <th>Ghi chú</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($roster)): foreach ($roster as $i=>$r): 
              $cur = $r['attendance_status'] ?? 'absent';
            ?>
              <tr>
                <td>
                  #<?= (int)$r['booking_id'] ?>
                  <input type="hidden" name="booking_id[]" value="<?= (int)$r['booking_id'] ?>">
                  <input type="hidden" name="customer_id[]" value="<?= (int)$r['customer_id'] ?>">
                </td>
                <td><?= h($r['customer_name']) ?></td>
                <td>
                  <?= h($r['customer_phone'] ?? '') ?><br>
                  <span class="text-muted small"><?= h($r['customer_email'] ?? '') ?></span>
                </td>
                <td>
                  <select name="status[]" class="form-select form-select-sm">
                    <?php foreach ($stLabels as $k=>$lb): ?>
                      <option value="<?= h($k) ?>" <?= $cur===$k?'selected':'' ?>>
                        <?= h($lb) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <input type="text" name="note[]" class="form-control form-control-sm"
                         value="<?= h($r['attendance_note'] ?? '') ?>"
                         placeholder="VD: đến trễ 10 phút">
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Chưa có booking cho lịch này.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-3 d-flex justify-content-end gap-2">
    <button class="btn btn-primary btn-sm">
      <i class="bi bi-save"></i> Lưu điểm danh
    </button>
  </div>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
