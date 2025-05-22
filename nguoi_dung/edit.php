<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['role'], ['admin', 'giam_doc', 'phó_giam_doc'])) {
    echo "⛔️ Bạn không có quyền!";
    exit;
}
require '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID người dùng không hợp lệ!";
    exit;
}

// Lấy thông tin người dùng theo id
$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "Người dùng không tồn tại!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? '';

    // Tạo câu SQL và tham số
    $sql = "UPDATE nguoi_dung SET username=?, role=?";
    $params = [$username, $role];

    if (!empty($_POST['mat_khau'])) {
        $password = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
        $sql .= ", password_hash=?";
        $params[] = $password;
    }

    $sql .= " WHERE user_id=?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    $types = str_repeat("s", count($params) - 1) . "i";
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    header("Location: list.php");
    exit;
}

$content_view = __DIR__ . '/edit_content.php';
include '../layout.php';
