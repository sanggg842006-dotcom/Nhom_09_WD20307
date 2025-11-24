<?php
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Schedule.php'; // ✅ thêm model lịch khởi hành

class DashboardController
{
    public function index()
    {
        $tourModel     = new Tour();
        $bookingModel  = new Booking();
        $customerModel = new Customer();
        $scheduleModel = new Schedule(); // ✅ dùng schedules thay cho tours.start_date

        $today = date('Y-m-d');

        // Thống kê (dùng COUNT(*) ở DB)
        $stats = [
            'totalTours'     => $tourModel->countAll(),
            'totalBookings'  => $bookingModel->countAll(),
            'totalCustomers' => $customerModel->countAll(),

            // ✅ FIX: tour khởi hành hôm nay lấy từ schedules
            'todayDepart'    => $scheduleModel->countWhere('start_date = ?', [$today]),
        ];

        // Booking gần đây (đã có trong Booking model)
        $recentBookings = $bookingModel->getRecentBookings(5);

        $title = 'Bảng điều khiển';
        include __DIR__ . '/../views/admin/dashboard/index.php';
    }
}
