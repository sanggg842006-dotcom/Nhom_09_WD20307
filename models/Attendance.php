<?php
require_once __DIR__ . '/BaseModel.php';

class Attendance extends BaseModel {
    protected $table = 'attendances';

    // Lấy danh sách khách cần điểm danh cho 1 lịch khởi hành
    public function getRosterBySchedule($scheduleId) {
        $sql = "
            SELECT 
                b.id AS booking_id,
                b.status AS booking_status,
                c.id AS customer_id,
                c.name AS customer_name,
                c.phone AS customer_phone,
                c.email AS customer_email,
                a.status AS attendance_status,
                a.note AS attendance_note
            FROM bookings b
            JOIN customers c ON c.id = b.customer_id
            LEFT JOIN attendances a 
                ON a.booking_id = b.id AND a.schedule_id = :sid
            WHERE b.schedule_id = :sid
            ORDER BY b.id DESC
        ";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute([':sid' => $scheduleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lưu điểm danh (upsert)
    public function saveForSchedule($scheduleId, $rows) {
        $sql = "
            INSERT INTO attendances (schedule_id, booking_id, customer_id, status, note, checked_at)
            VALUES (:schedule_id, :booking_id, :customer_id, :status, :note, NOW())
            ON DUPLICATE KEY UPDATE
                status = VALUES(status),
                note = VALUES(note),
                checked_at = NOW()
        ";
        $stmt = self::$conn->prepare($sql);

        foreach ($rows as $r) {
            $stmt->execute([
                ':schedule_id' => $scheduleId,
                ':booking_id'  => $r['booking_id'],
                ':customer_id' => $r['customer_id'],
                ':status'      => $r['status'],
                ':note'        => $r['note'],
            ]);
        }
    }

    // Thống kê nhanh theo lịch
    public function countStatusBySchedule($scheduleId) {
        $sql = "SELECT status, COUNT(*) as total
                FROM attendances 
                WHERE schedule_id = ?
                GROUP BY status";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute([$scheduleId]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
