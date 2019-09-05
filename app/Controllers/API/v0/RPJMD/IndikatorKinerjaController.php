<?php

namespace App\Controllers\API\v0\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class IndikatorKinerjaController extends Controller {
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
        $data = \DB::table('v_indikator_kinerja2')                     
                    ->select (\DB::raw('
                        "IndikatorKinerjaID",
                        "UrsID",
                        "PrgID",
                        "OrgIDRPJMD",			 
                        "kode_organisasi",
                        "OrgNm",
                        "OrgAlias",
                        "Nm_Urusan",
                        "Nm_Bidang",
                        "kode_program",
                        "PrgNm",
                        "Jns",
                        "NamaIndikator",
                        "Satuan",
                        "KondisiAwal",
                        "TargetN1",
                        "TargetN2",
                        "TargetN3",
                        "TargetN4",
                        "TargetN5",
                        "PaguDanaN1",
                        "PaguDanaN2",
                        "PaguDanaN3",
                        "PaguDanaN4",
                        "PaguDanaN5",
                        "KondisiAkhirTarget",
                        "KondisiAkhirPaguDana",
                        "Descr", 
                        '.$tahun_perencanaan.' AS "TA",
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
        $data =\DB::table('v_indikator_kinerja')                                        
                    ->where('IndikatorKinerjaID',$id)
                    ->first();
        return response()->json($data,200); 
    }   
}