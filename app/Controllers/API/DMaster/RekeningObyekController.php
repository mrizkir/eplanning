<?php

namespace App\Controllers\API\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningObyekModel;

class RekeningObyekController extends Controller {
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
        $columns=['ObyID','Kd_Rek_1','StrNm','Kd_Rek_2','KlpNm','Kd_Rek_3','JnsNm','Kd_Rek_4','ObyNm','tmOby.TA'];   
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
            $data=RekeningObyekModel::join('tmJns','tmJns.JnsID','tmOby.JnsID')
                                    ->join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                    ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                    ->where('tmOby.TA',$ta)
                                    ->orderBy('Kd_Rek_2','ASC')
                                    ->get();
            $daftar_rek1 = []; 
            foreach ($data as $v)
            {
                $daftar_rek1[]=['ObyID'=>$v->ObyID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'Kd_Rek_2'=>$v->Kd_Rek_2,
                                'KlpNm'=>$v->KlpNm,
                                'Kd_Rek_3'=>$v->Kd_Rek_2,
                                'JnsNm'=>$v->JnsNm,
                                'Kd_Rek_4'=>$v->Kd_Rek_4,
                                'ObyNm'=>$v->ObyNm,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$daftar_rek1],200); 
        }
        else
        {
            $data = RekeningObyekModel::join('tmJns','tmJns.JnsID','tmOby.JnsID')
                                        ->join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                        ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                        ->where('tmOby.TA',$ta)
                                        ->orderBy('Kd_Rek_2','ASC')
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
            $daftar_rek1 = []; 
            foreach ($data as $v)
            {
                $daftar_rek1[]=['ObyID'=>$v->ObyID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'Kd_Rek_2'=>$v->Kd_Rek_2,
                                'KlpNm'=>$v->KlpNm,
                                'Kd_Rek_3'=>$v->Kd_Rek_2,
                                'JnsNm'=>$v->JnsNm,
                                'Kd_Rek_4'=>$v->Kd_Rek_4,
                                'ObyNm'=>$v->ObyNm,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$daftar_rek1],200); 
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
        $data = RekeningObyekModel::join('tmJns','tmJns.JnsID','tmOby.JnsID')
                                    ->join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                    ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                    ->where('KlpID',$id)
                                    ->first();
        $daftar_rek1=[];
        if (!is_null($data) )  
        {
            $daftar_rek1=['ObyID'=>$data->ObyID,
                            'Kd_Rek_1'=>$v->Kd_Rek_1,
                            'StrNm'=>$v->StrNm,
                            'Kd_Rek_2'=>$data->Kd_Rek_2,
                            'KlpNm'=>$data->KlpNm,
                            'Kd_Rek_3'=>$v->Kd_Rek_2,
                            'JnsNm'=>$v->JnsNm,
                            'Kd_Rek_4'=>$v->Kd_Rek_4,
                            'ObyNm'=>$v->ObyNm,
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$daftar_rek1],200); 
    }   
}