<?php
// Biến $result đã có từ file cha
?>

<h2>Danh sách biên bản phạt</h2>

<?php if ($result->num_rows === 0): ?>
    <p>Không có biên bản nào phù hợp.</p>
<?php else: ?>
<table class="table table-bordered table-hover">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Người lập</th>
            <th>Người nhận</th>
            <th>Tiêu đề</th>
            <th>Nội dung</th>
            <th>Số tiền phạt (VNĐ)</th>
            <th>Ngày lập</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nguoi_lap']) ?></td>
            <td><?= htmlspecialchars($row['nguoi_nhan']) ?></td>
            <td><?= htmlspecialchars($row['tieu_de']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['noi_dung'])) ?></td>
            <td><?= number_format($row['so_tien_phat'], 0, ',', '.') ?></td>
            <td><?= date('d/m/Y H:i', strtotime($row['ngay_lap'])) ?></td>
            <td><?= htmlspecialchars($row['trang_thai']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>
