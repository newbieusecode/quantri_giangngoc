<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['role'], ['admin', 'giam_doc', 'phó_giam_doc'])) {
    echo "⛔ Bạn không có quyền truy cập trang này!";
    exit;
}
require '../config/db.php';

$content_view = __DIR__ . '/list_content.php';
include '../layout.php';
