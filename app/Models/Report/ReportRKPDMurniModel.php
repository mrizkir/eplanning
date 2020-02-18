<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

class ReportRKPDMurniModel extends ReportModel
{   
    public function __construct($dataReport,$print=true)
    {
        parent::__construct($dataReport); 
        $this->spreadsheet->getProperties()->setTitle("Laporan RKPD Tahun ".\HelperKegiatan::getTahunPerencanaan());
        $this->spreadsheet->getProperties()->setSubject("Laporan RKPD Tahun ".\HelperKegiatan::getTahunPerencanaan()); 
        if ($print)
        {
            $this->print();             
        }        
    }    
    private function  print()  
    {
        $OrgID = $this->dataReport['OrgID'];
        $SOrgID = $this->dataReport['SOrgID'];
        if ($SOrgID == 'none' || $SOrgID == '')
        {
            $field = 'OrgID';
            $id = $OrgID;
        }
        else
        {
            $field = 'SOrgID';
            $id = $SOrgID;
        }           

        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN RKPD TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:N1');
        $sheet->setCellValue('A1','RUMUSAN PROGRAM DAN KEGIATAN OPD TAHUN '.\HelperKegiatan::getTahunPerencanaan());
        $n1 = \HelperKegiatan::getTahunPerencanaan()+1;
        $sheet->mergeCells ('A2:N2');
        $sheet->setCellValue('A2','DAN PRAKIRAAN MAJU TAHUN '.$n1);
        $sheet->mergeCells ('A3:N3');
        $sheet->setCellValue('A3','KABUPATEN BINTAN');
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:A3")->applyFromArray($styleArray);        
        
        $sheet->mergeCells ('A5:D5');
        if ($SOrgID != 'none'&&$SOrgID != ''&&$SOrgID != null)
        {
            $sheet->setCellValue('A5','NAMA UNIT KERJA'); 
            $sheet->setCellValue('E5',': '.$this->dataReport['SOrgNm']. ' ['.$this->dataReport['kode_suborganisasi'].']'); 
        }        
        else
        {
            $sheet->setCellValue('A5','NAMA OPD / SKPD'); 
            $sheet->setCellValue('E5',': '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']'); 
        }

        $sheet->mergeCells ('A7:E8');
        $sheet->setCellValue('A7','KODE'); 
        $sheet->mergeCells ('F7:F8');
        $sheet->setCellValue('F7','URUSAN/BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('G7:G8');
        $sheet->setCellValue('G7','INDIKATOR KINERJA PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('H7:K7');
        $sheet->setCellValue('H7','RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan());         
        $sheet->mergeCells ('L7:L8');
        $sheet->setCellValue('L7','CATATAN PENTING');
        $sheet->mergeCells ('M7:N7');
        $sheet->setCellValue('M7','PERKIRAAN MAJU RENCANA TAHUN '.$n1);
        
        $sheet->setCellValue('H8','LOKASI'); 
        $sheet->setCellValue('I8','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('K8','SUMBER DANA');         
        $sheet->setCellValue('M8','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('N8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        
        $sheet->setCellValue('A9',1); 
        $sheet->setCellValue('B9',2); 
        $sheet->setCellValue('C9',3); 
        $sheet->setCellValue('D9',4); 
        $sheet->setCellValue('E9',5); 
        $sheet->setCellValue('F9',6); 
        $sheet->setCellValue('G9',7); 
        $sheet->setCellValue('H9',9); 
        $sheet->setCellValue('I9',9); 
        $sheet->setCellValue('J9',10); 
        $sheet->setCellValue('K9',11); 
        $sheet->setCellValue('L9',12); 
        $sheet->setCellValue('M9',13); 
        $sheet->setCellValue('N9',14); 

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(40);        
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(30);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(23);
        $sheet->getColumnDimension('M')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(20);
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:N9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:N9")->getAlignment()->setWrapText(true);
        
        $struktur = $this->generateStructureRKPD($field,$id,1);
        $row=10;
        $total_pagu=0;        
        $total_nilai_setelah=0;        

        $styleArrayProgram=array( 
                        'font' => array('bold' => true),                                                           
                    );       
        $styleArrayKegiatan=array( 
                        'font' => array('bold' => true),                                                           
                    );       
        foreach ($struktur as $Kd_Urusan=>$v1)
        {
            $sheet->getRowDimension($row)->setRowHeight(28);
            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
            $sheet->setCellValue("A$row",$Kd_Urusan);
            $sheet->mergeCells("B$row:E$row");                
            $sheet->setCellValue("F$row",$v1['Nm_Urusan']);
            $sheet->mergeCells("F$row:N$row");                
            if ($Kd_Urusan == 0)
            {
                $program=$v1['program'];
                $row+=1;
                foreach ($program as $v3)
                {   
                    $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',1)                                               
                                                ->where($field,$id)
                                                ->groupBy('trRKPD.KgtID')
                                                ->groupBy('tmKgt.Kd_Keg')
                                                ->groupBy('tmKgt.KgtNm')
                                                ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                ->get();

                    if (count($daftar_kegiatan)  > 0)
                    {
                        $Kd_Prog = $v3['Kd_Prog'];
                        $sheet->getRowDimension($row)->setRowHeight(28);
                        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);

                        $sheet->setCellValue("A$row",0);
                        $sheet->setCellValue("B$row",'00');
                        $sheet->setCellValue("C$row",$Kd_Prog);
                        $sheet->mergeCells("D$row:E$row");
                        $sheet->setCellValue("F$row",$v3['PrgNm']);        
                        $sheet->mergeCells("F$row:I$row");                
                        $sheet->mergeCells("K$row:M$row");       
                        $row_program=$row;
                        $totaleachprogram = 0;
                        $totaleachprogram_setelah=0;
                        $row+=1;
                        foreach ($daftar_kegiatan as $v4) 
                        {
                            $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',1)
                                                ->where($field,$id)
                                                ->first();

                            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);
                            $sheet->setCellValue("A$row",0);
                            $sheet->setCellValue("B$row",'00');
                            $sheet->setCellValue("C$row",$Kd_Prog);
                            $sheet->setCellValue("D$row",$v4->Kd_Keg);                            
                            $sheet->setCellValue("F$row",$v4->KgtNm); 
                            $nama_indikator=$rkpd->NamaIndikator;
                            $sheet->setCellValue("G$row",$nama_indikator); 
                            $sheet->setCellValue("H$row",'Kab. Bintan'); 
                            $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka2) . ' '.$rkpd->Sasaran_Uraian2))); 
                            $sheet->setCellValue("J$row",\Helper::formatUang($rkpd->NilaiUsulan2)); 
                            $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
                            $sheet->setCellValue("L$row",$rkpd->Descr); 
                            $sheet->setCellValue("M$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))); 
                            $sheet->setCellValue("N$row",\Helper::formatUang($rkpd->NilaiSetelah)); 
                            $total_nilai_setelah+=$rkpd->NilaiSetelah;  
                            $totaleachprogram_setelah+=$rkpd->NilaiSetelah;

                            $row_kegiatan=$row;
                            $row+=1;  
                            $no=1;
                            $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                ->select(\DB::raw('
                                                                "Uraian",
                                                                "Sasaran_Angka2",
                                                                "Sasaran_Uraian2",
                                                                "Target2",
                                                                "NilaiUsulan2",
                                                                "Nm_SumberDana",                                                                
                                                                "Lokasi",
                                                                "Descr"
                                                            ')
                                                )                                                
                                                ->where('EntryLvl',1)
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where($field,$id)
                                                ->orderByRaw('"No"::int ASC')
                                                ->get();
                            
                            $totaleachkegiatan = 0;
                            foreach ($rincian_kegiatan as $v5)
                            {
                                $sheet->setCellValue("A$row",0);
                                $sheet->setCellValue("B$row",'00');
                                $sheet->setCellValue("C$row",$Kd_Prog);
                                $sheet->setCellValue("D$row",$v4->Kd_Keg);                             
                                $sheet->setCellValue("E$row",$no);                            
                                $sheet->setCellValue("F$row",$v5->Uraian);    
                                $sheet->setCellValue("G$row",$nama_indikator); 
                                // $sheet->setCellValue("H$row",$v5->Lokasi); 
                                $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka2);
                                $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian2)));                                     
                                $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$v5->Descr); 
                                $total_pagu+=$v5->NilaiUsulan2;                                                           
                                $totaleachkegiatan+=$v5->NilaiUsulan2;
                                $no+=1;
                                $row+=1;
                            }
                            $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan)); 
                            $totaleachprogram+=$totaleachkegiatan;                            
                        }                  
                        $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram));                                 
                        $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_setelah));                                 
                    }                   
                }
            }
            else
            {
                $bidang_pemerintahan=$v1['bidang_pemerintahan'];
                $row+=1;
                foreach ($bidang_pemerintahan as $Kd_Bidang=>$v2)
                {
                    $sheet->getRowDimension($row)->setRowHeight(28);
                    $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
                    $sheet->setCellValue("A$row",$Kd_Urusan);
                    $sheet->setCellValue("B$row",$Kd_Bidang);
                    $sheet->mergeCells("C$row:E$row");
                    $sheet->setCellValue("F$row",$v2['Nm_Bidang']);
                    $sheet->mergeCells("F$row:N$row");
                    $program=$v2['program'];
                    $row+=1;
                    foreach ($program as $v3)
                    {                        
                        $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',1)
                                                ->groupBy('trRKPD.KgtID')
                                                ->groupBy('tmKgt.Kd_Keg')
                                                ->groupBy('tmKgt.KgtNm')
                                                ->where($field,$id)
                                                ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                ->get();       
                        if (count($daftar_kegiatan)  > 0)
                        {   
                            $Kd_Prog = $v3['Kd_Prog'];
                            $sheet->getRowDimension($row)->setRowHeight(28);
                            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
                            
                            $sheet->setCellValue("A$row",$Kd_Urusan);
                            $sheet->setCellValue("B$row",$Kd_Bidang);
                            $sheet->setCellValue("C$row",$Kd_Prog);
                            $sheet->mergeCells("D$row:E$row");
                            $sheet->setCellValue("F$row",$v3['PrgNm']); 
                            $sheet->mergeCells("F$row:I$row");                
                            $sheet->mergeCells("K$row:M$row");          
                            $row_program=$row;
                            $totaleachprogram = 0;             
                            $totaleachprogram_setelah=0;
                            $row+=1;               
                            foreach ($daftar_kegiatan as $v4) 
                            {                                
                                $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',1)
                                                ->where($field,$id)
                                                ->first();

                                $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);
                                $sheet->setCellValue("A$row",$Kd_Urusan);
                                $sheet->setCellValue("B$row",$Kd_Bidang);
                                $sheet->setCellValue("C$row",$Kd_Prog);
                                $sheet->setCellValue("D$row",$v4->Kd_Keg);                            
                                $sheet->setCellValue("F$row",$v4->KgtNm); 
                                $nama_indikator=$rkpd->NamaIndikator;
                                $sheet->setCellValue("G$row",$nama_indikator); 
                                $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka1) . ' '.$rkpd->Sasaran_Uraian1)));                                     
                                $sheet->setCellValue("J$row",0); //nilai ini akan di isi oleh dibawah
                                $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$rkpd->Descr); 
                                $sheet->setCellValue("M$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))); 
                                $sheet->setCellValue("N$row",\Helper::formatUang($rkpd->NilaiSetelah)); 
                                $total_nilai_setelah+=$rkpd->NilaiSetelah;  
                                $totaleachprogram_setelah+=$rkpd->NilaiSetelah;
                                
                                $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka2",
                                                                    "Sasaran_Uraian2",
                                                                    "Target2",
                                                                    "NilaiUsulan2",
                                                                    "Nm_SumberDana",
                                                                    "Lokasi",
                                                                    "Descr"
                                                                ')
                                                    )                                                
                                                    ->where('EntryLvl',1)
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where($field,$id)
                                                    ->orderByRaw('"No"::int ASC')
                                                    ->get();

                                $row_kegiatan=$row;
                                $no=1;                                
                                $row+=1;
                                $totaleachkegiatan = 0;
                                foreach ($rincian_kegiatan as $v5)
                                {                     
                                    $sheet->setCellValue("A$row",$Kd_Urusan);
                                    $sheet->setCellValue("B$row",$Kd_Bidang);
                                    $sheet->setCellValue("C$row",$Kd_Prog);
                                    $sheet->setCellValue("D$row",$v4->Kd_Keg);                                 
                                    $sheet->setCellValue("E$row",$no);                            
                                    $sheet->setCellValue("F$row",$v5->Uraian);    
                                    $sheet->setCellValue("G$row",$nama_indikator); 
                                    // $sheet->setCellValue("H$row",$v5->Lokasi); 
                                    $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                    $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka2);
                                    $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian2)));                                                                        
                                    $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                    $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                    $sheet->setCellValue("L$row",$v5->Descr); 
                                    $sheet->setCellValue("M$row",\Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah); 
                                    $sheet->setCellValue("N$row",\Helper::formatUang($rkpd->NilaiSetelah)); 
                                    $total_pagu+=$v5->NilaiUsulan2;
                                    $totaleachkegiatan+=$v5->NilaiUsulan2;                                    
                                    $no+=1;
                                    $row+=1;
                                }                                   
                                $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan)); 
                                $totaleachprogram+=$totaleachkegiatan;
                            }
                            $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram));
                            $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_setelah));                                 
                        }
                    }
                }
            }
        }      
        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);  
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:H$row"); 
        $sheet->setCellValue("I$row",'TOTAL'); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_pagu));       
        $sheet->mergeCells("K$row:M$row"); 
        $sheet->setCellValue("N$row",\Helper::formatUang($total_nilai_setelah));    

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A10:N$row")->applyFromArray($styleArray);
        $sheet->getStyle("A10:N$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("F10:G$row")->applyFromArray($styleArray);

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("N10:N$row")->applyFromArray($styleArray); 

        $row+=3;
        $sheet->setCellValue("H$row",'BANDAR SRI BENTAN, '.\Helper::tanggal('d F Y'));
        $row+=1;        
        $sheet->setCellValue("H$row",'KEPALA ');                                          
        $row+=1;        
        $sheet->setCellValue("H$row",strtoupper($this->dataReport['OrgNm']));                                          
                
        $row+=5;
        $sheet->setCellValue("H$row",$this->dataReport['NamaKepalaSKPD']);
        $row+=1;                
        
        $sheet->setCellValue("H$row",'NIP.'.$this->dataReport['NIPKepalaSKPD']);
    }   
}