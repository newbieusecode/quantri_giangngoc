<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['user'], $_SESSION['role']) || $_SESSION['role'] !== 'giam_doc') {
    die('Bạn không có quyền truy cập trang này.');
}

$user = $_SESSION['user'];

// Xử lý POST thêm biên bản mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_tao_bienban'])) {
    $tieu_de = $_POST['tieu_de'];
    $noi_dung = $_POST['noi_dung'];
    $nguoi_nhan = $_POST['nguoi_nhan'];
    $so_tien_phat = floatval($_POST['so_tien_phat'] ?? 0);
    $nguoi_lap = $user;
    $trang_thai = 'chưa gửi';

    $stmt = $conn->prepare("INSERT INTO bien_ban (nguoi_lap, nguoi_nhan, tieu_de, noi_dung, so_tien_phat, trang_thai) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdss", $nguoi_lap, $nguoi_nhan, $tieu_de, $noi_dung, $so_tien_phat, $trang_thai);

    if (!$stmt->execute()) {
        die("Lỗi khi thêm biên bản: " . $stmt->error);
    }

    // Có thể thêm thông báo thành công bằng session ở đây
    $_SESSION['popup_notify'] = [
        'title' => 'Thành công',
        'message' => 'Tạo biên bản thành công!',
        'icon' => 'success'
    ];

    header("Location: bienban_process.php"); // redirect để tránh submit lại form
    exit();
}

// Xử lý gửi biên bản
if (isset($_GET['send_id'])) {
    $id_send = intval($_GET['send_id']);
    $conn->query("UPDATE bien_ban SET trang_thai = 'đã gửi' WHERE id = $id_send");
    header("Location: bienban_process.php");
    exit();
}

// Xử lý xóa biên bản
if (isset($_GET['delete_id'])) {
    $id_del = intval($_GET['delete_id']);
    $conn->query("DELETE FROM bien_ban WHERE id = $id_del");
    header("Location: bienban_process.php");
    exit();
}

// Lấy danh sách biên bản mới nhất để hiển thị
$result = $conn->query("SELECT * FROM bien_ban ORDER BY ngay_lap DESC");
if (!$result) {
    die("Lỗi truy vấn biên bản: " . $conn->error);
}

// Gán biến để gọi trong giao diện
$content_view = __DIR__ . '/bienban_content.php';

// Gọi layout chung
include '../../layout.php';
