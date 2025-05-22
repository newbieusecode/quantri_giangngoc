<?php
require '../config/db.php';
$result = $conn->query("SELECT * FROM phong_ban");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Danh sách phòng ban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">

<h2 class="mb-4">📋 Danh sách phòng ban</h2>

<!-- Nút mở modal thêm phòng ban -->
<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addPhongBanModal">
    ➕ Thêm phòng ban
</button>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Mã phòng</th>
            <th>Tên phòng</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['ma_phong']) ?></td>
            <td><?= htmlspecialchars($row['ten_phong']) ?></td>
            <td>
                <button 
                    class="btn btn-sm btn-warning btn-edit" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editPhongBanModal"
                    data-ma_phong="<?= htmlspecialchars($row['ma_phong'], ENT_QUOTES) ?>"
                    data-ten_phong="<?= htmlspecialchars($row['ten_phong'], ENT_QUOTES) ?>"
                >✏️ Sửa</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal thêm phòng ban -->
<div class="modal fade" id="addPhongBanModal" tabindex="-1" aria-labelledby="addPhongBanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="save_phongban.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPhongBanModalLabel">➕ Thêm phòng ban mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="addMaPhong" class="form-label">Mã phòng</label>
            <input type="text" class="form-control" id="addMaPhong" name="ma_phong" required>
          </div>
          <div class="mb-3">
            <label for="addTenPhong" class="form-label">Tên phòng</label>
            <input type="text" class="form-control" id="addTenPhong" name="ten_phong" required>
          </div>
          <input type="hidden" name="ma_phong_cu" value=""> <!-- không có giá trị => thêm mới -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-success">Thêm mới</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal sửa phòng ban -->
<div class="modal fade" id="editPhongBanModal" tabindex="-1" aria-labelledby="editPhongBanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="save_phongban.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPhongBanModalLabel">✏️ Sửa phòng ban</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="ma_phong_cu" id="editMaPhongCu">
          <div class="mb-3">
            <label for="editMaPhong" class="form-label">Mã phòng</label>
            <input type="text" class="form-control" id="editMaPhong" name="ma_phong" required>
          </div>
          <div class="mb-3">
            <label for="editTenPhong" class="form-label">Tên phòng</label>
            <input type="text" class="form-control" id="editTenPhong" name="ten_phong" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', () => {
      const maPhong = button.getAttribute('data-ma_phong');
      const tenPhong = button.getAttribute('data-ten_phong');

      document.getElementById('editMaPhongCu').value = maPhong;
      document.getElementById('editMaPhong').value = maPhong;
      document.getElementById('editTenPhong').value = tenPhong;
    });
  });
</script>

</body>
</html>
