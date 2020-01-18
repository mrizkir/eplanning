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
            $this->print();             
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
        $sheet->mergeCells ('A1:H1');
        $sheet->setCellValue('A1','LAPORAN MUSRENBANG TINGKAT KECAMATAN TAHUN PERENCANAAN '.\HelperKegiatan::getTahunPerencanaan());
        $sheet->mergeCells ('A2:H2');
        $sheet->setCellValue('A2',strtoupper($this->dataReport['Nm_Kecamatan'])); 
        $sheet->mergeCells ('A3:H3');
        $sheet->setCellValue('A3','KABUPATEN BINTAN');
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:H3")->applyFromArray($styleArray);        
        
        $sheet->setCellValue('A5','NO'); 
        $sheet->setCellValue('B5','NAMA KEGIATAN'); 
        $sheet->setCellValue('C5','KELUARAN/OUTPUT'); 
        $sheet->setCellValue('D5','VOLUME'); 
        $sheet->setCellValue('E5','NILAI PAGU');         
        $sheet->setCellValue('F5','PRIORITAS');         
        $sheet->setCellValue('G5','STATUS');         
        $sheet->setCellValue('H5','KET.');         
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A5:H5")->applyFromArray($styleArray);
        $sheet->getStyle("A5:H5")->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(11);        
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->getColumnDimension('H')->setWidth(17);
        
        $data = \DB::table('trUsulanKec')
                ->select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","tmPmDesa"."Nm_Desa","trUsulanKec"."No_usulan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Bobot","trUsulanKec"."Privilege"'))
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
            $sheet->setCellValue("B$row",$item['NamaKegiatan']);

            $row+=1;
        }
        $row-=1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A6:H$row")->applyFromArray($styleArray);
        $sheet->getStyle("A6:H$row")->getAlignment()->setWrapText(true);  

        //         foreach ($bidang_pemerintahan as $Kd_Bidang=>$v2)
        //         {
        //             $sheet->getRowDimension($row)->setRowHeight(28);
        //             $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
        //             $sheet->setCellValue("A$row",$Kd_Urusan);
        //             $sheet->setCellValue("B$row",$Kd_Bidang);
        //             $sheet->mergeCells("C$row:E$row");
        //             $sheet->setCellValue("F$row",$v2['Nm_Bidang']);
        //             $sheet->mergeCells("F$row:N$row");
        //             $program=$v2['program'];
        //             $row+=1;
        //             foreach ($program as $v3)
        //             {                        
        //                 $daftar_kegiatan = \DB::table('trRKPD')
        //                                         ->select(\DB::raw('"trRKPD"."KgtID","tmKgt"."Kd_Keg","tmKgt"."KgtNm"'))
        //                                         ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
        //                                         ->where('PrgID',$v3['PrgID'])
        //                                         ->where('EntryLvl',1)
        //                                         ->groupBy('trRKPD.KgtID')
        //                                         ->groupBy('tmKgt.Kd_Keg')
        //                                         ->groupBy('tmKgt.KgtNm')
        //                                         ->where($field,$id)
        //                                         ->orderByRaw('"tmKgt"."Kd_Keg"::int ASC')
        //                                         ->get();       
        //                 if (count($daftar_kegiatan)  > 0)
        //                 {   
        //                     $Kd_Prog = $v3['Kd_Prog'];
        //                     $sheet->getRowDimension($row)->setRowHeight(28);
        //                     $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayProgram);
                            
        //                     $sheet->setCellValue("A$row",$Kd_Urusan);
        //                     $sheet->setCellValue("B$row",$Kd_Bidang);
        //                     $sheet->setCellValue("C$row",$Kd_Prog);
        //                     $sheet->mergeCells("D$row:E$row");
        //                     $sheet->setCellValue("F$row",$v3['PrgNm']); 
        //                     $sheet->mergeCells("F$row:I$row");                
        //                     $sheet->mergeCells("K$row:M$row");          
        //                     $row_program=$row;
        //                     $totaleachprogram = 0;             
        //                     $totaleachprogram_setelah=0;
        //                     $row+=1;               
        //                     foreach ($daftar_kegiatan as $v4) 
        //                     {                                
        //                         $rkpd = \DB::table('v_rkpd')
        //                                         ->where('KgtID',$v4->KgtID)
        //                                         ->where('EntryLvl',1)
        //                                         ->where($field,$id)
        //                                         ->first();

        //                         $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);
        //                         $sheet->setCellValue("A$row",$Kd_Urusan);
        //                         $sheet->setCellValue("B$row",$Kd_Bidang);
        //                         $sheet->setCellValue("C$row",$Kd_Prog);
        //                         $sheet->setCellValue("D$row",$v4->Kd_Keg);                            
        //                         $sheet->setCellValue("F$row",$v4->KgtNm); 
        //                         $nama_indikator=$rkpd->NamaIndikator;
        //                         $sheet->setCellValue("G$row",$nama_indikator); 
        //                         $sheet->setCellValue("H$row",'Kab. Bintan'); 
        //                         $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_Angka1) . ' '.$rkpd->Sasaran_Uraian1)));                                     
        //                         $sheet->setCellValue("J$row",0); //nilai ini akan di isi oleh dibawah
        //                         $sheet->setCellValue("K$row",$rkpd->Nm_SumberDana); 
        //                         $sheet->setCellValue("L$row",$rkpd->Descr); 
        //                         $sheet->setCellValue("M$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', \Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah))); 
        //                         $sheet->setCellValue("N$row",\Helper::formatUang($rkpd->NilaiSetelah)); 
        //                         $total_nilai_setelah+=$rkpd->NilaiSetelah;  
        //                         $totaleachprogram_setelah+=$rkpd->NilaiSetelah;
                                
        //                         $rincian_kegiatan = \DB::table('v_rkpd_rinci')
        //                                             ->select(\DB::raw('
        //                                                             "Uraian",
        //                                                             "Sasaran_Angka2",
        //                                                             "Sasaran_Uraian2",
        //                                                             "Target2",
        //                                                             "NilaiUsulan2",
        //                                                             "Nm_SumberDana",
        //                                                             "Lokasi",
        //                                                             "Descr"
        //                                                         ')
        //                                             )                                                
        //                                             ->where('EntryLvl',1)
        //                                             ->where('KgtID',$v4->KgtID)
        //                                             ->where('PrgID',$v3['PrgID'])
        //                                             ->where($field,$id)
        //                                             ->orderByRaw('"No"::int ASC')
        //                                             ->get();

        //                         $row_kegiatan=$row;
        //                         $no=1;                                
        //                         $row+=1;
        //                         $totaleachkegiatan = 0;
        //                         foreach ($rincian_kegiatan as $v5)
        //                         {                     
        //                             $sheet->setCellValue("A$row",$Kd_Urusan);
        //                             $sheet->setCellValue("B$row",$Kd_Bidang);
        //                             $sheet->setCellValue("C$row",$Kd_Prog);
        //                             $sheet->setCellValue("D$row",$v4->Kd_Keg);                                 
        //                             $sheet->setCellValue("E$row",$no);                            
        //                             $sheet->setCellValue("F$row",$v5->Uraian);    
        //                             $sheet->setCellValue("G$row",$nama_indikator); 
        //                             // $sheet->setCellValue("H$row",$v5->Lokasi); 
        //                             $sheet->setCellValue("H$row",'Kab. Bintan'); 
        //                             $sasaran_angka=\Helper::formatAngka($v5->Sasaran_Angka2);
        //                             $sheet->setCellValue("I$row",trim(preg_replace('/[\t\n\r\s]+/', ' ', $sasaran_angka.' '.$v5->Sasaran_Uraian2)));                                                                        
        //                             $sheet->setCellValue("J$row",\Helper::formatUang($v5->NilaiUsulan2)); 
        //                             $sheet->setCellValue("K$row",$v5->Nm_SumberDana); 
        //                             $sheet->setCellValue("L$row",$v5->Descr); 
        //                             $sheet->setCellValue("M$row",\Helper::formatAngka($rkpd->Sasaran_AngkaSetelah).' '.$rkpd->Sasaran_UraianSetelah); 
        //                             $sheet->setCellValue("N$row",\Helper::formatUang($rkpd->NilaiSetelah)); 
        //                             $total_pagu+=$v5->NilaiUsulan2;
        //                             $totaleachkegiatan+=$v5->NilaiUsulan2;                                    
        //                             $no+=1;
        //                             $row+=1;
        //                         }                                   
        //                         $sheet->setCellValue("J$row_kegiatan",\Helper::formatUang($totaleachkegiatan)); 
        //                         $totaleachprogram+=$totaleachkegiatan;
        //                     }
        //                     $sheet->setCellValue("J$row_program",\Helper::formatUang($totaleachprogram));
        //                     $sheet->setCellValue("N$row_program",\Helper::formatUang($totaleachprogram_setelah));                                 
        //                 }
        //             }
        //         }
        //     }
        // }      
        // $sheet->getStyle("A$row:N$row")->applyFromArray($styleArrayKegiatan);  
        // $sheet->getRowDimension($row)->setRowHeight(30);
        // $sheet->mergeCells("A$row:H$row"); 
        // $sheet->setCellValue("I$row",'TOTAL'); 
        // $sheet->setCellValue("J$row",\Helper::formatUang($total_pagu));       
        // $sheet->mergeCells("K$row:M$row"); 
        // $sheet->setCellValue("N$row",\Helper::formatUang($total_nilai_setelah));    

        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
        //                        'vertical'=>Alignment::HORIZONTAL_CENTER),
        //     'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        // );        																			 
        // $sheet->getStyle("A10:N$row")->applyFromArray($styleArray);
        // $sheet->getStyle("A10:N$row")->getAlignment()->setWrapText(true);      
        
        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        // );																					 
        // $sheet->getStyle("F10:G$row")->applyFromArray($styleArray);

        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        // );																					 
        // $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);
        // $sheet->getStyle("N10:N$row")->applyFromArray($styleArray); 

        // $row+=3;
        // $sheet->setCellValue("H$row",'BANDAR SRI BENTAN, '.\Helper::tanggal('d F Y'));
        // $row+=1;        
        // $sheet->setCellValue("H$row",'KEPALA ');                                          
        // $row+=1;        
        // $sheet->setCellValue("H$row",strtoupper($this->dataReport['OrgNm']));                                          
                
        // $row+=5;
        // $sheet->setCellValue("H$row",$this->dataReport['NamaKepalaSKPD']);
        // $row+=1;                
        
        // $sheet->setCellValue("H$row",'NIP.'.$this->dataReport['NIPKepalaSKPD']);
    }   
}