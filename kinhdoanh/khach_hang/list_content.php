<?php
$result = $conn->query("SELECT * FROM khach_hang");
?>

<h2 class="mb-4">🧾 Danh sách khách hàng</h2>
<a href="add.php" class="btn btn-primary mb-3">➕ Thêm khách hàng</a>

<table class="table table-bordered table-hover table-striped shadow-sm bg-white rounded">
    <thead class="table-primary">
        <tr>
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Địa chỉ</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['ten_khach']) ?></td>
            <td><?= htmlspecialchars($row['sdt']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['dia_chi']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
