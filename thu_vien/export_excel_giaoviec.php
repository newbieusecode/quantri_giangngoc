<?php
require '../vendor/autoload.php'; // Composer
require '../config/db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Khởi tạo file Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề lớn
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'DANH SÁCH CÔNG VIỆC ĐƯỢC GIAO');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header bảng
$sheet->fromArray(['STT', 'Tiêu đề', 'Người nhận', 'Hạn hoàn thành', 'Trạng thái', 'Chi tiết'], NULL, 'A3');
$sheet->getStyle('A3:F3')->getFont()->setBold(true);
$sheet->getStyle('A3:F3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
$sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Lấy dữ liệu từ DB
$sql = "SELECT * FROM cong_viec ORDER BY ngay_giao DESC";
$result = $conn->query($sql);

$rowIndex = 4;
$stt = 1;

while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowIndex", $stt);
    $sheet->setCellValue("B$rowIndex", $row['tieu_de']);
    $sheet->setCellValue("C$rowIndex", $row['nguoi_nhan']);
    $sheet->setCellValue("D$rowIndex", date('d/m/Y', strtotime($row['han_hoan_thanh'])));
    $sheet->setCellValue("E$rowIndex", $row['trang_thai']);

    // Xử lý chi tiết (JSON hoặc mô tả thường)
    $moTa = $row['mo_ta'];
    $text = json_decode($moTa, true);
    if (is_array($text)) {
        $lines = array_map(fn($line) => implode(" • ", $line), $text);
        $moTaText = implode("\n", $lines);
    } else {
        $moTaText = $moTa;
    }

    $sheet->setCellValue("F$rowIndex", $moTaText);
    $sheet->getStyle("F$rowIndex")->getAlignment()->setWrapText(true);

    // Tô màu tùy theo trạng thái
    $fill = match ($row['trang_thai']) {
        'chờ xử lý' => 'FFF3CD', // vàng nhạt
        'đang làm' => 'D1ECF1',  // xanh nhạt
        'hoàn thành' => 'D4EDDA', // xanh lá nhạt
        default => 'FFFFFF'
    };
    $sheet->getStyle("A$rowIndex:F$rowIndex")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($fill);

    $rowIndex++;
    $stt++;
}

// Tự động căn lề & độ rộng
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
$sheet->getStyle("A4:F$rowIndex")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

// Xuất file
$filename = "giao_viec_" . date('Ymd_His') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
