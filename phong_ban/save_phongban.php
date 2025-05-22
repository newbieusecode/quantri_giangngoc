<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list_content.php'); // hoặc trang danh sách phòng ban
    exit;
}

$ma_phong_cu = $_POST['ma_phong_cu'] ?? null; // nếu có là sửa, không có là thêm mới
$ma_phong = $_POST['ma_phong'] ?? '';
$ten_phong = $_POST['ten_phong'] ?? '';

if (!$ma_phong || !$ten_phong) {
    die('Vui lòng nhập đầy đủ thông tin.');
}

if ($ma_phong_cu) {
    // Cập nhật phòng ban
    $stmt = $conn->prepare("UPDATE phong_ban SET ma_phong = ?, ten_phong = ? WHERE ma_phong = ?");
    $stmt->bind_param("sss", $ma_phong, $ten_phong, $ma_phong_cu);
    if ($stmt->execute()) {
        header('Location: list_content.php?msg=update_success');
        exit;
    } else {
        die("Lỗi cập nhật phòng ban: " . $stmt->error);
    }
} else {
    // Thêm phòng ban mới
    // Kiểm tra mã phòng đã tồn tại chưa
    $checkStmt = $conn->prepare("SELECT ma_phong FROM phong_ban WHERE ma_phong = ?");
    $checkStmt->bind_param("s", $ma_phong);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows > 0) {
        die("Mã phòng đã tồn tại, vui lòng chọn mã khác.");
    }

    $stmt = $conn->prepare("INSERT INTO phong_ban (ma_phong, ten_phong) VALUES (?, ?)");
    $stmt->bind_param("ss", $ma_phong, $ten_phong);
    if ($stmt->execute()) {
        header('Location: list_content.php?msg=add_success');
        exit;
    } else {
        die("Lỗi thêm phòng ban: " . $stmt->error);
    }
}
