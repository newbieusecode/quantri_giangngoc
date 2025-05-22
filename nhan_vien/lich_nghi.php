<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = null;

// Lấy lịch tuần nếu truyền param để chỉnh sửa
$week_start_get = $_GET['week_start'] ?? null;
if ($week_start_get) {
    $week_start_get = date('Y-m-d', strtotime($week_start_get));
    $stmt = $pdo->prepare("SELECT * FROM lich_nghi WHERE employee_id = ? AND week_start = ?");
    $stmt->execute([$user_id, $week_start_get]);
    $existing_schedule = $stmt->fetch();
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

        // Tạo work_days: mặc định là "Làm việc", nếu thuộc off_days thì "Nghỉ"
        $work_days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = date('Y-m-d', strtotime("$week_start_date +$i days"));
            $work_days[$day] = in_array($day, $off_days) ? 'Nghỉ' : 'Làm việc';
        }

        $stmt = $pdo->prepare("SELECT id FROM lich_nghi WHERE employee_id = ? AND week_start = ?");
        $stmt->execute([$user_id, $week_start_date]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $pdo->prepare("UPDATE lich_nghi 
                SET task = ?, note = ?, work_days = ?, updated_at = NOW() 
                WHERE id = ?");
            $stmt->execute([$task, $note, json_encode($work_days, JSON_UNESCAPED_UNICODE), $existing['id']]);
            $success = "Cập nhật lịch làm việc tuần thành công.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO lich_nghi 
                (employee_id, week_start, week_end, task, note, work_days, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $user_id,
                $week_start_date,
                $week_end_date,
                $task,
                $note,
                json_encode($work_days, JSON_UNESCAPED_UNICODE)
            ]);
            $success = "Đăng ký lịch làm việc tuần thành công.";
        }
    }
}

$content_view = __DIR__ . '/lich_nghi_content.php';
include '../layout.php';