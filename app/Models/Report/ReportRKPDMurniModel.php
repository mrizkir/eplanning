<?php

namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


use App\Models\RKPD\RKPDMurniModel;

class ReportRKPDMurniModel 
{   
    
    private static $dataReport = array();

    public function __construct($dataReport)
    {
        self::$dataReport = $dataReport;
    }  
    
    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,            
        ];
    }    
    public function array() : array 
    {
        $OrgID = self::$dataReport['OrgID'];
        $data = RKPDMurniModel::select(\DB::raw('"kode_kegiatan","Uraian","Sasaran_Angka1","Sasaran_Uraian1","Target1","NilaiUsulan1","Nm_SumberDana"'))
                                ->where('OrgID',$OrgID)
                                ->where('TA',config('globalsettings.tahun_perencanaan'))
                                ->get()->toArray();

        $rkpd=array();
        foreach ($data as $v) 
        {
            $rkpd[]=array(
                'kode_kegiatan'=>$v['kode_kegiatan'],
                'Uraian'=>$v['Uraian'],
                'Sasaran_Angka1'=>$v['Sasaran_Angka1'],
                'Sasaran_Uraian1'=>$v['Sasaran_Uraian1'],
                'Target1'=>$v['Target1'],
                'NilaiUsulan1'=>$v['NilaiUsulan1'],
                'Nm_SumberDana'=>$v['Nm_SumberDana']
            );
        }
        return $rkpd;
    }
    public static function beforeExport(BeforeExport $event)
    {        
        $event->writer->getProperties()->setCreator(config('globalsettings.nama_institusi'));
        $event->writer->getProperties()->setLastModifiedBy(config('globalsettings.nama_institusi'));
        $event->writer->getProperties()->setTitle("Laporan RKPD Tahun ".config('globalsettings.tahun_perencanaan'));
        $event->writer->getProperties()->setSubject("Laporan RKPD Tahun ".config('globalsettings.tahun_perencanaan'));        
    }
    public static function beforeWriting(BeforeWriting $event)
    {
       
    }
    public static function beforeSheet(BeforeSheet $event)
    {
        $sheet = $event->sheet;
        $sheet->setTitle ('LAPORAN RKPD TA '.config('globalsettings.tahun_perencanaan'));   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);

        $sheet->mergeCells ('A1:K1');
        $sheet->setCellValue('A1','RUMUSAN PROGRAM DAN KEGIATAN OPD TAHUN '.config('globalsettings.tahun_perencanaan'));

        $n1 = config('globalsettings.tahun_perencanaan')+1;
        $sheet->mergeCells ('A2:K2');
        $sheet->setCellValue('A2','DAN PRAKIRAAN MAJU TAHUN '.$n1);

        $sheet->mergeCells ('A3:K3');
        $sheet->setCellValue('A3','KABUPATEN BINTAN');

        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:A3")->applyFromArray($styleArray);
        
        $sheet->mergeCells ('A4:G4');
        $sheet->setCellValue('A4','');  
        
        $OrgID = self::$dataReport['OrgID'];
        $sheet->setCellValue('A5','NAMA OPD / SKPD : '.$OrgID); 
        
        $sheet->mergeCells ('A6:A7');
        $sheet->setCellValue('A6','KODE'); 
        $sheet->mergeCells ('B6:B7');
        $sheet->setCellValue('B6','URUSAN/BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('C6:C7');
        $sheet->setCellValue('C6','INDIKATOR KINERJA PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('D6:G6');
        $sheet->setCellValue('D6','RENCANA TAHUN '.config('globalsettings.tahun_perencanaan')); 
        $sheet->mergeCells ('H6:J6');
        $sheet->setCellValue('H6','PERKIRAAN MAJU RENCANA TAHUN '.$n1);
        $sheet->mergeCells ('K6:K7');
        $sheet->setCellValue('K6','KETERANGAN'); 
        
        $sheet->setCellValue('D7','LOKASI'); 
        $sheet->setCellValue('E7','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('F7','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('G7','SUMBER DANA'); 
        $sheet->setCellValue('H7','LOKASI');         
        $sheet->setCellValue('I7','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J7','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        
        $sheet->setCellValue('A8',1); 
        $sheet->setCellValue('B8',2); 
        $sheet->setCellValue('C8',3); 
        $sheet->setCellValue('D8',4); 
        $sheet->setCellValue('E8',5); 
        $sheet->setCellValue('F8',6); 
        $sheet->setCellValue('G8',7); 
        $sheet->setCellValue('H8',8); 
        $sheet->setCellValue('I8',9); 
        $sheet->setCellValue('J8',10); 
        $sheet->setCellValue('K8',11); 
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet;
        $sheet->getDelegate()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);          
        
        $sheet->getColumnDimension('A')->setWidth(17);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(17);
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->getColumnDimension('H')->setWidth(11);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(17);
        $sheet->getColumnDimension('K')->setWidth(12);
        

        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A6:K8")->applyFromArray($styleArray);
        $sheet->getStyle("A6:K8")->getAlignment()->setWrapText(true);

        $row=$sheet->getHighestRow();
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A9:K$row")->applyFromArray($styleArray);
        $sheet->getStyle("A9:K$row")->getAlignment()->setWrapText(true);
    }
}