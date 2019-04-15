<?php

namespace App\Controllers\API\v0\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\UrusanModel;

class UrusanController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth:api']);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $ta=config('globalsettings.tahun_perencanaan');
        
        $data = \DB::table('v_urusan')
                    ->where('TA',$ta)
                    ->orderBy('Kode_Bidang','ASC')
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
        $data = \DB::table('v_urusan')
                            ->where('UrsID',$id)
                            ->first();
        $daftar_urusan=[];
        if (!is_null($data) )  
        {
            $daftar_urusan=['UrsID'=>$data->UrsID,
                            'Kd_Urusan'=>$data->Kd_Urusan,
                            'Nm_Urusan'=>$data->Nm_Urusan,
                            'Kd_Bidang'=>$data->Kd_Bidang, 
                            'Kode_Bidang'=>$data->Kode_Bidang,    
                            'Nm_Bidang'=>$data->Nm_Bidang,                         
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$daftar_urusan],200); 
    }   
}