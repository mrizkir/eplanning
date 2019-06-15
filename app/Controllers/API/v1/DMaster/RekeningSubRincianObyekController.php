<?php

namespace App\Controllers\API\v1\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningSubRincianObyekModel;

class RekeningSubRincianObyekController extends Controller {
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
        $columns=['SubRObyID','Kd_Rek_1','StrNm','Kd_Rek_2','KlpNm','Kd_Rek_3','JnsNm','Kd_Rek_4','ObyNm','Kd_Rek_5','RObyNm','Kd_Sub','Nm_Sub','tmSubROby.TA'];   
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
            $data=RekeningSubRincianObyekModel::join('tmROby','tmSubROby.RObyID','tmROby.RObyID')
                                                ->join('tmOby','tmOby.ObyID','tmROby.ObyID')
                                                ->join('tmJns','tmJns.JnsID','tmOby.JnsID')
                                                ->join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                                ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                                ->where('tmSubROby.TA',$ta)
                                                ->orderBy('Kd_Rek_2','ASC')
                                                ->get();
            $daftar_rek6 = []; 
            foreach ($data as $v)
            {
                $daftar_rek6[]=['SubRObyID'=>$v->SubRObyID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'Kd_Rek_2'=>$v->Kd_Rek_2,
                                'KlpNm'=>$v->KlpNm,
                                'Kd_Rek_3'=>$v->Kd_Rek_3,
                                'JnsNm'=>$v->JnsNm,
                                'Kd_Rek_4'=>$v->Kd_Rek_4,
                                'ObyNm'=>$v->ObyNm,
                                'Kd_Rek_5'=>$v->Kd_Rek_5,
                                'RObyNm'=>$v->RObyNm,
                                'Kd_Sub'=>$v->Kd_Sub,
                                'Nm_Sub'=>$v->Nm_Sub,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$daftar_rek6],200); 
        }
        else
        {
            $data = RekeningSubRincianObyekModel::join('tmROby','tmSubROby.RObyID','tmROby.RObyID')
                                        ->join('tmOby','tmOby.ObyID','tmROby.ObyID')
                                        ->join('tmJns','tmJns.JnsID','tmOby.JnsID')
                                        ->join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                        ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                        ->where('tmSubROby.TA',$ta)
                                        ->orderBy('Kd_Rek_2','ASC')
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
            $daftar_rek6 = []; 
            foreach ($data as $v)
            {
                $daftar_rek6[]=['SubRObyID'=>$v->SubRObyID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'Kd_Rek_2'=>$v->Kd_Rek_2,
                                'KlpNm'=>$v->KlpNm,
                                'Kd_Rek_3'=>$v->Kd_Rek_3,
                                'JnsNm'=>$v->JnsNm,
                                'Kd_Rek_4'=>$v->Kd_Rek_4,
                                'ObyNm'=>$v->ObyNm,
                                'Kd_Rek_5'=>$v->Kd_Rek_5,
                                'RObyNm'=>$v->RObyNm,
                                'Kd_Sub'=>$v->Kd_Sub,
                                'Nm_Sub'=>$v->Nm_Sub,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'per_page'=> $data->perPage(),
                                    'current_page'=>$data->currentPage(),
                                    'last_page'=>$data->lastPage(),
                                    'total'=>$data->total(),
                                    'data'=>$daftar_rek6],200); 
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
        $data = RekeningSubRincianObyekModel::join('tmROby','tmSubROby.RObyID','tmROby.RObyID')
                                    ->join('tmOby','tmOby.ObyID','tmROby.ObyID')
                                    ->join('tmJns','tmJns.JnsID','tmOby.JnsID')
                                    ->join('tmKlp','tmKlp.KlpID','tmJns.KlpID')
                                    ->join('tmStr','tmStr.StrID','tmKlp.StrID')
                                    ->where('SubRObyID',$id)
                                    ->first();
        $daftar_rek6=[];
        if (!is_null($data) )  
        {
            $daftar_rek6=['SubRObyID'=>$data->SubRObyID,
                            'Kd_Rek_1'=>$data->Kd_Rek_1,
                            'StrNm'=>$data->StrNm,
                            'Kd_Rek_2'=>$data->Kd_Rek_2,
                            'KlpNm'=>$data->KlpNm,
                            'Kd_Rek_3'=>$data->Kd_Rek_3,
                            'JnsNm'=>$data->JnsNm,
                            'Kd_Rek_4'=>$data->Kd_Rek_4,
                            'ObyNm'=>$data->ObyNm,
                            'Kd_Rek_5'=>$data->Kd_Rek_5,
                            'RObyNm'=>$data->RObyNm,
                            'Kd_Sub'=>$data->Kd_Sub,
                            'Nm_Sub'=>$data->Nm_Sub,
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$daftar_rek6],200); 
    }   
}