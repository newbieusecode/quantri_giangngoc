<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$content_view = __DIR__ . '/index_content.php';
include 'layout.php';
