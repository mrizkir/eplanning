<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDTujuanModel;
use App\Models\RPJMD\RPJMDTujuanIndikatorModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RPJMDTujuanController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang']);
    }
    private function populateIndikatorTujuan($PrioritasTujuanKabID)
    {
      
        $data = RPJMDTujuanIndikatorModel::where('PrioritasTujuanKabID',$PrioritasTujuanKabID)
                                        ->get();

        return $data;
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('rpjmdtujuan','orderby')) 
        {            
           $this->putControllerStateSession('rpjmdtujuan','orderby',['column_name'=>'Nm_Tujuan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rpjmdtujuan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rpjmdtujuan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rpjmdtujuan','search')) 
        {
            $search=$this->getControllerStateSession('rpjmdtujuan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Tujuan' :
                    $data = RPJMDTujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID","tmPrioritasTujuanKab"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan") AS "Kd_Tujuan","tmPrioritasTujuanKab"."Nm_Tujuan","tmPrioritasTujuanKab"."TA"'))
                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                            ->where(['Kd_Tujuan'=>$search['isikriteria']])
                                            ->orderBy('Kd_PrioritasKab','ASC')
                                            ->orderBy('Kd_Tujuan','ASC'); 
                break;
                case 'Nm_Tujuan' :
                    $data = RPJMDTujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID","tmPrioritasTujuanKab"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan") AS "Kd_Tujuan","tmPrioritasTujuanKab"."Nm_Tujuan","tmPrioritasTujuanKab"."TA"'))
                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                            ->where('Nm_Tujuan', 'ilike', '%' . $search['isikriteria'] . '%')
                                            ->orderBy('Kd_PrioritasKab','ASC')
                                            ->orderBy('Kd_Tujuan','ASC');                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RPJMDTujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID","tmPrioritasTujuanKab"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan") AS "Kd_Tujuan","tmPrioritasTujuanKab"."Nm_Tujuan","tmPrioritasTujuanKab"."TA"'))
                                    ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                    ->orderBy('Kd_PrioritasKab','ASC')
                                    ->orderBy('Kd_Tujuan','ASC')
                                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rpjmdtujuan.index'));
        return $data;
    }
    /**
     * digunakan untuk mengganti jumlah record per halaman
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changenumberrecordperpage (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('rpjmdtujuan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdtujuan.datatable")->with(['page_active'=>'rpjmdtujuan',
                                                                                'search'=>$this->getControllerStateSession('rpjmdtujuan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rpjmdtujuan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rpjmdtujuan.orderby','order'),
                                                                                'data'=>$data])->render();      
        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * digunakan untuk mengurutkan record 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderby (Request $request) 
    {
        return response()->json(['success'=>true,'datatable'=>null],200);
    }
    /**
     * paginate resource in storage called by ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paginate ($id) 
    {
        $theme = \Auth::user()->theme;

        $this->setCurrentPageInsideSession('rpjmdtujuan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rpjmd.rpjmdtujuan.datatable")->with(['page_active'=>'rpjmdtujuan',
                                                                            'search'=>$this->getControllerStateSession('rpjmdtujuan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rpjmdtujuan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rpjmdtujuan.orderby','order'),
                                                                            'data'=>$data])->render(); 

        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * search resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('rpjmdtujuan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rpjmdtujuan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rpjmdtujuan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdtujuan.datatable")->with(['page_active'=>'rpjmdtujuan',                                                            
                                                            'search'=>$this->getControllerStateSession('rpjmdtujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdtujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdtujuan.orderby','order'),
                                                            'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;
        
        $search=$this->getControllerStateSession('rpjmdtujuan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rpjmdtujuan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rpjmdtujuan',$data->currentPage());
        
        return view("pages.$theme.rpjmd.rpjmdtujuan.index")->with(['page_active'=>'rpjmdtujuan',
                                                                    'search'=>$this->getControllerStateSession('rpjmdtujuan','search'),
                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                    'column_order'=>$this->getControllerStateSession('rpjmdtujuan.orderby','column_name'),
                                                                    'direction'=>$this->getControllerStateSession('rpjmdtujuan.orderby','order'),
                                                                    'data'=>$data]);               
    }
    public function getdaftartujuanrpjmd($id)
    {
        $daftar_tujuan=\App\Models\RPJMD\RPJMDTujuanModel::select(\DB::raw('"PrioritasTujuanKabID",CONCAT(\'[\',"Kd_PrioritasKab",\'.\',"Kd_Tujuan",\']. \',"Nm_Tujuan") AS "Nm_Tujuan"'))
                                                        ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                                        ->where('tmPrioritasTujuanKab.PrioritasKabID',$id)                                                        
                                                        ->orderBy('Kd_Tujuan','ASC')
                                                        ->get()
                                                        ->pluck('Nm_Tujuan','PrioritasTujuanKabID')
                                                        ->toArray();
        return response()->json(['success'=>true,'daftar_tujuan'=>$daftar_tujuan],200);
    }  
    public function getkodetujuan($id)
    {
        $Kd_Tujuan = RPJMDTujuanModel::where('PrioritasKabID',$id)->count('Kd_Tujuan')+1;
        return response()->json(['success'=>true,'Kd_Tujuan'=>$Kd_Tujuan],200);
    }   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $daftar_misi=\App\Models\RPJMD\RPJMDMisiModel::select(\DB::raw('"PrioritasKabID",CONCAT(\'[\',"Kd_PrioritasKab",\']. \',"Nm_PrioritasKab") AS "Nm_PrioritasKab"'))
                                                    ->where('TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                    ->orderBy('Kd_PrioritasKab','ASC')
                                                    ->get()
                                                    ->pluck('Nm_PrioritasKab','PrioritasKabID')
                                                    ->toArray();

        
        return view("pages.$theme.rpjmd.rpjmdtujuan.create")->with(['page_active'=>'rpjmdtujuan',
                                                                    'daftar_misi'=>$daftar_misi
                                                                    ]);  
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Kd_Tujuan'=>[new CheckRecordIsExistValidation('tmPrioritasTujuanKab',['where'=>['PrioritasKabID','=',$request->input('PrioritasKabID')]]),
                            'required'
                        ],
            'PrioritasKabID'=>'required',
            'Nm_Tujuan'=>'required',
        ]);
        
        $rpjmdtujuan = RPJMDTujuanModel::create([
            'PrioritasTujuanKabID'=> uniqid ('uid'),
            'PrioritasKabID' => $request->input('PrioritasKabID'),
            'Kd_Tujuan' => $request->input('Kd_Tujuan'),
            'Nm_Tujuan' => $request->input('Nm_Tujuan'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getRPJMDTahunMulai()
        ]);        
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdtujuan.show',['uuid'=>$rpjmdtujuan->PrioritasTujuanKabID]))->with('success','Data ini telah berhasil disimpan.');
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {
        $this->validate($request, [            
            'NamaIndikator'=>'required',
            'KondisiAwal'=>'required',
            'KondisiAkhir'=>'required',
            'Satuan'=>'required',
        ]);
        
        $rpjmdindikatortujuan = RPJMDTujuanIndikatorModel::create([
            'PrioritasIndikatorTujuanID'=> uniqid ('uid'),
            'PrioritasTujuanKabID' => $request->input('PrioritasTujuanKabID'),
            'NamaIndikator' => $request->input('NamaIndikator'),
            'KondisiAwal' => $request->input('KondisiAwal'),
            'KondisiAkhir' => $request->input('KondisiAkhir'),
            'Satuan' => $request->input('Satuan'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getRPJMDTahunMulai()
        ]);        
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdtujuan.show',['uuid'=>$rpjmdindikatortujuan->PrioritasTujuanKabID]))->with('success','Data ini telah berhasil disimpan.');
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
        $theme = \Auth::user()->theme;

        $data = RPJMDTujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID",
                                                    "tmPrioritasKab"."Kd_PrioritasKab",
                                                    "tmPrioritasKab"."Nm_PrioritasKab",
                                                    "tmPrioritasTujuanKab"."Kd_Tujuan",
                                                    "tmPrioritasTujuanKab"."Nm_Tujuan",
                                                    "tmPrioritasTujuanKab"."Descr",
                                                    "tmPrioritasTujuanKab"."PrioritasTujuanKabID_Src",
                                                    "tmPrioritasTujuanKab"."created_at",
                                                    "tmPrioritasTujuanKab"."updated_at"'))
                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            $dataindikatortujuan=$this->populateIndikatorTujuan($id);
            return view("pages.$theme.rpjmd.rpjmdtujuan.show")->with(['page_active'=>'rpjmdtujuan',
                                                                        'data'=>$data,
                                                                        'dataindikatortujuan'=>$dataindikatortujuan
                                                                    ]);
        }        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $theme = \Auth::user()->theme;
        
        $data = RPJMDTujuanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_misi=\App\Models\RPJMD\RPJMDMisiModel::select(\DB::raw('"PrioritasKabID",CONCAT(\'[\',"Kd_PrioritasKab",\']. \',"Nm_PrioritasKab") AS "Nm_PrioritasKab"'))
                                                            ->where('TA',$data->TA)
                                                            ->orderBy('Kd_PrioritasKab','ASC')
                                                            ->get()
                                                            ->pluck('Nm_PrioritasKab','PrioritasKabID')
                                                            ->toArray();

            return view("pages.$theme.rpjmd.rpjmdtujuan.edit")->with(['page_active'=>'rpjmdtujuan',
                                                                        'daftar_misi'=>$daftar_misi,
                                                                        'data'=>$data
                                                                    ]);
        }        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit1($id)
    {
        $theme = \Auth::user()->theme;
        
        $data_indikator = RPJMDTujuanIndikatorModel::findOrFail($id);
        $data = RPJMDTujuanModel::find($data_indikator->PrioritasTujuanKabID);
        if (!is_null($data) ) 
        {        
            $dataindikatortujuan=$this->populateIndikatorTujuan($data_indikator->PrioritasTujuanKabID);
            return view("pages.$theme.rpjmd.rpjmdtujuan.edit1")->with(['page_active'=>'rpjmdtujuan',
                                                                        'dataindikatortujuan'=>$dataindikatortujuan,
                                                                        'data'=>$data,
                                                                        'data_indikator'=>$data_indikator
                                                                    ]);
        }        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rpjmdtujuan = RPJMDTujuanModel::find($id);
        
        $this->validate($request, [
            'Kd_Tujuan'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasTujuanKab',
                                                                        $rpjmdtujuan->Kd_Tujuan,
                                                                        ['where'=>['PrioritasKabID','=',$request->input('PrioritasKabID')]],
                                                                        'Kode Tujuan')],
            'PrioritasKabID'=>'required',
            'Nm_Tujuan'=>'required',
        ]);
               
        $rpjmdtujuan->PrioritasKabID = $request->input('PrioritasKabID');
        $rpjmdtujuan->Kd_Tujuan = $request->input('Kd_Tujuan');
        $rpjmdtujuan->Nm_Tujuan = $request->input('Nm_Tujuan');
        $rpjmdtujuan->Descr = $request->input('Descr');
        $rpjmdtujuan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdtujuan.show',['uuid'=>$rpjmdtujuan->PrioritasTujuanKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
        }
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update1(Request $request, $id)
    {
        $rpjmdindikatortujuan = RPJMDTujuanIndikatorModel::find($id);

        $this->validate($request, [            
            'NamaIndikator'=>'required',
            'KondisiAwal'=>'required',
            'KondisiAkhir'=>'required',
            'Satuan'=>'required',
        ]);
        
        $rpjmdindikatortujuan->NamaIndikator = $request->input('NamaIndikator');
        $rpjmdindikatortujuan->KondisiAwal = $request->input('KondisiAwal');
        $rpjmdindikatortujuan->KondisiAkhir = $request->input('KondisiAkhir');
        $rpjmdindikatortujuan->Satuan = $request->input('Satuan');        
        $rpjmdindikatortujuan->Descr = $request->input('Descr');
        $rpjmdindikatortujuan->save();
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdtujuan.show',['uuid'=>$rpjmdindikatortujuan->PrioritasTujuanKabID]))->with('success','Data ini telah berhasil disimpan.');
        }

    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $theme = \Auth::user()->theme;
        
        if ($request->exists('indikatortujuan'))
        {
            $rpjmdtujuan = RPJMDTujuanIndikatorModel::find($id);
            $PrioritasTujuanKabID=$rpjmdtujuan->PrioritasTujuanKabID;
            $result=$rpjmdtujuan->delete();
            if ($request->ajax()) 
            {                
                $dataindikatortujuan = $this->populateIndikatorTujuan($PrioritasTujuanKabID);                
                $datatable = view("pages.$theme.rpjmd.rpjmdtujuan.datatableindikatortujuan")->with(['page_active'=>'rpjmdtujuan',                                                                                    
                                                                                                    'dataindikatortujuan'=>$dataindikatortujuan])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('rpjmdtujuan.show',['uuid'=>$PrioritasTujuanKabID]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }       
        }
        else
        {
            $rpjmdtujuan = RPJMDTujuanModel::find($id);
            $result=$rpjmdtujuan->delete();
            if ($request->ajax()) 
            {
                $currentpage=$this->getCurrentPageInsideSession('rpjmdtujuan'); 
                $data=$this->populateData($currentpage);
                if ($currentpage > $data->lastPage())
                {            
                    $data = $this->populateData($data->lastPage());
                }
                $datatable = view("pages.$theme.rpjmd.rpjmdtujuan.datatable")->with(['page_active'=>'rpjmdtujuan',
                                                                'search'=>$this->getControllerStateSession('rpjmdtujuan','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('rpjmdtujuan.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('rpjmdtujuan.orderby','order'),
                                                                'data'=>$data])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('rpjmdtujuan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }       
        }         
    }
}
