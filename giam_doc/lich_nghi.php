<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null; // Đây phải là số ID hợp lệ, ví dụ 9, 10, 11...

if (!$user_id) {
    die("Bạn chưa đăng nhập hoặc session bị lỗi");
}

// Kiểm tra quyền
$role = $_SESSION['role'] ?? null;
if ($role !== 'giam_doc') {
    echo "⛔ Bạn không có quyền truy cập!";
    exit;
}

require_once '../config/db.php'; // $conn là MySQLi

$errors = [];
$success = null;

// Lấy tuần cần chỉnh sửa nếu có
$week_start_get = $_GET['week_start'] ?? null;
if ($week_start_get) {
    $week_start_get = date('Y-m-d', strtotime($week_start_get));
    $stmt = $conn->prepare("SELECT * FROM lich_nghi WHERE week_start = ?");
    $stmt->bind_param("s", $week_start_get);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_schedule = $result->fetch_assoc();
} else {
    $existing_schedule = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week_start = $_POST['week_start'] ?? '';
    $task = trim($_POST['task'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $off_days = $_POST['off_days'] ?? [];

    if (!$week_start) {
        $errors[] = "Vui lòng chọn tuần bắt đầu.";
    }
    if ($task === '') {
        $errors[] = "Vui lòng nhập nội dung công việc.";
    }

    if (empty($errors)) {
        $week_start_date = date('Y-m-d', strtotime($week_start));
        $week_end_date = date('Y-m-d', strtotime($week_start_date . ' +6 days'));

        // Tạo mảng work_days
        $work_days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = date('Y-m-d', strtotime("$week_start_date +$i days"));
            $work_days[$day] = in_array($day, $off_days) ? 'Nghỉ' : 'Làm việc';
        }

        $stmt = $conn->prepare("SELECT id FROM lich_nghi WHERE week_start = ?");
        $stmt->bind_param("s", $week_start_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing = $result->fetch_assoc();

        $json_work_days = json_encode($work_days, JSON_UNESCAPED_UNICODE);

        if ($existing) {
            $stmt = $conn->prepare("UPDATE lich_nghi SET task=?, note=?, work_days=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param("sssi", $task, $note, $json_work_days, $existing['id']);
            if (!$stmt->execute()) {
                $errors[] = "Lỗi cập nhật lịch: " . $stmt->error;
            } else {
                $success = "Cập nhật lịch làm việc tuần thành công.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO lich_nghi (week_start, week_end, task, note, work_days, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("sssss", $week_start_date, $week_end_date, $task, $note, $json_work_days);
            if (!$stmt->execute()) {
                $errors[] = "Lỗi thêm lịch: " . $stmt->error;
            } else {
                $success = "Đăng ký lịch làm việc tuần thành công.";
            }
        }
    }
}

// Hiển thị lỗi và thành công (bạn có thể chuyển vào file giao diện)
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>⚠ $error</p>";
    }
}
if ($success) {
    echo "<p style='color:green;'>✅ $success</p>";
}

// Tiếp tục include giao diện
$content_view = __DIR__ . '/lich_nghi_content.php';
include '../layout.php';
