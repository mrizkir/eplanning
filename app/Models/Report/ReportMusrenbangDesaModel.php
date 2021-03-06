<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

class ReportMusrenbangDesaModel extends ReportModel
{   
    public function __construct($dataReport,$print=true)
    {
        parent::__construct($dataReport); 
        $this->spreadsheet->getProperties()->setTitle("Laporan Musrenbang Tahun ".\HelperKegiatan::getTahunPerencanaan());
        $this->spreadsheet->getProperties()->setSubject("Laporan Musrenbang Tahun ".\HelperKegiatan::getTahunPerencanaan()); 
        if ($print)
        {
            $this->print();             
        }        
    }    
    private function  print()  
    {
        $PmDesaID = $this->dataReport['PmDesaID'];

        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN MUSRENBANG DESA');   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:I1');
        $sheet->setCellValue('A1','LAPORAN MUSRENBANG TINGKAT DESA TAHUN PERENCANAAN '.\HelperKegiatan::getTahunPerencanaan());
        $sheet->mergeCells ('A2:I2');
        $sheet->setCellValue('A2',strtoupper($this->dataReport['Nm_Desa'])); 
        $sheet->mergeCells ('A3:I3');
        $sheet->setCellValue('A3','KABUPATEN BINTAN');
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:I3")->applyFromArray($styleArray);        
        
        $sheet->setCellValue('A5','NO'); 
        $sheet->setCellValue('B5','NAMA KEGIATAN'); 
        $sheet->setCellValue('C5','KELUARAN/OUTPUT'); 
        $sheet->setCellValue('D5','VOLUME'); 
        $sheet->setCellValue('E5','NILAI PAGU');         
        $sheet->setCellValue('F5','PRIORITAS');         
        $sheet->setCellValue('G5','STATUS');         
        $sheet->setCellValue('H5','LOKASI');         
        $sheet->setCellValue('I5','KET.');         
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A5:I5")->applyFromArray($styleArray);
        $sheet->getStyle("A5:I5")->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(11);        
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->getColumnDimension('H')->setWidth(17);
        $sheet->getColumnDimension('I')->setWidth(17);
        
        $data = \DB::table('trUsulanDesa')
                ->select(\DB::raw('"trUsulanDesa"."UsulanDesaID","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Lokasi","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot","trUsulanDesa"."Privilege","trUsulanDesa"."Descr","trUsulanKec"."UsulanKecID"'))
                ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                ->join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                ->where('trUsulanDesa.TA', \HelperKegiatan::getTahunPerencanaan())
                ->where('trUsulanDesa.PmDesaID',$PmDesaID)                                            
                ->orderBy('Prioritas','ASC')
                ->get();
        
        $row=6;
        $total_pagu=0;      
        foreach ($data as $k=>$item)
        {
            $sheet->getRowDimension($row)->setRowHeight(28);
            $sheet->setCellValue("A$row",$k+1);
            $sheet->setCellValue("B$row",$item->NamaKegiatan);
            $sheet->setCellValue("C$row",$item->Output);
            $sheet->setCellValue("D$row",$item->Target_Angka.' '.$item->Target_Uraian);
            $sheet->setCellValue("E$row",\Helper::formatUang($item->NilaiUsulan));
            $sheet->setCellValue("F$row",\HelperKegiatan::getNamaPrioritas($item->Prioritas));
            $sheet->setCellValue("G$row",$item->Privilege==1?'ACC':'DUM');
            $sheet->setCellValue("H$row",$item->Lokasi);
            $sheet->setCellValue("H$row",$item->Descr);
            $row+=1;
        }
        $row-=1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A6:I$row")->applyFromArray($styleArray);
        $sheet->getStyle("A6:I$row")->getAlignment()->setWrapText(true);  

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("B6:D$row")->applyFromArray($styleArray);
        $sheet->getStyle("H6:I$row")->applyFromArray($styleArray);
    }   
}