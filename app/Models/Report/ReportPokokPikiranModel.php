<?php
namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Pokir\PokokPikiranModel;

class ReportPokokPikiranModel extends ReportModel
{   
    public function __construct($dataReport)
    {
        parent::__construct($dataReport); 
        switch ($dataReport['mode'])
        {
            case 'bypemilikpokokid' :
                $this->print();
            break;
            case 'byorgid' :
                $this->printByOrgID();
            break;
            
        }          
    }
    
    private function  print()  
    {
        $PemilikPokokID = $this->dataReport['PemilikPokokID'];
        
        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN POKOK PIKIRAN TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:J1');
        $sheet->setCellValue('A1','LAPORAN POKOK PIKIRAN TAHUN '.\HelperKegiatan::getTahunPerencanaan());
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:A3")->applyFromArray($styleArray);        
        
        $sheet->mergeCells ('A5:B5');
        if ($PemilikPokokID == 'all')
        {
            $sheet->setCellValue('A5','PEMILIK POKOK PIKIRAN'); 
            $sheet->setCellValue('C5',': SELURUH'); 

            $data = PokokPikiranModel::select(\DB::raw('"trPokPir"."PokPirID",
                                                        "tmPemilikPokok"."NmPk",
                                                        "trPokPir"."NamaUsulanKegiatan",
                                                        "trPokPir"."NilaiUsulan",
                                                        "tmOrg"."OrgNm",
                                                        "tmPmKecamatan"."Nm_Kecamatan",
                                                        "tmPmDesa"."Nm_Desa",
                                                        "trPokPir"."Lokasi",
                                                        "trPokPir"."Prioritas",
                                                        "trPokPir"."Privilege",
                                                        "trPokPir".created_at,
                                                        "trPokPir".updated_at
                                                    '))            
                                    ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                    ->join('tmOrg','tmOrg.OrgID','trPokPir.OrgID')
                                    ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trPokPir.PmKecamatanID')
                                    ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trPokPir.PmDesaID')
                                    ->where('trPokPir.TA',\HelperKegiatan::getTahunPerencanaan())
                                    ->orderBy('tmPemilikPokok.NmPk','ASC')
                                    ->orderBy('Prioritas','ASC')
                                    ->get();
        }        
        else
        {
            $data = PokokPikiranModel::select(\DB::raw('"trPokPir"."PokPirID",
                                                        "tmPemilikPokok"."NmPk",
                                                        "trPokPir"."NamaUsulanKegiatan",
                                                        "trPokPir"."NilaiUsulan",
                                                        "tmOrg"."OrgNm",
                                                        "tmPmKecamatan"."Nm_Kecamatan",
                                                        "tmPmDesa"."Nm_Desa",
                                                        "trPokPir"."Lokasi",
                                                        "trPokPir"."Prioritas",
                                                        "trPokPir"."Privilege",
                                                        "trPokPir".created_at,
                                                        "trPokPir".updated_at
                                                    '))            
                                    ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                    ->join('tmOrg','tmOrg.OrgID','trPokPir.OrgID')
                                    ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trPokPir.PmKecamatanID')
                                    ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trPokPir.PmDesaID')
                                    ->where('trPokPir.TA',\HelperKegiatan::getTahunPerencanaan())
                                    ->where('trPokPir.PemilikPokokID',$PemilikPokokID)
                                    ->orderBy('tmPemilikPokok.NmPk','ASC')
                                    ->orderBy('Prioritas','ASC')
                                    ->get();
            $pemilik = \App\Models\Pokir\PemilikPokokPikiranModel::find($PemilikPokokID);
            
            $sheet->setCellValue('A5','PEMILIK POKOK PIKIRAN'); 
            $sheet->setCellValue('C5',': '.$pemilik->NmPk); 
                        
        }

        $sheet->setCellValue('A7','NO'); 
        $sheet->setCellValue('B7','PEMILIK'); 
        $sheet->setCellValue('C7','NAMA KEGIATAN'); 
        $sheet->setCellValue('D7','NILAI USULAN');         
        $sheet->setCellValue('E7','DESA');
        $sheet->setCellValue('F7','KECAAMATAN');
        $sheet->setCellValue('G7','LOKASI');
        $sheet->setCellValue('H7','PRIORITAS');
        $sheet->setCellValue('I7','NAMA OPD');
        $sheet->setCellValue('J7','TANGGAL INPUT');
        
        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(45);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(50);
        $sheet->getColumnDimension('J')->setWidth(13);
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:J7")->applyFromArray($styleArray);
        $sheet->getStyle("A7:J7")->getAlignment()->setWrapText(true);
        
        $row=8;
        $total_pagu=0;        
        $no=1;
        foreach ($data as $v)
        {  
            $sheet->setCellValue("A$row",$no); 
            $sheet->setCellValue("B$row",$v['NmPk']); 
            $sheet->setCellValue("C$row",$v['NamaUsulanKegiatan']);             
            $sheet->setCellValue("D$row",\Helper::formatUang($v['NilaiUsulan'])); 
            $sheet->setCellValue("E$row",$v['Nm_Desa']); 
            $sheet->setCellValue("F$row",$v['Nm_Kecamatan']); 
            $sheet->setCellValue("G$row",$v['Lokasi']); 
            $sheet->setCellValue("H$row",$v['Prioritas']); 
            $sheet->setCellValue("I$row",$v['OrgNm']); 
            $sheet->setCellValue("J$row",\Helper::tanggal('d/m/Y',$v['created_at']));             
            $total_pagu+=$v['NilaiUsulan'];
            $row+=1;
            $no+=1;
        }        
        $sheet->setCellValue("C$row",'TOTAL'); 
        $sheet->setCellValue("D$row",\Helper::formatUang($total_pagu)); 


        $row=$row-1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A8:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("A8:J$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("B8:C$row")->applyFromArray($styleArray);
        $sheet->getStyle("E8:G$row")->applyFromArray($styleArray);
        $sheet->getStyle("I8:I$row")->applyFromArray($styleArray);

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );	
        $row=$row+1;																				 
        $sheet->getStyle("D8:D$row")->applyFromArray($styleArray);       
    }   
    private function  printByOrgID()  
    {
        $OrgID = $this->dataReport['OrgID'];
        
        $sheet = $this->spreadsheet->getActiveSheet();        
        $sheet->setTitle ('LAPORAN POKOK PIKIRAN TA '.\HelperKegiatan::getTahunPerencanaan());   
        
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => '9',
            ],
        ]);
        $sheet->mergeCells ('A1:J1');
        $sheet->setCellValue('A1','LAPORAN POKOK PIKIRAN TAHUN '.\HelperKegiatan::getTahunPerencanaan());
        
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),								
        );                
        $sheet->getStyle("A1:A3")->applyFromArray($styleArray);     
    
        $sheet->setCellValue('A4','NAMA OPD / SKPD : '.$this->dataReport['OrgNm']. ' ['.$this->dataReport['kode_organisasi'].']');  

        $data = PokokPikiranModel::select(\DB::raw('"trPokPir"."PokPirID",
                                                    "tmPemilikPokok"."NmPk",
                                                    "trPokPir"."NamaUsulanKegiatan",
                                                    "trPokPir"."NilaiUsulan",
                                                    "tmOrg"."OrgNm",
                                                    "tmPmKecamatan"."Nm_Kecamatan",
                                                    "tmPmDesa"."Nm_Desa",
                                                    "trPokPir"."Lokasi",
                                                    "trPokPir"."Prioritas",
                                                    "trPokPir"."Privilege",
                                                    "trPokPir".created_at,
                                                    "trPokPir".updated_at
                                                '))            
                                ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                ->join('tmOrg','tmOrg.OrgID','trPokPir.OrgID')
                                ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trPokPir.PmKecamatanID')
                                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trPokPir.PmDesaID')
                                ->where('trPokPir.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->where('trPokPir.OrgID',$OrgID)
                                ->orderBy('tmPemilikPokok.NmPk','ASC')
                                ->orderBy('Prioritas','ASC')
                                ->get();


        $sheet->setCellValue('A7','NO'); 
        $sheet->setCellValue('B7','PEMILIK'); 
        $sheet->setCellValue('C7','NAMA KEGIATAN'); 
        $sheet->setCellValue('D7','NILAI USULAN');         
        $sheet->setCellValue('E7','DESA');
        $sheet->setCellValue('F7','KECAAMATAN');
        $sheet->setCellValue('G7','LOKASI');
        $sheet->setCellValue('H7','PRIORITAS');
        $sheet->setCellValue('I7','NAMA OPD');
        $sheet->setCellValue('J7','TANGGAL INPUT');
        
        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(45);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(50);
        $sheet->getColumnDimension('J')->setWidth(13);
        $styleArray=array( 
            'font' => array('bold' => true,'size'=>'9'),
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );                
        $sheet->getStyle("A7:J7")->applyFromArray($styleArray);
        $sheet->getStyle("A7:J7")->getAlignment()->setWrapText(true);
        
        $row=8;
        $total_pagu=0;        
        $no=1;
        foreach ($data as $v)
        {  
            $sheet->setCellValue("A$row",$no); 
            $sheet->setCellValue("B$row",$v['NmPk']); 
            $sheet->setCellValue("C$row",$v['NamaUsulanKegiatan']);             
            $sheet->setCellValue("D$row",\Helper::formatUang($v['NilaiUsulan'])); 
            $sheet->setCellValue("E$row",$v['Nm_Desa']); 
            $sheet->setCellValue("F$row",$v['Nm_Kecamatan']); 
            $sheet->setCellValue("G$row",$v['Lokasi']); 
            $sheet->setCellValue("H$row",$v['Prioritas']); 
            $sheet->setCellValue("I$row",$v['OrgNm']); 
            $sheet->setCellValue("J$row",\Helper::tanggal('d/m/Y',$v['created_at']));             
            $total_pagu+=$v['NilaiUsulan'];
            $row+=1;
            $no+=1;
        }        
        $sheet->setCellValue("C$row",'TOTAL'); 
        $sheet->setCellValue("D$row",\Helper::formatUang($total_pagu)); 


        $row=$row-1;
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_CENTER,
                               'vertical'=>Alignment::HORIZONTAL_CENTER),
            'borders' => array('allBorders' => array('borderStyle' =>Border::BORDER_THIN))
        );        																			 
        $sheet->getStyle("A8:J$row")->applyFromArray($styleArray);
        $sheet->getStyle("A8:J$row")->getAlignment()->setWrapText(true);      
        
        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_LEFT)
        );																					 
        $sheet->getStyle("B8:C$row")->applyFromArray($styleArray);
        $sheet->getStyle("E8:G$row")->applyFromArray($styleArray);
        $sheet->getStyle("I8:I$row")->applyFromArray($styleArray);

        $styleArray=array(								
            'alignment' => array('horizontal'=>Alignment::HORIZONTAL_RIGHT)
        );	
        $row=$row+1;																				 
        $sheet->getStyle("D8:D$row")->applyFromArray($styleArray);       
    }   
}