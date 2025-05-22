<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$ho_ten = $_SESSION['ho_ten'] ?? $_SESSION['user'] ?? 'Khách';
?>
<!-- Navbar -->
<div class="navbar-custom">
    <button class="btn btn-sm btn-light d-md-none me-3" id="toggleSidebar">☰</button>
    <a class="navbar-brand" href="/index.php">
        <img src="/assets/logo.png" alt="Logo" class="logo-img">
        GNC Furniture - Hệ thống
    </a>
    <div class="user-box">
        👋 Xin chào, <strong><?= htmlspecialchars($ho_ten) ?></strong>
    </div>
</div>
