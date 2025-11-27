<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Guide.php';

class BookingController
{
    private function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    private function flash($type, $msg)
    {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }

    private function getFlash()
    {
        $f = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $f;
    }

    // =============================
    // LIST BOOKING (Admin)
    // =============================
    public function index()
    {
        $booking = new Booking();
        $page = $_GET['page'] ?? 1;

        $data = $booking->paginate($page, 10);
        $flash = $this->getFlash();

        include_once __DIR__ . '/../views/admin/booking/index.php';
    }

    // =============================
    // FORM CREATE BOOKING
    // =============================
    public function create()
    {
        $schedule = new Schedule();
        $schedules = $schedule->getAllWithTour();  // ✔ FIXED

        include_once __DIR__ . '/../views/admin/booking/form.php';
    }

    // =============================
    // STORE BOOKING
    // =============================
    public function store()
    {
        $model = new Booking();

        $data = [
            'schedule_id'    => $_POST['schedule_id'],
            'customer_name'  => $_POST['customer_name'],
            'customer_phone' => $_POST['customer_phone'],
            'customer_email' => $_POST['customer_email'],
            'quantity'       => $_POST['quantity'],
            'total_price'    => $_POST['total_price'],
            'status'         => $_POST['status'] ?? 'pending'
        ];

        $model->create($data);

        if ($data['status'] == 'confirmed') {
            $model->incBooked($data['schedule_id']);
        }

        $this->flash('success', 'Tạo booking thành công.');
        $this->redirect('index.php?c=Booking&a=index');
    }

    // =============================
    // EDIT
    // =============================
    public function edit()
    {
        $id = $_GET['id'];

        $model = new Booking();
        $schedule = new Schedule();
        $guide = new Guide();

        $booking = $model->find($id);
        $schedules = $schedule->getAllWithTour();  // ✔ FIXED
        $guides = $guide->all();

        include_once __DIR__ . '/../views/admin/booking/form.php';
    }

    // =============================
    // UPDATE
    // =============================
    public function update()
    {
        $model = new Booking();

        $id = $_POST['id'];
        $old = $model->find($id);

        $data = [
            'schedule_id'    => $_POST['schedule_id'],
            'customer_name'  => $_POST['customer_name'],
            'customer_phone' => $_POST['customer_phone'],
            'customer_email' => $_POST['customer_email'],
            'quantity'       => $_POST['quantity'],
            'total_price'    => $_POST['total_price'],
            'status'         => $_POST['status'],
            'note'           => $_POST['note']
        ];

        $model->update($id, $data);

        if ($old['status'] != 'confirmed' && $data['status'] == 'confirmed') {
            $model->incBooked($data['schedule_id']);
        }

        if ($old['status'] == 'confirmed' && $data['status'] != 'confirmed') {
            $model->decBooked($data['schedule_id']);
        }

        $this->flash('success', 'Cập nhật booking thành công!');
        $this->redirect('index.php?c=Booking&a=index');
    }

    // =============================
    // DELETE
    // =============================
    public function destroy()
    {
        $model = new Booking();
        $id = $_POST['id'];

        $old = $model->find($id);

        if ($old['status'] == 'confirmed') {
            $model->decBooked($old['schedule_id']);
        }

        $model->delete($id);

        $this->flash('success', 'Xóa booking thành công!');
        $this->redirect('index.php?c=Booking&a=index');
    }

    // =============================
    // ASSIGN GUIDE
    // =============================
    public function assignGuide()
    {
        $id = $_POST['id'];
        $guide_id = $_POST['guide_id'];

        $model = new Booking();
        $model->assignGuide($id, $guide_id);

        $this->flash('success', 'Phân công hướng dẫn viên thành công!');
        $this->redirect("index.php?c=Booking&a=edit&id={$id}");
    }

    // =============================
    // SAVE FEEDBACK
    // =============================
    public function saveFeedback()
    {
        $id = $_POST['id'];
        $feedback = $_POST['feedback'];

        $model = new Booking();
        $model->saveFeedback($id, $feedback);

        $this->flash('success', 'Gửi phản hồi thành công!');
        $this->redirect('index.php?c=Booking&a=index');
    }
}