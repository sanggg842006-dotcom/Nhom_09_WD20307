<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Schedule.php'; // ✅ thêm

class BookingController
{
    private function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    private function setFlash($type, $msg) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }

    private function takeFlash() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $f = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $f;
    }

    // GET /?c=Booking&a=index
    public function index() {
        $bookingModel = new Booking();

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;

        $filters = [
            'status' => $_GET['status'] ?? '',
            'q'      => $_GET['q']      ?? '',
        ];

        $data = $bookingModel->paginate($page, $perPage, $filters);
        $flash = $this->takeFlash();

        // map tour & khách để view dùng
        $tourModel = new Tour();
        $customerModel = new Customer();
        $tours = [];
        foreach ($tourModel->all() as $t) { $tours[$t['id']] = $t; }
        $customers = [];
        foreach ($customerModel->all() as $c) { $customers[$c['id']] = $c; }

        $title = 'Quản lý Booking';
        // ⚠️ bạn đang để thư mục view là booking hay bookings thì chỉnh cho khớp
        include __DIR__ . '/../views/admin/booking/index.php';
    }

    // GET /?c=Booking&a=create
    public function create() {
        $tourModel = new Tour();
        $customerModel = new Customer();
        $scheduleModel = new Schedule();

        $tours = $tourModel->all();
        $customers = $customerModel->all();

        // ✅ NEW: lịch mở bán theo tour để form render
        $schedulesByTour = $scheduleModel->getOpenSchedulesGroupedByTour();

        $booking = [
            'tour_id' => '',
            'schedule_id' => '',
            'customer_id' => '',
            'booking_date' => date('Y-m-d'),
            'status' => 'pending'
        ];

        $title = 'Thêm Booking';
        include __DIR__ . '/../views/admin/booking/form.php';
    }

    // POST /?c=Booking&a=store
    public function store() {
        $bookingModel  = new Booking();
        $scheduleModel = new Schedule();

        $data = [
            'tour_id'      => (int)($_POST['tour_id'] ?? 0),
            'schedule_id'  => (int)($_POST['schedule_id'] ?? 0),  // ✅ NEW
            'customer_id'  => (int)($_POST['customer_id'] ?? 0),
            'booking_date' => $_POST['booking_date'] ?? date('Y-m-d'),
            'status'       => $_POST['status'] ?? 'pending',
        ];

        // Validate
        if ($data['tour_id'] <= 0 || $data['customer_id'] <= 0) {
            $this->setFlash('danger', 'Vui lòng chọn Tour và Khách hàng hợp lệ.');
            $this->redirect('index.php?c=Booking&a=create');
        }

        if ($data['schedule_id'] <= 0) {
            $this->setFlash('danger', 'Vui lòng chọn lịch khởi hành.');
            $this->redirect('index.php?c=Booking&a=create');
        }

        // ✅ check lịch thuộc tour + còn chỗ
        $sc = $scheduleModel->find($data['schedule_id']);
        if (!$sc || (int)$sc['tour_id'] !== $data['tour_id']) {
            $this->setFlash('danger', 'Lịch khởi hành không hợp lệ.');
            $this->redirect('index.php?c=Booking&a=create');
        }
        $cap = (int)($sc['capacity'] ?? 0);
        $booked = (int)($sc['booked_count'] ?? 0);
        if ($cap > 0 && $booked >= $cap) {
            $this->setFlash('danger', 'Lịch này đã hết chỗ.');
            $this->redirect('index.php?c=Booking&a=create');
        }

        $bookingModel->create($data);

        // ✅ nếu tạo confirmed thì tăng booked_count
        if ($data['status'] === 'confirmed') {
            $bookingModel->incBooked($data['schedule_id']);
        }

        $this->setFlash('success', 'Tạo booking thành công.');
        $this->redirect('index.php?c=Booking&a=index');
    }

    // GET /?c=Booking&a=edit&id=...
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { $this->redirect('index.php?c=Booking&a=index'); }

        $bookingModel = new Booking();
        $scheduleModel = new Schedule();

        $booking = $bookingModel->find($id);
        if (!$booking) {
            $this->setFlash('danger', 'Booking không tồn tại.');
            $this->redirect('index.php?c=Booking&a=index');
        }

        $tourModel = new Tour();
        $customerModel = new Customer();

        $tours = $tourModel->all();
        $customers = $customerModel->all();

        // ✅ NEW: lịch mở bán theo tour để form render
        $schedulesByTour = $scheduleModel->getOpenSchedulesGroupedByTour();

        $title = 'Sửa Booking';
        include __DIR__ . '/../views/admin/booking/form.php';
    }

    // POST /?c=Booking&a=update
    public function update() {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) { $this->redirect('index.php?c=Booking&a=index'); }

        $bookingModel  = new Booking();
        $scheduleModel = new Schedule();

        $old = $bookingModel->find($id);
        if (!$old) {
            $this->setFlash('danger', 'Booking không tồn tại.');
            $this->redirect('index.php?c=Booking&a=index');
        }

        $data = [
            'tour_id'      => (int)($_POST['tour_id'] ?? 0),
            'schedule_id'  => (int)($_POST['schedule_id'] ?? 0),  // ✅ NEW
            'customer_id'  => (int)($_POST['customer_id'] ?? 0),
            'booking_date' => $_POST['booking_date'] ?? date('Y-m-d'),
            'status'       => $_POST['status'] ?? 'pending',
        ];

        if ($data['tour_id'] <= 0 || $data['customer_id'] <= 0) {
            $this->setFlash('danger', 'Vui lòng chọn Tour và Khách hàng hợp lệ.');
            $this->redirect('index.php?c=Booking&a=edit&id=' . $id);
        }

        if ($data['schedule_id'] <= 0) {
            $this->setFlash('danger', 'Vui lòng chọn lịch khởi hành.');
            $this->redirect('index.php?c=Booking&a=edit&id=' . $id);
        }

        // ✅ check lịch thuộc tour + còn chỗ (nếu đổi lịch)
        $sc = $scheduleModel->find($data['schedule_id']);
        if (!$sc || (int)$sc['tour_id'] !== $data['tour_id']) {
            $this->setFlash('danger', 'Lịch khởi hành không hợp lệ.');
            $this->redirect('index.php?c=Booking&a=edit&id=' . $id);
        }

        // Nếu đổi lịch và lịch mới full thì chặn
        if ((int)$old['schedule_id'] !== $data['schedule_id']) {
            $cap = (int)($sc['capacity'] ?? 0);
            $booked = (int)($sc['booked_count'] ?? 0);
            if ($cap > 0 && $booked >= $cap) {
                $this->setFlash('danger', 'Lịch mới đã hết chỗ.');
                $this->redirect('index.php?c=Booking&a=edit&id=' . $id);
            }
        }

        // ✅ xử lý booked_count theo chuyển trạng thái / đổi lịch
        $oldStatus = $old['status'] ?? 'pending';
        $oldScheduleId = (int)($old['schedule_id'] ?? 0);
        $newStatus = $data['status'];
        $newScheduleId = $data['schedule_id'];

        // case 1: đổi lịch
        if ($oldScheduleId && $oldScheduleId !== $newScheduleId) {
            // nếu booking cũ đang confirmed => trừ lịch cũ
            if ($oldStatus === 'confirmed') {
                $bookingModel->decBooked($oldScheduleId);
            }
            // nếu booking mới confirmed => cộng lịch mới
            if ($newStatus === 'confirmed') {
                $bookingModel->incBooked($newScheduleId);
            }
        } else {
            // case 2: cùng lịch, chỉ đổi status
            if ($oldStatus !== 'confirmed' && $newStatus === 'confirmed') {
                $bookingModel->incBooked($newScheduleId);
            }
            if ($oldStatus === 'confirmed' && $newStatus !== 'confirmed') {
                $bookingModel->decBooked($newScheduleId);
            }
        }

        $bookingModel->update($id, $data);

        $this->setFlash('success', 'Cập nhật booking thành công.');
        $this->redirect('index.php?c=Booking&a=index');
    }

    // POST /?c=Booking&a=destroy
    public function destroy() {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $bookingModel = new Booking();
            $old = $bookingModel->find($id);

            if ($old && ($old['status'] ?? '') === 'confirmed' && !empty($old['schedule_id'])) {
                $bookingModel->decBooked((int)$old['schedule_id']);
            }

            $bookingModel->delete($id);
            $this->setFlash('success', 'Xóa booking thành công.');
        }
        $this->redirect('index.php?c=Booking&a=index');
    }
}
    