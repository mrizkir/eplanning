<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

class ReportMusrenbangKecamatanModel extends ReportModel
{   
    public function __construct($dataReport,$print=true)
    {
        parent::__construct($dataReport); 
        $this->spreadsheet->getProperties()->setTitle("Laporan Musrenbang Tahun ".\HelperKegiatan::getTahunPerencanaan());
        $this->spreadsheet->getProperties()->setSubject("Laporan Musrenbang Tahun ".\HelperKegiatan::getTahunPerencanaan()); 
        if ($print)
        {
            switch ($dataReport['mode'])
            {
                case 'bypmkecamatanid' :
                    $this->print();
                break;
                case 'byorgid' :
                    $this->printByOrgID();
                break;
                
            }
                         
        }        
    }    
    private function  print()  
    {
        $PmKecamatanID = $this->dataReport['PmKecamatanID'];

        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN MUSRENBANG KECAMATAN');   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:J1');
        $sheet->setCellValue('A1','LAPORAN MUSRENBANG TINGKAT KECAMATAN TAHUN PERENCANAAN '.\HelperKegiatan::getTahunPerencanaan());
        $sheet->mergeCells ('A2:J2');
        $sheet->setCellValue('A2',strtoupper($this->dataReport['Nm_Kecamatan'])); 
        $sheet->mergeCells ('A3:J3');
        $sheet->setCellValue('A3','KABUPATEN BINTAN');
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:J3")->applyFromArray($styleArray);        
        
        $sheet->setCellValue('A5','NO'); 
        $sheet->setCellValue('B5','NAMA KEGIATAN'); 
        $sheet->setCellValue('C5','KELUARAN/OUTPUT'); 
        $sheet->setCellValue('D5','VOLUME'); 
        $sheet->setCellValue('E5','NILAI PAGU');         
        $sheet->setCellValue('F5','PRIORITAS');         
        $sheet->setCellValue('G5','STATUS');         
        $sheet->setCellValue('H5','DESA/KELURAHAN');         
        $sheet->setCellValue('I5','LOKASI');         
        $sheet->setCellValue('J5','KET.');         
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A5:J5")->applyFromArray($styleArray);
        $sheet->getStyle("A5:J5")->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(11);        
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(17);
        
        $data = \DB::table('trUsulanKec')
                ->select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","tmPmDesa"."Nm_Desa","trUsulanKec"."No_usulan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Lokasi","trUsulanKec"."Prioritas","trUsulanKec"."Bobot","trUsulanKec"."Privilege","trUsulanKec"."Descr"'))
                ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')                                                                                                
                ->where('trUsulanKec.TA', \HelperKegiatan::getTahunPerencanaan())
                ->where('trUsulanKec.PmKecamatanID',$PmKecamatanID)
                ->orderBy('trUsulanKec.Prioritas','ASC')
                ->orderBy("NamaKegiatan",'ASC')
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
            $sheet->setCellValue("H$row",empty($item->Nm_Desa)?'USULAN KEC.':$item->Nm_Desa);
            $sheet->setCellValue("I$row",$item->Lokasi);
            $sheet->setCellValue("J$row",$item->Descr);
            $row+=1;
        }
        $row-=1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A6:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("A6:J$row")->getAlignment()->setWrapText(true);  

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("B6:D$row")->applyFromArray($styleArray);
        $sheet->getStyle("H6:J$row")->applyFromArray($styleArray);
    }   
    /**
     * cetak berdasarkan OrgID
     */
    private function  printByOrgID()  
    {
        $OrgID = $this->dataReport['OrgID'];

        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN MUSRENBANG KECAMATAN');   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:J1');
        $sheet->setCellValue('A1','LAPORAN MUSRENBANG TINGKAT KECAMATAN TAHUN PERENCANAAN '.\HelperKegiatan::getTahunPerencanaan());        
        $sheet->mergeCells ('A2:J2');
        $sheet->setCellValue('A2','KABUPATEN BINTAN');
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:J2")->applyFromArray($styleArray);        
        
        $sheet->setCellValue('A4','NAMA OPD / SKPD : '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']'); 
        
        $sheet->setCellValue('A6','NO'); 
        $sheet->setCellValue('B6','NAMA KEGIATAN'); 
        $sheet->setCellValue('C6','KELUARAN/OUTPUT'); 
        $sheet->setCellValue('D6','VOLUME'); 
        $sheet->setCellValue('E6','NILAI PAGU');         
        $sheet->setCellValue('F6','PRIORITAS');         
        $sheet->setCellValue('G6','STATUS');         
        $sheet->setCellValue('H6','KECAMATAN');         
        $sheet->setCellValue('I6','DESA/KELURAHAN');         
        $sheet->setCellValue('J6','LOKASI');         
        $sheet->setCellValue('K6','KET.');         
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A6:K6")->applyFromArray($styleArray);
        $sheet->getStyle("A6:K6")->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(11);        
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(17);
        
        $data = \DB::table('trUsulanKec')
                ->select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","tmPmDesa"."Nm_Desa","trUsulanKec"."No_usulan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","tmPmKecamatan"."Nm_Kecamatan","trUsulanKec"."Jeniskeg","trUsulanKec"."Lokasi","trUsulanKec"."Prioritas","trUsulanKec"."Bobot","trUsulanKec"."Privilege","trUsulanKec"."Descr"'))
                ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')                                                                                                
                ->where('trUsulanKec.TA', \HelperKegiatan::getTahunPerencanaan())
                ->where('trUsulanKec.OrgID',$OrgID)
                ->orderBy('tmPmKecamatan.Nm_Kecamatan','ASC')
                ->orderBy('trUsulanKec.Prioritas','ASC')
                ->orderBy("NamaKegiatan",'ASC')
                ->get();
        
        $row=7;
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
            $sheet->setCellValue("H$row",$item->Nm_Kecamatan);
            $sheet->setCellValue("I$row",empty($item->Nm_Desa)?'USULAN KEC.':$item->Nm_Desa);
            $sheet->setCellValue("J$row",$item->Lokasi);
            $sheet->setCellValue("K$row",$item->Descr);
            $row+=1;
        }
        $row-=1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A7:K$row")->applyFromArray($styleArray);
        $sheet->getStyle("A7:K$row")->getAlignment()->setWrapText(true);  

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("B7:D$row")->applyFromArray($styleArray);
        $sheet->getStyle("H7:K$row")->applyFromArray($styleArray);
    }   
}