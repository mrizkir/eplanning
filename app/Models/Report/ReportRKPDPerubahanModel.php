<?php
namespace App\Models\Report;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;
class ReportRKPDPerubahanModel extends ReportModel
{   
    public function __construct($dataReport,$print=true)
    {
        parent::__construct($dataReport);
        if ($print)
        {
            $this->spreadsheet->getProperties()->setTitle("Laporan RKPDP Tahun ".\HelperKegiatan::getTahunPerencanaan());
            $this->spreadsheet->getProperties()->setSubject("Laporan RKPDP Tahun ".\HelperKegiatan::getTahunPerencanaan()); 
            switch($this->dataReport['mode'])
            {
                case 'rkpdperubahan' :
                    $this->printRKPDPerubahan();
                break;
                case 'pembahasanrkpdp' :                
                    $this->printPembahasanRKPDP();
                break;
            }
        }
    }
    
    private function printRKPDPerubahan()  
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
        $sheet->setTitle ('LAPORAN RKPD-P TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:O1');
        $sheet->setCellValue('A1','PEMERINTAH DAERAH KABUPATEN BINTAN ');
        $n = \HelperKegiatan::getTahunPerencanaan();
        $sheet->mergeCells ('A2:O2');
        $sheet->setCellValue('A2',"LAPORAN RANCANGAN AKHIR APBD-P $n");
        $sheet->mergeCells ('A3:O3');
        $sheet->setCellValue('A3',"TAHUN ANGGARAN $n");

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
        $sheet->setCellValue('H7','PERUBAHAN RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan());         
        $sheet->mergeCells ('L7:L8');
        $sheet->setCellValue('L7','CATATAN PENTING');
        $sheet->mergeCells ('M7:O7');
        $sheet->setCellValue('M7',"INDIKASI PERUBAHAN");
        
        $sheet->setCellValue('H8','LOKASI'); 
        $sheet->setCellValue('I8','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('K8','SUMBER DANA');         
        $sheet->setCellValue('M8','SEBELUM'); 
        $sheet->setCellValue('N8','SESUDAH'); 
        $sheet->setCellValue('O8','SISA'); 
        
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
        $sheet->setCellValue('O9',15); 

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
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:O9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:O9")->getAlignment()->setWrapText(true);
        
        $struktur = $this->generateStructureRKPD($field,$id,3);
        
        $row=10;        
        $styleArrayProgram=array( 
                        'font' => array('bold' => true),                                                           
                    );       
        $styleArrayKegiatan=array( 
                        'font' => array('bold' => true),                                                           
                    );       

        $total_pagu_kolom_j=0;        
        $total_pagu_kolom_m=0;        
        $total_pagu_kolom_n=0;        
        foreach ($struktur as $Kd_Urusan=>$v1)
        {
            $sheet->getRowDimension($row)->setRowHeight(28);
            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
            $sheet->setCellValue("A$row",$Kd_Urusan);
            $sheet->mergeCells("B$row:E$row");                
            $sheet->setCellValue("F$row",$v1['Nm_Urusan']);
            $sheet->mergeCells("F$row:L$row");                
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
                                                ->where('EntryLvl',3)                                               
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
                        $sheet->getStyle("A$row:O$row")->applyFromArray($styleArrayProgram);

                        $sheet->setCellValue("A$row",0);
                        $sheet->setCellValue("B$row",'00');
                        $sheet->setCellValue("C$row",$Kd_Prog);
                        $sheet->mergeCells("D$row:E$row");
                        $sheet->setCellValue("F$row",$v3['PrgNm']);        
                        $sheet->mergeCells("F$row:I$row");                
                        $sheet->mergeCells("K$row:L$row");       
                        $row_program=$row;
                        $totaleachprogram_kolom_j=0;
                        $totaleachprogram_kolom_m=0;
                        $totaleachprogram_kolom_n=0;
                        $row+=1;
                        foreach ($daftar_kegiatan as $v4) 
                        {
                            $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',3)
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
                            $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka3) . ' '.$rkpd->Sasaran_Uraian3))); 
                            $sheet->setCellValue("J$row",\Helper::formatUang($rkpd->NilaiUsulan3)); 
                            $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
                            $sheet->setCellValue("L$row",$rkpd->Descr);                            
                            
                            $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                ->select(\DB::raw('
                                                                "Uraian",
                                                                "Sasaran_Angka3",
                                                                "Sasaran_Uraian3",
                                                                "Target3",
                                                                "NilaiUsulan2",
                                                                "NilaiUsulan3",
                                                                "Nm_SumberDana",                                                                
                                                                "Lokasi",
                                                                "Descr"
                                                            ')
                                                )                                                
                                                ->where('EntryLvl',3)
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where($field,$id)
                                                ->orderByRaw('"No"::int ASC')
                                                ->get();
                            
                            $row_kegiatan=$row;
                            $row+=1;  
                            $no=1;
                            $totaleachkegiatan_kolom_j = 0;
                            $totaleachkegiatan_kolom_m = 0;
                            $totaleachkegiatan_kolom_n = 0;
                            foreach ($rincian_kegiatan as $v5)
                            {
                                $sheet->setCellValue("A$row",0);
                                $sheet->setCellValue("B$row",'00');
                                $sheet->setCellValue("C$row",$Kd_Prog);
                                $sheet->setCellValue("D$row",$v4->Kd_Keg);                             
                                $sheet->setCellValue("E$row",$no);                            
                                $sheet->setCellValue("F$row",$v5->Uraian);    
                                $sheet->setCellValue("G$row",$nama_indikator); 
                                $sheet->setCellValue("H$row",$v5->Lokasi); 
                                $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka3);
                                $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian3)));                                     
                                $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$v5->Descr); 
                                $sheet->setCellValue("M$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                $sheet->setCellValue("N$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                $sheet->setCellValue("O$row",\Helper::formatUang($v5->NilaiUsulan3-$v5->NilaiUsulan2)); 
                                $total_pagu_kolom_j+=$v5->NilaiUsulan3;
                                $total_pagu_kolom_m+=$v5->NilaiUsulan2;
                                $total_pagu_kolom_n+=$v5->NilaiUsulan3;
                                                                                       
                                $totaleachkegiatan_kolom_j+=$v5->NilaiUsulan3;
                                $totaleachkegiatan_kolom_m+=$v5->NilaiUsulan2;
                                $totaleachkegiatan_kolom_n+=$v5->NilaiUsulan3;
                                $no+=1;
                                $row+=1;
                            }
                            $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_j)); 
                            $sheet->setCellValue("M$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_m)); 
                            $sheet->setCellValue("N$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_n)); 
                            $sheet->setCellValue("O$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_n-$totaleachkegiatan_kolom_m));                             

                            $totaleachprogram_kolom_j+=$totaleachkegiatan_kolom_j;
                            $totaleachprogram_kolom_m+=$totaleachkegiatan_kolom_m;
                            $totaleachprogram_kolom_n+=$totaleachkegiatan_kolom_n;
                        }
                        $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram_kolom_j));                                                         
                        $sheet->setCellValue("M$row_program",\Helper::formatUang($totaleachprogram_kolom_m)); 
                        $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_kolom_n)); 
                        $sheet->setCellValue("O$row_program",\Helper::formatUang($totaleachprogram_kolom_n-$totaleachprogram_kolom_m)); 
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
                    $sheet->mergeCells("F$row:L$row");
                    $program=$v2['program'];
                    $row+=1;
                    foreach ($program as $v3)
                    {
                        $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',3)
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
                            $sheet->mergeCells("K$row:L$row");          
                            $row_program=$row;
                            $totaleachprogram_kolom_j=0;             
                            $totaleachprogram_kolom_m=0;
                            $totaleachprogram_kolom_n=0;
                            $row+=1; 
                            foreach ($daftar_kegiatan as $v4) 
                            {                                
                                $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',3)
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
                                $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka3) . ' '.$rkpd->Sasaran_Uraian3)));                                     
                                $sheet->setCellValue("J$row",0); //nilai ini akan di isi oleh dibawah
                                $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$rkpd->Descr); 
                                
                                $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka3",
                                                                    "Sasaran_Uraian3",
                                                                    "Target3",
                                                                    "NilaiUsulan2",
                                                                    "NilaiUsulan3",
                                                                    "Nm_SumberDana",
                                                                    "Lokasi",
                                                                    "Descr"
                                                                ')
                                                    )                                                
                                                    ->where('EntryLvl',3)
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where($field,$id)
                                                    ->orderByRaw('"No"::int ASC')
                                                    ->get();

                                $row_kegiatan=$row;
                                $no=1;                                
                                $row+=1;
                                $totaleachkegiatan_kolom_j = 0;
                                $totaleachkegiatan_kolom_m = 0;
                                $totaleachkegiatan_kolom_n = 0;
                                foreach ($rincian_kegiatan as $v5)
                                {                     
                                    $sheet->setCellValue("A$row",$Kd_Urusan);
                                    $sheet->setCellValue("B$row",$Kd_Bidang);
                                    $sheet->setCellValue("C$row",$Kd_Prog);
                                    $sheet->setCellValue("D$row",$v4->Kd_Keg);                                 
                                    $sheet->setCellValue("E$row",$no);                            
                                    $sheet->setCellValue("F$row",$v5->Uraian);    
                                    $sheet->setCellValue("G$row",$nama_indikator); 
                                    $sheet->setCellValue("H$row",$v5->Lokasi); 
                                    $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                    $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka3);
                                    $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian3)));                                                                        
                                    $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                    $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                    $sheet->setCellValue("L$row",$v5->Descr); 
                                    $sheet->setCellValue("M$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                    $sheet->setCellValue("N$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                    $sheet->setCellValue("O$row",\Helper::formatUang($v5->NilaiUsulan3-$v5->NilaiUsulan2)); 
                                    $total_pagu_kolom_j+=$v5->NilaiUsulan3;
                                    $total_pagu_kolom_m+=$v5->NilaiUsulan2;
                                    $total_pagu_kolom_n+=$v5->NilaiUsulan3;

                                    $totaleachkegiatan_kolom_j+=$v5->NilaiUsulan3;
                                    $totaleachkegiatan_kolom_m+=$v5->NilaiUsulan2;
                                    $totaleachkegiatan_kolom_n+=$v5->NilaiUsulan3;                                  
                                    $no+=1;
                                    $row+=1;
                                }                                   
                                $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_j)); 
                                $sheet->setCellValue("M$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_m)); 
                                $sheet->setCellValue("N$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_n)); 
                                $sheet->setCellValue("O$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_n-$totaleachkegiatan_kolom_m));                             

                                $totaleachprogram_kolom_j+=$totaleachkegiatan_kolom_j;
                                $totaleachprogram_kolom_m+=$totaleachkegiatan_kolom_m;
                                $totaleachprogram_kolom_n+=$totaleachkegiatan_kolom_n;
                            }
                            $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram_kolom_j));
                            $sheet->setCellValue("M$row_program",\Helper::formatUang($totaleachprogram_kolom_m));
                            $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_kolom_n));
                            $sheet->setCellValue("O$row_program",\Helper::formatUang($totaleachprogram_kolom_n-$totaleachprogram_kolom_m));
                        }                        
                    }
                }
            }
        }
        
        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);  
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:H$row"); 
        $sheet->setCellValue("I$row",'TOTAL'); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_pagu_kolom_j));       
        $sheet->mergeCells("K$row:L$row"); 
        $sheet->setCellValue("M$row",\Helper::formatUang($total_pagu_kolom_m));    
        $sheet->setCellValue("N$row",\Helper::formatUang($total_pagu_kolom_n));    
        $sheet->setCellValue("O$row",\Helper::formatUang($total_pagu_kolom_n-$total_pagu_kolom_m));    

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A10:O$row")->applyFromArray($styleArray);
        $sheet->getStyle("A10:O$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("F10:G$row")->applyFromArray($styleArray);

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("M10:O$row")->applyFromArray($styleArray); 

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
    private function printPembahasanRKPDP()  
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
        $sheet->setTitle ('LAPORAN RKPD-P TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:P1');
        $sheet->setCellValue('A1','PEMERINTAH DAERAH KABUPATEN BINTAN ');
        $n = \HelperKegiatan::getTahunPerencanaan();
        $sheet->mergeCells ('A2:P2');
        $sheet->setCellValue('A2',"LAPORAN RANCANGAN AKHIR APBD-P $n");
        $sheet->mergeCells ('A3:P3');
        $sheet->setCellValue('A3',"TAHUN ANGGARAN $n");

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
        $sheet->setCellValue('H7','PERUBAHAN RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan());         
        $sheet->mergeCells ('L7:L8');
        $sheet->setCellValue('L7','CATATAN PENTING');
        $sheet->mergeCells ('M7:O7');
        $sheet->setCellValue('M7',"INDIKASI PERUBAHAN");
        
        $sheet->setCellValue('H8','LOKASI'); 
        $sheet->setCellValue('I8','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('K8','SUMBER DANA');         
        $sheet->setCellValue('M8','MURNI'); 
        $sheet->setCellValue('N8','PERUBAHAN'); 
        $sheet->setCellValue('O8','PEMBAHASAN'); 
        $sheet->setCellValue('P8','SISA'); 
        
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
        $sheet->setCellValue('O9',15); 
        $sheet->setCellValue('P9',16); 

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
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('P')->setWidth(20);
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:P9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:P9")->getAlignment()->setWrapText(true);
        
        $struktur = $this->generateStructureRKPD($field,$id,3);
        
        $row=10;        
        $styleArrayProgram=array( 
                        'font' => array('bold' => true),                                                           
                    );       
        $styleArrayKegiatan=array( 
                        'font' => array('bold' => true),                                                           
                    );       

        $total_pagu_kolom_j=0;        
        $total_pagu_kolom_m=0;        
        $total_pagu_kolom_n=0;        
        $total_pagu_kolom_o=0;        
        foreach ($struktur as $Kd_Urusan=>$v1)
        {
            $sheet->getRowDimension($row)->setRowHeight(28);
            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
            $sheet->setCellValue("A$row",$Kd_Urusan);
            $sheet->mergeCells("B$row:E$row");                
            $sheet->setCellValue("F$row",$v1['Nm_Urusan']);
            $sheet->mergeCells("F$row:L$row");                
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
                                                ->where('EntryLvl',4)                                               
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
                        $sheet->getStyle("A$row:O$row")->applyFromArray($styleArrayProgram);

                        $sheet->setCellValue("A$row",0);
                        $sheet->setCellValue("B$row",'00');
                        $sheet->setCellValue("C$row",$Kd_Prog);
                        $sheet->mergeCells("D$row:E$row");
                        $sheet->setCellValue("F$row",$v3['PrgNm']);        
                        $sheet->mergeCells("F$row:I$row");                
                        $sheet->mergeCells("K$row:L$row");       
                        $row_program=$row;
                        $totaleachprogram_kolom_j=0;
                        $totaleachprogram_kolom_m=0;
                        $totaleachprogram_kolom_n=0;
                        $totaleachprogram_kolom_o=0;
                        $row+=1;
                        foreach ($daftar_kegiatan as $v4) 
                        {
                            $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',4)
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
                            $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka4) . ' '.$rkpd->Sasaran_Uraian4))); 
                            $sheet->setCellValue("J$row",\Helper::formatUang($rkpd->NilaiUsulan4)); 
                            $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
                            $sheet->setCellValue("L$row",$rkpd->Descr);                            
                            
                            $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                ->select(\DB::raw('
                                                                "Uraian",
                                                                "Sasaran_Angka4",
                                                                "Sasaran_Uraian4",
                                                                "Target4",
                                                                "NilaiUsulan2",
                                                                "NilaiUsulan3",
                                                                "NilaiUsulan4",
                                                                "Nm_SumberDana",                                                                
                                                                "Lokasi",
                                                                "Descr"
                                                            ')
                                                )                                                
                                                ->where('EntryLvl',4)
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where($field,$id)
                                                ->orderByRaw('"No"::int ASC')
                                                ->get();
                            
                            $row_kegiatan=$row;
                            $row+=1;  
                            $no=1;
                            $totaleachkegiatan_kolom_j = 0;
                            $totaleachkegiatan_kolom_m = 0;
                            $totaleachkegiatan_kolom_n = 0;
                            $totaleachkegiatan_kolom_o = 0;
                            foreach ($rincian_kegiatan as $v5)
                            {
                                $sheet->setCellValue("A$row",0);
                                $sheet->setCellValue("B$row",'00');
                                $sheet->setCellValue("C$row",$Kd_Prog);
                                $sheet->setCellValue("D$row",$v4->Kd_Keg);                             
                                $sheet->setCellValue("E$row",$no);                            
                                $sheet->setCellValue("F$row",$v5->Uraian);    
                                $sheet->setCellValue("G$row",$nama_indikator); 
                                $sheet->setCellValue("H$row",$v5->Lokasi); 
                                $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka4);
                                $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian4)));                                     
                                $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan4)); 
                                $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$v5->Descr); 
                                $sheet->setCellValue("M$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                $sheet->setCellValue("N$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                $sheet->setCellValue("O$row",\Helper::formatUang($v5->NilaiUsulan4)); 
                                $sheet->setCellValue("P$row",\Helper::formatUang($v5->NilaiUsulan4-$v5->NilaiUsulan2)); 
                                $total_pagu_kolom_j+=$v5->NilaiUsulan4;
                                $total_pagu_kolom_m+=$v5->NilaiUsulan2;
                                $total_pagu_kolom_n+=$v5->NilaiUsulan3;
                                $total_pagu_kolom_o+=$v5->NilaiUsulan4;
                                                                                       
                                $totaleachkegiatan_kolom_j+=$v5->NilaiUsulan4;
                                $totaleachkegiatan_kolom_m+=$v5->NilaiUsulan2;
                                $totaleachkegiatan_kolom_n+=$v5->NilaiUsulan3;
                                $totaleachkegiatan_kolom_o+=$v5->NilaiUsulan4;
                                $no+=1;
                                $row+=1;
                            }
                            $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_j)); 
                            $sheet->setCellValue("M$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_m)); 
                            $sheet->setCellValue("N$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_n)); 
                            $sheet->setCellValue("O$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_o-$totaleachkegiatan_kolom_m));                             

                            $totaleachprogram_kolom_j+=$totaleachkegiatan_kolom_j;
                            $totaleachprogram_kolom_m+=$totaleachkegiatan_kolom_m;
                            $totaleachprogram_kolom_n+=$totaleachkegiatan_kolom_n;
                            $totaleachprogram_kolom_o+=$totaleachkegiatan_kolom_o;
                        }
                        $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram_kolom_j));                                                         
                        $sheet->setCellValue("M$row_program",\Helper::formatUang($totaleachprogram_kolom_m)); 
                        $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_kolom_n)); 
                        $sheet->setCellValue("O$row_program",\Helper::formatUang($totaleachprogram_kolom_o-$totaleachprogram_kolom_m)); 
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
                    $sheet->mergeCells("F$row:L$row");
                    $program=$v2['program'];
                    $row+=1;
                    foreach ($program as $v3)
                    {
                        $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',4)
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
                            $sheet->mergeCells("K$row:L$row");          
                            $row_program=$row;
                            $totaleachprogram_kolom_j=0;             
                            $totaleachprogram_kolom_m=0;
                            $totaleachprogram_kolom_n=0;
                            $totaleachprogram_kolom_o=0;
                            $row+=1; 
                            foreach ($daftar_kegiatan as $v4) 
                            {                                
                                $rkpd = \DB::table('v_rkpd')
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('EntryLvl',4)
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
                                $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka4) . ' '.$rkpd->Sasaran_Uraian4)));                                     
                                $sheet->setCellValue("J$row",0); //nilai ini akan di isi oleh dibawah
                                $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$rkpd->Descr); 
                                
                                $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka4",
                                                                    "Sasaran_Uraian4",
                                                                    "Target4",
                                                                    "NilaiUsulan2",
                                                                    "NilaiUsulan3",
                                                                    "NilaiUsulan4",
                                                                    "Nm_SumberDana",
                                                                    "Lokasi",
                                                                    "Descr"
                                                                ')
                                                    )                                                
                                                    ->where('EntryLvl',4)
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where($field,$id)
                                                    ->orderByRaw('"No"::int ASC')
                                                    ->get();

                                $row_kegiatan=$row;
                                $no=1;                                
                                $row+=1;
                                $totaleachkegiatan_kolom_j = 0;
                                $totaleachkegiatan_kolom_m = 0;
                                $totaleachkegiatan_kolom_n = 0;
                                $totaleachkegiatan_kolom_o = 0;
                                foreach ($rincian_kegiatan as $v5)
                                {                     
                                    $sheet->setCellValue("A$row",$Kd_Urusan);
                                    $sheet->setCellValue("B$row",$Kd_Bidang);
                                    $sheet->setCellValue("C$row",$Kd_Prog);
                                    $sheet->setCellValue("D$row",$v4->Kd_Keg);                                 
                                    $sheet->setCellValue("E$row",$no);                            
                                    $sheet->setCellValue("F$row",$v5->Uraian);    
                                    $sheet->setCellValue("G$row",$nama_indikator); 
                                    $sheet->setCellValue("H$row",$v5->Lokasi); 
                                    $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                    $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka4);
                                    $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian4)));                                                                        
                                    $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan4)); 
                                    $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                    $sheet->setCellValue("L$row",$v5->Descr); 
                                    $sheet->setCellValue("M$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                    $sheet->setCellValue("N$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                    $sheet->setCellValue("O$row",\Helper::formatUang($v5->NilaiUsulan4)); 
                                    $sheet->setCellValue("P$row",\Helper::formatUang($v5->NilaiUsulan4-$v5->NilaiUsulan2)); 
                                    $total_pagu_kolom_j+=$v5->NilaiUsulan4;
                                    $total_pagu_kolom_m+=$v5->NilaiUsulan2;
                                    $total_pagu_kolom_n+=$v5->NilaiUsulan3;
                                    $total_pagu_kolom_o+=$v5->NilaiUsulan4;

                                    $totaleachkegiatan_kolom_j+=$v5->NilaiUsulan4;
                                    $totaleachkegiatan_kolom_m+=$v5->NilaiUsulan2;
                                    $totaleachkegiatan_kolom_n+=$v5->NilaiUsulan3;                                  
                                    $totaleachkegiatan_kolom_o+=$v5->NilaiUsulan4;                                  
                                    $no+=1;
                                    $row+=1;
                                }                                   
                                $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_j)); 
                                $sheet->setCellValue("M$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_m)); 
                                $sheet->setCellValue("N$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_n)); 
                                $sheet->setCellValue("O$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_o)); 
                                $sheet->setCellValue("P$row_kegiatan",\Helper::formatUang($totaleachkegiatan_kolom_o-$totaleachkegiatan_kolom_m));                             

                                $totaleachprogram_kolom_j+=$totaleachkegiatan_kolom_j;
                                $totaleachprogram_kolom_m+=$totaleachkegiatan_kolom_m;
                                $totaleachprogram_kolom_n+=$totaleachkegiatan_kolom_n;
                                $totaleachprogram_kolom_o+=$totaleachkegiatan_kolom_o;
                            }
                            $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram_kolom_j));
                            $sheet->setCellValue("M$row_program",\Helper::formatUang($totaleachprogram_kolom_m));
                            $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_kolom_n));
                            $sheet->setCellValue("O$row_program",\Helper::formatUang($totaleachprogram_kolom_o));
                            $sheet->setCellValue("P$row_program",\Helper::formatUang($totaleachprogram_kolom_o-$totaleachprogram_kolom_m));
                        }                        
                    }
                }
            }
        }
        
        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);  
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:H$row"); 
        $sheet->setCellValue("I$row",'TOTAL'); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_pagu_kolom_j));       
        $sheet->mergeCells("K$row:L$row"); 
        $sheet->setCellValue("M$row",\Helper::formatUang($total_pagu_kolom_m));    
        $sheet->setCellValue("N$row",\Helper::formatUang($total_pagu_kolom_n));    
        $sheet->setCellValue("O$row",\Helper::formatUang($total_pagu_kolom_o));    
        $sheet->setCellValue("P$row",\Helper::formatUang($total_pagu_kolom_o-$total_pagu_kolom_m));    

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
        $sheet->getStyle("F10:G$row")->applyFromArray($styleArray);

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("M10:P$row")->applyFromArray($styleArray); 

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