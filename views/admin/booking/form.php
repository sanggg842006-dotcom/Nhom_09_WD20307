<?php
ob_start();
?>

<h3><?= isset($booking) ? "Sửa Booking #{$booking['id']}" : "Tạo Booking" ?></h3>

<div class="card p-4">

<form method="POST"
      action="index.php?c=Booking&a=<?= isset($booking) ? 'update' : 'store' ?>">

    <?php if (isset($booking)): ?>
        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Lịch khởi hành</label>
        <select name="schedule_id" class="form-select" required>
            <?php foreach ($schedules as $sc): ?>
                <option value="<?= $sc['id'] ?>"
                        <?= isset($booking) && $booking['schedule_id']==$sc['id'] ? 'selected' : '' ?>>
                    <?= $sc['tour_name'] ?> - <?= $sc['start_date'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Tên khách</label>
            <input name="customer_name" class="form-control"
                   value="<?= $booking['customer_name'] ?? '' ?>" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Điện thoại</label>
            <input name="customer_phone" class="form-control"
                   value="<?= $booking['customer_phone'] ?? '' ?>" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Email</label>
            <input name="customer_email" class="form-control"
                   value="<?= $booking['customer_email'] ?? '' ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Số lượng khách</label>
            <input name="quantity" type="number" min="1" class="form-control"
                   value="<?= $booking['quantity'] ?? 1 ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Tổng tiền</label>
            <input name="total_price" type="number" class="form-control"
                   value="<?= $booking['total_price'] ?? 0 ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <?php $st = $booking['status'] ?? 'pending'; ?>
                <option value="pending" <?= $st=='pending'?'selected':'' ?>>Chờ xác nhận</option>
                <option value="confirmed" <?= $st=='confirmed'?'selected':'' ?>>Xác nhận</option>
                <option value="paid" <?= $st=='paid'?'selected':'' ?>>Đã thanh toán</option>
                <option value="cancelled" <?= $st=='cancelled'?'selected':'' ?>>Hủy</option>
                <option value="completed" <?= $st=='completed'?'selected':'' ?>>Hoàn thành</option>
            </select>
        </div>
    </div>

    <?php if (isset($booking)): ?>
    <div class="mb-3">
        <label class="form-label">Ghi chú</label>
        <textarea name="note" class="form-control"><?= $booking['note'] ?></textarea>
    </div>
    <?php endif; ?>

    <button class="btn btn-primary"><?= isset($booking) ? "Cập nhật" : "Tạo mới" ?></button>
    <a href="index.php?c=Booking&a=index" class="btn btn-secondary">Quay lại</a>

</form>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';