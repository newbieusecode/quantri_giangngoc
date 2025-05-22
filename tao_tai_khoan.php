<?php
require 'config/db.php';

$accounts = [
    ['giamdoc',      'giam_doc'],
    ['pho_giamdoc',  'phó_giam_doc'],
    ['hanhchinh',    'nhan_vien'],
    ['kinhdoanh',    'nhan_vien'],
    ['thietke',      'nhan_vien'],
    ['muahang',      'nhan_vien'],
    ['khovan',       'nhan_vien'],
    ['ketoan',       'nhan_vien']
];

foreach ($accounts as [$username, $role]) {
    $hash = password_hash('123456', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO nguoi_dung (ten_dang_nhap, mat_khau, vai_tro) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hash, $role);
    $stmt->execute();
}

echo "✅ Đã thêm tài khoản thành công!";
