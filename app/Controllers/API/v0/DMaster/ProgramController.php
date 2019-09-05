<?php

namespace App\Controllers\API\v0\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\ProgramModel;

class ProgramController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $tahun_perencanaan = \HelperKegiatan::getTahunPerencanaan (true);
        $ta=\HelperKegiatan::getRPJMDTahunMulai(true);
        
        $data = \DB::table('v_urusan_program')                    
                    ->select (\DB::raw('
                        "PrgID",
                        "UrsID",
                        "KUrsID",
                        "Kd_Urusan",
                        "Kd_Bidang",			 
                        "Kd_Prog",
                        kode_program,
                        "Nm_Urusan",
                        "Nm_Bidang",
                        "PrgNm",
                        "Jns",
                        '.$tahun_perencanaan.' AS "TA" ,
                        "Locked",
                        "created_at",
                        "updated_at"
                    '))
                    ->where('TA',$ta)
                    ->get();

        return response()->json($data,200); 
       
    }     
       
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data =  \DB::table('v_urusan_program')                                        
                    ->where('TA',$ta)
                    ->where('a."PrgID"',$id)
                    ->first();
            
        return response()->json($data,200); 
    }   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function byurusan ($id)
    {
        $data=ProgramModel::getDaftarProgram(\HelperKegiatan::getRPJMDTahunMulai(true),false,$id);
        return response()->json($data,200); 
    }
}