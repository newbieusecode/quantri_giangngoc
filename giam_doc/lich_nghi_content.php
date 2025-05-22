<?php
// Hàm tính tuần trong tháng (giữ nguyên)
function getWeekOfMonth($date) {
    $firstDay = date('Y-m-01', strtotime($date));
    $weekOfMonth = date('W', strtotime($date)) - date('W', strtotime($firstDay)) + 1;
    if ($weekOfMonth <= 0) $weekOfMonth = 1;
    return $weekOfMonth;
}

$today = $_POST['week_start'] ?? $existing_schedule['week_start'] ?? date('Y-m-d');
$weekNumberInMonth = getWeekOfMonth($today);

function getWeekRange($date) {
    $ts = strtotime($date);
    $dayOfWeek = date('N', $ts);
    $monday = date('Y-m-d', strtotime("-".($dayOfWeek - 1)." days", $ts));
    $sunday = date('Y-m-d', strtotime("+".(7 - $dayOfWeek)." days", $ts));
    return [$monday, $sunday];
}

list($weekStart, $weekEnd) = getWeekRange($today);

$selectedOffDays = $_POST['off_days'] ?? ($existing_schedule['off_days'] ?? []);
if (!is_array($selectedOffDays)) $selectedOffDays = [];

// Bảng chuyển đổi tên ngày tiếng Anh sang tiếng Việt
$dayNamesVi = [
    'Mon' => 'Thứ Hai',
    'Tue' => 'Thứ Ba',
    'Wed' => 'Thứ Tư',
    'Thu' => 'Thứ Năm',
    'Fri' => 'Thứ Sáu',
    'Sat' => 'Thứ Bảy',
    'Sun' => 'Chủ Nhật'
];
?>

<style>
.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
.char-count {
    font-size: 0.85rem;
    color: #6c757d;
    float: right;
}
.form-section {
    background-color: #f8f9fa;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
}
.section-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #0d6efd;
}
label small {
    font-size: 0.85rem;
}
</style>

<h2 class="mb-4 text-primary">📝 Đăng ký lịch làm việc tuần</h2>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($errors): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" class="needs-validation" novalidate>
    <div class="form-section">
        <div class="section-title">🗓️ Tuần làm việc</div>
        <p class="mb-2">Tuần thứ <span class="text-primary fw-bold fs-5"><?= $weekNumberInMonth ?></span> trong tháng</p>
        <p class="text-muted mb-0">Thời gian: <strong><?= date('d/m/Y', strtotime($weekStart)) ?></strong> - <strong><?= date('d/m/Y', strtotime($weekEnd)) ?></strong></p>
        <input type="hidden" name="week_start" value="<?= htmlspecialchars($weekStart) ?>">
    </div>

    <div class="form-section">
        <div class="section-title">📌 Chọn ngày nghỉ</div>
        <div class="btn-group d-flex flex-wrap" role="group" aria-label="Ngày nghỉ">
            <?php for ($i=0; $i<7; $i++):
                $date = date('Y-m-d', strtotime("$weekStart +$i days"));
                $dayShort = date('D', strtotime($date));
                $dayNameVi = $dayNamesVi[$dayShort] ?? $dayShort;
                $dayNum = date('N', strtotime($date));
                $checked = in_array($date, $selectedOffDays) ? 'checked' : '';
            ?>
                <input type="checkbox" class="btn-check" name="off_days[]" id="off_<?= $dayNum ?>" autocomplete="off" value="<?= $date ?>" <?= $checked ?>>
                <label class="btn btn-outline-primary m-1" for="off_<?= $dayNum ?>" style="min-width: 120px;">
                    <?= $dayNameVi ?> <br><small><?= date('d/m', strtotime($date)) ?></small>
                </label>
            <?php endfor; ?>
        </div>
    </div>

    <div class="form-section">
        <div class="section-title">✍️ Lý do nghỉ</div>
        <label for="task" class="form-label fw-semibold">Lý do <small>(tối đa 500 ký tự)</small></label>
        <textarea id="task" name="task" class="form-control" rows="4" maxlength="500" required><?= htmlspecialchars($_POST['task'] ?? $existing_schedule['task'] ?? '') ?></textarea>
        <div class="char-count" id="task-char-count">0 / 500</div>
        <div class="invalid-feedback">Vui lòng nhập lý do.</div>
    </div>

    <div class="form-section">
        <div class="section-title">🗒️ Ghi chú thêm</div>
        <label for="note" class="form-label fw-semibold">Ghi chú <small>(không bắt buộc, tối đa 300 ký tự)</small></label>
        <textarea id="note" name="note" class="form-control" rows="3" maxlength="300"><?= htmlspecialchars($_POST['note'] ?? $existing_schedule['note'] ?? '') ?></textarea>
        <div class="char-count" id="note-char-count">0 / 300</div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary position-relative" id="submit-btn">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true" id="loading-spinner"></span>
            Gửi đăng ký
        </button>
        <a href="index.php" class="btn btn-secondary ms-2">Quay lại</a>
    </div>
</form>

<script>
(() => {
    'use strict';
    const form = document.querySelector('form');
    const task = document.getElementById('task');
    const note = document.getElementById('note');
    const taskCount = document.getElementById('task-char-count');
    const noteCount = document.getElementById('note-char-count');
    const submitBtn = document.getElementById('submit-btn');
    const spinner = document.getElementById('loading-spinner');

    const updateCount = (el, countEl) => {
        countEl.textContent = `${el.value.length} / ${el.getAttribute('maxlength')}`;
    };
    task.addEventListener('input', () => updateCount(task, taskCount));
    note.addEventListener('input', () => updateCount(note, noteCount));
    updateCount(task, taskCount);
    updateCount(note, noteCount);

    form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
    });
})();
</script>
