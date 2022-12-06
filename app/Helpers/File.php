<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\ConditionalStyles;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class File extends Model
{
    public static function download($path){
        if(isset($path))
        {
        //Read the url
        $url = $path;

        //Clear the cache
        clearstatcache();

        //Check the file path exists or not
        if(file_exists($url)) {

        //Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($url).'"');
        header('Content-Length: ' . filesize($url));
        header('Pragma: public');

        //Clear system output buffer
        flush();

        //Read the size of the file
        readfile($url,true);

        //Terminate from the script
        die();
        }
        else{
        echo "File path does not exist.";
        }
        }
        echo "File path is not defined.";
    }

    public static function getSpreadSheet($datas){
        // Read a Exist Excel File Format
        $reader = IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load("excel/Daftar NIP untuk QR Code Pegawai Packing.xlsx");

        // Sheet 2
        $sheet = $spreadsheet->getSheet(1)->setTitle("Tahap 2");

        $i=2;
        foreach($datas as $emp){
            $qr_text = strtoupper(substr($emp->employee()->first()->name,0,1)).substr($emp->employee()->first()->nip, 5);
            $sheet->setCellValueByColumnAndRow(14,$i, $qr_text);
            $sheet->setCellValueByColumnAndRow(15,$i, $emp->employee()->first()->name);
            $i++;
        }

        $sheet = $spreadsheet->getSheet(0)->setTitle("Tahap 1");
        $sheet->getStyle('A2');

        return $spreadsheet;
    }

    public static function getNewSpreadSheet($datas){
        $spreadsheet = new Spreadsheet();

        // Sheet 2
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(1)->setTitle("Tahap 2");

        for ($i = 'A'; $i !=  'Q'; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(TRUE);
        }
        $sheet->setCellValueByColumnAndRow(1, 1, 'No');
        $sheet->setCellValueByColumnAndRow(2, 1, 'ID Pesanan');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Nama Toko');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Channel');
        $sheet->setCellValueByColumnAndRow(5, 1, 'SKU');
        $sheet->setCellValueByColumnAndRow(6, 1, 'Jumlah');
        $sheet->setCellValueByColumnAndRow(7, 1, 'Produk');
        $sheet->setCellValueByColumnAndRow(8, 1, 'Harga Promosi');
        $sheet->setCellValueByColumnAndRow(9, 1, 'Kurir');
        $sheet->setCellValueByColumnAndRow(10, 1, 'Kode Packer =VLOOKUP(B2;\'Tahap 1\'!$A$2:$B$27;2;0)');
        $sheet->setCellValueByColumnAndRow(11, 1, 'Nama Packer =VLOOKUP(J2;$O$2:$P$50;2;0)');
        $sheet->setCellValueByColumnAndRow(15, 1, 'Kode Packing QR');
        $sheet->setCellValueByColumnAndRow(16, 1, 'Nama');

        $styleArray = [
            'fill' => [
                'fillType' => Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'C0C0C0'],
            ],
            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]
        ];

        $styleArrayRef = [
            'fill' => [
                'fillType' => Style\Fill::FILL_SOLID,
                'color' => ['argb' => '778899'],
            ],
            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $sheet->getStyle('A1:K1')->applyFromArray($styleArray);
        $sheet->getStyle('O1:P1')->applyFromArray($styleArrayRef);
        $sheet->getStyle('A2');

        $i=2;
        foreach($datas as $emp){
            $qr_text = strtoupper(substr($emp->employee()->first()->name,0,1)).substr($emp->employee()->first()->nip, 5);
            $sheet->setCellValueByColumnAndRow(15,$i, $qr_text);
            $sheet->setCellValueByColumnAndRow(16,$i, $emp->employee()->first()->name);
            $i++;
        }

        $sheet = $spreadsheet->getSheet(0)->setTitle("Tahap 1");

        // Sheet 1
        for ($i = 'A'; $i !=  'C'; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(TRUE);
        }
        $sheet->setCellValueByColumnAndRow(1, 1, 'Scan Invoice disini');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Scan Kode QR Disini');

        $styleArray = [
            'fill' => [
                'fillType' => Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'C0C0C0'],
            ],
            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]
        ];

        $sheet->getStyle('A1:B1')->applyFromArray($styleArray);
        $sheet->getStyle('A2');

        return $spreadsheet;
    }

    public static function getNewStandartSpreadSheet($datas){
        $spreadsheet = new Spreadsheet();

        // Sheet 2
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(0);

        $last_letter = 'A';
        if(count($datas) != 0){
            $header = array_keys($datas[0]);
        }else{
            $header = ["Data is Empty"];
        }

        for($i=0; $i<count($header); $i++){
            ++$last_letter;
            $sheet->setCellValueByColumnAndRow($i+1, 1, $header[$i]);
        }
        for ($i = 'A'; $i != $last_letter; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $styleArray = [
            'fill' => [
                'fillType' => Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'C0C0C0'],
            ],
            'alignment' => [
                'horizontal' => Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]
        ];

        $cell = 'A1:'.chr(ord($last_letter) - 1).'1';
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle('A2');

        if(count($datas) != 0){
            for($i=0; $i<count($datas); $i++){
                for($j=0; $j<count($header); $j++){
                    $sheet->setCellValueByColumnAndRow($j+1,$i+2,$datas[$i][$header[$j]]);
                }
            }
        }

        $sheet->getStyle('A2');

        return $spreadsheet;
    }
}
