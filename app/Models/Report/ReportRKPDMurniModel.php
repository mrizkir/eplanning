<?php

namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


use App\Models\RKPD\RKPDMurniModel;

class ReportRKPDMurniModel extends ReportModel
{   
    public function __construct($dataReport)
    {
        parent::__construct($dataReport); 
        $this->print();             
    }
    
    private function  print()  
    {
        $OrgID = $this->dataReport['OrgID'];

        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN RKPD TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);

        $sheet->mergeCells ('A1:K1');
        $sheet->setCellValue('A1','RUMUSAN PROGRAM DAN KEGIATAN OPD TAHUN '.\HelperKegiatan::getTahunPerencanaan());

        $n1 = \HelperKegiatan::getTahunPerencanaan()+1;
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
        
        $sheet->setCellValue('A5','KELOMPOK URUSAN'); 
        $sheet->setCellValue('B5',': '.$this->dataReport['Nm_Urusan']. ' ['.$this->dataReport['Kd_Urusan'].']'); 
        $sheet->setCellValue('A6','URUSAN'); 
        $sheet->setCellValue('B6',': '.$this->dataReport['Nm_Bidang']. ' ['.$this->dataReport['Kd_Urusan'].'.'.$this->dataReport['Kd_Bidang'].']'); 
        $sheet->setCellValue('A7','NAMA OPD / SKPD'); 
        $sheet->setCellValue('B7',': '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']'); 
                
        $sheet->mergeCells ('A10:A11');
        $sheet->setCellValue('A10','KODE'); 
        $sheet->mergeCells ('B10:B11');
        $sheet->setCellValue('B10','URUSAN/BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('C10:C11');
        $sheet->setCellValue('C10','INDIKATOR KINERJA PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('D10:G10');
        $sheet->setCellValue('D10','RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan()); 
        $sheet->mergeCells ('H10:J10');
        $sheet->setCellValue('H10','PERKIRAAN MAJU RENCANA TAHUN '.$n1);
        $sheet->mergeCells ('K10:K11');
        $sheet->setCellValue('K10','KETERANGAN'); 
        
        $sheet->setCellValue('D11','LOKASI'); 
        $sheet->setCellValue('E11','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('F11','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('G11','SUMBER DANA'); 
        $sheet->setCellValue('H11','LOKASI');         
        $sheet->setCellValue('I11','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J11','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        
        $sheet->setCellValue('A12',1); 
        $sheet->setCellValue('B12',2); 
        $sheet->setCellValue('C12',3); 
        $sheet->setCellValue('D12',4); 
        $sheet->setCellValue('E12',5); 
        $sheet->setCellValue('F12',6); 
        $sheet->setCellValue('G12',7); 
        $sheet->setCellValue('H12',12); 
        $sheet->setCellValue('I12',9); 
        $sheet->setCellValue('J12',10); 
        $sheet->setCellValue('K12',11); 

        $sheet->getColumnDimension('A')->setWidth(19);
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
        $sheet->getStyle("A10:K12")->applyFromArray($styleArray);
        $sheet->getStyle("A10:K12")->getAlignment()->setWrapText(true);



        $daftar_program=RKPDMurniModel::select(\DB::raw('"PrgID","kode_program","PrgNm"'))
                                        ->where('OrgID',$OrgID)
                                        ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->limit(1)
                                        ->get()->toArray();

        
        
        $row=13;
        foreach ($daftar_program as $v)
        {
            $PrgID=$v['PrgID'];
            $daftar_kegiatan = RKPDMurniModel::select(\DB::raw('"kode_kegiatan","KgtNm","NamaIndikator","Sasaran_Angka1","Sasaran_Uraian1","Target1","NilaiUsulan1","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
                                    ->where('PrgID',$PrgID)             
                                    ->limit(5)                       
                                    ->get()->toArray();

            if (isset($daftar_kegiatan[0])) 
            {
                $sheet->getStyle("A$row:K$row")->getFont()->setBold(true);
                $totalpagueachprogram=RKPDMurniModel::where('PrgID',$PrgID)->sum('NilaiUsulan1');
                $sheet->setCellValue("A$row",$v['kode_program']);
                $sheet->setCellValue("B$row",$v['PrgNm']);
                $sheet->setCellValue("F$row",\Helper::formatUang($totalpagueachprogram)); 
                $row+=1;
                foreach ($daftar_kegiatan as $n) 
                {
                    $sheet->setCellValue("A$row",$n['kode_kegiatan']); 
                    $sheet->setCellValue("B$row",$n['KgtNm']); 
                    $sheet->setCellValue("C$row",$n['NamaIndikator']); 
                    $sheet->setCellValue("D$row",'Kab. Bintan'); 
                    $sheet->setCellValue("E$row",\Helper::formatAngka($n['Sasaran_Angka1']) . ' '.$n['Sasaran_Uraian1']); 
                    $sheet->setCellValue("F$row",\Helper::formatUang($n['NilaiUsulan1'])); 
                    $sheet->setCellValue("G$row",$n['Nm_SumberDana']); 
                    $sheet->setCellValue("H$row",'Kab. Bintan'); 
                    $sheet->setCellValue("I$row",\Helper::formatAngka($n['Sasaran_AngkaSetelah']).' '.$n['Sasaran_UraianSetelah']); 
                    $sheet->setCellValue("J$row",\Helper::formatUang($n['NilaiSetelah'])); 
                    $sheet->setCellValue("K$row",$n['Descr']); 
                    $row+=1;
                }
            }
        }
        

        $row=$row-1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A13:K$row")->applyFromArray($styleArray);
        $sheet->getStyle("A13:K$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("A13:C$row")->applyFromArray($styleArray);

        $row+=3;
        $sheet->setCellValue("H$row",'BANDAR SRI BENTAN, '.\Helper::tanggal('d F Y'));

        $row+=1;        
        $sheet->setCellValue("H$row",'KEPALA DINAS');                                          

        $row+=1;        
        $sheet->setCellValue("H$row",strtoupper($this->dataReport['OrgNm']));                                          
                
        $row+=5;
        $sheet->setCellValue("H$row",$this->dataReport['NamaKepalaSKPD']);
        $row+=1;                
        
        $sheet->setCellValue("H$row",'NIP.'.$this->dataReport['NamaKepalaSKPD']);
    }   
}