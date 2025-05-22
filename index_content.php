<?php
$role = $_SESSION['role'];
$ten_dang_nhap = $_SESSION['user'];
$ho_ten = $_SESSION['ho_ten'] ?? null;

$ten_hien_thi = ($ho_ten && trim($ho_ten) !== '') ? $ho_ten : $ten_dang_nhap;

function role_label($role) {
    switch ($role) {
        case 'admin': return 'Admin hệ thống';
        case 'giam_doc': return 'Giám đốc';
        case 'phó_giam_doc': return 'Phó giám đốc';
        case 'nhan_vien': return 'Nhân viên';
        default: return ucfirst($role);
    }
}
?>

<div class="p-4 bg-light rounded">
    <h2>🎉 Chào mừng <?= htmlspecialchars($ten_hien_thi) ?>!</h2>
    <p class="lead">Bạn đang đăng nhập với vai trò: <strong><?= role_label($role) ?></strong></p>

    <hr>

    <div class="row g-4 mt-3">
        <?php if (in_array($role, ['admin', 'giam_doc', 'phó_giam_doc'])): ?>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">👤 Quản lý người dùng</h5>
                    <p class="card-text">Thêm, sửa, phân quyền tài khoản.</p>
                    <a href="/nguoi_dung/list.php" class="btn btn-primary">Vào quản lý</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">👥 Nhân viên</h5>
                    <p class="card-text">Quản lý thông tin nhân viên theo phòng ban.</p>
                    <a href="/nhan_vien/list.php" class="btn btn-success">Xem danh sách</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🏢 Phòng ban</h5>
                    <p class="card-text">Xem, cập nhật thông tin các phòng trong công ty.</p>
                    <a href="/phong_ban/list.php" class="btn btn-info">Danh sách phòng</a>
                </div>
            </div>
        </div>
    </div>
</div>
