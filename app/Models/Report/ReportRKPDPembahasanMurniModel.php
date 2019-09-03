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
        $field = 'OrgID';
        $id = $OrgID;

        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN RKPD TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:J1');
        $sheet->setCellValue('A1','RUMUSAN PROGRAM DAN KEGIATAN OPD TAHUN '.\HelperKegiatan::getTahunPerencanaan());
        $n1 = \HelperKegiatan::getTahunPerencanaan()+1;
        $sheet->mergeCells ('A2:J2');
        $sheet->setCellValue('A2','DAN PRAKIRAAN MAJU TAHUN '.$n1);
        $sheet->mergeCells ('A3:J3');
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
        // $sheet->mergeCells ('C7:C8');
        // $sheet->setCellValue('C7','INDIKATOR KINERJA PROGRAM/KEGIATAN'); 
        // $sheet->mergeCells ('D7:G7');
        // $sheet->setCellValue('D7','RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan());         
        // $sheet->mergeCells ('H7:H8');
        // $sheet->setCellValue('H7','CATATAN PENTING');
        // $sheet->mergeCells ('I7:J7');
        // $sheet->setCellValue('I7','PERKIRAAN MAJU RENCANA TAHUN '.$n1);
        
        // $sheet->setCellValue('D8','LOKASI'); 
        // $sheet->setCellValue('E8','TARGET CAPAIAN KINERJA'); 
        // $sheet->setCellValue('F8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        // $sheet->setCellValue('G8','SUMBER DANA');         
        // $sheet->setCellValue('I8','TARGET CAPAIAN KINERJA'); 
        // $sheet->setCellValue('J8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        
        // $sheet->setCellValue('A9',1); 
        // $sheet->setCellValue('B9',2); 
        // $sheet->setCellValue('C9',3); 
        // $sheet->setCellValue('D9',4); 
        // $sheet->setCellValue('E9',5); 
        // $sheet->setCellValue('F9',6); 
        // $sheet->setCellValue('G9',7); 
        // $sheet->setCellValue('H9',9); 
        // $sheet->setCellValue('I9',9); 
        // $sheet->setCellValue('J9',10); 

        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(7);
        $sheet->getColumnDimension('C')->setWidth(7);
        $sheet->getColumnDimension('D')->setWidth(7);
        $sheet->getColumnDimension('E')->setWidth(7);
        $sheet->getColumnDimension('F')->setWidth(40);
        // $sheet->getColumnDimension('C')->setWidth(30);
        // $sheet->getColumnDimension('D')->setWidth(15);
        // $sheet->getColumnDimension('E')->setWidth(20);
        // $sheet->getColumnDimension('F')->setWidth(17);
        // $sheet->getColumnDimension('G')->setWidth(9);
        // $sheet->getColumnDimension('H')->setWidth(17);
        // $sheet->getColumnDimension('I')->setWidth(20);
        // $sheet->getColumnDimension('J')->setWidth(17);
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:J9")->applyFromArray($styleArray);
        $sheet->getStyle("A7:J9")->getAlignment()->setWrapText(true);
        
        $struktur = $this->generateStructure('OrgIDRPJMD',$OrgIDRPJMD);
        $row=11;
        $total_pagu=0;
        $total_nilai_setelah=0;

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
                    $sheet->setCellValue("A$row",0);
                    $sheet->setCellValue("B$row",'00');
                    $sheet->setCellValue("C$row",$Kd_Prog);
                    $sheet->setCellValue("F$row",$v3['PrgNm']);                        
                    $row+=1;
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
                        $sheet->setCellValue("A$row",$Kd_Urusan);
                        $sheet->setCellValue("B$row",$Kd_Bidang);
                        $sheet->setCellValue("C$row",$Kd_Prog);
                        $sheet->setCellValue("F$row",$v3['PrgNm']);                        
                        $row+=1;
                        $daftar_kegiatan = \DB::table('v_rkpd')
                                                ->select(\DB::raw('"kode_kegiatan","Kd_Keg","KgtNm","NamaIndikator","Sasaran_Angka2","Sasaran_Uraian2","Target2","NilaiUsulan2","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
                                                ->where('PrgID',$v3['PrgID'])
                                                ->where('EntryLvl',2)
                                                ->where($field,$id)
                                                ->get();

                        foreach ($daftar_kegiatan as $n) 
                        {
                            $sheet->mergeCells("A$row:C$row");
                            $sheet->setCellValue("D$row",$n->Kd_Keg);
                            $sheet->setCellValue("E$row",0);
                            $sheet->setCellValue("F$row",$n->KgtNm);              
                            // $sheet->setCellValue("A$row",$n['kode_kegiatan']); 
                            // $sheet->setCellValue("B$row",$n['KgtNm']); 
                            // $sheet->setCellValue("C$row",$n['NamaIndikator']); 
                            // $sheet->setCellValue("D$row",'Kab. Bintan'); 
                            // $sheet->setCellValue("E$row",\Helper::formatAngka($n['Sasaran_Angka2']) . ' '.$n['Sasaran_Uraian2']); 
                            // $sheet->setCellValue("F$row",\Helper::formatUang($n['NilaiUsulan2'])); 
                            // $sheet->setCellValue("G$row",$n['Nm_SumberDana']); 
                            // $sheet->setCellValue("H$row",$n['Descr']); 
                            // $sheet->setCellValue("I$row",\Helper::formatAngka($n['Sasaran_AngkaSetelah']).' '.$n['Sasaran_UraianSetelah']); 
                            // $sheet->setCellValue("J$row",\Helper::formatUang($n['NilaiSetelah'])); 
                            // $total_pagu+=$n['NilaiUsulan2'];
                            // $total_nilai_setelah+=$n['NilaiSetelah'];
                            $row+=1;
                        }
                    }
                }

            }
            $row+=1;
        }
        // $daftar_program=\DB::table('v_organisasi_program')
        
        //                     ->select(\DB::raw('"PrgID","kode_program","Kd_Prog","PrgNm","Jns"'))
        //                     ->where('OrgIDRPJMD',$OrgID)
        //                     ->where('TA',\HelperKegiatan::getTahunPerencanaan())
        //                     ->orderByRaw('kode_program ASC NULLS FIRST')
        //                     ->orderBy('Kd_Prog','ASC')
        //                     ->get();
        
        // $row=11;
        // $total_pagu=0;
        // $total_nilai_setelah=0;

        // foreach ($daftar_program as $v)
        // {
            // $PrgID=$v->PrgID;           
            // $daftar_kegiatan = RKPDViewJudulModel::select(\DB::raw('"kode_kegiatan","KgtNm","NamaIndikator","Sasaran_Angka2","Sasaran_Uraian2","Target2","NilaiUsulan2","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
            //                                 ->where('PrgID',$PrgID)      
            //                                 ->where('OrgID',$OrgID);

            // $daftar_kegiatan = ($SOrgID == 'none' || $SOrgID == '') ? 
            //                                                         $daftar_kegiatan->where('TA',\HelperKegiatan::getTahunPerencanaan())
            //                                                         ->orderBy('kode_kegiatan','ASC')       
            //                                                         ->get()
            //                                                     :
            //                                                         $daftar_kegiatan->where('TA',\HelperKegiatan::getTahunPerencanaan())
            //                                                         ->where('SOrgID',$SOrgID)
            //                                                         ->orderBy('kode_kegiatan','ASC')       
            //                                                         ->get();

                                            
        //     if (isset($daftar_kegiatan[0])) 
        //     {   
        //         $kode_program=$v->kode_program==''?$this->dataReport['kode_organisasi'].'.'.$v->Kd_Prog:$v->kode_program;
        //         $PrgNm=$v->PrgNm;
        //         $sheet->getStyle("A$row:J$row")->getFont()->setBold(true);                                
        //         $sheet->setCellValue("A$row",$kode_program);
        //         $sheet->setCellValue("B$row",$PrgNm);
        //         $totalpagueachprogram= $daftar_kegiatan->sum('NilaiUsulan2');      
        //         $totalnilaisetelah= $daftar_kegiatan->sum('NilaiSetelah');  
        //         $sheet->setCellValue("F$row",\Helper::formatUang($totalpagueachprogram)); 
        //         $sheet->setCellValue("J$row",\Helper::formatUang($totalnilaisetelah)); 
        //         $row+=1;
        //         foreach ($daftar_kegiatan as $n) 
        //         {
        //             $sheet->setCellValue("A$row",$n['kode_kegiatan']); 
        //             $sheet->setCellValue("B$row",$n['KgtNm']); 
        //             $sheet->setCellValue("C$row",$n['NamaIndikator']); 
        //             $sheet->setCellValue("D$row",'Kab. Bintan'); 
        //             $sheet->setCellValue("E$row",\Helper::formatAngka($n['Sasaran_Angka2']) . ' '.$n['Sasaran_Uraian2']); 
        //             $sheet->setCellValue("F$row",\Helper::formatUang($n['NilaiUsulan2'])); 
        //             $sheet->setCellValue("G$row",$n['Nm_SumberDana']); 
        //             $sheet->setCellValue("H$row",$n['Descr']); 
        //             $sheet->setCellValue("I$row",\Helper::formatAngka($n['Sasaran_AngkaSetelah']).' '.$n['Sasaran_UraianSetelah']); 
        //             $sheet->setCellValue("J$row",\Helper::formatUang($n['NilaiSetelah'])); 
        //             $total_pagu+=$n['NilaiUsulan2'];
        //             $total_nilai_setelah+=$n['NilaiSetelah'];
        //             $row+=1;
        //         }
        //     }
        // }        
        // $sheet->setCellValue("E$row",'TOTAL'); 
        // $sheet->setCellValue("F$row",\Helper::formatUang($total_pagu)); 
        // $sheet->setCellValue("J$row",\Helper::formatUang($total_nilai_setelah)); 
        

        // if ($SOrgID != 'none'&&$SOrgID != ''&&$SOrgID != null)
        // {
        //     $sheet->setCellValue("A10",$this->dataReport['kode_suborganisasi']); 
        //     $sheet->setCellValue("B10",$this->dataReport['SOrgNm']);         
        //     $sheet->setCellValue("F10",\Helper::formatUang($total_pagu));  
        //     $sheet->setCellValue("J10",\Helper::formatUang($total_nilai_setelah));                     
        // }        
        // else
        // {
        //     $sheet->setCellValue("A10",$this->dataReport['kode_organisasi']); 
        //     $sheet->setCellValue("B10",$this->dataReport['OrgNm']);         
        //     $sheet->setCellValue("F10",\Helper::formatUang($total_pagu));  
        //     $sheet->setCellValue("J10",\Helper::formatUang($total_nilai_setelah));   
        // }

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
        $sheet->getStyle("F10:F$row")->applyFromArray($styleArray);

        // $row=$row+1;
        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        // );																					 
        // $sheet->getStyle("F10:F$row")->applyFromArray($styleArray);
        // $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);

        // $row+=3;
        // $sheet->setCellValue("H$row",'BANDAR SRI BENTAN, '.\Helper::tanggal('d F Y'));
        // $row+=1;        
        // $sheet->setCellValue("H$row",'KEPALA DINAS');                                          
        // $row+=1;        
        // $sheet->setCellValue("H$row",strtoupper($this->dataReport['OrgNm']));                                          
                
        // $row+=5;
        // $sheet->setCellValue("H$row",$this->dataReport['NamaKepalaSKPD']);
        // $row+=1;                
        
        // $sheet->setCellValue("H$row",'NIP.'.$this->dataReport['NamaKepalaSKPD']);
    }   
}