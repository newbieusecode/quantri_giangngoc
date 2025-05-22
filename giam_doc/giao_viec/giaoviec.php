<?php
session_start();

// Xử lý giao việc ở đây
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Giả sử $success là kết quả xử lý
    $success = true; // hoặc false tùy kết quả thực tế

    if ($success) {
        $_SESSION['popup_notify'] = [
            'title' => 'Thành công',
            'message' => 'Bạn đã được giao việc thành công!',
            'icon' => 'success'
        ];
    } else {
        $_SESSION['popup_notify'] = [
            'title' => 'Lỗi',
            'message' => 'Giao việc không thành công, vui lòng thử lại.',
            'icon' => 'error'
        ];
    }
    header("Location: giaoviec.php");
    exit();
}

$content_view = __DIR__ . '/giaoviec_content.php';
include '../../layout.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giao việc</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/toast.js"></script>
</head>
<body>

<!-- Nội dung trang -->

<?php if (isset($_SESSION['popup_notify'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        showPopupNotification(
            <?php echo json_encode($_SESSION['popup_notify']['title']); ?>,
            <?php echo json_encode($_SESSION['popup_notify']['message']); ?>,
            <?php echo json_encode($_SESSION['popup_notify']['icon']); ?>
        );
    });
</script>
<?php unset($_SESSION['popup_notify']); endif; ?>

</body>
</html>
