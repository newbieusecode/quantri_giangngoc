<?php
$result = $conn->query("SELECT * FROM nguoi_dung");
?>

<h2 class="mb-4">📋 Danh sách người dùng</h2>

<!-- Nút mở modal thêm người dùng mới -->
<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
    ➕ Thêm người dùng
</button>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Tên đăng nhập</th>
            <th>Vai trò</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <!-- Nút mở modal sửa -->
                <button type="button" 
                    class="btn btn-sm btn-warning btn-edit" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editUserModal"
                    data-id="<?= $row['user_id'] ?>"
                    data-username="<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>"
                    data-role="<?= $row['role'] ?>"
                >✏️ Sửa</button>

                <a href="delete.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa?')">🗑️ Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal sửa người dùng -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editUserForm" method="post" action="edit_save.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">✏️ Sửa người dùng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="user_id" id="editUserId">

          <div class="mb-3">
            <label for="editUsername" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="editUsername" name="username" required>
          </div>

          <div class="mb-3">
            <label for="editPassword" class="form-label">Mật khẩu mới (nếu đổi)</label>
            <input type="password" class="form-control" id="editPassword" name="mat_khau" placeholder="Để trống nếu không đổi">
          </div>

          <div class="mb-3">
            <label for="editRole" class="form-label">Vai trò</label>
            <select class="form-select" id="editRole" name="role" required>
              <option value="nhan_vien">Nhân viên</option>
              <option value="phó_giam_doc">Phó giám đốc</option>
              <option value="giam_doc">Giám đốc</option>
              <option value="admin">Admin</option>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal thêm người dùng mới -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addUserForm" method="post" action="add_save.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">➕ Thêm người dùng mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">

          <div class="mb-3">
            <label for="addUsername" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="addUsername" name="username" required>
          </div>

          <div class="mb-3">
            <label for="addPassword" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="addPassword" name="mat_khau" required>
          </div>

          <div class="mb-3">
            <label for="addRole" class="form-label">Vai trò</label>
            <select class="form-select" id="addRole" name="role" required>
              <option value="nhan_vien">Nhân viên</option>
              <option value="phó_giam_doc">Phó giám đốc</option>
              <option value="giam_doc">Giám đốc</option>
              <option value="admin">Admin</option>
            </select>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-success">Thêm mới</button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS (bắt buộc) và script xử lý modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Khi modal sửa mở, điền dữ liệu user vào form
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', () => {
      const userId = button.getAttribute('data-id');
      const username = button.getAttribute('data-username');
      const role = button.getAttribute('data-role');

      document.getElementById('editUserId').value = userId;
      document.getElementById('editUsername').value = username;
      document.getElementById('editRole').value = role;
      document.getElementById('editPassword').value = ''; // reset mật khẩu mới
    });
  });
</script>
