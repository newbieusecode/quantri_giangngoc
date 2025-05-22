<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['role'], ['admin', 'giam_doc', 'phó_giam_doc'])) {
    echo "⛔️ Bạn không có quyền!";
    exit;
}
require '../config/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM nguoi_dung WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
exit;
