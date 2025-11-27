<?php
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Quản lý Booking</h3>
    <a href="index.php?c=Booking&a=create" class="btn btn-primary">
        + Thêm Booking
    </a>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['msg'] ?></div>
<?php endif; ?>

<div class="card p-3 mb-3">
    <form class="row g-2" method="GET">
        <input type="hidden" name="c" value="Booking">
        <input type="hidden" name="a" value="index">

        <div class="col-md-4">
            <input name="q" class="form-control" placeholder="Tìm khách / tour..."
                   value="<?= $_GET['q'] ?? '' ?>">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="pending">Chờ xác nhận</option>
                <option value="confirmed">Xác nhận</option>
                <option value="paid">Đã thanh toán</option>
                <option value="cancelled">Hủy</option>
                <option value="completed">Hoàn thành</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Lọc</button>
        </div>

        <div class="col-md-2">
            <a href="index.php?c=Booking&a=index" class="btn btn-secondary w-100">Xóa lọc</a>
        </div>
    </form>
</div>

<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Tour</th>
                <th>Khởi hành</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
                <th>HDV</th>
                <th>Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data['items'] as $b): ?>
            <tr>
                <td>#<?= $b['id'] ?></td>
                <td><?= htmlspecialchars($b['customer_name']) ?></td>
                <td><?= htmlspecialchars($b['tour_name']) ?></td>
                <td><?= $b['start_date'] ?></td>
                <td><?= $b['quantity'] ?></td>
                <td><?= number_format($b['total_price']) ?>đ</td>
                <td><?= $b['guide_id'] ?: '-' ?></td>
                <td>
                    <span class="badge bg-info"><?= $b['status'] ?></span>
                </td>
                <td class="text-center">

                    <a href="index.php?c=Booking&a=edit&id=<?= $b['id'] ?>" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form method="POST" action="index.php?c=Booking&a=destroy"
                          style="display:inline-block"
                          onsubmit="return confirm('Xóa booking này?')">
                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';