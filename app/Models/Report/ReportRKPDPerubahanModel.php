<?php
namespace App\Models\Report;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;
class ReportRKPDPerubahanModel extends ReportModel
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
        $sheet->setTitle ('LAPORAN RKPD-P TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:P1');
        $sheet->setCellValue('A1','PEMERINTAH DAERAH KABUPATEN BINTAN ');
        $n1 = \HelperKegiatan::getTahunPerencanaan()+1;
        $sheet->mergeCells ('A2:P2');
        $sheet->setCellValue('A2','LAPORAN RANCANGAN AKHIR APBD-P'.$n1);
        $sheet->mergeCells ('A3:P3');
        $sheet->setCellValue('A3','TAHUN ANGGARAN '.\HelperKegiatan::getTahunPerencanaan());

        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:A3")->applyFromArray($styleArray);        
       
        $sheet->setCellValue('A5','NAMA OPD / SKPD'); 
        $sheet->setCellValue('B5',': '.$this->dataReport['kode_organisasi'].'. '.$this->dataReport['OrgNm']); 
                
        $sheet->mergeCells ('A7:F8');
        $sheet->setCellValue('A7','KODE'); 
        $sheet->mergeCells ('G7:G8');
        $sheet->setCellValue('G7','BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('H7:I7');
        $sheet->setCellValue('H7','SASARAN'); 
        $sheet->mergeCells ('J7:J8');
        $sheet->setCellValue('J7','LOKASI');         
        $sheet->mergeCells ('K7:K8');
        $sheet->setCellValue('K7','TARGET (%)');
        $sheet->mergeCells ('L7:L8');
        $sheet->setCellValue('L7','SUMBER DANA');
        $sheet->mergeCells ('M7:O7');
        $sheet->setCellValue('M7','INDIKASI TA(n)');
        $sheet->mergeCells ('P7:P8');
        $sheet->setCellValue('P7','KETERANGAN');

        $sheet->setCellValue('H8','SEBELUM'); 
        $sheet->setCellValue('I8','SESUDAH'); 

        $sheet->setCellValue('M8','SEBELUM'); 
        $sheet->setCellValue('N8','SESUDAH'); 
        $sheet->setCellValue('O8','SELISIH'); 

        $sheet->mergeCells ('A9:F9');
        $sheet->setCellValue('A9',1); 
        $sheet->setCellValue('G9',2); 
        $sheet->setCellValue('H9',3); 
        $sheet->setCellValue('I9',4); 
        $sheet->setCellValue('J9',5); 
        $sheet->setCellValue('K9',6); 
        $sheet->setCellValue('L9',7); 
        $sheet->setCellValue('M9',9); 
        $sheet->setCellValue('N9',9); 
        $sheet->setCellValue('O9',10); 
        $sheet->setCellValue('P9',11);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(5);

        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(17);
        $sheet->getColumnDimension('L')->setWidth(12);
        $sheet->getColumnDimension('M')->setWidth(17);
        $sheet->getColumnDimension('N')->setWidth(17);
        $sheet->getColumnDimension('O')->setWidth(17);
        $sheet->getColumnDimension('P')->setWidth(17);
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:P9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:P9")->getAlignment()->setWrapText(true);

        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","kode_program","Kd_Prog","PrgNm","Jns"'))
                            ->where('OrgID',$OrgID)
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderByRaw('kode_program ASC NULLS FIRST')
                            ->orderBy('Kd_Prog','ASC')
                            ->get();
                
        $row=10;
        $total_pagu_m=0;
        $total_pagu_p=0;
        foreach ($daftar_program as $v)
        {
            $PrgID=$v->PrgID;                 
            $daftar_kegiatan = RKPDViewJudulModel::select(\DB::raw('"kode_kegiatan","KgtNm","Sasaran_Angka1","Sasaran_Uraian2","Target1","Target2","NilaiUsulan1","NilaiUsulan2","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
                                            ->where('PrgID',$PrgID)      
                                            ->where('OrgID',$OrgID)
                                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                            ->orderBy('kode_kegiatan','ASC')       
                                            ->get();
                                            
            if (isset($daftar_kegiatan[0])) 
            {   
                $kode_program=$v->kode_program==''?$this->dataReport['kode_organisasi'].'.'.$v->Kd_Prog:$v->kode_program;
                $PrgNm=$v->PrgNm;     
                $sheet->getStyle("A$row:J$row")->getFont()->setBold(true);                                
                $sheet->mergeCells ("A$row:F$row");
                $sheet->setCellValue("A$row",$kode_program);
                $sheet->setCellValue("G$row",$PrgNm);
                $totalpagueachprogramM= $daftar_kegiatan->sum('NilaiUsulan1');      
                $totalpagueachprogramP= $daftar_kegiatan->sum('NilaiUsulan2');                      
                $sheet->setCellValue("M$row",\Helper::formatUang($totalpagueachprogramM)); 
                $sheet->setCellValue("N$row",\Helper::formatUang($totalpagueachprogramP)); 
                $sheet->setCellValue("O$row",\Helper::formatUang($totalpagueachprogramP-$totalpagueachprogramM)); 
                $row+=1;
                foreach ($daftar_kegiatan as $n) 
                {
                    $sheet->mergeCells ("A$row:F$row");
                    $sheet->setCellValue("A$row",$n['kode_kegiatan']);                     
                    $sheet->setCellValue("G$row",$n['KgtNm']); 
                    $sheet->setCellValue("H$row",\Helper::formatAngka($n['Sasaran_Angka1']) . ' '.$n['Sasaran_Uraian1']); 
                    $sheet->setCellValue("I$row",\Helper::formatAngka($n['Sasaran_Angka2']) . ' '.$n['Sasaran_Uraian2']); 
                    $sheet->setCellValue("J$row",'Kab. Bintan'); 
                    $sheet->setCellValue("K$row",\Helper::formatUang($n['Target2'])); 
                    $sheet->setCellValue("L$row",$n['Nm_SumberDana']); 
                    $sheet->setCellValue("M$row",\Helper::formatUang($n['NilaiUsulan1'])); 
                    $sheet->setCellValue("N$row",\Helper::formatUang($n['NilaiUsulan2'])); 
                    $sheet->setCellValue("O$row",\Helper::formatUang($n['NilaiUsulan2']-$n['NilaiUsulan1'])); 
                    $sheet->setCellValue("P$row",$n['Descr']); 

                    $total_pagu_m+=$n['NilaiUsulan1'];
                    $total_pagu_p+=$n['NilaiUsulan2'];
                    $row+=1;
                }
            }
        }        
        $sheet->setCellValue("L$row",'TOTAL'); 
        $sheet->setCellValue("M$row",\Helper::formatUang($total_pagu_m)); 
        $sheet->setCellValue("N$row",\Helper::formatUang($total_pagu_p)); 
        $sheet->setCellValue("O$row",\Helper::formatUang($total_pagu_p-$total_pagu_m)); 
        
        $row=$row-1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A10:P$row")->applyFromArray($styleArray);
        $sheet->getStyle("A10:P$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("G10:G$row")->applyFromArray($styleArray);
        $sheet->getStyle("I10:J$row")->applyFromArray($styleArray);

        $row=$row+1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("M10:O$row")->applyFromArray($styleArray);

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