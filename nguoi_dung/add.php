<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['role'], ['admin', 'giam_doc', 'phó_giam_doc'])) {
    echo "⛔️ Bạn không có quyền truy cập!";
    exit;
}
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['ten_dang_nhap'];
    $password = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
    $role = $_POST['vai_tro'];

    $stmt = $conn->prepare("INSERT INTO nguoi_dung (ten_dang_nhap, mat_khau, vai_tro) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    header("Location: list.php");
    exit;
}
?>

<h2>Thêm người dùng</h2>
<form method="post">
    <label>Tên đăng nhập:</label><br>
    <input type="text" name="ten_dang_nhap" required><br>
    <label>Mật khẩu:</label><br>
    <input type="password" name="mat_khau" required><br>
    <label>Vai trò:</label><br>
    <select name="vai_tro">
        <option value="nhan_vien">nhân viên</option>
        <option value="phó_giam_doc">phó giám đốc</option>
        <option value="giam_doc">giám đốc</option>
        <option value="admin">admin</option>
    </select><br><br>
    <button type="submit">Lưu</button>
</form>
