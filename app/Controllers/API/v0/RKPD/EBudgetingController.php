<?php

namespace App\Controllers\API\v0\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\TAModel;

class EBudgetingController extends Controller {
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
    
        $data = 'test';
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
        $data ='test';

        return response()->json($data,200); 
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ta = TAModel::create ([
            'TAID'=> uniqid ('uid'),
            'TACd'=>2040,
            'TANm'=>$request->input('transid'),
            'Descr'=>$request->input('Kd_UrusanUnit')
        ]);
    }
}