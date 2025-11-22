<?php
require_once __DIR__ . '/../configs/env.php';

class BaseModel {
    protected static $conn;
    protected $table; // tên bảng, set ở model con

   public function __construct()
{
    if (!self::$conn) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        self::$conn = new PDO($dsn, DB_USER, DB_PASS);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}


    public function all()
    {
        $stmt = self::$conn->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = self::$conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $cols = array_keys($data);
        $colStr = implode(',', $cols);
        $placeholders = implode(',', array_fill(0, count($cols), '?'));

        $sql = "INSERT INTO {$this->table} ($colStr) VALUES ($placeholders)";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute(array_values($data));
        return self::$conn->lastInsertId();
    }

    public function update($id, $data)
    {
        $set = [];
        foreach ($data as $col => $val) {
            $set[] = "$col = ?";
        }
        $setStr = implode(',', $set);

        $sql = "UPDATE {$this->table} SET $setStr WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        $values = array_values($data);
        $values[] = $id;
        return $stmt->execute($values);
    }

    public function delete($id)
    {
        $stmt = self::$conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
