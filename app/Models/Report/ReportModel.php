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
        $pathToFile = config('eplanning.local_path').DIRECTORY_SEPARATOR.$filename;

        $this->spreadsheet->getProperties()->setCreator(config('eplanning.nama_institusi'));
        $this->spreadsheet->getProperties()->setLastModifiedBy(config('eplanning.nama_institusi'));
        $this->spreadsheet->getProperties()->setTitle("Laporan RKPD Tahun ".config('eplanning.tahun_perencanaan'));
        $this->spreadsheet->getProperties()->setSubject("Laporan RKPD Tahun ".config('eplanning.tahun_perencanaan'));        

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($pathToFile);        

        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}