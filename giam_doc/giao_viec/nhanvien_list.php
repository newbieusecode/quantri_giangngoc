<?php
session_start();
require '../config/db.php';
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['trang_thai'];

    $stmt = $conn->prepare("UPDATE cong_viec SET trang_thai = ? WHERE id = ? AND nguoi_nhan = ?");
    $stmt->bind_param("sis", $status, $id, $user);
    $stmt->execute();
    header("Location: nhanvien_list.php");
    exit;
}

$result = $conn->query("SELECT * FROM cong_viec WHERE nguoi_nhan = '$user' ORDER BY ngay_giao DESC");
?>

<h2>Công việc của bạn</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Hạn hoàn thành</th>
            <th>Trạng thái</th>
            <th>Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['tieu_de']) ?></td>
            <td><?= date('d/m/Y', strtotime($row['han_hoan_thanh'])) ?></td>
            <td>
                <span class="badge bg-<?= match ($row['trang_thai']) {
                    'chờ xử lý' => 'secondary',
                    'đang làm' => 'warning',
                    'hoàn thành' => 'success',
                    default => 'light'
                } ?>"><?= $row['trang_thai'] ?></span>
            </td>
            <td>
                <form method="post" class="d-flex gap-2 align-items-center">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <select name="trang_thai" class="form-select form-select-sm" required>
                        <option value="chờ xử lý" <?= $row['trang_thai'] == 'chờ xử lý' ? 'selected' : '' ?>>⏳ Chờ xử lý</option>
                        <option value="đang làm" <?= $row['trang_thai'] == 'đang làm' ? 'selected' : '' ?>>🛠️ Đang làm</option>
                        <option value="hoàn thành" <?= $row['trang_thai'] == 'hoàn thành' ? 'selected' : '' ?>>✅ Hoàn thành</option>
                    </select>
                    <button type="submit" name="update_status" class="btn btn-sm btn-primary">Cập nhật</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
