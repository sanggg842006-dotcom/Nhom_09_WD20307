<?php

class DashboardController
{
    public function index()
    {
        // Cách 1: Hiển thị 1 trang dashboard đơn giản
        // include __DIR__ . '/../views/admin/index.php';

        // Cách 2 (dễ hơn): chuyển luôn về trang quản lý tour
        header('Location: index.php?c=Tour&a=index');
        exit;
    }
}
