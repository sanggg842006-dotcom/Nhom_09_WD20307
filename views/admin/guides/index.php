<?php
$title = 'Quản lý nhân sự';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Quản lý Nhân sự (Hướng dẫn viên)</h1>
    <a href="index.php?c=Guide&a=create" class="btn btn-primary">+ Thêm nhân sự</a>
</div>

<?php if (!empty($guides)): ?>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th class="text-end">Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($guides as $g): ?>
                    <tr>
                        <td><?= $g['id'] ?></td>
                        <td><?= htmlspecialchars($g['name']) ?></td>
                        <td><?= htmlspecialchars($g['phone']) ?></td>
                        <td><?= htmlspecialchars($g['email']) ?></td>
                        <td><?= htmlspecialchars($g['address']) ?></td>
                        <td class="text-end">
                            <a href="index.php?c=Guide&a=edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a onclick="return confirm('Xóa nhân sự này?')" 
                               href="index.php?c=Guide&a=delete&id=<?= $g['id'] ?>" 
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
    <div class="alert alert-info">Chưa có nhân sự nào.</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
