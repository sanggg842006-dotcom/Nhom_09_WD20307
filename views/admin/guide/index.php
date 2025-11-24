<?php
// $title, $data (items,total,page,pages,perPage), $flash có từ controller
ob_start();

/**
 * Helper escape an toàn cho PHP 8.1+ (null -> '')
 */
function h($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><?= h($title) ?></h5>
  <a href="index.php?c=Guide&a=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg"></i> Thêm Hướng dẫn viên
  </a>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-<?= h($flash['type'] ?? 'info') ?>"><?= h($flash['msg'] ?? '') ?></div>
<?php endif; ?>

<form class="row g-2 mb-3" method="get">
  <input type="hidden" name="c" value="Guide">
  <input type="hidden" name="a" value="index">
  <div class="col-sm-4">
    <input type="text" class="form-control" name="q"
           placeholder="Tìm theo tên, điện thoại, email, ngôn ngữ..."
           value="<?= h($_GET['q'] ?? '') ?>">
  </div>
  <div class="col-sm-3">
    <select class="form-select" name="status">
      <option value="">-- Trạng thái --</option>
      <?php
        $statuses = ['active' => 'Đang hoạt động', 'inactive' => 'Ngừng hoạt động'];
        $cur = $_GET['status'] ?? '';
        foreach ($statuses as $k => $label):
      ?>
        <option value="<?= h($k) ?>" <?= $cur === $k ? 'selected' : '' ?>>
          <?= h($label) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-sm-3">
    <button class="btn btn-outline-secondary" type="submit">
      <i class="bi bi-funnel"></i> Lọc
    </button>
    <a class="btn btn-outline-dark" href="index.php?c=Guide&a=index">
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
            <th style="width:80px;">ID</th>
            <th>Họ tên</th>
            <th>Liên hệ</th>
            <th>Ngôn ngữ</th>
            <th>Trạng thái</th>
            <th style="width:160px;" class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['items'])): foreach ($data['items'] as $row): ?>
            <tr>
              <td>#<?= (int)($row['id'] ?? 0) ?></td>

              <td><?= h($row['name'] ?? '') ?></td>

              <td>
                <?php if (!empty($row['phone'])): ?>
                  <div><i class="bi bi-telephone"></i> <?= h($row['phone']) ?></div>
                <?php endif; ?>
                <?php if (!empty($row['email'])): ?>
                  <div><i class="bi bi-envelope"></i> <?= h($row['email']) ?></div>
                <?php endif; ?>

                <?php if (empty($row['phone']) && empty($row['email'])): ?>
                  <span class="text-muted small">Chưa có liên hệ</span>
                <?php endif; ?>
              </td>

              <td><?= h($row['languages'] ?? '') ?></td>

              <td>
                <?php
                  $st = $row['status'] ?? 'inactive';
                  $badge = ($st === 'active') ? 'success' : 'secondary';
                  $label = ($st === 'active') ? 'Đang hoạt động' : 'Ngừng hoạt động';
                ?>
                <span class="badge text-bg-<?= h($badge) ?>"><?= h($label) ?></span>
              </td>

              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary"
                   href="index.php?c=Guide&a=edit&id=<?= (int)($row['id'] ?? 0) ?>">
                  <i class="bi bi-pencil-square"></i>
                </a>

                <form action="index.php?c=Guide&a=destroy" method="post" class="d-inline"
                      onsubmit="return confirm('Xóa hướng dẫn viên #<?= (int)($row['id'] ?? 0) ?>?');">
                  <input type="hidden" name="id" value="<?= (int)($row['id'] ?? 0) ?>">
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash3"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Không có dữ liệu.</td>
            </tr>
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
      $buildUrl = function($p) {
        $qs = $_GET;
        $qs['page'] = $p;
        return 'index.php?' . http_build_query($qs);
      };

      $page  = (int)($data['page'] ?? 1);
      $pages = (int)($data['pages'] ?? 1);
    ?>
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= h($buildUrl($page-1)) ?>">«</a>
    </li>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
        <a class="page-link" href="<?= h($buildUrl($i)) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= h($buildUrl($page+1)) ?>">»</a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
