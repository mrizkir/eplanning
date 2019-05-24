<?php

namespace App\Controllers\API\v0\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningStrukturModel;

class RekeningStrukturController extends Controller {
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
        $data = RekeningStrukturModel::where('TA',$ta)->get();
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
        $data = RekeningStrukturModel::where('StrID',$id)
                                    ->first();
                                    
        return response()->json($data,200); 
    }   
}