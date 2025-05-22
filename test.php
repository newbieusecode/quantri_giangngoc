<?php
require 'config/db.php';

$new_password = '123456'; // mật khẩu mới
$username = 'giamdoc';   // username cần đổi mật khẩu

$hash = password_hash($new_password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE nguoi_dung SET password_hash = ? WHERE username = ?");
$stmt->bind_param("ss", $hash, $username);
if ($stmt->execute()) {
    echo "Đã cập nhật mật khẩu hash thành công!";
} else {
    echo "Lỗi: " . $stmt->error;
}
