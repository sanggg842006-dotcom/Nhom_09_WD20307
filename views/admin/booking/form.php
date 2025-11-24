<?php
// Biến có sẵn: $title, $booking (mảng), $tours, $customers, $schedulesByTour
// $schedulesByTour = [tour_id => [list schedules của tour đó]]
$isEdit = !empty($booking['id']);
ob_start();
function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><?= h($title) ?></h5>
  <a href="index.php?c=Booking&a=index" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left"></i> Quay lại
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="index.php?c=Booking&a=<?= $isEdit ? 'update' : 'store' ?>" method="post" class="row g-3">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$booking['id'] ?>">
      <?php endif; ?>

      <div class="col-md-6">
        <label class="form-label">Tour</label>
        <select class="form-select" id="tourSelect" name="tour_id" required>
          <option value="">-- Chọn tour --</option>
          <?php foreach ($tours as $t): ?>
            <option value="<?= (int)$t['id'] ?>"
              <?= (isset($booking['tour_id']) && (int)$booking['tour_id']==(int)$t['id']) ? 'selected' : '' ?>>
              #<?= (int)$t['id'] ?> - <?= h($t['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- ✅ NEW: chọn lịch khởi hành theo tour -->
      <div class="col-md-6">
        <label class="form-label">Lịch khởi hành</label>
        <select class="form-select" id="scheduleSelect" name="schedule_id" required>
          <option value="">-- Chọn lịch khởi hành --</option>
        </select>
        <div class="form-text small">
          Booking phải gắn vào 1 lịch khởi hành cụ thể.
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label">Khách hàng</label>
        <select class="form-select" name="customer_id" required>
          <option value="">-- Chọn khách hàng --</option>
          <?php foreach ($customers as $c): ?>
            <option value="<?= (int)$c['id'] ?>"
              <?= (isset($booking['customer_id']) && (int)$booking['customer_id']==(int)$c['id']) ? 'selected' : '' ?>>
              #<?= (int)$c['id'] ?> - <?= h($c['name']) ?> (<?= h($c['email'] ?? '') ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Ngày đặt</label>
        <input type="date" class="form-control" name="booking_date"
               value="<?= h($booking['booking_date'] ?? date('Y-m-d')) ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Trạng thái</label>
        <?php
          $statuses = [
            'pending'   => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'completed' => 'Hoàn thành'
          ];
          $cur = $booking['status'] ?? 'pending';
        ?>
        <select class="form-select" name="status">
          <?php foreach ($statuses as $k => $label): ?>
            <option value="<?= h($k) ?>" <?= $cur===$k?'selected':''; ?>><?= h($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">
          <i class="bi bi-save"></i> <?= $isEdit ? 'Cập nhật' : 'Lưu' ?>
        </button>
        <a href="index.php?c=Booking&a=index" class="btn btn-outline-secondary">Hủy</a>
      </div>
    </form>
  </div>
</div>

<script>
  // dữ liệu schedules từ PHP
  const schedulesByTour = <?= json_encode($schedulesByTour ?? [], JSON_UNESCAPED_UNICODE); ?>;
  const tourSelect = document.getElementById('tourSelect');
  const scheduleSelect = document.getElementById('scheduleSelect');
  const selectedScheduleId = <?= (int)($booking['schedule_id'] ?? 0); ?>;

  function renderSchedules(tourId){
    scheduleSelect.innerHTML = '<option value="">-- Chọn lịch khởi hành --</option>';
    if(!tourId || !schedulesByTour[tourId]) return;

    schedulesByTour[tourId].forEach(sc => {
      const cap = parseInt(sc.capacity || 0);
      const booked = parseInt(sc.booked_count || 0);
      const left = cap > 0 ? (cap - booked) : '?';
      const disabled = (cap > 0 && booked >= cap);

      const opt = document.createElement('option');
      opt.value = sc.id;
      opt.disabled = disabled;
      opt.textContent =
        `#${sc.id} | ${sc.start_date}` +
        (sc.end_date ? ` → ${sc.end_date}` : '') +
        ` | còn ${left} chỗ | ${sc.status}`;

      if (selectedScheduleId && parseInt(sc.id) === selectedScheduleId) {
        opt.selected = true;
      }
      scheduleSelect.appendChild(opt);
    });
  }

  tourSelect.addEventListener('change', e => renderSchedules(e.target.value));
  // init khi load trang (edit / create)
  renderSchedules(tourSelect.value);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
