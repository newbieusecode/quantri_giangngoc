<?php
session_start();
if ($_SESSION['role'] !== 'giam_doc') {
    echo "⛔ Bạn không có quyền truy cập!";
    exit;
}
require '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tieu_de = $_POST['tieu_de'];
    $mo_ta = $_POST['mo_ta'];
    $nguoi_giao = $_SESSION['user'];
    $nguoi_nhan = $_POST['nguoi_nhan'];
    $han = $_POST['han_hoan_thanh'];

    $stmt = $conn->prepare("INSERT INTO cong_viec (tieu_de, mo_ta, nguoi_giao, nguoi_nhan, han_hoan_thanh) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $tieu_de, $mo_ta, $nguoi_giao, $nguoi_nhan, $han);
    $stmt->execute();
    header("Location: list.php");
    exit;
}

$ds_nv = $conn->query("SELECT ten_dang_nhap FROM nguoi_dung WHERE vai_tro = 'nhan_vien'");
?>

<h2 class="mb-4">🆕 Giao việc cho nhân viên</h2>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Tiêu đề công việc</label>
        <input type="text" name="tieu_de" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Mô tả công việc</label>
        <textarea name="mo_ta" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Người nhận</label>
        <select name="nguoi_nhan" class="form-select" required>
            <?php while ($nv = $ds_nv->fetch_assoc()): ?>
                <option value="<?= $nv['ten_dang_nhap'] ?>"><?= $nv['ten_dang_nhap'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Hạn hoàn thành</label>
        <input type="date" name="han_hoan_thanh" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">💼 Giao việc</button>
</form>
