<?php

namespace App\Models\Report;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;

use App\Models\RKPD\RKPDViewJudulModel;

class ReportRKPDMurniSpoutModel extends ReportModel
{   
    public function __construct($dataReport,$filename)
    {
        parent::__construct($dataReport); 
        $this->print($filename);
        $this->pathFileGenerated = $filename;             
    }
    
    private function print($filename)  
    {
        $writer=$this->writer;
        //default style
        $writer->setDefaultRowStyle((new StyleBuilder())
                                    ->setFontSize(8)
                                    ->build());

        $writer->openToFile($this->customTempFolderPath.DIRECTORY_SEPARATOR.$filename);

        $SOrgID = $this->dataReport['SOrgID'];
        $OrgID = $this->dataReport['OrgID'];
        
        $daftar_program=\DB::table('v_organisasi_program')
                            ->select(\DB::raw('"PrgID","kode_program","PrgNm"'))
                            ->where('OrgID',$OrgID)
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->orderBy('kode_program','ASC')
                            ->get()->toArray();

        foreach ($daftar_program as $v)
        {
            $PrgID=$v->PrgID;

            $daftar_kegiatan = RKPDViewJudulModel::select(\DB::raw('"kode_kegiatan","KgtNm","NamaIndikator","Sasaran_Angka1","Sasaran_Uraian1","Target1","NilaiUsulan1","Sasaran_AngkaSetelah","Sasaran_UraianSetelah","NilaiSetelah","Nm_SumberDana","Descr"'))
                                            ->where('PrgID',$PrgID)      
                                            ->where('SOrgID',$SOrgID)
                                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                            ->orderBy('kode_kegiatan','ASC')       
                                            ->get();
            $totalpagueachprogram= $daftar_kegiatan->sum('NilaiUsulan1');      
            $cells = [
                WriterEntityFactory::createCell($v->kode_program),
                WriterEntityFactory::createCell($v->PrgNm),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(\Helper::formatAngka($totalpagueachprogram)),
            ];
        
            // /** add a row at a time */
            /** Create a style with the StyleBuilder */
            $style_program = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(10)
                    ->setFontColor(Color::BLUE)
                    ->setShouldWrapText()
                    ->setBackgroundColor(Color::YELLOW)
                    ->build();
            $row = WriterEntityFactory::createRow($cells);
            $row->setStyle($style_program);
            $writer->addRow($row);

            $multipleRows=[];
            foreach ($daftar_kegiatan as $n) 
            {
                $cells=[
                    WriterEntityFactory::createCell($n['kode_kegiatan']),
                    WriterEntityFactory::createCell($n['KgtNm']),
                    WriterEntityFactory::createCell($n['NamaIndikator']),
                    WriterEntityFactory::createCell('Kab. Bintan'),
                    WriterEntityFactory::createCell(\Helper::formatAngka($n['Sasaran_Angka1']) . ' '.$n['Sasaran_Uraian1']),
                    WriterEntityFactory::createCell(\Helper::formatAngka($n['NilaiUsulan1']))
                ];
                $multipleRows[] = WriterEntityFactory::createRow($cells);
            }
            $this->writer->addRows($multipleRows);
        }       
    }   
}