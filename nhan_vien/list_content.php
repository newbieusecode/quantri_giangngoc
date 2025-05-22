<?php
require '../config/db.php';

$sql = "SELECT nv.*, pb.ten_phong
        FROM nhan_vien nv
        JOIN phong_ban pb ON nv.phong_ban_id = pb.ma_phong";


$result = $conn->query($sql);

if (!$result) {
    // Hiển thị lỗi truy vấn SQL để debug
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<h2>Danh sách nhân viên</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>Họ tên</th>
        <th>Email</th>
        <th>SĐT</th>
        <th>Phòng ban</th>
        <th>Lương (VNĐ)</th>
    </tr>
    <?php while ($nv = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($nv['ma_nv']) ?></td>
        <td><?= htmlspecialchars($nv['so_dien_thoai']) ?></td>
        <td><?= htmlspecialchars($nv['ten_phong']) ?></td>
        <td><?= number_format($nv['luong'], 0, ',', '.') ?></td>
    </tr>
    <?php endwhile; ?>
</table>
