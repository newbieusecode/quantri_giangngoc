<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
    exit;
}
$content_view = $content_view ?? ''; // đảm bảo biến tồn tại
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>GNC Furniture - Hệ thống</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background-color: #D62828;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1000;
            color: white;
            border-bottom-right-radius: 16px;
        }

        .logo-img {
            height: 36px;
            width: auto;
            margin-right: 10px;
            object-fit: contain;
            background-color: white;
            padding: 6px;
            border-radius: 50%;
            box-shadow: 0 0 4px rgba(0,0,0,0.1);
        }

        .user-box {
            margin-left: auto;
            color: white;
            font-size: 15px;
        }

        .user-box strong {
            color: #ffc107;
        }

        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            bottom: 0;
            width: 200px;
            background-color: #0096D6;
            color: white;
            padding: 1.5rem 1rem;
            border-left: 8px solid #007BB5;
            box-shadow: 4px 0 12px rgba(0,0,0,0.15);
            z-index: 999;
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
            transition: transform 0.3s ease;
        }

        .sidebar a {
            color: white;
            border-radius: 8px;
            padding: 10px 14px;
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateX(4px);
        }

        .sidebar a.active {
            background-color: #007BB5;
            font-weight: bold;
            box-shadow: inset 4px 0 0 #ffc107;
        }

        .content-area {
            margin-left: 200px;
            padding: 80px 2rem 2rem;
            min-height: 100vh;
        }

        #overlay {
            position: fixed;
            top: 56px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 998;
            display: none;
        }

        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-220px);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0;
                padding: 80px 1rem 2rem;
            }

            #overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Giao diện header -->
<?php include __DIR__ . '/header.php'; ?>

<!-- Sidebar cố định -->
<div class="sidebar" id="sidebar">
    <button class="btn btn-close btn-close-white d-md-none mb-3" id="closeSidebar"></button>
    <?php include __DIR__ . '/sidebar.php'; ?>
</div>

<!-- Overlay khi mở sidebar -->
<div id="overlay" class="d-md-none"></div>

<!-- Nội dung chính -->
<div class="content-area">
    <?php include $content_view; ?>
</div>

<!-- JS bật/tắt sidebar mobile -->
<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const closeBtn = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.add('show');
        overlay.classList.add('show');
    });

    closeBtn?.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
</script>

</body>
</html>

<!-- Bootstrap JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

