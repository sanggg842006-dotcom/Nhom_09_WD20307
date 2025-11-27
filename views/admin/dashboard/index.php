<?php
$title = 'Bảng điều khiển';
ob_start();
?>  

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <!-- Thẻ thống kê Tổng số tour -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Tổng số tour</div>
                        <div class="fs-4 fw-semibold"><?= $stats['totalTours'] ?? 0 ?></div>
                    </div>
                    <span class="rounded-circle p-2 bg-light">
                        <i class="bi bi-map fs-5"></i>
                    </span>
                </div>
                <div class="mt-2 small text-success">
                    <i class="bi bi-arrow-up-right"></i> Đang mở bán tốt
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <!-- Thẻ thống kê Tổng booking -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Tổng booking</div>
                        <div class="fs-4 fw-semibold"><?= $stats['totalBookings'] ?? 0 ?></div>
                    </div>
                    <span class="rounded-circle p-2 bg-light">
                        <i class="bi bi-journal-check fs-5"></i>
                    </span>
                </div>
                <div class="mt-2 small text-primary">
                    <i class="bi bi-graph-up"></i> Số liệu tháng này
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <!-- Thẻ thống kê Số khách hàng -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Số khách hàng</div>
                        <div class="fs-4 fw-semibold"><?= $stats['totalCustomers'] ?? 0 ?></div>
                    </div>
                    <span class="rounded-circle p-2 bg-light">
                        <i class="bi bi-people fs-5"></i>
                    </span>
                </div>
                <div class="mt-2 small text-secondary">
                    <i class="bi bi-person-plus"></i> Thêm mới gần đây
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <!-- Thẻ thống kê Tour khởi hành hôm nay -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Tour khởi hành hôm nay</div>
                        <div class="fs-4 fw-semibold"><?= $stats['todayDepart'] ?? 0 ?></div>
                    </div>
                    <span class="rounded-circle p-2 bg-light">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </span>
                </div>
                <div class="mt-2 small text-warning">
                    <i class="bi bi-bell"></i> Cần theo dõi check-in
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Card Booking gần đây -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Booking gần đây</h6>
                <a href="index.php?c=Booking&a=index" class="small">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentBookings)): ?>
                    <!-- Bảng danh sách các booking gần đây -->
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;">ID</th>
                                <th>Tour</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentBookings as $b): ?>
                            <tr>
                                <td>#<?= $b['id'] ?></td>

                                <td>
                                    <strong><?= htmlspecialchars($b['tour_name']) ?></strong><br>
                                    <?php if (!empty($b['start_date'])): ?>
                                        <small class="text-muted">
                                            Khởi hành: <?= $b['start_date'] ?>
                                        </small>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($b['customer_name']) ?><br>
                                    <?php if (!empty($b['customer_phone'])): ?>
                                        <small class="text-muted"><?= $b['customer_phone'] ?></small>
                                    <?php endif; ?>
                                </td>

                                <td><?= $b['booking_date'] ?></td>

                                <td>
                                    <?php
                                    $status = $b['status'];
                                    $label  = '';
                                    $class  = '';

                                    switch ($status) {
                                        case 'pending':
                                            $label = 'Chờ xác nhận';
                                            $class = 'warning';
                                            break;
                                        case 'confirmed':
                                            $label = 'Đã xác nhận';
                                            $class = 'success';
                                            break;
                                        case 'paid':
                                            $label = 'Đã thanh toán';
                                            $class = 'primary';
                                            break;
                                        case 'cancelled':
                                            $label = 'Đã hủy';
                                            $class = 'danger';
                                            break;
                                        default:
                                            $label = 'Hoàn thành';
                                            $class = 'secondary';
                                            break;
                                    }
                                    ?>
                                    <span class="badge text-bg-<?= $class ?>">
                                        <?= $label ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <!-- Thông báo nếu không có booking nào -->
                    <div class="alert alert-light border small mb-0">
                        Chưa có booking nào.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Card Việc cần làm -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Việc cần làm</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-1"></i>
                        Xác nhận các booking mới.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-1"></i>
                        Cập nhật lịch khởi hành tour.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-1"></i>
                        Thêm mới danh mục tour đặc biệt.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-1"></i>
                        Hoàn thiện chức năng điểm danh theo lịch trình.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>