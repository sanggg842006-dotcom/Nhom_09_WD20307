<?php
ob_start();
function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

$statuses = [
  'open' => 'Mở bán',
  'closed' => 'Đóng',
  'completed' => 'Hoàn thành',
  'cancelled' => 'Hủy'
];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><?= h($title) ?></h5>
  <a href="index.php?c=Schedule&a=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg"></i> Thêm lịch khởi hành
  </a>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-<?= h($flash['type'] ?? 'info') ?>"><?= h($flash['msg'] ?? '') ?></div>
<?php endif; ?>

<form class="row g-2 mb-3" method="get">
  <input type="hidden" name="c" value="Schedule">
  <input type="hidden" name="a" value="index">

  <div class="col-md-4">
    <input type="text" class="form-control" name="q"
           placeholder="Tìm tour/HDV/điểm hẹn..."
           value="<?= h($_GET['q'] ?? '') ?>">
  </div>

  <div class="col-md-3">
    <select class="form-select" name="tour_id">
      <option value="">-- Tất cả tour --</option>
      <?php $curTour = $_GET['tour_id'] ?? ''; foreach ($tours as $t): ?>
        <option value="<?= (int)$t['id'] ?>" <?= $curTour == $t['id'] ? 'selected' : '' ?>>
          #<?= (int)$t['id'] ?> - <?= h($t['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-2">
    <select class="form-select" name="status">
      <option value="">-- Trạng thái --</option>
      <?php $curSt = $_GET['status'] ?? ''; foreach ($statuses as $k=>$lb): ?>
        <option value="<?= h($k) ?>" <?= $curSt===$k?'selected':''; ?>><?= h($lb) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-3">
    <button class="btn btn-outline-secondary" type="submit">
      <i class="bi bi-funnel"></i> Lọc
    </button>
    <a class="btn btn-outline-dark" href="index.php?c=Schedule&a=index">
      <i class="bi bi-x-circle"></i> Xóa lọc
    </a>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-sm mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Tour</th>
            <th>Hướng dẫn viên</th>
            <th>Khởi hành</th>
            <th>Kết thúc</th>
            <th>Điểm hẹn</th>
            <th>Chỗ</th>
            <th>Giá riêng</th>
            <th>Trạng thái</th>
            <th class="text-end" style="width:140px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['items'])): foreach ($data['items'] as $row): ?>
            <tr>
              <td>#<?= (int)$row['id'] ?></td>
              <td><?= h($row['tour_name'] ?? ('Tour#'.$row['tour_id'])) ?></td>
              <td><?= h($row['guide_name'] ?? 'Chưa gán') ?></td>
              <td><?= h($row['start_date']) ?></td>
              <td><?= h($row['end_date'] ?? '') ?></td>
              <td><?= h($row['meeting_point'] ?? '') ?></td>
              <td><?= (int)($row['booked_count'] ?? 0) ?>/<?= (int)($row['capacity'] ?? 0) ?></td>
              <td><?= $row['price_override'] !== null ? number_format((float)$row['price_override']) : '-' ?></td>
              <td>
                <?php
                  $st = $row['status'] ?? 'open';
                  $badge = match($st){
                    'open' => 'success',
                    'closed' => 'secondary',
                    'completed' => 'primary',
                    'cancelled' => 'danger',
                    default => 'secondary'
                  };
                ?>
                <span class="badge text-bg-<?= h($badge) ?>"><?= h($statuses[$st] ?? $st) ?></span>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary"
                   href="index.php?c=Schedule&a=edit&id=<?= (int)$row['id'] ?>">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form action="index.php?c=Schedule&a=destroy" method="post" class="d-inline"
                      onsubmit="return confirm('Xóa lịch khởi hành #<?= (int)$row['id'] ?>?');">
                  <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="10" class="text-center text-muted py-4">Không có dữ liệu.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if (($data['pages'] ?? 1) > 1): ?>
<nav class="mt-3">
  <ul class="pagination pagination-sm mb-0">
    <?php
      $buildUrl=function($p){
        $qs=$_GET; $qs['page']=$p; return 'index.php?'.http_build_query($qs);
      };
      $page=(int)$data['page']; $pages=(int)$data['pages'];
    ?>
    <li class="page-item <?= $page<=1?'disabled':'' ?>"><a class="page-link" href="<?= h($buildUrl($page-1)) ?>">«</a></li>
    <?php for($i=1;$i<=$pages;$i++): ?>
      <li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="<?= h($buildUrl($i)) ?>"><?= $i ?></a></li>
    <?php endfor; ?>
    <li class="page-item <?= $page>=$pages?'disabled':'' ?>"><a class="page-link" href="<?= h($buildUrl($page+1)) ?>">»</a></li>
  </ul>
</nav>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
