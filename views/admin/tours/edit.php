<h1>Sửa tour</h1>
<form method="post" action="index.php?c=Tour&a=update">
    <input type="hidden" name="id" value="<?= $tour['id'] ?>">
    <p>
        Tên tour: <input type="text" name="name" value="<?= htmlspecialchars($tour['name']) ?>" required>
    </p>
    <p>
        Giá: <input type="number" name="price" value="<?= $tour['price'] ?>" required>
    </p>
    <p>
        Thời lượng: <input type="text" name="duration" value="<?= htmlspecialchars($tour['duration']) ?>">
    </p>
    <p>
        Ngày khởi hành: <input type="date" name="start_date" value="<?= $tour['start_date'] ?>">
    </p>
    <p>
        Mô tả: <textarea name="description"><?= htmlspecialchars($tour['description']) ?></textarea>
    </p>
    <button type="submit">Cập nhật</button>
</form>
