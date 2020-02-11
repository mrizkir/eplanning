<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\ProgramModel;
use App\Models\DMaster\ProgramKegiatanModel;
use App\Models\DMaster\SubKegiatanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class SubKegiatanController extends Controller {
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
        if (!$this->checkStateIsExistSession('subkegiatan','orderby')) 
        {            
           $this->putControllerStateSession('subkegiatan','orderby',['column_name'=>'kode_subkegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('subkegiatan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('subkegiatan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        

        //filter
        if (!$this->checkStateIsExistSession('subkegiatan','filters')) 
        {            
            $this->putControllerStateSession('subkegiatan','filters',['PrgID'=>'none']);
        }
        $filter_prgid=$this->getControllerStateSession('subkegiatan.filters','PrgID'); 
        if ($this->checkStateIsExistSession('subkegiatan','search')) 
        {
            $search=$this->getControllerStateSession('subkegiatan','search');
            switch ($search['kriteria']) 
            {
                case 'kode_subkegiatan' :
                    $data = \DB::table('v_sub_kegiatan')
                            ->select(\DB::raw('"SubKgtID","KgtID","PrgID","kode_subkegiatan","KgtNm","SubKgtNm","PrgNm","TA","created_at","updated_at"'))
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->where(['kode_subkegiatan'=>$search['isikriteria']])
                            ->orderByRaw('"Kd_Urusan" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_Bidang" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_Prog" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_Keg" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_SubKeg" ASC NULLS FIRST');
                break;
                case 'SubKgtNm' :
                    $data = \DB::table('v_sub_kegiatan')
                            ->select(\DB::raw('"SubKgtID","KgtID","PrgID","kode_subkegiatan","KgtNm","SubKgtNm","PrgNm","TA","created_at","updated_at"'))
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->where('SubKgtNm', 'ilike', '%' . $search['isikriteria'] . '%')
                            ->orderByRaw('"Kd_Urusan" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_Bidang" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_Prog" ASC NULLS FIRST')
                            ->orderByRaw('"Kd_Keg" ASC NULLS FIRST')     
                            ->orderByRaw('"Kd_SubKeg" ASC NULLS FIRST');     
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data =$filter_prgid == 'none' ? 
                                            \DB::table('v_sub_kegiatan')
                                                    ->select(\DB::raw('"SubKgtID","KgtID","PrgID","kode_subkegiatan","KgtNm","SubKgtNm","PrgNm","TA","created_at","updated_at"'))
                                                    ->orderByRaw('"Kd_Urusan" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_Bidang" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_Prog" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_Keg" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_SubKeg" ASC NULLS FIRST')
                                                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage)
                                            :
                                            \DB::table('v_sub_kegiatan')
                                                    ->select(\DB::raw('"SubKgtID","KgtID","PrgID","kode_subkegiatan","KgtNm","SubKgtNm","PrgNm","TA","created_at","updated_at"'))
                                                    ->orderByRaw('"Kd_Urusan" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_Bidang" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_Prog" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_Keg" ASC NULLS FIRST')
                                                    ->orderByRaw('"Kd_SubKeg" ASC NULLS FIRST')
                                                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('PrgID',$filter_prgid)                                                
                                                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
        }        
        
        $data->setPath(route('subkegiatan.index'));
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
        $daftar_program=ProgramModel::getDaftarProgram(\HelperKegiatan::getTahunPerencanaan());
        $daftar_program['none']='SELURUH PROGRAM';
        $filter_kode_program_selected=ProgramModel::getKodeProgramByPrgID($this->getControllerStateSession('subkegiatan.filters','PrgID'));
        
        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('subkegiatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.subkegiatan.datatable")->with(['page_active'=>'subkegiatan',
                                                                                'search'=>$this->getControllerStateSession('subkegiatan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('subkegiatan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('subkegiatan.orderby','order'),
                                                                                'daftar_program'=>$daftar_program,
                                                                                'filter_prgid_selected'=>$this->getControllerStateSession('subkegiatan.filters','PrgID'), 
                                                                                'filter_kode_program_selected'=>$filter_kode_program_selected,
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
        $daftar_program=ProgramModel::getDaftarProgram(\HelperKegiatan::getTahunPerencanaan());
        $daftar_program['none']='SELURUH PROGRAM';
        $filter_kode_program_selected=ProgramModel::getKodeProgramByPrgID($this->getControllerStateSession('subkegiatan.filters','PrgID'));

        $this->setCurrentPageInsideSession('subkegiatan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.subkegiatan.datatable")->with(['page_active'=>'subkegiatan',
                                                                            'search'=>$this->getControllerStateSession('subkegiatan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('subkegiatan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('subkegiatan.orderby','order'),
                                                                            'daftar_program'=>$daftar_program,
                                                                            'filter_prgid_selected'=>$this->getControllerStateSession('subkegiatan.filters','PrgID'), 
                                                                            'filter_kode_program_selected'=>$filter_kode_program_selected,
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
        $daftar_program=ProgramModel::getDaftarProgram(\HelperKegiatan::getTahunPerencanaan());
        $daftar_program['none']='SELURUH PROGRAM';
        $filter_kode_program_selected=ProgramModel::getKodeProgramByPrgID($this->getControllerStateSession('subkegiatan.filters','PrgID'));

        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('subkegiatan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('subkegiatan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('subkegiatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.subkegiatan.datatable")->with(['page_active'=>'subkegiatan',                                                            
                                                                                    'search'=>$this->getControllerStateSession('subkegiatan','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('subkegiatan.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('subkegiatan.orderby','order'),
                                                                                    'daftar_program'=>$daftar_program,
                                                                                    'filter_prgid_selected'=>$this->getControllerStateSession('subkegiatan.filters','PrgID'), 
                                                                                    'filter_kode_program_selected'=>$filter_kode_program_selected,
                                                                                    'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) 
    {
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $json_data = [];
        
        //index
        if ($request->exists('PrgID') && $request->exists('index'))
        {
            $PrgID = $request->input('PrgID')==''?'none':$request->input('PrgID');
            $filters['PrgID']=$PrgID;
            $this->putControllerStateSession('subkegiatan','filters',$filters);
            $this->setCurrentPageInsideSession('subkegiatan',1);

            $data = $this->populateData();            
            $filter_kode_program_selected=ProgramModel::getKodeProgramByPrgID($this->getControllerStateSession('subkegiatan.filters','PrgID'));
            $datatable = view("pages.$theme.dmaster.subkegiatan.datatable")->with(['page_active'=>'subkegiatan',   
                                                                                'search'=>$this->getControllerStateSession('subkegiatan','search'),                                                                                
                                                                                'filter_prgid_selected'=>$this->getControllerStateSession('subkegiatan.filters','PrgID'), 
                                                                                'filter_kode_program_selected'=>$filter_kode_program_selected,
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                'data'=>$data])->render();                                                                                       
                        
            
            $json_data = ['success'=>true,'datatable'=>$datatable];            
        }
        //create
        if ($request->exists('KgtID') && $request->exists('create'))
        {
            $KgtID = $request->input('KgtID');
            $Kd_SubKeg = SubKegiatanModel::where('KgtID',$KgtID)->count('Kd_SubKeg')+1;
            $json_data = ['success'=>true,'Kd_SubKeg'=>$Kd_SubKeg];
        }
        return response()->json($json_data,200);  
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;
        $daftar_program=ProgramModel::getDaftarProgram(\HelperKegiatan::getRPJMDTahunMulai());
        $daftar_program['none']='SELURUH PROGRAM';

        $search=$this->getControllerStateSession('subkegiatan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('subkegiatan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('subkegiatan',$data->currentPage());
        $filter_kode_program_selected=ProgramModel::getKodeProgramByPrgID($this->getControllerStateSession('subkegiatan.filters','PrgID'));

        return view("pages.$theme.dmaster.subkegiatan.index")->with(['page_active'=>'subkegiatan',
                                                                        'search'=>$this->getControllerStateSession('subkegiatan','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                        'column_order'=>$this->getControllerStateSession('subkegiatan.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('subkegiatan.orderby','order'),
                                                                        'daftar_program'=>$daftar_program,
                                                                        'filter_prgid_selected'=>$this->getControllerStateSession('subkegiatan.filters','PrgID'), 
                                                                        'filter_kode_program_selected'=>$filter_kode_program_selected,
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
        $daftar_kegiatan=ProgramKegiatanModel::getDaftarKegiatan(\HelperKegiatan::getTahunPerencanaan(),false);
        $daftar_kegiatan['']='';
        return view("pages.$theme.dmaster.subkegiatan.create")->with(['page_active'=>'subkegiatan',
                                                                          'daftar_kegiatan'=>$daftar_kegiatan
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
            'Kd_SubKeg'=>[new CheckRecordIsExistValidation('tmSubKgt',['where'=>['KgtID','=',$request->input('KgtID')]]),
                            'required',
                            'min:1',
                            'max:4',
                            'regex:/^[0-9]+$/'
                        ],         
            'KgtID'=>'required',
            'SubKgtNm'=>'required|min:5',
        ]);
        
        $subkegiatan = SubKegiatanModel::create([
            'SubKgtID'=> uniqid ('uid'),
            'KgtID' => $request->input('KgtID'),
            'Kd_SubKeg' => $request->input('Kd_SubKeg'),
            'SubKgtNm' => $request->input('SubKgtNm'),
            'Descr' => $request->input('Descr'),
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
            return redirect(route('subkegiatan.show',['uuid'=>$subkegiatan->SubKgtID]))->with('success','Data ini telah berhasil disimpan.');
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
        $data = \DB::table('v_sub_kegiatan')
                    ->select(\DB::raw('"SubKgtID","Kd_Urusan","Nm_Urusan","Kd_Bidang","Nm_Bidang","kode_program","PrgNm","kode_kegiatan","KgtNm","kode_subkegiatan","SubKgtNm","Descr","TA","created_at","updated_at"'))
                    ->where('SubKgtID',$id)
                    ->get();   
                    
        if (count($data) > 0)  
        {
            $data = $data[0];
            return view("pages.$theme.dmaster.subkegiatan.show")->with(['page_active'=>'subkegiatan',
                                                                            'data'=>$data
                                                                        ]);
        }  
        else
        {
            return view("pages.$theme.dmaster.subkegiatan.error")->with(['page_active'=>'subkegiatan',
                                                                            'errormessage'=>"ID Kegiatan ($id) tidak ditemukan di database"
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
        
        $data = SubKegiatanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_kegiatan=ProgramKegiatanModel::getDaftarKegiatan(\HelperKegiatan::getTahunPerencanaan(),false);
            $daftar_kegiatan['']='';
            return view("pages.$theme.dmaster.subkegiatan.edit")->with(['page_active'=>'subkegiatan',
                                                                                'daftar_kegiatan'=>$daftar_kegiatan,
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
        $subkegiatan = SubKegiatanModel::find($id);

        $this->validate($request, [            
            'Kd_SubKeg'=>[new IgnoreIfDataIsEqualValidation('tmSubKgt',$subkegiatan->Kd_SubKeg,['where'=>['KgtID','=',$subkegiatan->KgtID]]),
                            'required',
                            'min:1',
                            'max:4',
                            'regex:/^[0-9]+$/'
                        ],   
            'KgtID'=>'required',
            'SubKgtNm'=>'required|min:5',
        ]);        
        
        $subkegiatan->KgtID = $request->input('KgtID');
        $subkegiatan->Kd_SubKeg = $request->input('Kd_SubKeg');
        $subkegiatan->SubKgtNm = $request->input('SubKgtNm');
        $subkegiatan->Descr = $request->input('Descr');
        $subkegiatan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('subkegiatan.show',['uuid'=>$subkegiatan->SubKgtID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $subkegiatan = SubKegiatanModel::find($id);
        $result=$subkegiatan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('subkegiatan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.subkegiatan.datatable")->with(['page_active'=>'subkegiatan',
                                                                                        'search'=>$this->getControllerStateSession('subkegiatan','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                        'column_order'=>$this->getControllerStateSession('subkegiatan.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('subkegiatan.orderby','order'),
                                                                                        'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('subkegiatan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}