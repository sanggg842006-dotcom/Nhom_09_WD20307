<h1>Sửa nhân sự</h1>
<form method="post" action="index.php?c=Guide&a=update">
    <input type="hidden" name="id" value="<?= $guide['id'] ?>">
    <p>Họ tên: <input type="text" name="name" value="<?= htmlspecialchars($guide['name']) ?>" required></p>
    <p>Điện thoại: <input type="text" name="phone" value="<?= htmlspecialchars($guide['phone']) ?>"></p>
    <p>Email: <input type="email" name="email" value="<?= htmlspecialchars($guide['email']) ?>"></p>
    <p>Địa chỉ: <input type="text" name="address" value="<?= htmlspecialchars($guide['address']) ?>"></p>
    <button type="submit">Cập nhật</button>
</form>
