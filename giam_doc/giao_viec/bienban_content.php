<?php
require '../../config/db.php';

// Kiểm tra đăng nhập và role
if (!isset($_SESSION['user'], $_SESSION['role']) || $_SESSION['role'] !== 'giam_doc') {
    die('Bạn không có quyền truy cập trang này.');
}

$user = $_SESSION['user'];

// Lấy danh sách nhân viên để chọn người nhận
$ds_nv = $conn->query("SELECT username FROM nguoi_dung WHERE role NOT IN ('admin','giam_doc','pho_giam_doc')");
if (!$ds_nv) {
    die("Lỗi truy vấn danh sách nhân viên: " . $conn->error);
}

// Lấy danh sách biên bản
$result = $conn->query("SELECT * FROM bien_ban ORDER BY ngay_lap DESC");
if (!$result) {
    die("Lỗi truy vấn biên bản: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Quản lý biên bản - Giám đốc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="p-4">

<h2>Quản lý biên bản - Giám đốc</h2>

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTaoBienBan">➕ Tạo biên bản mới</button>

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
            <th>Hành động</th>
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
            <td>
                <?php if ($row['trang_thai'] === 'chưa gửi'): ?>
                    <a href="/giam_doc/giao_viec/bienban_process.php?send_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" onclick="return confirm('Xác nhận gửi biên bản?')">Gửi</a>
                <?php endif; ?>
                <a href="/giam_doc/giao_viec/bienban_process.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa biên bản?')">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal tạo biên bản -->
<div class="modal fade" id="modalTaoBienBan" tabindex="-1" aria-labelledby="modalTaoBienBanLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="post" action="/giam_doc/giao_viec/bienban_process.php" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTaoBienBanLabel">Tạo biên bản mới</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Tiêu đề</label>
              <input type="text" name="tieu_de" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Nội dung</label>
              <textarea name="noi_dung" id="noi_dung" rows="8" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Số tiền phạt (VNĐ)</label>
              <input type="number" step="0.01" min="0" name="so_tien_phat" class="form-control" value="0" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Người nhận</label>
              <select name="nguoi_nhan" class="form-select" required>
                  <?php
                  $ds_nv->data_seek(0);
                  while ($nv = $ds_nv->fetch_assoc()):
                  ?>
                  <option value="<?= htmlspecialchars($nv['username']) ?>"><?= htmlspecialchars($nv['username']) ?></option>
                  <?php endwhile; ?>
              </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit_tao_bienban" class="btn btn-success">Tạo biên bản</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#noi_dung'))
    .catch(error => {
      console.error(error);
    });
</script>

</body>
</html>
