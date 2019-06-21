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
        
        $sheet->setCellValue('A5','KELOMPOK URUSAN'); 
        $sheet->setCellValue('B5',': '.$this->dataReport['Nm_Urusan']. ' ['.$this->dataReport['Kd_Urusan'].']'); 
        $sheet->setCellValue('A6','URUSAN'); 
        $sheet->setCellValue('B6',': '.$this->dataReport['Nm_Bidang']. ' ['.$this->dataReport['Kd_Urusan'].'.'.$this->dataReport['Kd_Bidang'].']'); 
        $sheet->setCellValue('A7','NAMA OPD / SKPD'); 
        $sheet->setCellValue('B7',': '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']'); 
                
        $sheet->mergeCells ('A9:A10');
        $sheet->setCellValue('A9','KODE'); 
        $sheet->mergeCells ('B9:B10');
        $sheet->setCellValue('B9','URUSAN/BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('C9:C10');
        $sheet->setCellValue('C9','INDIKATOR KINERJA PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('D9:G9');
        $sheet->setCellValue('D9','RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan());         
        $sheet->mergeCells ('H9:H10');
        $sheet->setCellValue('H9','CATATAN PENTING');
        $sheet->mergeCells ('I9:J9');
        $sheet->setCellValue('I9','PERKIRAAN MAJU RENCANA TAHUN '.$n1);
        
        $sheet->setCellValue('D10','LOKASI'); 
        $sheet->setCellValue('E10','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('F10','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('G10','SUMBER DANA');         
        $sheet->setCellValue('I10','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J10','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        
        $sheet->setCellValue('A11',1); 
        $sheet->setCellValue('B11',2); 
        $sheet->setCellValue('C11',3); 
        $sheet->setCellValue('D11',4); 
        $sheet->setCellValue('E11',5); 
        $sheet->setCellValue('F11',6); 
        $sheet->setCellValue('G11',7); 
        $sheet->setCellValue('H11',11); 
        $sheet->setCellValue('I11',9); 
        $sheet->setCellValue('J11',10); 
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
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A9:J11")->applyFromArray($styleArray);
        $sheet->getStyle("A9:J11")->getAlignment()->setWrapText(true);

        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","kode_program","PrgNm"'))
                            ->where('OrgID',$OrgID)
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderBy('kode_program','ASC')
                            ->get()->toArray();
        
        
        $row=12;
        $total_pagu=0;
        $total_nilai_setelah=0;
        foreach ($daftar_program as $v)
        {
            $PrgID=$v->PrgID;

            $daftar_kegiatan = RKPDMurniModel::select(\DB::raw('"kode_kegiatan","KgtNm","NamaIndikator","Sasaran_Angka1","Sasaran_Uraian1","Target1","NilaiUsulan1","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
                                            ->where('PrgID',$PrgID)      
                                            ->where('OrgID',$OrgID)
                                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                            ->orderBy('kode_kegiatan','ASC')       
                                            ->get();
            
            if (isset($daftar_kegiatan[0])) 
            {
                $totalpagueachprogram= $daftar_kegiatan->sum('NilaiUsulan1');      
                $totalnilaisetelah= $daftar_kegiatan->sum('NilaiSetelah');  
                $sheet->getStyle("A$row:J$row")->getFont()->setBold(true);                
                $sheet->setCellValue("A$row",$v->kode_program);
                $sheet->setCellValue("B$row",$v->PrgNm);
                $sheet->setCellValue("F$row",\Helper::formatUang($totalpagueachprogram)); 
                $sheet->setCellValue("J$row",\Helper::formatUang($totalnilaisetelah)); 
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
                    $total_pagu+=$n['NilaiUsulan1'];
                    $total_nilai_setelah+=$n['NilaiSetelah'];
                }
            }
        }        
        $sheet->setCellValue("E$row",'TOTAL'); 
        $sheet->setCellValue("F$row",\Helper::formatUang($total_pagu)); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_nilai_setelah)); 
        
        // $row=$row-1;
        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
        //                        'vertical'=>Alignment::HORIZONTAL_CENTER),
        //     'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        // );        																			 
        // $sheet->getStyle("A13:J$row")->applyFromArray($styleArray);
        // $sheet->getStyle("A13:J$row")->getAlignment()->setWrapText(true);      
        
        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        // );																					 
        // $sheet->getStyle("A13:C$row")->applyFromArray($styleArray);

        // $row=$row+1;
        // $styleArray=array(								
        //     'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        // );																					 
        // $sheet->getStyle("F13:C$row")->applyFromArray($styleArray);
        // $sheet->getStyle("J13:C$row")->applyFromArray($styleArray);

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