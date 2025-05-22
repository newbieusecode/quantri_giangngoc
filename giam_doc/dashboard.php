<?php
session_start();
if ($_SESSION['role'] !== 'giam_doc') {
    echo "⛔ Bạn không có quyền truy cập!";
    exit;
}
require '../config/db.php';
$content_view = __DIR__ . '/dashboard_content.php';
include '../layout.php';
