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
        $OrgIDRPJMD = $this->dataReport['OrgIDRPJMD'];

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
                            ->where('OrgIDRPJMD',$OrgIDRPJMD)
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
                                            ->where('OrgID',$OrgID);
            $daftar_kegiatan = ($SOrgID == 'none' || $SOrgID == '') ?
                                                                    $daftar_kegiatan->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                                                    ->orderBy('kode_kegiatan','ASC')       
                                                                                    ->get()
                                                                    :
                                                                    $daftar_kegiatan->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                                                    ->where('SOrgID',$SOrgID)
                                                                                    ->orderBy('kode_kegiatan','ASC')       
                                                                                    ->get();
                                            
                                            
            if (isset($daftar_kegiatan[0])) 
            {   
                $kode_program=$v->kode_program==''?$this->dataReport['kode_organisasi'].'.'.$v->Kd_Prog:$v->kode_program;
                $PrgNm=$v->PrgNm;     
                $sheet->getStyle("A$row:P$row")->getFont()->setBold(true);                                
                $sheet->mergeCells ("A$row:F$row");
                $sheet->setCellValue("A$row",$kode_program);
                $sheet->setCellValue("G$row",$PrgNm);
                $totalpagueachprogramM= $daftar_kegiatan->sum('NilaiUsulan1');      
                $totalpagueachprogramP= $daftar_kegiatan->sum('NilaiUsulan2');                      
                $sheet->setCellValue("M$row",\Helper::formatUang($totalpagueachprogramM)); 
                $sheet->setCellValue("N$row",\Helper::formatUang($totalpagueachprogramP)); 
                $sheet->setCellValue("O$row",\Helper::formatUang($totalpagueachprogramP-$totalpagueachprogramM)); 
                $sheet->setCellValue("P$row",'');
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
        $sheet->setTitle ('LAPORAN RKPDP TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:O1');
        $sheet->setCellValue('A1','PEMERINTAH DAERAH KABUPATEN BINTAN');
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
        $sheet->setCellValue('F7','BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('G7:H7');
        $sheet->setCellValue('G7','SASARAN'); 
        $sheet->mergeCells ('I7:I8');
        $sheet->setCellValue('I7','LOKASI');         
        $sheet->mergeCells ('J7:J8');
        $sheet->setCellValue('J7','TARGET (%)');
        $sheet->mergeCells ('K7:K8');
        $sheet->setCellValue('K7','SUMBER DANA');
        $sheet->mergeCells ('L7:N7');
        $sheet->setCellValue('L7','INDIKASI TA(n)');
        $sheet->mergeCells ('O7:O8');
        $sheet->setCellValue('O7','KETERANGAN');

        $sheet->setCellValue('G8','SEBELUM'); 
        $sheet->setCellValue('H8','SESUDAH'); 

        $sheet->setCellValue('L8','SEBELUM'); 
        $sheet->setCellValue('M8','SESUDAH'); 
        $sheet->setCellValue('N8','SELISIH'); 

        $sheet->mergeCells ('A9:E9');
        $sheet->setCellValue('A9',1); 
        $sheet->setCellValue('F9',2); 
        $sheet->setCellValue('G9',3); 
        $sheet->setCellValue('H9',4); 
        $sheet->setCellValue('I9',5); 
        $sheet->setCellValue('J9',6); 
        $sheet->setCellValue('K9',7); 
        $sheet->setCellValue('L9',9); 
        $sheet->setCellValue('M9',9); 
        $sheet->setCellValue('N9',10); 
        $sheet->setCellValue('O9',11);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(5);

        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(17);
        $sheet->getColumnDimension('K')->setWidth(12);
        $sheet->getColumnDimension('L')->setWidth(17);
        $sheet->getColumnDimension('M')->setWidth(17);
        $sheet->getColumnDimension('N')->setWidth(17);
        $sheet->getColumnDimension('O')->setWidth(17);

        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:O9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:O9")->getAlignment()->setWrapText(true);

        $struktur = $this->generateStructureRKPD($field,$id,4);
        $row=10;
        $total_pagu=0;        
        $total_pagu_setelah=0;

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
            $sheet->mergeCells("F$row:O$row");
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
                        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);

                        $sheet->setCellValue("A$row",0);
                        $sheet->setCellValue("B$row",'00');
                        $sheet->setCellValue("C$row",$Kd_Prog);
                        $sheet->mergeCells("D$row:E$row");
                        $sheet->setCellValue("F$row",$v3['PrgNm']);        
                        $sheet->mergeCells("F$row:K$row");                
                        $row_program=$row;
                        $totaleachprogram = 0;
                        $totaleachprogram_setelah=0;
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
                            $sasaran_angka3=\Helper::formatAngka($rkpd->Sasaran_Angka3);
                            $sheet->setCellValue("G$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka3.' '.$rkpd->Sasaran_Uraian3)));                                                                        
                            $sasaran_angka4=\Helper::formatAngka($rkpd->Sasaran_Angka4);
                            $sheet->setCellValue("H$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka4.' '.$rkpd->Sasaran_Uraian4)));
                            $sheet->setCellValue("I$row",'Kab. Bintan'); 
                            $sheet->setCellValue("J$row",\Helper::formatAngka($rkpd->Target4));                              
                            $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana);                            
                            $sheet->setCellValue("O$row",$rkpd->Descr); 

                            $row_kegiatan=$row;
                            $row+=1;  
                            $no=1;
                            $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                ->select(\DB::raw('
                                                                "Uraian",
                                                                    "Sasaran_Angka3",
                                                                    "Sasaran_Angka4",
                                                                    "Sasaran_Uraian3",
                                                                    "Sasaran_Uraian4",
                                                                    "Target3",
                                                                    "Target4",
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

                            $totaleachkegiatan = 0;
                            $totaleachkegiatan_setelah = 0;                            
                            foreach ($rincian_kegiatan as $v5)
                            {
                                $sheet->setCellValue("A$row",0);                                
                                $sheet->setCellValue("B$row",'00');
                                $sheet->setCellValue("C$row",$Kd_Prog);
                                $sheet->setCellValue("D$row",$v4->Kd_Keg);                             
                                $sheet->setCellValue("E$row",$no);                            
                                $sheet->setCellValue("F$row",$v5->Uraian);    
                                $sasaran_angka3=\Helper::formatAngka($v5->Sasaran_Angka3);
                                $sheet->setCellValue("G$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka3.' '.$v5->Sasaran_Uraian3)));                                                                        
                                $sasaran_angka4=\Helper::formatAngka($v5->Sasaran_Angka4);
                                $sheet->setCellValue("H$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka4.' '.$v5->Sasaran_Uraian4)));                                                                        
                                // $sheet->setCellValue("I$row",$v5->Lokasi); 
                                $sheet->setCellValue("I$row",'Kab. Bintan');                                     
                                $sheet->setCellValue("J$row",\Helper::formatAngka($v5->Target4));                                     
                                $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                $sheet->setCellValue("M$row",\Helper::formatUang($v5->NilaiUsulan4)); 
                                $sheet->setCellValue("N$row",\Helper::formatUang($v5->NilaiUsulan4-$v5->NilaiUsulan3)); 
                                $sheet->setCellValue("O$row",$v5->Descr);
                                $total_pagu+=$v5->NilaiUsulan3;
                                $total_pagu_setelah+=$v5->NilaiUsulan4;
                                $totaleachkegiatan+=$v5->NilaiUsulan3;                   
                                $totaleachkegiatan_setelah+=$v5->NilaiUsulan4;                   
                                $no+=1;
                                $row+=1;
                            }
                            $sheet->setCellValue("L$row_kegiatan",\Helper::formatUang($totaleachkegiatan)); 
                            $sheet->setCellValue("M$row_kegiatan",\Helper::formatUang($totaleachkegiatan_setelah)); 
                            $sheet->setCellValue("N$row_kegiatan",\Helper::formatUang($totaleachkegiatan_setelah-$totaleachkegiatan)); 
                            $totaleachprogram+=$totaleachkegiatan; 
                            $totaleachprogram_setelah+=$totaleachkegiatan_setelah;
                        }
                        $sheet->setCellValue("L$row_program",\Helper::formatUang($totaleachprogram));                                 
                        $sheet->setCellValue("M$row_program",\Helper::formatUang($totaleachprogram_setelah));
                        $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_setelah-$totaleachprogram));
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
                            $sheet->mergeCells("K$row:M$row");          
                            $row_program=$row;
                            $totaleachprogram = 0;             
                            $totaleachprogram_setelah=0;
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
                                $sasaran_angka3=\Helper::formatAngka($rkpd->Sasaran_Angka3);
                                $sheet->setCellValue("G$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka3.' '.$rkpd->Sasaran_Uraian3)));
                                $sasaran_angka4=\Helper::formatAngka($rkpd->Sasaran_Angka4);
                                $sheet->setCellValue("H$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka4.' '.$rkpd->Sasaran_Uraian4)));
                                $sheet->setCellValue("I$row",'Kab. Bintan'); 
                                $sheet->setCellValue("J$row",\Helper::formatAngka($rkpd->Target4));                              
                                $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana);                            
                                $sheet->setCellValue("O$row",$rkpd->Descr); 
                                
                                $row_kegiatan=$row;
                                $no=1;                                
                                $row+=1;                                
                                $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka3",
                                                                    "Sasaran_Angka4",
                                                                    "Sasaran_Uraian3",
                                                                    "Sasaran_Uraian4",
                                                                    "Target3",
                                                                    "Target4",
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

                               
                                $totaleachkegiatan = 0;
                                $totaleachkegiatan_setelah = 0;                              
                                foreach ($rincian_kegiatan as $v5)
                                {                     
                                    $sheet->setCellValue("A$row",$Kd_Urusan);
                                    $sheet->setCellValue("B$row",$Kd_Bidang);
                                    $sheet->setCellValue("C$row",$Kd_Prog);
                                    $sheet->setCellValue("D$row",$v4->Kd_Keg);                                 
                                    $sheet->setCellValue("E$row",$no);                            
                                    $sheet->setCellValue("F$row",$v5->Uraian);    
                                    $sasaran_angka3=\Helper::formatAngka($v5->Sasaran_Angka3);
                                    $sheet->setCellValue("G$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka3.' '.$v5->Sasaran_Uraian3)));                                                                        
                                    $sasaran_angka4=\Helper::formatAngka($v5->Sasaran_Angka4);
                                    $sheet->setCellValue("H$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka4.' '.$v5->Sasaran_Uraian4)));                                                                        
                                    // $sheet->setCellValue("I$row",$v5->Lokasi); 
                                    $sheet->setCellValue("I$row",'Kab. Bintan');                                     
                                    $sheet->setCellValue("J$row",\Helper::formatAngka($v5->Target4));                                     
                                    $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                    $sheet->setCellValue("L$row",\Helper::formatUang($v5->NilaiUsulan3)); 
                                    $sheet->setCellValue("M$row",\Helper::formatUang($v5->NilaiUsulan4)); 
                                    $sheet->setCellValue("N$row",\Helper::formatUang($v5->NilaiUsulan4-$v5->NilaiUsulan3)); 
                                    $sheet->setCellValue("O$row",$v5->Descr);

                                    $total_pagu+=$v5->NilaiUsulan3;
                                    $total_pagu_setelah+=$v5->NilaiUsulan4;
                                    $totaleachkegiatan+=$v5->NilaiUsulan3;                   
                                    $totaleachkegiatan_setelah+=$v5->NilaiUsulan4;                   
                                    $no+=1;
                                    $row+=1;
                                }  
                                $sheet->setCellValue("L$row_kegiatan",\Helper::formatUang($totaleachkegiatan)); 
                                $sheet->setCellValue("M$row_kegiatan",\Helper::formatUang($totaleachkegiatan_setelah)); 
                                $sheet->setCellValue("N$row_kegiatan",\Helper::formatUang($totaleachkegiatan_setelah-$totaleachkegiatan)); 
                                $totaleachprogram+=$totaleachkegiatan; 
                                $totaleachprogram_setelah+=$totaleachkegiatan_setelah;
                            }
                            $sheet->setCellValue("L$row_program",\Helper::formatUang($totaleachprogram));                                 
                            $sheet->setCellValue("M$row_program",\Helper::formatUang($totaleachprogram_setelah));
                            $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_setelah-$totaleachprogram));
                        }
                    }
                }
            }
        }
        $row+=1;
        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);  
        $sheet->getRowDimension($row)->setRowHeight(30);
        $sheet->mergeCells("A$row:J$row"); 
        $sheet->setCellValue("K$row",'TOTAL'); 
        $sheet->setCellValue("L$row",\Helper::formatUang($total_pagu));       
        $sheet->setCellValue("M$row",\Helper::formatUang($total_pagu_setelah));    
        $sheet->setCellValue("N$row",\Helper::formatUang($total_pagu_setelah-$total_pagu));    

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