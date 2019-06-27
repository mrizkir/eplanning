<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\RKPD\RKPDViewJudulModel;

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
        $SOrgID = $this->dataReport['SOrgID'];

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
        
        if ($SOrgID != 'none'&&$SOrgID != ''&&$SOrgID != null)
        {
            $sheet->setCellValue('A5','NAMA UNIT KERJA'); 
            $sheet->setCellValue('B5',': '.$this->dataReport['SOrgNm']. ' ['.$this->dataReport['kode_suborganisasi'].']'); 
        }        
        else
        {
            $sheet->setCellValue('A5','NAMA OPD / SKPD'); 
            $sheet->setCellValue('B5',': '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']'); 
        }

        $sheet->mergeCells ('A7:A8');
        $sheet->setCellValue('A7','KODE'); 
        $sheet->mergeCells ('B7:B8');
        $sheet->setCellValue('B7','URUSAN/BIDANG URUSAN PEMERINTAH DAERAH DAN PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('C7:C8');
        $sheet->setCellValue('C7','INDIKATOR KINERJA PROGRAM/KEGIATAN'); 
        $sheet->mergeCells ('D7:G7');
        $sheet->setCellValue('D7','RENCANA TAHUN '.\HelperKegiatan::getTahunPerencanaan());         
        $sheet->mergeCells ('H7:H8');
        $sheet->setCellValue('H7','CATATAN PENTING');
        $sheet->mergeCells ('I7:J7');
        $sheet->setCellValue('I7','PERKIRAAN MAJU RENCANA TAHUN '.$n1);
        
        $sheet->setCellValue('D8','LOKASI'); 
        $sheet->setCellValue('E8','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('F8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        $sheet->setCellValue('G8','SUMBER DANA');         
        $sheet->setCellValue('I8','TARGET CAPAIAN KINERJA'); 
        $sheet->setCellValue('J8','KEBUTUHAN DANA/PAGU INDIKATIF'); 
        
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
        $sheet->getColumnDimension('A')->setWidth(19);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(17);
        $sheet->getColumnDimension('G')->setWidth(9);
        $sheet->getColumnDimension('H')->setWidth(17);
        $sheet->getColumnDimension('I')->setWidth(20);
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
                            ->select(\DB::raw('"PrgID","kode_program","Kd_Prog","PrgNm","Jns"'))
                            ->where('OrgID',$OrgID)
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderByRaw('kode_program ASC NULLS FIRST')
                            ->orderBy('Kd_Prog','ASC')
                            ->get();
        
        $row=11;
        $total_pagu=0;
        $total_nilai_setelah=0;

        foreach ($daftar_program as $v)
        {
            $PrgID=$v->PrgID;           
            $daftar_kegiatan = RKPDViewJudulModel::select(\DB::raw('"kode_kegiatan","KgtNm","NamaIndikator","Sasaran_Angka1","Sasaran_Uraian1","Target1","NilaiUsulan1","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
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
                $sheet->getStyle("A$row:J$row")->getFont()->setBold(true);                                
                $sheet->setCellValue("A$row",$kode_program);
                $sheet->setCellValue("B$row",$PrgNm);
                $totalpagueachprogram= $daftar_kegiatan->sum('NilaiUsulan1');      
                $totalnilaisetelah= $daftar_kegiatan->sum('NilaiSetelah');  
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
                    $sheet->setCellValue("H$row",$n['Descr']); 
                    $sheet->setCellValue("I$row",\Helper::formatAngka($n['Sasaran_AngkaSetelah']).' '.$n['Sasaran_UraianSetelah']); 
                    $sheet->setCellValue("J$row",\Helper::formatUang($n['NilaiSetelah'])); 
                    $total_pagu+=$n['NilaiUsulan1'];
                    $total_nilai_setelah+=$n['NilaiSetelah'];
                    $row+=1;
                }
            }
        }        
        $sheet->setCellValue("E$row",'TOTAL'); 
        $sheet->setCellValue("F$row",\Helper::formatUang($total_pagu)); 
        $sheet->setCellValue("J$row",\Helper::formatUang($total_nilai_setelah)); 
        

        if ($SOrgID != 'none'&&$SOrgID != ''&&$SOrgID != null)
        {
            $sheet->setCellValue("A10",$this->dataReport['kode_suborganisasi']); 
            $sheet->setCellValue("B10",$this->dataReport['SOrgNm']);         
            $sheet->setCellValue("F10",\Helper::formatUang($total_pagu));  
            $sheet->setCellValue("J10",\Helper::formatUang($total_nilai_setelah));                     
        }        
        else
        {
            $sheet->setCellValue("A10",$this->dataReport['kode_organisasi']); 
            $sheet->setCellValue("B10",$this->dataReport['OrgNm']);         
            $sheet->setCellValue("F10",\Helper::formatUang($total_pagu));  
            $sheet->setCellValue("J10",\Helper::formatUang($total_nilai_setelah));   
        }

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
        $sheet->getStyle("A10:C$row")->applyFromArray($styleArray);

        $row=$row+1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );																					 
        $sheet->getStyle("F10:F$row")->applyFromArray($styleArray);
        $sheet->getStyle("J10:J$row")->applyFromArray($styleArray);

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