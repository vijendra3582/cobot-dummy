<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet\Cell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; 
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StructuredOutcomeEtfExport implements FromCollection, WithHeadings,WithHeadingRow, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data;

    public function __construct($data, $AS_OF_DATE=null)
    { 
        $this->data = $data;
        $this->AS_OF_DATE = $AS_OF_DATE;
    }

    public function collection()
    {
        return $this->data;
    }


    public function headingRow(): int
    {
        return 3;
    }

    public function headings(): array
    {
        return [
            [
                ' STRUCTURED OUTCOME ETFs '
            ],
            [],
            [
                $this->AS_OF_DATE
            ],
            [
                'Ticker',
                'Name',
                'Series',
                'Fund Price',
                'Fund Return',
                'Index',
                'Index Return',
                'Est. Upside Market Participation Rate',
                'Remaining Buffer',
                'ETF Downside to Buffer',
                'S&P Downside to Floor of Buffer',
                'Remaining Outcome Period'
            ]   
        ];
    }

    

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                
                
                $event->sheet->getDelegate()->getStyle('A1:W1')->getFont()->setSize(16);
                $event->sheet->getDelegate()->getStyle('A1:W1')->getFont()->setBold(500); 
                $event->sheet->getDelegate()->getStyle('A1:W1')->getAlignment()->applyFromArray(
                    array('horizontal' => 'center')
                );

                $event->sheet->getDelegate()->getStyle('A3:W3')->getFont()->setBold(500);
                $event->sheet->getDelegate()->getStyle('A4:W4')->getFont()->setBold(500);

                $event->sheet->getDelegate()->getStyle('A4:W4')->getAlignment()->applyFromArray(
                    array('horizontal' => 'center')
                );

                $event->sheet->mergeCells('A1:L1');     
            },
        ];
    }
    
    // public function columnFormats(): array
    // {
    //     return [
    //         'F' => NumberFormat::FORMAT_DATE_DDMMYYYY
    //     ];
    // }

}
