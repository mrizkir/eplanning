<?php

namespace App\Controllers\API\v1\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningKelompokModel;

class RekeningKelompokController extends Controller {
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
        $columns=['KlpID','Kd_Rek_1','StrNm','Kd_Rek_2','KlpNm','tmKlp.TA'];   
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
            $data=RekeningKelompokModel::join('tmStr','tmStr.StrID','tmKlp.StrID')
                                        ->where('tmKlp.TA',$ta)
                                        ->orderBy('Kd_Rek_2','ASC')
                                        ->get();
            $daftar_rek2 = []; 
            foreach ($data as $v)
            {
                $daftar_rek2[]=['KlpID'=>$v->KlpID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'Kd_Rek_2'=>$v->Kd_Rek_2,
                                'KlpNm'=>$v->KlpNm,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$daftar_rek2],200); 
        }
        else
        {
            $data = RekeningKelompokModel::join('tmStr','tmStr.StrID','tmKlp.StrID')
                                        ->where('tmKlp.TA',$ta)
                                        ->orderBy('Kd_Rek_2','ASC')
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
            $daftar_rek2 = []; 
            foreach ($data as $v)
            {
                $daftar_rek2[]=['KlpID'=>$v->KlpID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'Kd_Rek_2'=>$v->Kd_Rek_2,
                                'KlpNm'=>$v->KlpNm,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$daftar_rek2],200); 
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
        $data = RekeningKelompokModel::join('tmStr','tmStr.StrID','tmKlp.StrID')
                                    ->where('KlpID',$id)
                                    ->first();
        $daftar_rek2=[];
        if (!is_null($data) )  
        {
            $daftar_rek2=['KlpID'=>$data->KlpID,
                            'Kd_Rek_1'=>$data->Kd_Rek_1,
                            'StrNm'=>$data->StrNm,
                            'Kd_Rek_2'=>$data->Kd_Rek_2,
                            'KlpNm'=>$data->KlpNm,
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$daftar_rek2],200); 
    }   
}