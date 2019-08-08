<?php

namespace App\Controllers\API\v0\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class Plafon6Controller extends Controller {
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
       
        $ta=config('eplanning.tahun_perencanaan');
    
        $data = \DB::table('v_rkpd')   
                    ->where('TA',2019)
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