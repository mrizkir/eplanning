<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\UrusanModel;
use App\Models\DMaster\OrganisasiModel;
use App\Models\DMaster\MappingProgramToOPDModel;

class MappingProgramToOPDController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('mappingprogramtoopd','orderby')) 
        {            
           $this->putControllerStateSession('mappingprogramtoopd','orderby',['column_name'=>'OrgID','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'); 
        $direction=$this->getControllerStateSession('mappingprogramtoopd.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('mappingprogramtoopd','search')) 
        {
            $search=$this->getControllerStateSession('mappingprogramtoopd','search');
            switch ($search['kriteria']) 
            {
                case 'kode_program' :
                    $data = MappingProgramToOPDModel::where(['OrgID'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                    $data = \DB::table('v_organisasi_program')
                            ->select(\DB::raw('
                                "v_organisasi_program"."orgProgramID",
                                CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"tmOrg"."OrgCd") AS kode_organisasi_all_urusan,
                                "v_organisasi_program"."OrgNm",
                                CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"v_organisasi_program"."Kd_Prog") AS kode_program_all_urusan,
                                "v_organisasi_program"."kode_program",
                                "v_organisasi_program"."PrgNm",
                                "v_organisasi_program"."Nm_Urusan",
                                "v_organisasi_program"."Jns"
                            '))
                            ->join ('tmOrg','v_organisasi_program.OrgID','tmOrg.OrgID')
                            ->join ('tmUrs','tmOrg.UrsID','tmUrs.UrsID')
                            ->join ('tmKUrs','tmUrs.KUrsID','tmKUrs.KUrsID')
                            ->where('v_organisasi_program.TA',config('eplanning.tahun_perencanaan'))
                            ->where(['OrgID'=>$search['isikriteria']])
                            ->orderBy("v_organisasi_program.$column_order",$direction);
                break;
                case 'PrgNm' :
                    $data = MappingProgramToOPDModel::where('PrgNm', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                    $data = \DB::table('v_organisasi_program')
                            ->select(\DB::raw('
                                "v_organisasi_program"."orgProgramID",
                                CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"tmOrg"."OrgCd") AS kode_organisasi_all_urusan,
                                "v_organisasi_program"."OrgNm",
                                CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"v_organisasi_program"."Kd_Prog") AS kode_program_all_urusan,
                                "v_organisasi_program"."kode_program",
                                "v_organisasi_program"."PrgNm",
                                "v_organisasi_program"."Nm_Urusan",
                                "v_organisasi_program"."Jns"
                            '))
                            ->join ('tmOrg','v_organisasi_program.OrgID','tmOrg.OrgID')
                            ->join ('tmUrs','tmOrg.UrsID','tmUrs.UrsID')
                            ->join ('tmKUrs','tmUrs.KUrsID','tmKUrs.KUrsID')
                            ->where('v_organisasi_program.TA',config('eplanning.tahun_perencanaan'))
                            ->where('PrgNm', 'ilike', '%' . $search['isikriteria'] . '%')                                        
                            ->orderBy("v_organisasi_program.$column_order",$direction);
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_organisasi_program')
                    ->select(\DB::raw('
                        "v_organisasi_program"."orgProgramID",
                        CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"tmOrg"."OrgCd") AS kode_organisasi_all_urusan,
                        "v_organisasi_program"."OrgNm",
                        CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"v_organisasi_program"."Kd_Prog") AS kode_program_all_urusan,
                        "v_organisasi_program"."kode_program",
                        "v_organisasi_program"."PrgNm",
                        "v_organisasi_program"."Nm_Urusan",
                        "v_organisasi_program"."Jns"
                    '))
                    ->join ('tmOrg','v_organisasi_program.OrgID','tmOrg.OrgID')
                    ->join ('tmUrs','tmOrg.UrsID','tmUrs.UrsID')
                    ->join ('tmKUrs','tmUrs.KUrsID','tmKUrs.KUrsID')
                    ->where('v_organisasi_program.TA',config('eplanning.tahun_perencanaan'))
                    ->orderBy("v_organisasi_program.$column_order",$direction)
                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
        }        
        $data->setPath(route('mappingprogramtoopd.index'));
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
        
        $this->setCurrentPageInsideSession('mappingprogramtoopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                                                'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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
            case 'OrgID' :
                $column_name = 'OrgID';
            break; 
            case 'NmOrg' :
                $column_name = 'NmOrg';
            break;  
            case 'Kode_Program' :
                $column_name = 'kode_program';
            break; 
            case 'PrgNm' :
                $column_name = 'PrgNm';
            break; 
            case 'Nm_Urusan' :
                $column_name = 'Nm_Urusan';
            break;          
            default :
                $column_name = 'OrgID';
        }
        $this->putControllerStateSession('mappingprogramtoopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('mappingprogramtoopd'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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

        $this->setCurrentPageInsideSession('mappingprogramtoopd',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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
            $this->destroyControllerStateSession('mappingprogramtoopd','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('mappingprogramtoopd','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('mappingprogramtoopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',                                                            
                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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

        $search=$this->getControllerStateSession('mappingprogramtoopd','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('mappingprogramtoopd'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('mappingprogramtoopd',$data->currentPage());
        
        return view("pages.$theme.dmaster.mappingprogramtoopd.index")->with(['page_active'=>'mappingprogramtoopd',
                                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
                                                                            'data'=>$data]);               
    }

    /**
     * digunakan untuk mengganti jumlah record per halaman
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changenumberrecordperpagecreate (Request $request) 
    {
        $theme = \Auth::user()->theme;
        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'));
        $daftar_urusan['none']='SELURUH URUSAN';    
        $filter_kode_urusan_selected=UrusanModel::getKodeUrusanByUrsID($this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'));

        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('mappingprogramtoopd',1);
        
        $data=$this->populateDataProgram();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatableprogram")->with(['page_active'=>'mappingprogramtoopd',
                                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','column_name'),
                                                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','order'),
                                                                                            'daftar_urusan'=>$daftar_urusan,
                                                                                            'filter_ursid_selected'=>$this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'), 
                                                                                            'filter_kode_urusan_selected'=>$filter_kode_urusan_selected,
                                                                                            'data'=>$data
                                                                                            ])->render();  

        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * paginate resource in storage called by ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paginatecreate ($id) 
    {
        $theme = \Auth::user()->theme;
        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'));
        $daftar_urusan['none']='SELURUH URUSAN';    
        $filter_kode_urusan_selected=UrusanModel::getKodeUrusanByUrsID($this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'));

        $this->setCurrentPageInsideSession('mappingprogramtoopd',$id);
        $data=$this->populateDataProgram($id);
        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatableprogram")->with(['page_active'=>'mappingprogramtoopd',                                                                            
                                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','column_name'),
                                                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','order'),
                                                                                            'daftar_urusan'=>$daftar_urusan,
                                                                                            'filter_ursid_selected'=>$this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'), 
                                                                                            'filter_kode_urusan_selected'=>$filter_kode_urusan_selected,
                                                                                            'data'=>$data])->render(); 

        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * filter create resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtercreate (Request $request) 
    {
        $theme = \Auth::user()->theme;
        
        $filter_ursid=$this->getControllerStateSession('mappingprogramtoopd.filters','UrsID');
        $filter_orgid=$this->getControllerStateSession('mappingprogramtoopd.filters','OrgID');

        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID');
            $ursid = UrusanModel::getKodeUrusanByOrgID($OrgID);
            $this->putControllerStateSession('mappingprogramtoopd','filters',['OrgID'=>$OrgID,
                                                                            'UrsID'=>$ursid]);     
            
            
        }

        if ($request->exists('UrsID'))
        {
            $UrsID = $request->input('UrsID');
            $this->putControllerStateSession('mappingprogramtoopd','filters',['OrgID'=>$filter_orgid,
                                                                            'UrsID'=>$UrsID]);         
        }        

        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'));
        $daftar_urusan['none']='SELURUH URUSAN';
        $filter_kode_urusan_selected=UrusanModel::getKodeUrusanByUrsID($this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'));

        $this->setCurrentPageInsideSession('mappingprogramtoopd',1);

        $data=$this->populateDataProgram();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatableprogram")->with(['page_active'=>'mappingprogramtoopd',                                                            
                                                                                'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','order'),
                                                                                'daftar_urusan'=>$daftar_urusan,
                                                                                'filter_ursid_selected'=>$this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'), 
                                                                                'filter_kode_urusan_selected'=>$filter_kode_urusan_selected,
                                                                                'data'=>$data])->render();      

        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * digunakan untuk mendapatkan daftar program
     */
    public function populateDataProgram ($currentpage=1)
    {
        $data=[];
              
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('mappingprogramtoopd','orderbycreate')) 
        {            
        $this->putControllerStateSession('mappingprogramtoopd','orderbycreate',['column_name'=>'kode_program','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','column_name'); 
        $direction=$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('mappingprogramtoopd','filters')) 
        {            
            $this->putControllerStateSession('mappingprogramtoopd','filters',['UrsID'=>'none',
                                                                            'OrgID'=>'']);
        }        
        $filter_orgid=$this->getControllerStateSession('mappingprogramtoopd.filters','OrgID');
        
        if ($filter_orgid != 'none' && $filter_orgid !='' && !(is_array($filter_orgid)))
        {
            $filter_ursid=$this->getControllerStateSession('mappingprogramtoopd.filters','UrsID');               
            $data =$filter_ursid == 'none' ? 
                                                \DB::table('v_urusan_program')
                                                            ->WhereNotIn('PrgID',function($query){
                                                                $filter_orgid=$this->getControllerStateSession('mappingprogramtoopd.filters','OrgID');
                                                                $query->select('PrgID')
                                                                    ->from('trOrgProgram')
                                                                    ->where('OrgID', $filter_orgid)
                                                                    ->where('TA',config('eplanning.tahun_perencanaan'));
                                                            })
                                                            ->where('TA',config('eplanning.tahun_perencanaan'))
                                                            ->orderBy($column_order,$direction)                                                        
                                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage)
                                                :
                                                \DB::table('v_urusan_program')
                                                            ->WhereNotIn('PrgID',function($query){
                                                                $filter_orgid=$this->getControllerStateSession('mappingprogramtoopd.filters','OrgID');
                                                                $query->select('PrgID')
                                                                    ->from('trOrgProgram')
                                                                    ->where('OrgID', $filter_orgid)
                                                                    ->where('TA',config('eplanning.tahun_perencanaan'));
                                                            })
                                                            ->where('TA',config('eplanning.tahun_perencanaan'))                                                            
                                                            ->where('UrsID',$filter_ursid)
                                                            ->orWhereNull('UrsID')
                                                            ->orderBy($column_order,$direction)                                                        
                                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
            
            $data->setPath(route('mappingprogramtoopd.create'));            
        }        
        return $data;

    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        $theme = \Auth::user()->theme;
        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'));
        $daftar_urusan['none']='SELURUH URUSAN';           

        $daftar_opd=OrganisasiModel::getDaftarOPD(config('eplanning.tahun_perencanaan'),false);

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('mappingprogramtoopd'); 
        $data = $this->populateDataProgram($currentpage);

        if (method_exists($data,'lastPage'))
        {
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateDataProgram($data->lastPage());
            }
            $this->setCurrentPageInsideSession('mappingprogramtoopd',$data->currentPage());
        }
        $filter_kode_urusan_selected=UrusanModel::getKodeUrusanByUrsID($this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'));
        return view("pages.$theme.dmaster.mappingprogramtoopd.create")->with(['page_active'=>'mappingprogramtoopd',
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderbycreate','order'),
                                                                            'filter_orgid_selected'=>$this->getControllerStateSession('mappingprogramtoopd.filters','OrgID'),
                                                                            'daftar_urusan'=>$daftar_urusan,
                                                                            'filter_ursid_selected'=>$this->getControllerStateSession('mappingprogramtoopd.filters','UrsID'), 
                                                                            'filter_kode_urusan_selected'=>$filter_kode_urusan_selected,
                                                                            'daftar_opd'=>$daftar_opd,
                                                                            'data'=>$data
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
            'OrgID'=>'required',
        ]);
        $orgid=$request->input('OrgID');
        $prgid=$request->exists('chkprgid')?$request->input('chkprgid'):[];
        
        if (count($prgid) > 0)
        {
            $now = \Carbon\Carbon::now('utc')->toDateTimeString();
            foreach ($prgid as $v)
            {
                $data[] = ['orgProgramID'=>uniqid ('uid'),'OrgID'=>$orgid,'PrgID'=>$v,'Descr'=>'-','TA'=>config('eplanning.tahun_perencanaan'),'created_at'=>$now,'updated_at'=>$now];
            }
            MappingProgramToOPDModel::insert($data);        
        
            if ($request->ajax()) 
            {
                return response()->json([
                    'success'=>true,
                    'message'=>'Data ini telah berhasil disimpan.'
                ]);
            }
            else
            {
                return redirect(route('mappingprogramtoopd.index'))->with('success','Data ini telah berhasil disimpan.');
            }
        }
        else
        {            
            $theme = \Auth::user()->theme;
            return view("pages.$theme.dmaster.mappingprogramtoopd.error")->with(['page_active'=>'mappingprogramtoopd',
                                                                                'errormessage'=>'Gagal melakukan mapping program ke OPD karena program tidak dipilih.'
                                                                            ]);  
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

        $data = MappingProgramToOPDModel::join('v_organisasi_program','v_organisasi_program.OrgID','trOrgProgram.OrgID')
                                        ->join ('tmOrg','v_organisasi_program.OrgID','tmOrg.OrgID')
                                        ->join ('tmUrs','tmOrg.UrsID','tmUrs.UrsID')
                                        ->join ('tmKUrs','tmUrs.KUrsID','tmKUrs.KUrsID')
                                        ->select(\DB::raw('
                                            "v_organisasi_program"."orgProgramID",
                                            CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"tmOrg"."OrgCd") AS kode_organisasi_all_urusan,
                                            "v_organisasi_program"."OrgNm",
                                            CONCAT("tmKUrs"."Kd_Urusan",\'.\',"tmUrs"."Kd_Bidang",\'.\',"v_organisasi_program"."Kd_Prog") AS kode_program_all_urusan,
                                            "v_organisasi_program"."kode_program",
                                            "v_organisasi_program"."PrgNm",
                                            "v_organisasi_program"."Nm_Urusan",
                                            "v_organisasi_program"."Jns"
                                        '))
                                        ->where('v_organisasi_program.TA',config('eplanning.tahun_perencanaan'))
                                        ->where('v_organisasi_program.orgProgramID',$id)
                                        ->firstOrFail();
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.mappingprogramtoopd.show")->with(['page_active'=>'mappingprogramtoopd',
                                                    'data'=>$data
                                                    ]);
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
        
        $mappingprogramtoopd = MappingProgramToOPDModel::find($id);
        $result=$mappingprogramtoopd->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('mappingprogramtoopd'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('mappingprogramtoopd.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}