<?php
require '../../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    die('Bạn cần đăng nhập để truy cập trang này');
}
$user = $_SESSION['user'];

// Lấy danh sách nhân viên để chọn người nhận (chỉ truy vấn 1 lần)
$ds_nv = $conn->query("SELECT username FROM nguoi_dung WHERE role NOT IN ('admin','giam_doc','pho_giam_doc')");
if (!$ds_nv) {
    die("Lỗi truy vấn danh sách nhân viên: " . $conn->error);
}

// Xử lý form giao việc
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_giao_viec'])) {
    $tieu_de = $_POST['tieu_de'];
    $mo_ta = $_POST['mo_ta'];
    $nguoi_giao = $user;
    $nguoi_nhan = $_POST['nguoi_nhan'];
    $han_hoan_thanh = $_POST['han_hoan_thanh'];
    $trang_thai = 'chờ xử lý';

    $stmt = $conn->prepare("INSERT INTO cong_viec (tieu_de, mo_ta, nguoi_giao, nguoi_nhan, han_hoan_thanh, trang_thai) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $tieu_de, $mo_ta, $nguoi_giao, $nguoi_nhan, $han_hoan_thanh, $trang_thai);
    $stmt->execute();

    // Sau khi thêm xong redirect về trang danh sách
    header("Location: list.php");
    exit();
}

// Lấy danh sách công việc đã giao bởi user hiện tại
$result = $conn->query("SELECT * FROM cong_viec WHERE nguoi_giao = '$user' ORDER BY ngay_giao DESC");
if (!$result) {
    die("Lỗi truy vấn công việc: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giao việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 class="mb-4">📋 Danh sách công việc đã giao</h2>

<div class="mb-3 d-flex justify-content-between">
    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#giaoViecModal">➕ Giao việc mới</button>
    <a href="/giam_doc/giao_viec/export_excel_giaoviec.php" class="btn btn-outline-primary">📥 Xuất Excel</a>
</div>

<table class="table table-bordered table-hover table-striped shadow-sm bg-white rounded">
    <thead class="table-primary">
        <tr>
            <th>STT</th>
            <th>Tiêu đề</th>
            <th>Người nhận</th>
            <th>Hạn hoàn thành</th>
            <th>Trạng thái</th>
            <th>Chi tiết</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['tieu_de']) ?></td>
            <td><?= htmlspecialchars($row['nguoi_nhan']) ?></td>
            <td><?= date('d/m/Y', strtotime($row['han_hoan_thanh'])) ?></td>
            <td>
                <span class="badge bg-<?= match ($row['trang_thai']) {
                    'chờ xử lý' => 'secondary',
                    'đang làm' => 'warning',
                    'hoàn thành' => 'success',
                    default => 'light'
                } ?>"><?= htmlspecialchars($row['trang_thai']) ?></span>
            </td>
            <td><?= $row['mo_ta'] /* Hiển thị rich text, không escape */ ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal Giao việc -->
<div class="modal fade" id="giaoViecModal" tabindex="-1" aria-labelledby="giaoViecLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="giaoViecLabel">🆕 Giao việc cho nhân viên</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">Tiêu đề</label>
            <input type="text" name="tieu_de" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Chi tiết công việc</label>
            <textarea name="mo_ta" id="mo_ta" rows="10" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Người nhận</label>
            <select name="nguoi_nhan" class="form-select" required>
                <?php 
                // Reset pointer $ds_nv trước khi vòng lặp select
                $ds_nv->data_seek(0);
                while ($nv = $ds_nv->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($nv['username']) ?>"><?= htmlspecialchars($nv['username']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Hạn hoàn thành</label>
            <input type="date" name="han_hoan_thanh" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit_giao_viec" class="btn btn-primary">💼 Giao việc</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- CKEditor 5 CDN + script kích hoạt -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#mo_ta'))
    .catch(error => {
        console.error(error);
    });
</script>

</body>
</html>
