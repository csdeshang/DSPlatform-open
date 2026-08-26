<?php

namespace app\deshang\utils;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Excel 导出工具（基于 PhpSpreadsheet）
 */
class ExcelExporter
{
    /**
     * 将表头与数据行输出为 xlsx 下载响应
     *
     * @param array $headers 表头（一维字符串数组）
     * @param array $rows 数据行（二维数组，每行与表头列对齐）
     * @param string $filename 下载文件名（不含路径，建议含 .xlsx）
     */
    public static function download(array $headers, array $rows, string $filename): void
    {
        if (!str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');

        $colCount = count($headers);
        for ($i = 0; $i < $colCount; $i++) {
            $cell = self::columnLetter($i + 1) . '1';
            $sheet->setCellValue($cell, $headers[$i]);
        }

        $headerRange = 'A1:' . self::columnLetter($colCount) . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F3F5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $rowIndex = 2;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach ($row as $value) {
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                $sheet->setCellValue(self::columnLetter($colIndex) . $rowIndex, $value ?? '');
                $colIndex++;
            }
            $rowIndex++;
        }

        for ($i = 1; $i <= $colCount; $i++) {
            $sheet->getColumnDimension(self::columnLetter($i))->setAutoSize(true);
        }

        // 避免中文文件名在部分浏览器乱码
        $encodedFilename = rawurlencode($filename);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"; filename*=UTF-8''{$encodedFilename}");
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }

    /**
     * 列序号转 Excel 列字母（1 -> A）
     */
    private static function columnLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = intdiv($index - 1, 26);
        }
        return $letter;
    }
}
