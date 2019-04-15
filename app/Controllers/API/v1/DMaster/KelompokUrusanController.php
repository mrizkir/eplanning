<?php

namespace App\Controllers\API\DMaster;

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
        $this->middleware(['auth:api']);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {               
        $columns=['KUrsID','Kd_Urusan','Nm_Urusan','TA'];   
        $currentpage=1;
        if ($request->exists('page'))
        {
            $currentpage = $request->input('page');
        }
        $numberRecordPerPage=10;
        if ($request->exists('numberrecordperpage'))
        {
            $numberRecordPerPage = $request->input('numberrecordperpage');
        }
        $ta=config('globalsettings.tahun_perencanaan');
        if ($request->exists('ta'))
        {
            $ta = $request->input('ta');
        }
        if ($currentpage == 'all')
        {
            $data=KelompokUrusanModel::where('TA',$ta)
                                        ->orderBy('Kd_Urusan','ASC')
                                        ->get();
            $daftar_urusan = []; 
            foreach ($data as $v)
            {
                $daftar_urusan[]=['KUrsID'=>$v->KUrsID,
                                'Kd_Urusan'=>$v->Kd_Urusan,
                                'Nm_Urusan'=>$v->Nm_Urusan,                                
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$daftar_urusan],200); 
        }
        else
        {
            $data=KelompokUrusanModel::where('TA',$ta)
                                        ->orderBy('Kd_Urusan','ASC')
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
            $daftar_urusan = []; 
            foreach ($data as $v)
            {
                $daftar_urusan[]=['KUrsID'=>$v->KUrsID,
                                'Kd_Urusan'=>$v->Kd_Urusan,
                                'Nm_Urusan'=>$v->Nm_Urusan,                                
                                'TA'=>$v->TA
                                ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$daftar_urusan],200); 
        }
        
       
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
        $daftar_urusan=[];
        if (!is_null($data) )  
        {
            $daftar_urusan=['KUrsID'=>$data->KUrsID,
                            'Kd_Urusan'=>$data->Kd_Urusan,
                            'Nm_Urusan'=>$data->Nm_Urusan,                            
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$daftar_urusan],200); 
    }   
}