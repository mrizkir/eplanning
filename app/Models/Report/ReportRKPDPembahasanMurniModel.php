<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

class ReportRKPDPembahasanMurniModel extends ReportModel
{   
    public function __construct($dataReport)
    {
        parent::__construct($dataReport); 
        $this->print();             
    }
    private function generateStructure($field,$id)
    {
        $urusan_program = \DB::table('v_organisasi_program')
                            ->where($field,$id)
                            ->get();
        // dd($urusan_program);
        $bp=$urusan_program;
        $p=$urusan_program;
        $data=[];
        foreach ($urusan_program as $v)
        {
            if (is_null($v->Kd_Urusan)) // semua urusan
            {
                $program=[];
                foreach ($p as $p_value)
                {
                    if ($p_value->Kd_Urusan == 0 && $p_value->Kd_Bidang == 0)
                    {
                        $program[$p_value->Kd_Prog]=[
                                                        'PrgID'=>$p_value->PrgID,
                                                        'PrgNm'=>$p_value->PrgNm
                                                    ];
                    }
                }
                $data[0]=['Nm_Urusan'=>'SEMUA URUSAN',
                            'program'=>$program  
                        ];
            }
            else
            {
                $Kd_Urusan = $v->Kd_Urusan;
                $bidang_pemerintahan=[];

                foreach ($bp as $bp_value)
                {
                    if ($bp_value->Kd_Urusan==$Kd_Urusan)
                    {
                        $Kd_Bidang=$bp_value->Kd_Bidang;
                        $program=[];

                        foreach ($p as $p_value)
                        {
                            if ($p_value->Kd_Urusan == $Kd_Urusan && $p_value->Kd_Bidang == $Kd_Bidang)
                            {
                                $program[$p_value->Kd_Prog]=[
                                                                'PrgID'=>$p_value->PrgID,
                                                                'PrgNm'=>$p_value->PrgNm
                                                            ];
                            }
                        }
                        $bidang_pemerintahan[$Kd_Bidang]=[
                                                            'Nm_Bidang'=>$bp_value->Nm_Bidang,
                                                            'program'=>$program
                                                        ];
                    }
                }
                
                $data[$Kd_Urusan]=[                                        
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'bidang_pemerintahan'=>$bidang_pemerintahan
                                ];
            }
        }
        // dd($data);
        return $data;
    }
    private function  print()  
    {
       
        $OrgIDRPJMD = $this->dataReport['OrgIDRPJMD'];
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

        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(7);
        $sheet->getColumnDimension('C')->setWidth(7);
        $sheet->getColumnDimension('D')->setWidth(7);
        $sheet->getColumnDimension('E')->setWidth(7);
        $sheet->getColumnDimension('F')->setWidth(40);        
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(14);
        $sheet->getColumnDimension('J')->setWidth(17);
        $sheet->getColumnDimension('K')->setWidth(23);
        $sheet->getColumnDimension('L')->setWidth(23);
        $sheet->getColumnDimension('M')->setWidth(14);
        $sheet->getColumnDimension('N')->setWidth(17);
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:N9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:N9")->getAlignment()->setWrapText(true);
        
        $struktur = $this->generateStructure('OrgIDRPJMD',$OrgIDRPJMD);
        $row=10;
        $total_pagu=0;
        $total_nilai_setelah=0;

        

        $styleArrayProgram=array( 
                        'font' => array('bold' => true),   
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKYELLOW,
                            ],
                            'endColor' => [
                                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKYELLOW,
                            ],
                        ]                                
                    );       
        $styleArrayKegiatan=array( 
                        'font' => array('bold' => true),   
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN,
                            ],
                            'endColor' => [
                                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN,
                            ],
                        ]                                
                    );       
        foreach ($struktur as $Kd_Urusan=>$v1)
        {
            $sheet->setCellValue("A$row",$Kd_Urusan);
            $sheet->setCellValue("F$row",$v1['Nm_Urusan']);
            if ($Kd_Urusan == 0)
            {
                $program=$v1['program'];
                $row+=1;
                foreach ($program as $Kd_Prog=>$v3)
                {   
                    $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',2)
                                                ->groupBy('trRKPD.KgtID')
                                                ->groupBy('tmKgt.Kd_Keg')
                                                ->groupBy('tmKgt.KgtNm')
                                                ->where($field,$id)
                                                ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                ->get();
                                                
                    if (count($daftar_kegiatan)  > 0)
                    {
                        $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);

                        $sheet->setCellValue("A$row",0);
                        $sheet->setCellValue("B$row",'00');
                        $sheet->setCellValue("C$row",$Kd_Prog);
                        $sheet->setCellValue("F$row",$v3['PrgNm']);                        
                        $row+=1;

                        
                        foreach ($daftar_kegiatan as $v4) 
                        {
                            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);
                            $sheet->setCellValue("A$row",0);
                            $sheet->setCellValue("B$row",'00');
                            $sheet->setCellValue("C$row",$Kd_Prog);
                            $sheet->setCellValue("D$row",$v4->Kd_Keg);                            
                            $sheet->setCellValue("F$row",$v4->KgtNm);  
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
                                                                "NilaiUsulan2",
                                                                "Descr"
                                                            ')
                                                )                                                
                                                ->where('EntryLvl',2)
                                                ->where('KgtID',$v4->KgtID)
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where($field,$id)
                                                ->orderByRaw('"No"::int ASC')
                                                ->get();
                            foreach ($rincian_kegiatan as $v5)
                            {
                                $sheet->mergeCells("A$row:D$row",$Kd_Urusan);                          
                                $sheet->setCellValue("E$row",$no);                            
                                $sheet->setCellValue("F$row",$v5->Uraian);    
                                $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka2);
                                $sheet->setCellValue("G$row",$sasaran_angka.' '.$v5->Sasaran_Uraian2);                                    
                                $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                $sheet->setCellValue("I$row",\Helper::formatAngka($v5->Target2)); 
                                $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                $sheet->setCellValue("L$row",$v5->Descr); 
                                $total_pagu+=$v5->NilaiUsulan2;
                                $no+=1;
                                $row+=1;
                            }
                        }
                    }
                }
            }
            else
            {
                $bidang_pemerintahan=$v1['bidang_pemerintahan'];
                $row+=1;
                foreach ($bidang_pemerintahan as $Kd_Bidang=>$v2)
                {
                    $sheet->setCellValue("A$row",$Kd_Urusan);
                    $sheet->setCellValue("B$row",$Kd_Bidang);
                    $sheet->setCellValue("F$row",$v2['Nm_Bidang']);
                    $program=$v2['program'];
                    $row+=1;
                    foreach ($program as $Kd_Prog=>$v3)
                    {
                        $daftar_kegiatan = \DB::table('trRKPD')
                                                ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',2)
                                                ->groupBy('trRKPD.KgtID')
                                                ->groupBy('tmKgt.Kd_Keg')
                                                ->groupBy('tmKgt.KgtNm')
                                                ->where($field,$id)
                                                ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
                                                ->get();       
                        if (count($daftar_kegiatan)  > 0)
                        {   
                            $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
                            
                            $sheet->setCellValue("A$row",$Kd_Urusan);
                            $sheet->setCellValue("B$row",$Kd_Bidang);
                            $sheet->setCellValue("C$row",$Kd_Prog);
                            $sheet->setCellValue("F$row",$v3['PrgNm']);                        
                            $row+=1;               
                            foreach ($daftar_kegiatan as $v4) 
                            {
                                $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);
                                $sheet->setCellValue("A$row",$Kd_Urusan);
                                $sheet->setCellValue("B$row",$Kd_Bidang);
                                $sheet->setCellValue("C$row",$Kd_Prog);
                                $sheet->setCellValue("D$row",$v4->Kd_Keg);                            
                                $sheet->setCellValue("F$row",$v4->KgtNm);    
                                $no=1;
                                $rincian_kegiatan = \DB::table('v_rkpd_rinci')
                                                    ->select(\DB::raw('
                                                                    "Uraian",
                                                                    "Sasaran_Angka2",
                                                                    "Sasaran_Uraian2",
                                                                    "Target2",
                                                                    "NilaiUsulan2",
                                                                    "Nm_SumberDana",
                                                                    "NilaiUsulan2",
                                                                    "Descr"
                                                                ')
                                                    )                                                
                                                    ->where('EntryLvl',2)
                                                    ->where('KgtID',$v4->KgtID)
                                                    ->where('PrgID',$v3['PrgID'])
                                                    ->where($field,$id)
                                                    ->orderByRaw('"No"::int ASC')
                                                    ->get();
                                $row+=1;
                                foreach ($rincian_kegiatan as $v5)
                                {                                
                                    $sheet->mergeCells("A$row:D$row",$Kd_Urusan);                          
                                    $sheet->setCellValue("E$row",$no);                            
                                    $sheet->setCellValue("F$row",$v5->Uraian);    
                                    $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka2);
                                    $sheet->setCellValue("G$row",$sasaran_angka.' '.$v5->Sasaran_Uraian2);                                    
                                    $sheet->setCellValue("H$row",'Kab. Bintan'); 
                                    $sheet->setCellValue("I$row",\Helper::formatAngka($v5->Target2)); 
                                    $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan2)); 
                                    $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
                                    $sheet->setCellValue("L$row",$v5->Descr); 
                                    $total_pagu+=$v5->NilaiUsulan2;
                                    $no+=1;
                                    $row+=1;
                                }
                            }
                        }
                    }
                }

            }
            $row+=1;
        }

        $sheet->setCellValue("I$row",'TOTAL'); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_pagu));      

        $row=$row-1;
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
        $sheet->getStyle("F10:F$row")->applyFromArray($styleArray);

        $row=$row+1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("N10:N$row")->applyFromArray($styleArray);

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