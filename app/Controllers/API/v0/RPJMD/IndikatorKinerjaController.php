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
        $ta=\HelperKegiatan::getRPJMDTahunMulai(true);    
        $data = \DB::table('v_indikator_kinerja2')                     
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