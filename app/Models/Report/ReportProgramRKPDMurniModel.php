<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

class ReportProgramRKPDMurniModel extends ReportModel
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
        $sheet->setTitle ('LAPORAN PROGRAM RKPD TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:G1');
        $sheet->setCellValue('A1','PEMERINTAH DAERAH KABUPATEN BINTAN ');        
        $sheet->mergeCells ('A2:G2');
        $sheet->setCellValue('A2','LAPORAN RANCANGAN AKHIR RKPD');
        $sheet->mergeCells ('A3:G3');
        $sheet->setCellValue('A3','TAHUN ANGGARAN '.\HelperKegiatan::getTahunPerencanaan());
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:A3")->applyFromArray($styleArray);        
        
        $sheet->mergeCells ('A5:D5'); 
        $sheet->setCellValue('A5','NAMA OPD / SKPD'); 
        $sheet->setCellValue('E5',': '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']'); 

        $sheet->mergeCells ('A7:D7');
        $sheet->setCellValue('A7','KODE');
        $sheet->setCellValue('E7','BIDANG URUSAN PEMERINTAH');         
        $sheet->setCellValue('F7','JUMLAH KEGIATAN');                
        $sheet->setCellValue('G7','INDIKASI TAHUN ('.\HelperKegiatan::getTahunPerencanaan().')');                
        
        $sheet->mergeCells ('A7:D7');
        $sheet->setCellValue('A8',1); 
        $sheet->setCellValue('E8',2); 
        $sheet->setCellValue('F8',2); 
        $sheet->setCellValue('G8',3); 
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(17);
        $sheet->getColumnDimension('G')->setWidth(17);

        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:G8")->applyFromArray($styleArray);
        $sheet->getStyle("A7:G8")->getAlignment()->setWrapText(true);
        
        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","Kd_Urusan","Kd_Bidang","OrgCd","kode_program","Kd_Prog","PrgNm","Jns"'))
                            ->where('OrgID',$OrgID)
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderByRaw('kode_program ASC NULLS FIRST')
                            ->orderBy('Kd_Prog','ASC')
                            ->get();
        
        $row=9;
        $total_pagu_m=0;
        $total_jumlah_kegiatan_m=0;
        foreach ($daftar_program as $v)
        {
            $PrgID=$v->PrgID;           
            $daftar_kegiatan = \DB::table('v_rkpd')
                                    ->select(\DB::raw('SUM("NilaiUsulan1") AS jumlah_nilaiusulan,COUNT("RKPDID") AS jumlah_kegiatan'))
                                    ->where('PrgID',$PrgID)                                              
                                    ->where('OrgID',$OrgID)
                                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())                                        
                                    ->first(); 
            
            $sheet->setCellValue("A$row",$v->Kd_Urusan);           
            $sheet->setCellValue("B$row",$v->Kd_Bidang);           
            $sheet->setCellValue("C$row",$v->OrgCd);           
            $sheet->setCellValue("D$row",$v->Kd_Prog);           
            $sheet->setCellValue("E$row",$v->PrgNm);     
            
            $totalpagueachprogramNilaiUsulan= $daftar_kegiatan->jumlah_nilaiusulan;
            $jumlah_kegiatan= $daftar_kegiatan->jumlah_kegiatan;     
            $total_pagu_m+=$totalpagueachprogramNilaiUsulan;
            $total_jumlah_kegiatan_m+=$jumlah_kegiatan;      

            $sheet->setCellValue("F$row",$jumlah_kegiatan);
            $sheet->setCellValue("G$row",$totalpagueachprogramNilaiUsulan);            
            $row+=1;
        }        
        $sheet->setCellValue("E$row",'TOTAL'); 
        $sheet->setCellValue("F$row",$total_jumlah_kegiatan_m); 
        $sheet->setCellValue("G$row",\Helper::formatUang($total_pagu_m)); 
       
        $row=$row-1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A9:G$row")->applyFromArray($styleArray);
        $sheet->getStyle("A9:G$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("E9:E$row")->applyFromArray($styleArray);

        $row=$row+1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("G9:G$row")->applyFromArray($styleArray);

        $row+=3;
        $sheet->setCellValue("F$row",'BANDAR SRI BENTAN, '.\Helper::tanggal('d F Y'));
        $row+=1;        
        $sheet->setCellValue("F$row",'KEPALA DINAS');                                          
        $row+=1;        
        $sheet->setCellValue("F$row",strtoupper($this->dataReport['OrgNm']));                                          
                
        $row+=5;
        $sheet->setCellValue("F$row",$this->dataReport['NamaKepalaSKPD']);
        $row+=1;                
        
        $sheet->setCellValue("F$row",'NIP.'.$this->dataReport['NamaKepalaSKPD']);
    }   
}