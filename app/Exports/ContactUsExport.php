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
use App\ContactUs;
class ContactUsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents,WithHeadingRow
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
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
                'Truemark | Contact-Us DB '
            ],
            [],
            [
                'Name',
                'Email',
                'Phone',
                'Investor Type', 
                'Message',
                'Added On'
            ]   
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                
                $event->sheet->getDelegate()->getStyle('A3:W3')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A3:W3')->getFont()->setBold(500); 
                $event->sheet->getDelegate()->getStyle('A1:F1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1:F1')->getFont()->setBold(500); 
                $event->sheet->getDelegate()->getStyle('A1:F1')->getFont()->setBold(500); 
                $event->sheet->getDelegate()->getStyle('A1:F1')->getAlignment()->applyFromArray(
                    array('horizontal' => 'center')
                ); 
                $event->sheet->mergeCells('A1:F1');     
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
