<?php
$title = 'Quản lý Tour';

ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Quản lý Tour</h1>
    <a href="index.php?c=Tour&a=create" class="btn btn-primary">+ Thêm tour</a>
</div>

<?php if (!empty($tours)): ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên tour</th>
                    <th>Giá</th>
                    <th>Thời lượng</th>
                    <th>Ngày khởi hành</th>
                    <th class="text-end">Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tours as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td><?= number_format($t['price']) ?> đ</td>
                        <td><?= htmlspecialchars($t['duration']) ?></td>
                        <td><?= htmlspecialchars($t['start_date']) ?></td>
                        <td class="text-end">
                            <a href="index.php?c=Tour&a=edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a onclick="return confirm('Xóa tour này?')" 
                               href="index.php?c=Tour&a=delete&id=<?= $t['id'] ?>" 
                               class="btn btn-sm btn-danger">
                                Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">Chưa có tour nào.</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
