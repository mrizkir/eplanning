<?php

namespace App\Models\Report;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class ReportSpoutModel 
{   
    /**
     * data report
     */
    protected $dataReport = [];
    /**
     * object writer
     */
    protected $writer;
    /**
     * custom temp folder path
     */
    protected $customTempFolderPath;

    /**
     * file generated path
     */
    protected $pathFileGenerated;

    public function __construct($dataReport)
    {
        $this->dataReport = $dataReport;              
        $this->writer = WriterEntityFactory::createXLSXWriter();  
        $this->customTempFolderPath = config('eplanning.local_path');
        $this->writer->setTempFolder($this->customTempFolderPath);        
    }
    public function download()
    {  
        $this->writer->close();        
        return response()->download($this->customTempFolderPath.DIRECTORY_SEPARATOR.$this->pathFileGenerated)->deleteFileAfterSend(true);
    }
}