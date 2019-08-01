<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\KelompokUrusanModel;
use App\Models\DMaster\UrusanModel;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Helpers\SQL;

class UrusanController extends Controller {
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
        $columns=['tmUrs.UrsID','v_urusan.Nm_Urusan','Kode_Bidang','tmUrs.Nm_Bidang','tmUrs.Descr'];       
        if (!$this->checkStateIsExistSession('urusan','orderby')) 
        {            
           $this->putControllerStateSession('urusan','orderby',['column_name'=>'Kode_Bidang','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('urusan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('urusan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('urusan','search')) 
        {
            $search=$this->getControllerStateSession('urusan','search');
            switch ($search['kriteria']) 
            {
                case 'Kode_Bidang' :
                    $data = UrusanModel::join('v_urusan','v_urusan.UrsID','tmUrs.UrsID')
                                        ->where('tmUrs.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                        ->where(['Kode_Bidang'=>$search['isikriteria']])
                                        ->orderBy($column_order,$direction); 
                break;
                case 'Nm_Bidang' :
                    $data = UrusanModel::join('v_urusan','v_urusan.UrsID','tmUrs.UrsID')
                                        ->where('tmUrs.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                        ->where('tmUrs.Nm_Bidang', SQL::like(), '%' . $search['isikriteria'] . '%')
                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = UrusanModel::join('v_urusan','v_urusan.UrsID','tmUrs.UrsID')
                                ->select(\DB::raw('"tmUrs"."UrsID","tmUrs"."KUrsID",v_urusan."Nm_Urusan",v_urusan."Kode_Bidang","tmUrs"."Nm_Bidang","tmUrs"."Descr","tmUrs"."created_at","tmUrs"."updated_at"'))
                                ->where('tmUrs.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }
        $data->setPath(route('urusan.index'));
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
        
        $this->setCurrentPageInsideSession('urusan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                                                'search'=>$this->getControllerStateSession('urusan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
            case 'col-Kd_Bidang' :
                $column_name = 'Kode_Bidang';
            break;  
            case 'col-Nm_Bidang' :
                $column_name = 'Nm_Bidang';
            break;          
            default :
                $column_name = 'Kode_Bidang';
        }
        $this->putControllerStateSession('urusan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('urusan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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

        $this->setCurrentPageInsideSession('urusan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
            $this->destroyControllerStateSession('urusan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('urusan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('urusan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.urusan.datatable")->with(['page_active'=>'urusan',                                                            
                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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

        $search=$this->getControllerStateSession('urusan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('urusan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('urusan',$data->currentPage());
        
        return view("pages.$theme.dmaster.urusan.index")->with(['page_active'=>'urusan',
                                                'search'=>$this->getControllerStateSession('urusan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
        $kelompok_urusan=KelompokUrusanModel::getKelompokUrusan(\HelperKegiatan::getRPJMDTahunMulai());
        return view("pages.$theme.dmaster.urusan.create")->with(['page_active'=>'urusan',
                                                                'kelompok_urusan'=>$kelompok_urusan
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
        $requestData = $request->all();
        $requestData['Kode_Bidang']=$request->input('Kode_Bidang').'.'.$request->input('Kd_Bidang');
        $request->replace($requestData);

        $this->validate($request,
        [
            'Kd_Bidang'=>'required|min:1|max:4|regex:/^[0-9]+$/', 
            'Kode_Bidang'=>['required',new IgnoreIfDataIsEqualValidation('v_urusan',
                                                                        null,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getRPJMDTahunMulai()]                                                                                
                                                                        ],
                                                                        'Kode Urusan')],   
            'KUrsID'=>'required|not_in:none', 
            'Nm_Bidang'=>'required|min:5', 
        ],
        [            
            'Kd_Bidang.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',
            'Kd_Bidang.min'=>'Mohon Kode Urusan untuk di isi minimal 1 digit',
            'Kd_Bidang.max'=>'Mohon Kode Urusan untuk di isi maksimal 4 digit',
            
            'Kode_Bidang.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',

            'KUrsID.required'=>'Mohon Kode Kelompok Urusan untuk dipilih',

            'Nm_Bidang.required'=>'Mohon Nama Urusan untuk di isi karena ini diperlukan',
            'Nm_Bidang.min'=>'Mohon Nama Urusan di isi minimal 5 karakter'
        ]
        );
        
        $urusan = UrusanModel::create ([
            'UrsID'=> uniqid ('uid'),
            'KUrsID'=>$request->input('KUrsID'),
            'Kd_Bidang'=>$request->input('Kd_Bidang'),        
            'Nm_Bidang'=>$request->input('Nm_Bidang'),
            'Descr'=>$request->input('Descr'),
            'TA'=>\HelperKegiatan::getRPJMDTahunMulai(),
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
            return redirect(route('urusan.show',['id'=>$urusan->UrsID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = UrusanModel::with('kelompokurusan')->findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.dmaster.urusan.show")->with(['page_active'=>'urusan',
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
        
        $data = UrusanModel::with('kelompokurusan')->findOrFail($id);
        if (!is_null($data) ) 
        {   
            $kelompok_urusan=KelompokUrusanModel::getKelompokUrusan(\HelperKegiatan::getRPJMDTahunMulai(),false);
            return view("pages.$theme.dmaster.urusan.edit")->with(['page_active'=>'urusan',
                                                                    'kelompok_urusan'=>$kelompok_urusan,
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
        $urusan = UrusanModel::with('kelompokurusan')->find($id);

        $requestData = $request->all();
        $requestData['Kode_Bidang']=$request->input('Kode_Bidang').'.'.$request->input('Kd_Bidang');
        $request->replace($requestData);

        $this->validate($request,
        [
            'Kd_Bidang'=>'required|min:1|max:4|regex:/^[0-9]+$/', 
            'Kode_Bidang'=>['required',new IgnoreIfDataIsEqualValidation('v_urusan',
                                                                        $urusan->kelompokurusan->Kd_Urusan.'.'.$urusan->Kd_Bidang,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getRPJMDTahunMulai()]],
                                                                        'Kode Urusan')],   
            'KUrsID'=>'required|not_in:none', 
            'Nm_Bidang'=>'required|min:5', 
        ],
        [            
            'Kd_Bidang.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',
            'Kd_Bidang.min'=>'Mohon Kode Urusan untuk di isi minimal 1 digit',
            'Kd_Bidang.max'=>'Mohon Kode Urusan untuk di isi maksimal 4 digit',
            
            'Kode_Bidang.required'=>'Mohon Kode Urusan untuk di isi karena ini diperlukan',

            'KUrsID.required'=>'Mohon Kode Kelompok Urusan untuk dipilih',

            'Nm_Bidang.required'=>'Mohon Nama Urusan untuk di isi karena ini diperlukan',
            'Nm_Bidang.min'=>'Mohon Nama Urusan di isi minimal 5 karakter'
        ]
        );

        $urusan->KUrsID = $request->input('KUrsID');
        $urusan->Kd_Bidang = $request->input('Kd_Bidang');
        $urusan->Nm_Bidang = $request->input('Nm_Bidang');
        $urusan->Descr = $request->input('Descr');
        
        $urusan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('urusan.show',['id'=>$urusan->UrsID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $urusan = UrusanModel::find($id);
        \DB::update('UPDATE "tmPrg" SET "Jns"=false FROM "v_urusan_program","tmPrg" AS program WHERE program."PrgID"=v_urusan_program."PrgID" AND v_urusan_program."UrsID"=?',[$urusan->UrsID]);
        $result=$urusan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('urusan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('urusan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}