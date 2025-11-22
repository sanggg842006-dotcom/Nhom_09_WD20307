<?php
class GuideController {
    private $guideModel;

    public function __construct()
    {
        $this->guideModel = new Guide();
    }

    public function index()
    {
        $guides = $this->guideModel->all();
        include __DIR__ . '/../views/admin/guides/index.php';
    }

    public function create()
    {
        include __DIR__ . '/../views/admin/guides/create.php';
    }

    public function store()
    {
        $data = [
            'name'    => $_POST['name'] ?? '',
            'phone'   => $_POST['phone'] ?? '',
            'email'   => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
        ];
        $this->guideModel->create($data);
        header('Location: index.php?c=Guide&a=index');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $guide = $this->guideModel->find($id);
        if (!$guide) {
            die('Nhân sự không tồn tại');
        }
        include __DIR__ . '/../views/admin/guides/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $data = [
            'name'    => $_POST['name'] ?? '',
            'phone'   => $_POST['phone'] ?? '',
            'email'   => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
        ];
        $this->guideModel->update($id, $data);
        header('Location: index.php?c=Guide&a=index');
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        $this->guideModel->delete($id);
        header('Location: index.php?c=Guide&a=index');
        exit;
    }
}
