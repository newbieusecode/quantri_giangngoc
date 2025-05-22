<?php
require '../config/db.php';

// Hàm hỗ trợ an toàn
function get_value($sql, $default = 0) {
    global $conn;
    $result = $conn->query($sql);
    if (!$result) {
        echo "<p class='text-danger'>❌ Lỗi truy vấn: " . $conn->error . "</p>";
        return $default;
    }
    $row = $result->fetch_assoc();
    return $row['total'] ?? $default;
}

// Truy vấn thống kê tổng hợp
$tong_nv     = get_value("SELECT COUNT(*) AS total FROM nhan_vien");
$tong_don    = get_value("SELECT COUNT(*) AS total FROM don_hang");
$ton_kho     = get_value("SELECT SUM(so_luong) AS total FROM kho_hang");
$doanh_thu   = get_value("SELECT SUM(tong_tien) AS total FROM don_hang");
?>

<h2 class="mb-4">📊 Báo cáo điều hành</h2>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <h5 class="card-title">👥 Nhân viên</h5>
                <p class="display-6"><?= $tong_nv ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info shadow-sm">
            <div class="card-body">
                <h5 class="card-title">🛒 Đơn hàng</h5>
                <p class="display-6"><?= $tong_don ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning shadow-sm">
            <div class="card-body">
                <h5 class="card-title">📦 Hàng tồn</h5>
                <p class="display-6"><?= $ton_kho ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <h5 class="card-title">💰 Doanh thu</h5>
                <p class="display-6"><?= number_format($doanh_thu, 0, ',', '.') ?> đ</p>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<h4>📝 Các yêu cầu đang chờ duyệt</h4>
<ul class="list-group shadow-sm">
    <?php
    $yeu_cau = $conn->query("SELECT * FROM yeu_cau WHERE trang_thai = 'chờ duyệt' ORDER BY ngay_gui DESC LIMIT 5");
    if (!$yeu_cau) {
        echo "<li class='list-group-item text-danger'>❌ Lỗi: " . $conn->error . "</li>";
    } elseif ($yeu_cau->num_rows === 0) {
        echo "<li class='list-group-item'>✅ Không có yêu cầu chờ duyệt.</li>";
    } else {
        while ($row = $yeu_cau->fetch_assoc()):
    ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($row['tieu_de']) ?>
            <span class="badge bg-secondary"><?= date('d/m/Y', strtotime($row['ngay_gui'])) ?></span>
        </li>
    <?php endwhile; } ?>
</ul>
