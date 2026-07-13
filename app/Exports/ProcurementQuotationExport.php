<?php

namespace App\Exports;

use App\Models\Procurement;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProcurementQuotationExport
{
    public function __construct(protected Procurement $procurement)
    {
    }

    public function generate(): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setTitle('Purchase Quotation')
            ->setSubject($this->procurement->procurement_number)
            ->setDescription('Purchase Quotation document');
        
        // Header info
        $sheet->setCellValue('A1', 'FOR QUOTATION');
        $sheet->setCellValue('A2', $this->procurement->procurement_number);
        
        // Quotation details
        $sheet->setCellValue('A4', 'Reference No.:');
        $sheet->setCellValue('B4', $this->procurement->procurement_number);
        $sheet->setCellValue('A5', 'Status:');
        $sheet->setCellValue('B5', $this->procurement->status);
        $sheet->setCellValue('A6', 'Agency/ies:');
        $sheet->setCellValue('B6', $this->procurement->agencies->pluck('name')->filter()->join(', ') ?: 'N/A');
        $sheet->setCellValue('A7', 'Prepared By:');
        $sheet->setCellValue('B7', $this->procurement->preparedBy?->name ?? 'N/A');
        $sheet->setCellValue('A8', 'Date:');
        $sheet->setCellValue('B8', $this->procurement->date_prepared->format('F d, Y'));
        $sheet->setCellValue('A9', 'Delivery Deadline:');
        $sheet->setCellValue('B9', $this->procurement->delivery_deadline?->format('F d, Y') ?? 'N/A');
        
        // Group items by description + brand + unit
        $items = $this->procurement->items;
        $grouped = $items->groupBy(fn($item) => $item->item_description . '|' . ($item->brand ?? '') . '|' . $item->unit);
        
        // Table headers
        $row = 11;
        $sheet->setCellValue('A' . $row, '#');
        $sheet->setCellValue('B' . $row, 'Description');
        $sheet->setCellValue('C' . $row, 'Brand');
        $sheet->setCellValue('D' . $row, 'Agency');
        $sheet->setCellValue('E' . $row, 'Unit');
        $sheet->setCellValue('F' . $row, 'Qty');
        $sheet->setCellValue('G' . $row, 'Unit Price');
        $sheet->setCellValue('H' . $row, 'Subtotal');
        $sheet->setCellValue('I' . $row, 'Total');
        
        // Style headers
        $headerRange = 'A' . $row . ':I' . $row;
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        
        // Items
        $rowNum = 0;
        foreach ($grouped as $groupItems) {
            $rowNum++;
            $first = $groupItems->first();
            $count = $groupItems->count();
            $totalPrice = $groupItems->sum('total_price');
            $allSamePrice = $groupItems->pluck('unit_price')->unique()->filter()->count() <= 1;
            $totalQty = $groupItems->sum('quantity');
            
            foreach ($groupItems as $i => $gi) {
                $row++;
                if ($i === 0) {
                    $sheet->setCellValue('A' . $row, $rowNum);
                    $sheet->setCellValue('B' . $row, $first->item_description);
                    $sheet->setCellValue('C' . $row, $first->brand ?? '-');
                    $sheet->setCellValue('E' . $row, $first->unit);
                    
                    if ($allSamePrice && $first->unit_price) {
                        $sheet->setCellValue('G' . $row, $first->unit_price);
                    }
                }
                
                $sheet->setCellValue('D' . $row, $gi->agency->name ?? 'N/A');
                $sheet->setCellValue('F' . $row, $gi->quantity);
                
                $hasUnitPrice = ($allSamePrice && $first->unit_price) || (!$allSamePrice && $gi->unit_price);

                if ($hasUnitPrice) {
                    $sheet->setCellValue('H' . $row, $gi->total_price);
                }

                if ($i === 0 && $first->unit_price) {
                    $sheet->setCellValue('I' . $row, $totalPrice);
                }
            }
        }
        
        // Total row
        $row++;
        $sheet->setCellValue('H' . $row, 'TOTAL AMOUNT:');
        $sheet->setCellValue('I' . $row, $this->procurement->total_amount);
        $sheet->getStyle('H' . $row . ':I' . $row)->getFont()->setBold(true);
        $sheet->getStyle('H' . $row . ':I' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');
        
        // Format currency cells
        $sheet->getStyle('H12:H' . ($row - 1))->getNumberFormat()
            ->setFormatCode('₱#,##0.00');
        $sheet->getStyle('I12:I' . ($row - 1))->getNumberFormat()
            ->setFormatCode('₱#,##0.00');
        if ($this->procurement->total_amount) {
            $sheet->getStyle('I' . $row)->getNumberFormat()
                ->setFormatCode('₱#,##0.00');
        }
        
        // Auto-size columns
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Right-align numbers
        $sheet->getStyle('F12:I' . $row)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Write to output buffer
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_contents();
        ob_end_clean();
        
        return $excelOutput;
    }
    
    public function fileName(): string
    {
        return 'quotation-' . str_replace('/', '-', $this->procurement->procurement_number) . '.xlsx';
    }
}