<?php

namespace App\Controllers\API\v1\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\OrganisasiModel;

class OrganisasiController extends Controller {
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
        $ta=\HelperKegiatan::getTahunPerencanaan();
        if ($request->exists('ta'))
        {
            $ta = $request->input('ta');
        }
        if ($currentpage == 'all')
        {
            $data = \DB::table('v_urusan_organisasi')
                        ->where('TA',$ta)
                        ->orderBy('kode_organisasi','ASC')
                        ->get();
            $data_opd = []; 
            foreach ($data as $v)
            {
                $data_opd[]=['OrgID'=>$v->OrgID,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'Nm_Bidang'=>$v->Nm_Bidang, 
                                    'OrgCd'=>$v->OrgCd,                                
                                    'kode_organisasi'=>$v->kode_organisasi,    
                                    'OrgNm'=>$v->OrgNm,                                
                                    'TA'=>$v->TA
                                ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$data_opd],200); 
        }
        else
        {
            $data = \DB::table('v_urusan_organisasi')
                        ->where('TA',$ta)
                        ->orderBy('kode_organisasi','ASC')
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            $data_opd = []; 
            foreach ($data as $v)
            {
                $data_opd[]=['OrgID'=>$v->OrgID,
                                'Kd_Urusan'=>$v->Kd_Urusan,
                                'Nm_Urusan'=>$v->Nm_Urusan,
                                'Kd_Bidang'=>$v->Kd_Bidang, 
                                'Nm_Bidang'=>$v->Nm_Bidang, 
                                'OrgCd'=>$v->OrgCd,                                
                                'kode_organisasi'=>$v->kode_organisasi,    
                                'OrgNm'=>$v->OrgNm,                                
                                'TA'=>$v->TA
                                ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$data_opd],200); 
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
        $data = \DB::table('v_urusan_organisasi')
                            ->where('OrgID',$id)
                            ->first();
        $data_opd=[];
        if (!is_null($data) )  
        {
            $data_opd=['OrgID'=>$data->OrgID,
                            'Kd_Urusan'=>$data->Kd_Urusan,
                            'Nm_Urusan'=>$data->Nm_Urusan,
                            'Kd_Bidang'=>$data->Kd_Bidang, 
                            'Nm_Bidang'=>$data->Nm_Bidang, 
                            'OrgCd'=>$data->OrgCd,                                
                            'kode_organisasi'=>$data->kode_organisasi,    
                            'OrgNm'=>$data->OrgNm,                                
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$data_opd],200); 
    }   
}