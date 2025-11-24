<?php
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/Guide.php';

class ScheduleController
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

    // GET: index.php?c=Schedule&a=index
    public function index() {
        $model = new Schedule();

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $filters = [
            'tour_id' => $_GET['tour_id'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'q'       => $_GET['q'] ?? '',
        ];

        $data  = $model->paginate($page, $perPage, $filters);
        $flash = $this->takeFlash();

        $tourModel  = new Tour();
        $guideModel = new Guide();
        $tours  = $tourModel->all('id DESC');
        $guides = $guideModel->all('id DESC');

        $title = 'Lịch khởi hành';
        include __DIR__ . '/../views/admin/schedule/index.php';
    }

    // GET: create
    public function create() {
        $tourModel  = new Tour();
        $guideModel = new Guide();
        $tours  = $tourModel->all('id DESC');
        $guides = $guideModel->all('id DESC');

        $schedule = [
            'tour_id' => '',
            'guide_id' => '',
            'start_date' => date('Y-m-d'),
            'end_date' => '',
            'meeting_point' => '',
            'capacity' => 0,
            'booked_count' => 0,
            'price_override' => '',
            'status' => 'open',
            'note' => '',
        ];

        $title = 'Thêm lịch khởi hành';
        include __DIR__ . '/../views/admin/schedule/form.php';
    }

    // POST: store
    public function store() {
    $model = new Schedule();

    $data = [
        'tour_id'        => (int)($_POST['tour_id'] ?? 0),
        'guide_id'       => ($_POST['guide_id'] ?? '') !== '' ? (int)$_POST['guide_id'] : null,
        'start_date'     => $_POST['start_date'] ?? date('Y-m-d'),

        // FIX ở đây
        'end_date'       => (($_POST['end_date'] ?? '') !== '') ? $_POST['end_date'] : null,

        'meeting_point'  => trim($_POST['meeting_point'] ?? ''),
        'capacity'       => (int)($_POST['capacity'] ?? 0),
        'booked_count'   => (int)($_POST['booked_count'] ?? 0),
        'price_override' => ($_POST['price_override'] ?? '') !== '' ? (float)$_POST['price_override'] : null,
        'status'         => $_POST['status'] ?? 'open',
        'note'           => trim($_POST['note'] ?? ''),
    ];

    if ($data['tour_id'] <= 0) {
        $this->setFlash('danger', 'Vui lòng chọn tour.');
        $this->redirect('index.php?c=Schedule&a=create');
    }

    if ($data['capacity'] < 0 || $data['booked_count'] < 0) {
        $this->setFlash('danger', 'Số chỗ / số đã đặt không hợp lệ.');
        $this->redirect('index.php?c=Schedule&a=create');
    }

    $model->create($data);
    $this->setFlash('success', 'Tạo lịch khởi hành thành công.');
    $this->redirect('index.php?c=Schedule&a=index');
}


    // GET: edit&id=
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) $this->redirect('index.php?c=Schedule&a=index');

        $model = new Schedule();
        $schedule = $model->find($id);
        if (!$schedule) {
            $this->setFlash('danger', 'Lịch khởi hành không tồn tại.');
            $this->redirect('index.php?c=Schedule&a=index');
        }

        $tourModel  = new Tour();
        $guideModel = new Guide();
        $tours  = $tourModel->all('id DESC');
        $guides = $guideModel->all('id DESC');

        $title = 'Sửa lịch khởi hành';
        include __DIR__ . '/../views/admin/schedule/form.php';
    }

    // POST: update
   public function update() {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) $this->redirect('index.php?c=Schedule&a=index');

    $model = new Schedule();

    $data = [
        'tour_id'        => (int)($_POST['tour_id'] ?? 0),
        'guide_id'       => ($_POST['guide_id'] ?? '') !== '' ? (int)$_POST['guide_id'] : null,
        'start_date'     => $_POST['start_date'] ?? date('Y-m-d'),

        // FIX ở đây
        'end_date'       => (($_POST['end_date'] ?? '') !== '') ? $_POST['end_date'] : null,

        'meeting_point'  => trim($_POST['meeting_point'] ?? ''),
        'capacity'       => (int)($_POST['capacity'] ?? 0),
        'booked_count'   => (int)($_POST['booked_count'] ?? 0),
        'price_override' => ($_POST['price_override'] ?? '') !== '' ? (float)$_POST['price_override'] : null,
        'status'         => $_POST['status'] ?? 'open',
        'note'           => trim($_POST['note'] ?? ''),
    ];

    if ($data['tour_id'] <= 0) {
        $this->setFlash('danger', 'Vui lòng chọn tour.');
        $this->redirect('index.php?c=Schedule&a=edit&id='.$id);
    }

    $model->update($id, $data);
    $this->setFlash('success', 'Cập nhật lịch khởi hành thành công.');
    $this->redirect('index.php?c=Schedule&a=index');
}


    // POST: destroy
    public function destroy() {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $model = new Schedule();
            $model->delete($id);
            $this->setFlash('success', 'Xóa lịch khởi hành thành công.');
        }
        $this->redirect('index.php?c=Schedule&a=index');
    }
}
