<?php
$sql = "SELECT dh.*, kh.ten_khach FROM don_hang dh 
        LEFT JOIN khach_hang kh ON dh.khach_id = kh.id";
$result = $conn->query($sql);
?>

<h2 class="mb-4">🛒 Danh sách đơn hàng</h2>
<a href="add.php" class="btn btn-primary mb-3">➕ Tạo đơn hàng</a>

<table class="table table-bordered table-hover table-striped shadow-sm bg-white rounded">
    <thead class="table-primary">
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Ngày tạo</th>
            <th>Tổng tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['ma_don']) ?></td>
            <td><?= htmlspecialchars($row['ten_khach']) ?></td>
            <td><?= htmlspecialchars($row['ngay_tao']) ?></td>
            <td><?= number_format($row['tong_tien'], 0, ',', '.') ?> đ</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
