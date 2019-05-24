<?php

namespace App\Controllers\API\v1\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\SubOrganisasiModel;

class SubOrganisasiController extends Controller {
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
        $columns=['*'];        
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
        $ta=config('eplanning.tahun_perencanaan');
        if ($request->exists('ta'))
        {
            $ta = $request->input('ta');
        }
        if ($currentpage == 'all')
        {
            $data = \DB::table('v_suborganisasi')
                        ->where('TA',$ta)
                        ->orderBy('kode_suborganisasi','ASC')
                        ->get();
            $data_unitkerja = []; 
            foreach ($data as $v)
            {
                $data_unitkerja[]=['SOrgID'=>$v->SOrgID,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'Nm_Bidang'=>$v->Nm_Bidang, 
                                    'OrgCd'=>$v->OrgCd, 
                                    'OrgNm'=>$v->OrgNm, 
                                    'SOrgCd'=>$v->SOrgCd,                                
                                    'kode_suborganisasi'=>$v->kode_suborganisasi,    
                                    'SOrgNm'=>$v->SOrgNm,                                
                                    'TA'=>$v->TA
                                ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$data_unitkerja],200); 
        }
        else
        {
            $data = \DB::table('v_suborganisasi')
                        ->where('TA',$ta)
                        ->orderBy('kode_suborganisasi','ASC')
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            $data_unitkerja = []; 
            foreach ($data as $v)
            {
                $data_unitkerja[]=['SOrgID'=>$v->SOrgID,
                                'Kd_Urusan'=>$v->Kd_Urusan,
                                'Nm_Urusan'=>$v->Nm_Urusan,
                                'Kd_Bidang'=>$v->Kd_Bidang, 
                                'Nm_Bidang'=>$v->Nm_Bidang, 
                                'OrgCd'=>$v->OrgCd, 
                                'OrgNm'=>$v->OrgNm, 
                                'SOrgCd'=>$v->SOrgCd,                                
                                'kode_suborganisasi'=>$v->kode_suborganisasi,    
                                'SOrgNm'=>$v->SOrgNm,                                
                                'TA'=>$v->TA
                                ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$data_unitkerja],200); 
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
        $data = \DB::table('v_suborganisasi')
                            ->where('SOrgID',$id)
                            ->first();
        $data_unitkerja=[];
        if (!is_null($data) )  
        {
            $data_unitkerja=['SOrgID'=>$data->SOrgID,
                            'Kd_Urusan'=>$data->Kd_Urusan,
                            'Nm_Urusan'=>$data->Nm_Urusan,
                            'Kd_Bidang'=>$data->Kd_Bidang, 
                            'Nm_Bidang'=>$data->Nm_Bidang, 
                            'OrgCd'=>$data->OrgCd, 
                            'OrgNm'=>$data->OrgNm, 
                            'SOrgCd'=>$data->SOrgCd,                                
                            'kode_suborganisasi'=>$data->kode_suborganisasi,    
                            'SOrgNm'=>$data->SOrgNm,                                
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$data_unitkerja],200); 
    }   
}