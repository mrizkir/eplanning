<?php

namespace App\Controllers\API\v0\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class Plafon5Controller extends Controller {
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
       
        $ta=\HelperKegiatan::getTahunPerencanaan(true);
    
        $data = \DB::table('v_rkpd')   
                    ->select (\DB::raw('
                        "RKPDID" AS transid,
                        "PrgID",
                        "Kd_UrusanUnit",
                        "Kd_BidangUnit",
                        "OrgCd",
                        "OrgNm",
                        "SOrgCd",
                        "SOrgNm",
                        "Kd_Urusan" AS "Kd_UrusanPrg",
                        "Kd_Bidang" AS "Kd_BidangPrg",
                        "Kd_Prog",
                        "PrgNm",
                        "Kd_Keg",
                        "KgtNm",
                        "NilaiUsulan2" AS "Jumlah1",
                        "Descr",
                        "TA",                        
                        \'SETUJU\' AS "status",
                        "EntryLvl"
                    '))
                    ->where('TA',$ta)
                    ->where('EntryLvl',1)
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
        $data =\DB::table('v_rkpd')                    
                    ->where('RKPDID',$id)
                    ->first();

        return response()->json($data,200); 
    }   
}