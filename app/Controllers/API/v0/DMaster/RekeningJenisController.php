<?php

namespace App\Controllers\API\v0\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningJenisModel;

class RekeningJenisController extends Controller {
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
       
        $data=RekeningJenisModel::join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                ->where('tmJns.TA',$ta)
                                ->orderBy('Kd_Rek_2','ASC')
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
        $data = RekeningJenisModel::join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                    ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                    ->where('tmJns.JnsID',$id)
                                    ->first();
      
        return response()->json($data,200);  
    }   
}