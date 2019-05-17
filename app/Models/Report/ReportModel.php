<?php

namespace App\Models\Report;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportModel 
{   
    /**
     * data report
     */
    protected $dataReport = array();
    /**
     * object spreadsheet
     */
    protected $spreadsheet;

    public function __construct($dataReport)
    {
        $this->dataReport = $dataReport;
        $this->spreadsheet = new Spreadsheet();         
    }
    public function download(string $filename)
    {
        $pathToFile = config('globalsettings.local_path').DIRECTORY_SEPARATOR.$filename;

        $this->spreadsheet->getProperties()->setCreator(config('globalsettings.nama_institusi'));
        $this->spreadsheet->getProperties()->setLastModifiedBy(config('globalsettings.nama_institusi'));
        $this->spreadsheet->getProperties()->setTitle("Laporan RKPD Tahun ".config('globalsettings.tahun_perencanaan'));
        $this->spreadsheet->getProperties()->setSubject("Laporan RKPD Tahun ".config('globalsettings.tahun_perencanaan'));        

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($pathToFile);        

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}