<?php

namespace App\Controllers\API\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class Plafon3Controller extends Controller {
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
        $ta=config('globalsettings.tahun_perencanaan');
        if ($request->exists('ta'))
        {
            $ta = $request->input('ta');
        }
        if ($currentpage == 'all')
        {
            $data = \DB::table('v_plafon3')   
                        ->where('TA',$ta)
                        ->get();
            $data_plafon = []; 
            foreach ($data as $v)
            {
                $data_plafon[]=['transid'=>$v->transid,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'OrgCd'=>$v->OrgCd, 
                                    'OrgNm'=>$v->OrgNm,
                                    'SOrgCd'=>$v->SOrgCd,                                    
                                    'SOrgNm'=>$v->SOrgNm,
                                    'Kd_UrusanPrg'=>$v->Kd_UrusanPrg,
                                    'Kd_BidangPrg'=>$v->Kd_BidangPrg,
                                    'Kd_Prog'=>$v->Kd_Prog,
                                    'PrgNm'=>$v->PrgNm,
                                    'Kd_Keg'=>$v->Kd_Keg,
                                    'KgtNm'=>$v->KgtNm,
                                    'Jumlah3'=>$v->Jumlah3,
                                    'Descr'=>$v->Descr,
                                    'Status'=>$v->Status,
                                    'TA'=>$v->TA  
                                ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$data_plafon],200); 
        }
        else
        {
            $data = \DB::table('v_plafon3')   
                        ->where('TA',$ta)
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            $data_plafon = []; 
            foreach ($data as $v)
            {
                $data_plafon[]=['transid'=>$v->transid,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'OrgCd'=>$v->OrgCd, 
                                    'OrgNm'=>$v->OrgNm,
                                    'SOrgCd'=>$v->SOrgCd,                                    
                                    'SOrgNm'=>$v->SOrgNm,
                                    'Kd_UrusanPrg'=>$v->Kd_UrusanPrg,
                                    'Kd_BidangPrg'=>$v->Kd_BidangPrg,
                                    'Kd_Prog'=>$v->Kd_Prog,
                                    'PrgNm'=>$v->PrgNm,
                                    'Kd_Keg'=>$v->Kd_Keg,
                                    'KgtNm'=>$v->KgtNm,
                                    'Jumlah3'=>$v->Jumlah3,
                                    'Descr'=>$v->Descr,
                                    'Status'=>$v->Status,
                                    'TA'=>$v->TA 
                                ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$data_plafon],200); 
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
        $data =\DB::table('v_plafon3')                    
                    ->where('transid',$id)
                    ->first();
        $data_plafon=[];
        if (!is_null($data) )  
        {
            $data_plafon=['transid'=>$v->transid,
                            'Kd_Urusan'=>$v->Kd_Urusan,
                            'Kd_Bidang'=>$v->Kd_Bidang, 
                            'OrgCd'=>$v->OrgCd, 
                            'OrgNm'=>$v->OrgNm,
                            'SOrgCd'=>$v->SOrgCd,                                    
                            'SOrgNm'=>$v->SOrgNm,
                            'Kd_UrusanPrg'=>$v->Kd_UrusanPrg,
                            'Kd_BidangPrg'=>$v->Kd_BidangPrg,
                            'Kd_Prog'=>$v->Kd_Prog,
                            'PrgNm'=>$v->PrgNm,
                            'Kd_Keg'=>$v->Kd_Keg,
                            'KgtNm'=>$v->KgtNm,
                            'Jumlah3'=>$v->Jumlah3,
                            'Descr'=>$v->Descr,
                            'Status'=>$v->Status,
                            'TA'=>$v->TA 
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$data_plafon],200); 
    }   
}