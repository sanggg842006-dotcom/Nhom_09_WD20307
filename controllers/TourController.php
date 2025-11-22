<?php
class TourController {
    private $tourModel;

    public function __construct()
    {
        $this->tourModel = new Tour();
    }

    // GET: index.php?c=Tour&a=index
    public function index()
    {
        $tours = $this->tourModel->all();
        include __DIR__ . '/../views/admin/tours/index.php';
    }

    // GET: form thêm
    public function create()
    {
        include __DIR__ . '/../views/admin/tours/create.php';
    }

    // POST: lưu tour mới
    public function store()
    {
        $data = [
            'name'        => $_POST['name'] ?? '',
            'price'       => $_POST['price'] ?? 0,
            'duration'    => $_POST['duration'] ?? '',
            'start_date'  => $_POST['start_date'] ?? null,
            'description' => $_POST['description'] ?? '',
        ];
        $this->tourModel->create($data);
        header('Location: index.php?c=Tour&a=index');
        exit;
    }

    // GET: form sửa
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $tour = $this->tourModel->find($id);
        if (!$tour) {
            die('Tour không tồn tại');
        }
        include __DIR__ . '/../views/admin/tours/edit.php';
    }

    // POST: cập nhật
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $data = [
            'name'        => $_POST['name'] ?? '',
            'price'       => $_POST['price'] ?? 0,
            'duration'    => $_POST['duration'] ?? '',
            'start_date'  => $_POST['start_date'] ?? null,
            'description' => $_POST['description'] ?? '',
        ];
        $this->tourModel->update($id, $data);
        header('Location: index.php?c=Tour&a=index');
        exit;
    }

    // GET: xóa
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        $this->tourModel->delete($id);
        header('Location: index.php?c=Tour&a=index');
        exit;
    }
}
