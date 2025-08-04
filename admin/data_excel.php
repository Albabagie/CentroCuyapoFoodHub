<?php
require 'session.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No. #');
$sheet->setCellValue('B1', 'Item Order Count');
$sheet->setCellValue('C1', 'Order type');
$sheet->setCellValue('D1', 'Order Date');
$sheet->setCellValue('E1', 'Order total');



$list_of = "SELECT 
    (SELECT COUNT(over_name) FROM over_items WHERE over_id = od.over_id) AS item_total, 
    od.over_id, 
    od.over_date AS date, 
    ot.over_tamt AS total
FROM over_total ot
LEFT JOIN over_orders od ON od.over_id = ot.over_id
UNION
SELECT 
    (SELECT COUNT(item_name) FROM order_items WHERE order_id = o.order_id) AS item_total, 
    o.order_id, 
    o.order_date AS date, 
    ot.total_amt AS total
FROM order_total ot
LEFT JOIN orders o ON o.order_id = ot.order_id 
ORDER BY date DESC";

$result_of = $conn->query($list_of);

if ($result_of->num_rows > 0) {
    $rowNumber = 2;
    $counter = 1;
    while ($row_of = $result_of->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $counter);
        $sheet->setCellValue('B' . $rowNumber, $row_of['item_total']);
        $sheet->setCellValue('C' . $rowNumber, $row_of['over_id'] ? "Counter" : "App Order");
        $sheet->setCellValue('D' . $rowNumber, $row_of['date']);
        $sheet->setCellValue('E' . $rowNumber, $row_of['total']);
        $rowNumber++;
        $counter++;
    }
}


$writer = new Xlsx($spreadsheet);
$date = date('Y-m-d'); 
$fileName = "order_list_$date.xlsx";
$writer->save($fileName);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

readfile($fileName);


unlink($fileName); 
$conn->close();
exit;
