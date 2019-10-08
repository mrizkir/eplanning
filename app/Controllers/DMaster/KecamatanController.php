<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\KotaModel;
use App\Models\DMaster\KecamatanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class KecamatanController extends Controller {
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
        if (!$this->checkStateIsExistSession('kecamatan','orderby')) 
        {            
           $this->putControllerStateSession('kecamatan','orderby',['column_name'=>'Kd_Kecamatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('kecamatan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('kecamatan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('kecamatan','search')) 
        {
            $search=$this->getControllerStateSession('kecamatan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Kecamatan' :
                    $data = KecamatanModel::join('tmPmKota','tmPmKota.PmKotaID','tmPmKecamatan.PmKotaID')
                                        ->select(\DB::raw('"tmPmKecamatan"."PmKecamatanID","tmPmKecamatan"."PmKotaID","tmPmKota"."Nm_Kota","tmPmKecamatan"."Kd_Kecamatan","tmPmKecamatan"."Nm_Kecamatan","tmPmKecamatan"."Descr","tmPmKecamatan"."TA","tmPmKecamatan"."created_at","tmPmKecamatan"."updated_at"'))
                                        ->where('tmPmKecamatan.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where(['Kd_Kecamatan'=>$search['isikriteria']])
                                        ->orderBy($column_order,$direction); 
                break;
                case 'Nm_Kecamatan' :
                    $data = KecamatanModel::join('tmPmKota','tmPmKota.PmKotaID','tmPmKecamatan.PmKotaID')
                                        ->select(\DB::raw('"tmPmKecamatan"."PmKecamatanID","tmPmKecamatan"."PmKotaID","tmPmKota"."Nm_Kota","tmPmKecamatan"."Kd_Kecamatan","tmPmKecamatan"."Nm_Kecamatan","tmPmKecamatan"."Descr","tmPmKecamatan"."TA","tmPmKecamatan"."created_at","tmPmKecamatan"."updated_at"'))
                                        ->where('tmPmKecamatan.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where('tmPmKecamatan.Nm_Kecamatan', 'ILIKE', '%' . $search['isikriteria'] . '%')
                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = KecamatanModel::join('tmPmKota','tmPmKota.PmKotaID','tmPmKecamatan.PmKotaID')
                                ->select(\DB::raw('"tmPmKecamatan"."PmKecamatanID","tmPmKecamatan"."PmKotaID","tmPmKota"."Nm_Kota","tmPmKecamatan"."Kd_Kecamatan","tmPmKecamatan"."Nm_Kecamatan","tmPmKecamatan"."Descr","tmPmKecamatan"."TA","tmPmKecamatan"."created_at","tmPmKecamatan"."updated_at"'))
                                ->where('tmPmKecamatan.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }
        $data->setPath(route('kecamatan.index'));
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
        
        $this->setCurrentPageInsideSession('kecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.kecamatan.datatable")->with(['page_active'=>'kecamatan',
                                                                                'search'=>$this->getControllerStateSession('kecamatan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('kecamatan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('kecamatan.orderby','order'),
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
            case 'col-Kd_Kecamatan' :
                $column_name = 'Kd_Kecamatan';
            break;  
            case 'col-Nm_Kecamatan' :
                $column_name = 'Nm_Kecamatan';
            break;          
            default :
                $column_name = 'Kd_Kecamatan';
        }
        $this->putControllerStateSession('kecamatan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('kecamatan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.kecamatan.datatable")->with(['page_active'=>'kecamatan',
                                                            'search'=>$this->getControllerStateSession('kecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('kecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('kecamatan.orderby','order'),
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

        $this->setCurrentPageInsideSession('kecamatan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.kecamatan.datatable")->with(['page_active'=>'kecamatan',
                                                                            'search'=>$this->getControllerStateSession('kecamatan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('kecamatan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('kecamatan.orderby','order'),
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
            $this->destroyControllerStateSession('kecamatan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('kecamatan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('kecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.kecamatan.datatable")->with(['page_active'=>'kecamatan',                                                            
                                                                        'search'=>$this->getControllerStateSession('kecamatan','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                        'column_order'=>$this->getControllerStateSession('kecamatan.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('kecamatan.orderby','order'),
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

        $search=$this->getControllerStateSession('kecamatan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('kecamatan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('kecamatan',$data->currentPage());

        return view("pages.$theme.dmaster.kecamatan.index")->with(['page_active'=>'kecamatan',
                                                            'search'=>$this->getControllerStateSession('kecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('kecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('kecamatan.orderby','order'),
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
        $kota=KotaModel::getDaftarKota(\HelperKegiatan::getTahunPerencanaan(),false,false);        
        $kota['']='';
        return view("pages.$theme.dmaster.kecamatan.create")->with(['page_active'=>'kecamatan',
                                                                'kota'=>$kota
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
                'PmKotaID'=>'required|not_in:none', 
                'Kd_Kecamatan' => [
                                new CheckRecordIsExistValidation('tmPmKecamatan', ['where' => ['PmKotaID', '=', $request->input('PmKotaID')]]),
                                'required',
                                'min:1',
                                'regex:/^[0-9]+$/'
                ],               
                'Nm_Kecamatan'=>'required|min:5', 
            ],
            [   
                'PmKotaID.required'=>'Mohon Kode Kelompok Urusan untuk dipilih',         
                'Kd_Kecamatan.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',
                'Kd_Kecamatan.min'=>'Mohon Kode Urusan untuk di isi minimal 1 digit',

                'Nm_Kecamatan.required'=>'Mohon Nama Urusan untuk di isi karena ini diperlukan',
                'Nm_Kecamatan.min'=>'Mohon Nama Urusan di isi minimal 5 karakter'
            ]
        );
        
        $kecamatan = KecamatanModel::create ([
            'PmKecamatanID'=> uniqid ('uid'),
            'PmKotaID'=>$request->input('PmKotaID'),
            'Kd_Kecamatan'=>$request->input('Kd_Kecamatan'),        
            'Nm_Kecamatan'=>$request->input('Nm_Kecamatan'),
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
            return redirect(route('kecamatan.show',['id'=>$kecamatan->PmKecamatanID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = KecamatanModel::with('kota')->findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.dmaster.kecamatan.show")->with(['page_active'=>'kecamatan',
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
        
        $data = KecamatanModel::findOrFail($id);
        if (!is_null($data) ) 
        {   
            $kota=KotaModel::getDaftarKota(\HelperKegiatan::getTahunPerencanaan(),false,false);        
            $kota['']='';
            return view("pages.$theme.dmaster.kecamatan.edit")->with(['page_active'=>'kecamatan',
                                                                    'kota'=>$kota,
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
        $kecamatan = KecamatanModel::find($id);

        $this->validate($request,
        [
            'PmKotaID'=>'required|not_in:none',
            'Kd_Kecamatan'=>['required',
                        new IgnoreIfDataIsEqualValidation('tmPmKecamatan',
                                                            $kecamatan->Kd_Kecamatan,
                                                            ['PmKotaID', '=', $request->input('PmKotaID')],
                                                            'Kd_Kecamatan'),
                        'min:1',
                        'regex:/^[0-9]+$/'

                    ],   
             
            'Nm_Kecamatan'=>'required|min:5', 
        ],
        [            
            'Kd_Kecamatan.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',
            'Kd_Kecamatan.min'=>'Mohon Kode Urusan untuk di isi minimal 1 digit',
            'Kd_Kecamatan.max'=>'Mohon Kode Urusan untuk di isi maksimal 4 digit',
            
            'Kd_Kecamatan.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',

            'PmKotaID.required'=>'Mohon Kode Kelompok Urusan untuk dipilih',

            'Nm_Kecamatan.required'=>'Mohon Nama Urusan untuk di isi karena ini diperlukan',
            'Nm_Kecamatan.min'=>'Mohon Nama Urusan di isi minimal 5 karakter'
        ]
        );

        $kecamatan->PmKotaID = $request->input('PmKotaID');
        $kecamatan->Kd_Kecamatan = $request->input('Kd_Kecamatan');
        $kecamatan->Nm_Kecamatan = $request->input('Nm_Kecamatan');
        $kecamatan->Descr = $request->input('Descr');
        
        $kecamatan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('kecamatan.show',['id'=>$kecamatan->PmKecamatanID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $kecamatan = KecamatanModel::find($id);        
        $result=$kecamatan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('kecamatan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.kecamatan.datatable")->with(['page_active'=>'kecamatan',
                                                                            'search'=>$this->getControllerStateSession('kecamatan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('kecamatan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('kecamatan.orderby','order'),
                                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('kecamatan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}