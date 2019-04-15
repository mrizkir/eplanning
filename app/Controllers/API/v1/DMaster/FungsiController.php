<?php

namespace App\Controllers\API\v1\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\FungsiModel;

class FungsiController extends Controller {
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
            $data = \DB::table('tmFungsi AS a')
                        ->select(\DB::raw('
                            a."FungsiID",
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
                            a."Kd_Fungsi",
                            a."Nm_Fungsi",
                            a."TA" 
                        '))
                        ->join('trUrsFungsi AS b',\DB::raw('b."FungsiID"'),'=',\DB::raw('a."FungsiID"'))
                        ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                        ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                        ->where('a.TA',$ta)
                        ->orderBy('a.Kd_Fungsi','ASC')
                        ->get();
            $data_fungsi = []; 
            foreach ($data as $v)
            {
                $data_fungsi[]=['FungsiID'=>$v->FungsiID,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'Nm_Bidang'=>$v->Nm_Bidang, 
                                    'Kd_Fungsi'=>$v->Kd_Fungsi,
                                    'Nm_Fungsi'=>$v->Nm_Fungsi,
                                    'TA'=>$v->TA                                     
                                ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$data_fungsi],200); 
        }
        else
        {
            $data = \DB::table('tmFungsi AS a')
                        ->select(\DB::raw('
                            a."FungsiID",
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
                            a."Kd_Fungsi",
                            a."Nm_Fungsi",
                            a."TA" 
                        '))
                        ->join('trUrsFungsi AS b',\DB::raw('b."FungsiID"'),'=',\DB::raw('a."FungsiID"'))
                        ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                        ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                        ->where('a.TA',$ta)
                        ->orderBy('a.Kd_Fungsi','ASC')
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            $data_fungsi = []; 
            foreach ($data as $v)
            {
                $data_fungsi[]=['FungsiID'=>$v->FungsiID,
                                    'Kd_Urusan'=>$v->Kd_Urusan,
                                    'Nm_Urusan'=>$v->Nm_Urusan,
                                    'Kd_Bidang'=>$v->Kd_Bidang, 
                                    'Nm_Bidang'=>$v->Nm_Bidang, 
                                    'Kd_Fungsi'=>$v->Kd_Fungsi,
                                    'Nm_Fungsi'=>$v->Nm_Fungsi,
                                    'TA'=>$v->TA 
                                ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$data_fungsi],200); 
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
        $data = \DB::table('tmFungsi AS a')
                        ->select(\DB::raw('
                        a."FungsiID",
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
                        a."Kd_Fungsi",
                        a."Nm_Fungsi",
                        a."TA" 
                    '))
                    ->join('trUrsFungsi AS b',\DB::raw('b."FungsiID"'),'=',\DB::raw('a."FungsiID"'))
                    ->leftJoin('tmUrs AS c',\DB::raw('c."UrsID"'),'=',\DB::raw('b."UrsID"'))
                    ->leftJoin('tmKUrs AS d',\DB::raw('d."KUrsID"'),'=',\DB::raw('c."KUrsID"'))
                    ->where('a."FungsiID"',$id)
                    ->first();
        $data_fungsi=[];
        if (!is_null($data) )  
        {
            $data_fungsi=['FungsiID'=>$data->FungsiID,
                            'Kd_Urusan'=>$data->Kd_Urusan,
                            'Nm_Urusan'=>$data->Nm_Urusan,
                            'Kd_Bidang'=>$data->Kd_Bidang, 
                            'Nm_Bidang'=>$data->Nm_Bidang, 
                            'Kd_Fungsi'=>$data->Kd_Fungsi,
                            'Nm_Fungsi'=>$data->Nm_Fungsi,
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$data_fungsi],200); 
    }   
}