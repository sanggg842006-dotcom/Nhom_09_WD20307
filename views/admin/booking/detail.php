<div class="container mt-4">

    <h3>Chi tiết Booking #<?= $booking['id'] ?></h3>

    <!-- thông tin khách -->
    <div class="card mt-3">
        <div class="card-header bg-primary text-white">Khách hàng</div>
        <div class="card-body">
            <p><strong>Tên:</strong> <?= $booking['customer_name'] ?></p>
            <p><strong>Điện thoại:</strong> <?= $booking['customer_phone'] ?></p>
            <p><strong>Email:</strong> <?= $booking['customer_email'] ?></p>
        </div>
    </div>

    <!-- tour -->
    <div class="card mt-3">
        <div class="card-header bg-info text-white">Tour</div>
        <div class="card-body">
            <p><strong>Tour:</strong> <?= $booking['tour_name'] ?></p>
            <p><strong>Ngày khởi hành:</strong> <?= $booking['start_date'] ?></p>
            <p><strong>Số khách:</strong> <?= $booking['quantity'] ?></p>
            <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_price']) ?>đ</p>
        </div>
    </div>

    <!-- HDV -->
    <div class="card mt-3">
        <div class="card-header bg-success text-white">Hướng dẫn viên</div>
        <div class="card-body">

            <form method="POST" action="index.php?c=Booking&a=assignGuide">
                <input type="hidden" name="id" value="<?= $booking['id'] ?>">

                <select name="guide_id" class="form-select">
                    <option value="">-- Chọn hướng dẫn viên --</option>

                    <?php foreach ($guides as $g): ?>
                        <option value="<?= $g['id'] ?>"
                            <?= $booking['guide_id'] == $g['id'] ? 'selected' : '' ?>>
                            <?= $g['name'] ?> (<?= $g['phone'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <button class="btn btn-success mt-2">Phân công</button>
            </form>

        </div>
    </div>

    <!-- ghi chú -->
    <div class="card mt-3">
        <div class="card-header bg-warning">Ghi chú</div>
        <div class="card-body">
            <p><?= $booking['note'] ?: "<i>Không có ghi chú</i>" ?></p>
        </div>
    </div>

    <!-- phản hồi -->
    <div class="card mt-3">
        <div class="card-header bg-danger text-white">Phản hồi khách hàng</div>
        <div class="card-body">
            <?= $booking['feedback'] ?: "<i>Chưa có phản hồi</i>" ?>
        </div>
    </div>

    <a href="index.php?c=Booking&a=index" class="btn btn-secondary mt-3">Quay lại</a>
</div>