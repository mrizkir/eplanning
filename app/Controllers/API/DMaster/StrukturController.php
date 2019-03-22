<?php

namespace App\Controllers\API\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningStrukturModel;

class StrukturController extends Controller {
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
        $columns=['StrID','Kd_Rek_1','StrNm','TA'];   
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
            $data=RekeningStrukturModel::all();
            $daftar_rek1 = []; 
            foreach ($data as $v)
            {
                $daftar_rek1[]=['StrID'=>$v->StrID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
                                'TA'=>$v->TA
                            ];
            }
            return response()->json(['status'=>1,
                                    'data'=>$daftar_rek1],200); 
        }
        else
        {
            $data = RekeningStrukturModel::where('TA',$ta)->orderBy('Kd_Rek_1','ASC')->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
            $daftar_rek1 = []; 
            foreach ($data as $v)
            {
                $daftar_rek1[]=['StrID'=>$v->StrID,
                                'Kd_Rek_1'=>$v->Kd_Rek_1,
                                'StrNm'=>$v->StrNm,
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
        $data = RekeningStrukturModel::where('StrID',$id)
                                    ->first();
        $daftar_rek1=[];
        if (!is_null($data) )  
        {
            $daftar_rek1=['StrID'=>$data->StrID,
                            'Kd_Rek_1'=>$data->Kd_Rek_1,
                            'StrNm'=>$data->StrNm,
                            'TA'=>$data->TA
                        ];
        }
        return response()->json(['status'=>1,                                    
                                'data'=>$daftar_rek1],200); 
    }   
}