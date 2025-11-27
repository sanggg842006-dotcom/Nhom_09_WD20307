<?php
require_once __DIR__ . '/BaseModel.php';

class Booking extends BaseModel
{
    protected $table = 'bookings';

    // ============================================
    // Tạo booking mới
    // ============================================
    public function create($data)
    {
        // Nếu chưa truyền ngày đặt thì tự gán hôm nay
        if (empty($data['booking_date'])) {
            $data['booking_date'] = date('Y-m-d');
        }

        $sql = "INSERT INTO {$this->table}
                (schedule_id, customer_name, customer_phone, customer_email,
                 quantity, total_price, status, booking_date)
                VALUES
                (:schedule_id, :customer_name, :customer_phone, :customer_email,
                 :quantity, :total_price, :status, :booking_date)";

        $stmt = self::$conn->prepare($sql);
        return $stmt->execute($data);
    }

    // ============================================
    // Cập nhật booking
    // ============================================
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET 
                    schedule_id    = :schedule_id,
                    customer_name  = :customer_name,
                    customer_phone = :customer_phone,
                    customer_email = :customer_email,
                    quantity       = :quantity,
                    total_price    = :total_price,
                    status         = :status,
                    note           = :note
                WHERE id = :id";

        $stmt = self::$conn->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // ============================================
    // Xoá booking
    // ============================================
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ============================================
    // Lấy 1 booking theo id (kèm tour & schedule)
    // ============================================
    public function find($id)
    {
        $sql = "SELECT 
                    b.*,
                    s.start_date,
                    s.end_date,
                    s.tour_id,
                    t.name AS tour_name
                FROM {$this->table} b
                JOIN schedules s ON b.schedule_id = s.id
                JOIN tours t     ON s.tour_id      = t.id
                WHERE b.id = ?
                LIMIT 1";

        $stmt = self::$conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================
    // Phân trang danh sách booking (admin)
    // ============================================
    public function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT 
                    b.*,
                    s.start_date,
                    s.tour_id,
                    t.name AS tour_name
                FROM {$this->table} b
                JOIN schedules s ON b.schedule_id = s.id
                JOIN tours t     ON s.tour_id      = t.id
                ORDER BY b.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = self::$conn->prepare($sql);
        $stmt->bindValue(':limit',  (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset,  PDO::PARAM_INT);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = (int) self::$conn
            ->query("SELECT COUNT(*) FROM {$this->table}")
            ->fetchColumn();

        return [
            'items' => $items,
            'total' => $count,
            'page'  => $page,
            'pages' => (int) ceil($count / $perPage),
        ];
    }

    // ============================================
    // Tăng số lượng đã đặt (trên schedules)
    // ============================================
    public function incBooked($schedule_id)
    {
        $sql = "UPDATE schedules 
                SET booked_count = booked_count + 1
                WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute([$schedule_id]);
    }

    // ============================================
    // Giảm số lượng đã đặt (trên schedules)
    // ============================================
    public function decBooked($schedule_id)
    {
        $sql = "UPDATE schedules 
                SET booked_count = IF(booked_count > 0, booked_count - 1, 0)
                WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute([$schedule_id]);
    }

    // ============================================
    // Phân công hướng dẫn viên
    // ============================================
    public function assignGuide($id, $guide_id)
    {
        $sql = "UPDATE {$this->table}
                SET guide_id = ?
                WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        return $stmt->execute([$guide_id, $id]);
    }

    // ============================================
    // Lưu feedback khách hàng
    // ============================================
    public function saveFeedback($id, $feedback)
    {
        $sql = "UPDATE {$this->table}
                SET feedback = ?
                WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        return $stmt->execute([$feedback, $id]);
    }

    // ============================================
    // Lấy booking gần đây cho Dashboard
    // ============================================
    public function getRecentBookings($limit = 5)
    {
        $sql = "SELECT 
                    b.id,
                    b.customer_name,
                    b.status,
                    b.quantity,
                    b.total_price,
                    b.booking_date,
                    b.created_at,
                    s.tour_id,
                    s.start_date,
                    t.name AS tour_name
                FROM {$this->table} b
                JOIN schedules s ON b.schedule_id = s.id
                JOIN tours t     ON s.tour_id      = t.id
                ORDER BY b.id DESC
                LIMIT :limit";

        $stmt = self::$conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}