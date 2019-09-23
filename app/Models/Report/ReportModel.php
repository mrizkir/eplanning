<?php
namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportModel extends Model
{   
    /**
     * data report
     */
    protected $dataReport = [];
    /**
     * object spreadsheet
     */
    protected $spreadsheet;

    public function __construct($dataReport)
    {
        $this->dataReport = $dataReport;
        $this->spreadsheet = new Spreadsheet();         
    }
    /**
     * digunakan untuk menghasilkan struktur urusan program
     */
    public function generateStructure($field,$id,$entrylvl)
    {
        $urusan_program = \DB::select('   
                                SELECT 
                                    t."PrgID",v."Kd_Urusan",v."Kd_Urusan",v."Nm_Urusan",v."Kd_Bidang",v."Nm_Bidang",v."Kd_Prog",v."PrgNm"
                                FROM 
                                    v_urusan_program v,
                                    (
                                        SELECT 
                                            B."PrgID"
                                        FROM 
                                            "trRKPD" A
                                        JOIN "tmKgt" B ON A."KgtID"=B."KgtID" 
                                        WHERE
                                            A."'.$field.'"=\''.$id.'\'
                                            AND A."EntryLvl"='.$entrylvl.'
                                        GROUP BY B."PrgID"
                                    ) t
                                WHERE 
                                v."PrgID"=t."PrgID"
                        ');                            
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
                        $str = "";
                        $program[]=['Kd_Prog'=>$p_value->Kd_Prog,
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
                                $program[]=['Kd_Prog'=>$p_value->Kd_Prog,
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
        return $data;
    }
    /**
     * digunakan untuk mengeset data report dan inisialisasi object spreadsheet
     */
    public function setObjReport($dataReport)
    {   
        $this->dataReport = $dataReport;
        $this->spreadsheet = new Spreadsheet();         
    }
    public function download(string $filename)
    {
        $pathToFile = config('eplanning.local_path').DIRECTORY_SEPARATOR.$filename;
        $this->spreadsheet->getProperties()->setCreator(config('eplanning.nama_institusi'));
        $this->spreadsheet->getProperties()->setLastModifiedBy(config('eplanning.nama_institusi'));         
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($pathToFile);        
        return response()->download($pathToFile)->deleteFileAfterSend(true);
    }
}