<?php

namespace App\Controllers\API\v0\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\KelompokUrusanModel;

class KelompokUrusanController extends Controller {
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
        $data=KelompokUrusanModel::select (\DB::raw('"KUrsID","RpjmdVisiID","Kd_Urusan","Nm_Urusan","Descr",'.$tahun_perencanaan.' AS "TA" ,"KUrsID_Src","Locked","created_at","updated_at"'))
                                    ->where('TA',$ta)
                                    ->orderBy('Kd_Urusan','ASC')
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
        $data = KelompokUrusanModel::where('KUrsID',$id)
                                    ->first();        
        return response()->json($data,200); 
    }   
}