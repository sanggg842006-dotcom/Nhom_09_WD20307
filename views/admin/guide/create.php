<?php
$title = 'Thêm nhân sự';
ob_start();
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h1 class="h5 mb-0">Thêm nhân sự</h1>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?c=Guide&a=store">
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Điện thoại</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?c=Guide&a=index" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Lưu nhân sự</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
