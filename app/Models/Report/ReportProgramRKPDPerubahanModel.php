<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

class ReportProgramRKPDPerubahanModel extends ReportModel
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
        $sheet->mergeCells ('A1:J1');
        $sheet->setCellValue('A1','PEMERINTAH DAERAH KABUPATEN BINTAN ');        
        $sheet->mergeCells ('A2:J2');
        $sheet->setCellValue('A2','LAPORAN RANCANGAN AKHIR RKPD PERUBAHAN');
        $sheet->mergeCells ('A3:J3');
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

        $sheet->mergeCells ('A7:D8');        
        $sheet->setCellValue('A7','KODE');
        $sheet->mergeCells ('E7:E8');
        $sheet->setCellValue('E7','BIDANG URUSAN PEMERINTAH');         
        $sheet->mergeCells ('F7:F8');
        $sheet->setCellValue('F7','JUMLAH KEGIATAN KESELURUHAN');                
        $sheet->mergeCells ('G7:G8');
        $sheet->setCellValue('G7','JUMLAH KEGIATAN PERUBAHAN');                
        $sheet->mergeCells ('H7:J7');
        $sheet->setCellValue('H7','INDIKASI TAHUN ('.\HelperKegiatan::getTahunPerencanaan().')');                
        
        $sheet->setCellValue('H8','SEBELUM');                
        $sheet->setCellValue('I8','SESUDAH');                
        $sheet->setCellValue('J8','SELISIH');                

        $sheet->mergeCells ('A9:D9');
        $sheet->setCellValue('A9',1); 
        $sheet->setCellValue('E9',2); 
        $sheet->setCellValue('F9',3); 
        $sheet->setCellValue('G9',4); 
        $sheet->setCellValue('H9',5); 
        $sheet->setCellValue('I9',6); 
        $sheet->setCellValue('J9',7); 
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(17);
        $sheet->getColumnDimension('G')->setWidth(17);
        $sheet->getColumnDimension('H')->setWidth(17);
        $sheet->getColumnDimension('I')->setWidth(17);
        $sheet->getColumnDimension('J')->setWidth(17);

        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:J9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:J9")->getAlignment()->setWrapText(true);
        
        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","Kd_Urusan","Kd_Bidang","OrgCd","kode_program","Kd_Prog","PrgNm","Jns"'))
                            ->where('OrgID',$OrgID)
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderByRaw('kode_program ASC NULLS FIRST')
                            ->orderBy('Kd_Prog','ASC')
                            ->get();
        
        $row=10;
        $total_pagu_m=0;
        $total_pagu_p=0;
        $total_jumlah_kegiatan=0;
        $total_jumlah_kegiatan_p=0;
        foreach ($daftar_program as $v)
        {
            $PrgID=$v->PrgID;           
            $daftar_kegiatan = \DB::table('v_rkpd')
                                    ->select(\DB::raw('SUM("NilaiUsulan1") AS jumlah_nilaiusulanm,SUM("NilaiUsulan2") AS jumlah_nilaiusulanp,COUNT("RKPDID") AS jumlah_kegiatan'))
                                    ->where('PrgID',$PrgID)                                              
                                    ->where('OrgID',$OrgID)
                                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())                                        
                                    ->first(); 

            $p = \DB::table('v_rkpd')
                                ->select(\DB::raw('COUNT("RKPDID") AS jumlah_kegiatan'))
                                ->where('PrgID',$PrgID)                                              
                                ->where('OrgID',$OrgID)
                                ->whereRaw('"NilaiUsulan1"!="NilaiUsulan2"')
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())                                        
                                ->first();      

            $sheet->setCellValue("A$row",$v->Kd_Urusan);           
            $sheet->setCellValue("B$row",$v->Kd_Bidang);           
            $sheet->setCellValue("C$row",$v->OrgCd);           
            $sheet->setCellValue("D$row",$v->Kd_Prog);           
            $sheet->setCellValue("E$row",$v->PrgNm);     
            
            $jumlah_nilaiusulanm= $daftar_kegiatan->jumlah_nilaiusulanm;
            $jumlah_nilaiusulanp= $daftar_kegiatan->jumlah_nilaiusulanp;
            $jumlah_kegiatan= $daftar_kegiatan->jumlah_kegiatan;     
            $jumlah_kegiatan_p= $p->jumlah_kegiatan;   
            $total_pagu_m+=$jumlah_nilaiusulanm;
            $total_pagu_p+=$jumlah_nilaiusulanp;
            $total_jumlah_kegiatan+=$jumlah_kegiatan;      
            $total_jumlah_kegiatan_p+=$jumlah_kegiatan_p; 

            $sheet->setCellValue("F$row",$jumlah_kegiatan);
            $sheet->setCellValue("G$row",$jumlah_kegiatan_p);
            $sheet->setCellValue("H$row",\Helper::formatUang($jumlah_nilaiusulanm));            
            $sheet->setCellValue("I$row",\Helper::formatUang($jumlah_nilaiusulanp));            
            $sheet->setCellValue("J$row",\Helper::formatUang($jumlah_nilaiusulanp-$jumlah_nilaiusulanm));            
            
            $row+=1;
        }        
        $sheet->setCellValue("E$row",'TOTAL'); 
        $sheet->setCellValue("F$row",$total_jumlah_kegiatan); 
        $sheet->setCellValue("G$row",$total_jumlah_kegiatan_p); 
        $sheet->setCellValue("H$row",\Helper::formatUang($total_pagu_m)); 
        $sheet->setCellValue("I$row",\Helper::formatUang($total_pagu_p)); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_pagu_p-$total_pagu_m)); 
       
        $row=$row-1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A10:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("A10:J$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("E10:E$row")->applyFromArray($styleArray);

        $row=$row+1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("G10:J$row")->applyFromArray($styleArray);

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