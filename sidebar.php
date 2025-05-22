<?php
$role = $_SESSION['role'];
$current = basename($_SERVER['PHP_SELF']);

function is_active($target) {
    return strpos($_SERVER['REQUEST_URI'], $target) !== false ? 'active' : '';
}
?>

<h5 class="text-white text-center mb-4">📁 MENU CHÍNH</h5>
<ul class="nav flex-column">
    <li><a href="/index.php" class="<?= is_active('index.php') ?>">🏠 Trang chính</a></li>

    <?php if (in_array($role, ['admin', 'giam_doc', 'phó_giam_doc'])): ?>
        <li><a href="/thu_vien/danh_sach_bien_ban.php" class="<?= is_active('thu_vien') ?>">📨 Biên Bản Phạt</a></li>
        <li><a href="/nguoi_dung/list.php" class="<?= is_active('nguoi_dung') ?>">👤 Người dùng</a></li>
        <li><a href="/nhan_vien/list.php" class="<?= is_active('nhan_vien') ?>">👥 Nhân viên</a></li>
        <li><a href="/phong_ban/list.php" class="<?= is_active('phong_ban') ?>">🏢 Phòng ban</a></li>
    <?php endif; ?>

    <?php if ($role === 'giam_doc'): ?>
    <li><a href="/giam_doc/lich_nghi.php" class="<?= is_active('lich_nghi') ?>">📨 Đăng Ký Lịch Trực BGĐ</a></li>
    <li><a href="/giam_doc/dashboard.php" class="<?= is_active('dashboard') ?>">📊 Báo cáo & Điều hành</a></li>
    <li><a href="/giam_doc/giao_viec/giaoviec.php" class="<?= is_active('giaoviec') ?>">📌 Giao việc</a></li>
    <li><a href="/giam_doc/giao_viec/bienban_process.php" class="<?= is_active('bienban') ?>">📨 Lập Biên Bản</a></li>
    <?php endif; ?>

    <?php if ($role === 'hanhchinh'): ?>
        <li><a href="/thu_vien/danh_sach_bien_ban.php" class="<?= is_active('nha_vien') ?>">📨 Biên Bản Phạt</a></li>
        <li><a href="/nhan_su/list.php" class="<?= is_active('nhan_su') ?>">📋 Hồ sơ nhân sự</a></li>
    <?php endif; ?>

    <?php if ($role === 'kinhdoanh'): ?>
        <li><a href="/nha_vien/lich_nghi.php" class="<?= is_active('nha_vien') ?>">📨 Đăng Ký Lịch Làm Việc</a></li>
        <li><a href="/khach_hang/list.php" class="<?= is_active('khach_hang') ?>">🧾 Khách hàng</a></li>
        <li><a href="/don_hang/list.php" class="<?= is_active('don_hang') ?>">🛒 Đơn hàng</a></li>
    <?php endif; ?>

    <?php if ($role === 'thietke'): ?>danh_sach_bien_ban.php
        <li><a href="/thu_vien/danh_sach_bien_ban.php" class="<?= is_active('nha_vien') ?>">📨 Biên Bản Phạt</a></li>
        <li><a href="/nhan_vien/lich_nghi.php" class="<?= is_active('nha_vien') ?>">📨 Đăng Ký Lịch Làm Việc</a></li>
        <li><a href="/tai_lieu/list.php" class="<?= is_active('tai_lieu') ?>">🖼️ Tài liệu thiết kế</a></li>
    <?php endif; ?>

    <?php if ($role === 'muahang'): ?>
        <li><a href="/thu_vien/danh_sach_bien_ban.php" class="<?= is_active('nha_vien') ?>">📨 Biên Bản Phạt</a></li>
        <li><a href="/nha_vien/lich_nghi.php" class="<?= is_active('nha_vien') ?>">📨 Đăng Ký Lịch Làm Việc</a></li>
        <li><a href="/bao_gia/list.php" class="<?= is_active('bao_gia') ?>">📨 Gửi báo giá</a></li>
        <li><a href="/nha_cung_cap/list.php" class="<?= is_active('nha_cung_cap') ?>">🏭 Nhà cung cấp</a></li>
    <?php endif; ?>

    <?php if ($role === 'khovan'): ?>
        <li><a href="/thu_vien/danh_sach_bien_ban.php" class="<?= is_active('nha_vien') ?>">📨 Biên Bản Phạt</a></li>
        <li><a href="/nha_vien/lich_nghi.php" class="<?= is_active('nha_vien') ?>">📨 Đăng Ký Lịch Làm Việc</a></li>
        <li><a href="/kho_hang/list.php" class="<?= is_active('kho_hang') ?>">📦 Tồn kho</a></li>
        <li><a href="/xuat_nhap/list.php" class="<?= is_active('xuat_nhap') ?>">📤 Nhập / Xuất hàng</a></li>
    <?php endif; ?>

    <?php if ($role === 'ketoan'): ?>
        <li><a href="/thu_vien/danh_sach_bien_ban.php" class="<?= is_active('nha_vien') ?>">📨 Biên Bản Phạt</a></li>
        <li><a href="/nha_vien/lich_nghi.php" class="<?= is_active('nha_vien') ?>">📨 Đăng Ký Lịch Làm Việc</a></li>
        <li><a href="/bang_luong/list.php" class="<?= is_active('bang_luong') ?>">💰 Bảng lương</a></li>
        <li><a href="/hoa_don/list.php" class="<?= is_active('hoa_don') ?>">🧾 Hóa đơn</a></li>
    <?php endif; ?>

    <hr class="text-white">
    <li><a href="/logout.php" class="text-warning">🚪 Đăng xuất</a></li>
</ul>
