<?php

namespace App\Controllers\API\v1\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\ProgramModel;

class ProgramController extends Controller {
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
            $data = \DB::table('tmPrg AS a')
                        ->select(\DB::raw('
                            a."PrgID",
                            CASE WHEN d."Kd_Urusan" IS NULL THEN 
                                                \'0\'
                                        ELSE
                                                d."Kd_Urusan"
                            END AS "Kd_Urusan", 
                            d."Nm_Urusan",
                            CASE WHEN c."Kd_Bidang" IS NULL THEN 
                                                \'0\'
                                        ELSE
                                                c."Kd_Bidang"
                            END AS "Kd_Bidang", 
                            c."Nm_Bidang",
                            a."Kd_Prog",
                            a."PrgNm",
                            a."Jns",
                            a."TA" 
                        '))
                        ->leftJoin('trUrsPrg AS b',\DB::raw('b."PrgID"'),'=',\DB::raw('a."PrgID"'))
                        ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                        ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                        ->where('a.TA',$ta)
                        ->orderBy('a.Kd_Prog','ASC')
                        ->get();
            $data_program = []; 
            foreach ($data as $v)
            {
                $data_program[]=['PrgID'=>$v->PrgID,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'Nm_Bidang'=>$v->Nm_Bidang, 
                                    'Kd_Prog'=>$v->Kd_Prog,
                                    'PrgNm'=>$v->PrgNm,
                                    'Jns'=>$v->Jns,
                                    'TA'=>$v->TA                                     
                                ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$data_program],200); 
        }
        else
        {
            $data = \DB::table('tmPrg AS a')
                        ->select(\DB::raw('
                            a."PrgID",
                            CASE WHEN d."Kd_Urusan" IS NULL THEN 
                                                \'0\'
                                        ELSE
                                                d."Kd_Urusan"
                            END AS "Kd_Urusan", 
                            d."Nm_Urusan",
                            CASE WHEN c."Kd_Bidang" IS NULL THEN 
                                                \'0\'
                                        ELSE
                                                c."Kd_Bidang"
                            END AS "Kd_Bidang", 
                            c."Nm_Bidang",
                            a."Kd_Prog",
                            a."PrgNm",
                            a."Jns",
                            a."TA" 
                        '))
                        ->leftJoin('trUrsPrg AS b',\DB::raw('b."PrgID"'),'=',\DB::raw('a."PrgID"'))
                        ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                        ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                        ->where('a.TA',$ta)
                        ->orderBy('a.Kd_Prog','ASC')
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            $data_program = []; 
            foreach ($data as $v)
            {
                $data_program[]=['PrgID'=>$v->PrgID,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'Nm_Bidang'=>$v->Nm_Bidang, 
                                    'Kd_Prog'=>$v->Kd_Prog,
                                    'PrgNm'=>$v->PrgNm,
                                    'Jns'=>$v->Jns,
                                    'TA'=>$v->TA 
                                ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$data_program],200); 
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
        $data = \DB::table('tmPrg AS a')
                        ->select(\DB::raw('
                        a."PrgID",
                        CASE WHEN d."Kd_Urusan" IS NULL THEN 
                                            \'0\'
                                    ELSE
                                            d."Kd_Urusan"
                        END AS "Kd_Urusan", 
                        d."Nm_Urusan",
                        CASE WHEN c."Kd_Bidang" IS NULL THEN 
                                            \'0\'
                                    ELSE
                                            c."Kd_Bidang"
                        END AS "Kd_Bidang", 
                        c."Nm_Bidang",
                        a."Kd_Prog",
                        a."PrgNm",
                        a."Jns",
                        a."TA" 
                    '))
                    ->leftJoin('trUrsPrg AS b',\DB::raw('b."PrgID"'),'=',\DB::raw('a."PrgID"'))
                    ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                    ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                    ->where('a."PrgID"',$id)
                    ->first();
        $data_program=[];
        if (!is_null($data) )  
        {
            $data_program=['PrgID'=>$data->PrgID,
                            'Kd_Urusan'=>$data->Kd_Urusan,
                            'Nm_Urusan'=>$data->Nm_Urusan,
                            'Kd_Bidang'=>$data->Kd_Bidang, 
                            'Nm_Bidang'=>$data->Nm_Bidang, 
                            'Kd_Prog'=>$data->Kd_Prog,
                            'PrgNm'=>$data->PrgNm,
                            'Jns'=>$data->Jns,
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$data_program],200); 
    }   
}