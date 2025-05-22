<?php
session_start();

if (!isset($_SESSION['user'], $_SESSION['role'])) {
    die('Bạn chưa đăng nhập hoặc không có quyền truy cập.');
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];
$phong_ban = $_SESSION['phong_ban'] ?? '';

require_once __DIR__ . '/../config/db.php';

$allowed_roles = ['giam_doc', 'pho_giam_doc', 'nhan_vien'];
if (!in_array($role, $allowed_roles) && strtolower($phong_ban) !== 'ke_toan') {
    die('Bạn không có quyền truy cập trang này.');
}

$thang_hien_tai = date('m');
$nam_hien_tai = date('Y');

if (strtolower($phong_ban) === 'ke_toan') {
    $stmt = $conn->prepare("
        SELECT * FROM bien_ban
        WHERE MONTH(ngay_lap) = ? AND YEAR(ngay_lap) = ?
        ORDER BY ngay_lap DESC
    ");
    $stmt->bind_param("ii", $thang_hien_tai, $nam_hien_tai);
} elseif ($role === 'giam_doc' || $role === 'pho_giam_doc') {
    $stmt = $conn->prepare("SELECT * FROM bien_ban ORDER BY ngay_lap DESC");
} else {
    $stmt = $conn->prepare("
        SELECT * FROM bien_ban
        WHERE nguoi_nhan = ? AND trang_thai = 'đã gửi'
        ORDER BY ngay_lap DESC
    ");
    $stmt->bind_param("s", $user);
}

$stmt->execute();
$result = $stmt->get_result();

$content_view = __DIR__ . '/danh_sach_bien_ban_content.php';

include '../layout.php';
