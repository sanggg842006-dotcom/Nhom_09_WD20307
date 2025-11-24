<?php
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Tour.php';

class AttendanceController {
    private function redirect($url){
        header("Location: {$url}");
        exit;
    }
    private function setFlash($type,$msg){
        if (session_status()!==PHP_SESSION_ACTIVE) session_start();
        $_SESSION['flash']=['type'=>$type,'msg'=>$msg];
    }
    private function takeFlash(){
        if (session_status()!==PHP_SESSION_ACTIVE) session_start();
        $f=$_SESSION['flash']??null;
        unset($_SESSION['flash']);
        return $f;
    }

    // Danh sách lịch khởi hành để chọn điểm danh
    public function index(){
        $scheduleModel = new Schedule();

        // lấy tất cả lịch + tên tour
        $schedules = $scheduleModel->getAllWithTour();

        $flash = $this->takeFlash();
        $title = "Điểm danh theo lịch trình";
        include __DIR__ . '/../views/admin/attendances/index.php';
    }

    // Form điểm danh cho 1 lịch
    public function show(){
        $scheduleId = (int)($_GET['schedule_id'] ?? 0);
        if ($scheduleId<=0) $this->redirect('index.php?c=Attendance&a=index');

        $scheduleModel = new Schedule();
        $attendanceModel = new Attendance();

        $schedule = $scheduleModel->findWithTourGuide($scheduleId);
        if (!$schedule){
            $this->setFlash('danger','Lịch khởi hành không tồn tại.');
            $this->redirect('index.php?c=Attendance&a=index');
        }

        $roster = $attendanceModel->getRosterBySchedule($scheduleId);
        $stats  = $attendanceModel->countStatusBySchedule($scheduleId);

        $flash = $this->takeFlash();
        $title = "Điểm danh lịch #{$scheduleId}";
        include __DIR__ . '/../views/admin/attendances/form.php';
    }

    // Lưu điểm danh
    public function store(){
        $scheduleId = (int)($_POST['schedule_id'] ?? 0);
        if ($scheduleId<=0) $this->redirect('index.php?c=Attendance&a=index');

        $rows = [];
        $bookingIds = $_POST['booking_id'] ?? [];
        $customerIds = $_POST['customer_id'] ?? [];
        $statuses = $_POST['status'] ?? [];
        $notes = $_POST['note'] ?? [];

        foreach ($bookingIds as $i => $bid){
            $rows[] = [
                'booking_id'  => (int)$bid,
                'customer_id' => (int)($customerIds[$i] ?? 0),
                'status'      => $statuses[$i] ?? 'absent',
                'note'        => trim($notes[$i] ?? ''),
            ];
        }

        $attendanceModel = new Attendance();
        $attendanceModel->saveForSchedule($scheduleId, $rows);

        $this->setFlash('success','Lưu điểm danh thành công.');
        $this->redirect('index.php?c=Attendance&a=show&schedule_id='.$scheduleId);
    }
}
