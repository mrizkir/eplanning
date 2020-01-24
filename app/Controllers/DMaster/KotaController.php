<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\ProvinsiModel;
use App\Models\DMaster\KotaModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class KotaController extends Controller {
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
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('kota','orderby')) 
        {            
           $this->putControllerStateSession('kota','orderby',['column_name'=>'Kd_Kota','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('kota.orderby','column_name'); 
        $direction=$this->getControllerStateSession('kota.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('kota','search')) 
        {
            $search=$this->getControllerStateSession('kota','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Kota' :
                    $data = KotaModel::join('tmPMProv','tmPMProv.PMProvID','tmPmKota.PMProvID')
                                        ->select(\DB::raw('"tmPmKota"."PmKotaID","tmPmKota"."PMProvID","tmPMProv"."Nm_Prov","tmPmKota"."Kd_Kota","tmPmKota"."Nm_Kota","tmPmKota"."Descr","tmPmKota"."TA","tmPmKota"."created_at","tmPmKota"."updated_at"'))
                                        ->where('tmPmKota.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where(['Kd_Kota'=>$search['isikriteria']])
                                        ->orderBy($column_order,$direction); 
                break;
                case 'Nm_Kota' :
                    $data = KotaModel::join('tmPMProv','tmPMProv.PMProvID','tmPmKota.PMProvID')
                                        ->select(\DB::raw('"tmPmKota"."PmKotaID","tmPmKota"."PMProvID","tmPMProv"."Nm_Prov","tmPmKota"."Kd_Kota","tmPmKota"."Nm_Kota","tmPmKota"."Descr","tmPmKota"."TA","tmPmKota"."created_at","tmPmKota"."updated_at"'))
                                        ->where('tmPmKota.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where('tmPmKota.Nm_Kota', 'ILIKE', '%' . $search['isikriteria'] . '%')
                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = KotaModel::join('tmPMProv','tmPMProv.PMProvID','tmPmKota.PMProvID')
                                ->select(\DB::raw('"tmPmKota"."PmKotaID","tmPmKota"."PMProvID","tmPMProv"."Nm_Prov","tmPmKota"."Kd_Kota","tmPmKota"."Nm_Kota","tmPmKota"."Descr","tmPmKota"."TA","tmPmKota"."created_at","tmPmKota"."updated_at"'))
                                ->where('tmPmKota.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }
        $data->setPath(route('kota.index'));
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
        
        $this->setCurrentPageInsideSession('kota',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.kota.datatable")->with(['page_active'=>'kota',
                                                                                'search'=>$this->getControllerStateSession('kota','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('kota.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('kota.orderby','order'),
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
        $theme = \Auth::user()->theme;

        $orderby = $request->input('orderby') == 'asc'?'desc':'asc';
        $column=$request->input('column_name');
        switch($column) 
        {
            case 'col-Kd_Kota' :
                $column_name = 'Kd_Kota';
            break;  
            case 'col-Nm_Kota' :
                $column_name = 'Nm_Kota';
            break;          
            default :
                $column_name = 'Kd_Kota';
        }
        $this->putControllerStateSession('kota','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('kota'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.kota.datatable")->with(['page_active'=>'kota',
                                                            'search'=>$this->getControllerStateSession('kota','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('kota.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('kota.orderby','order'),
                                                            'data'=>$data])->render();     

        return response()->json(['success'=>true,'datatable'=>$datatable],200);
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

        $this->setCurrentPageInsideSession('kota',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.kota.datatable")->with(['page_active'=>'kota',
                                                                            'search'=>$this->getControllerStateSession('kota','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('kota.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('kota.orderby','order'),
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
            $this->destroyControllerStateSession('kota','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('kota','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('kota',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.kota.datatable")->with(['page_active'=>'kota',                                                            
                                                                        'search'=>$this->getControllerStateSession('kota','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                        'column_order'=>$this->getControllerStateSession('kota.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('kota.orderby','order'),
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

        $search=$this->getControllerStateSession('kota','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('kota'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('kota',$data->currentPage());

        return view("pages.$theme.dmaster.kota.index")->with(['page_active'=>'kota',
                                                            'search'=>$this->getControllerStateSession('kota','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('kota.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('kota.orderby','order'),
                                                            'data'=>$data]);               
                }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $provinsi=ProvinsiModel::getDaftarProvinsi(\HelperKegiatan::getTahunPerencanaan(),false,false);        
        $provinsi['']='';
        return view("pages.$theme.dmaster.kota.create")->with(['page_active'=>'kota',
                                                                'provinsi'=>$provinsi
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
        $this->validate($request,
            [
                'PMProvID'=>'required|not_in:none', 
                'Kd_Kota' => [
                                new CheckRecordIsExistValidation('tmPmKota', ['where' => ['PMProvID', '=', $request->input('PMProvID')]]),
                                'required',
                                'min:1',
                                'regex:/^[0-9]+$/'
                ],               
                'Nm_Kota'=>'required|min:5', 
            ],
            [   
                'PMProvID.required'=>'Mohon Provinsi untuk dipilih',         
                'Kd_Kota.required'=>'Mohon Kode Kota untuk di isi karena ini diperlukan',
                'Kd_Kota.min'=>'Mohon Kode Kota untuk di isi minimal 1 digit',

                'Nm_Kota.required'=>'Mohon Nama Kota untuk di isi karena ini diperlukan',
                'Nm_Kota.min'=>'Mohon Nama Kota di isi minimal 5 karakter'
            ]
        );
        
        $kota = KotaModel::create ([
            'PmKotaID'=> uniqid ('uid'),
            'PMProvID'=>$request->input('PMProvID'),
            'Kd_Kota'=>$request->input('Kd_Kota'),        
            'Nm_Kota'=>$request->input('Nm_Kota'),
            'Descr'=>$request->input('Descr'),
            'TA'=>\HelperKegiatan::getTahunPerencanaan(),
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
            return redirect(route('kota.show',['uuid'=>$kota->PmKotaID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = KotaModel::with('provinsi')->findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.dmaster.kota.show")->with(['page_active'=>'kota',
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
    public function edit($id)
    {
        $theme = \Auth::user()->theme;
        
        $data = KotaModel::findOrFail($id);
        if (!is_null($data) ) 
        {   
            $provinsi=ProvinsiModel::getDaftarProvinsi(\HelperKegiatan::getTahunPerencanaan(),false,false);        
            $provinsi['']='';
            return view("pages.$theme.dmaster.kota.edit")->with(['page_active'=>'kota',
                                                                    'provinsi'=>$provinsi,
                                                                    'data'=>$data                                                                    
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
        $kota = KotaModel::find($id);

        $this->validate($request,
        [
            'PMProvID'=>'required|not_in:none',
            'Kd_Kota'=>['required',
                        new IgnoreIfDataIsEqualValidation('tmPmKota',
                                                            $kota->Kd_Kota,
                                                            ['where'=>['PMProvID', '=', $request->input('PMProvID')]],
                                                            'Kd_Kota'),
                        'min:1',
                        'regex:/^[0-9]+$/'

                    ],   
             
            'Nm_Kota'=>'required|min:5', 
        ],
        [            
            'Kd_Kota.required'=>'Mohon Kode Kota untuk di isi karena ini diperlukan',
            'Kd_Kota.min'=>'Mohon Kode Kota untuk di isi minimal 1 digit',
            'Kd_Kota.max'=>'Mohon Kode Kota untuk di isi maksimal 4 digit',
            
            'Kd_Kota.required'=>'Mohon Kode Kota untuk di isi karena ini diperlukan',

            'PMProvID.required'=>'Mohon Provinsi untuk dipilih',

            'Nm_Kota.required'=>'Mohon Nama Kota untuk di isi karena ini diperlukan',
            'Nm_Kota.min'=>'Mohon Nama Kota di isi minimal 5 karakter'
        ]
        );

        $kota->PMProvID= $request->input('PMProvID');
        $kota->Kd_Kota = $request->input('Kd_Kota');
        $kota->Nm_Kota = $request->input('Nm_Kota');
        $kota->Descr = $request->input('Descr');
        
        $kota->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('kota.show',['uuid'=>$kota->PmKotaID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $kota = KotaModel::find($id);        
        $result=$kota->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('kota'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.kota.datatable")->with(['page_active'=>'kota',
                                                                            'search'=>$this->getControllerStateSession('kota','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('kota.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('kota.orderby','order'),
                                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('kota.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}