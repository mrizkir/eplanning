<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRATujuanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRATujuanController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|opd']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('renstratujuan','orderby')) 
        {            
           $this->putControllerStateSession('renstratujuan','orderby',['column_name'=>'Nm_RenstraTujuan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstratujuan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstratujuan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        

        //filter
        if (!$this->checkStateIsExistSession('renstratujuan','filters')) 
        {            
            $this->putControllerStateSession('renstratujuan','filters',[
                                                                    'OrgID'=>'none'
                                                                    ]);
        }        
        $OrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgID');        
        
        if ($this->checkStateIsExistSession('renstratujuan','search')) 
        {
            $search=$this->getControllerStateSession('renstratujuan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_RenstraTujuan' :
                    $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID","tmRenstraTujuan"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan") AS "Kd_RenstraTujuan","tmRenstraTujuan"."Nm_RenstraTujuan","tmRenstraTujuan"."TA"'))
                                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmRenstraTujuan.PrioritasKabID')
                                                ->where('OrgID',$OrgID)
                                                ->where(['Kd_RenstraTujuan'=>$search['isikriteria']])
                                                ->orderBy($column_order,$direction); 
                break;
                case 'Nm_RenstraTujuan' :
                    $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID","tmRenstraTujuan"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan") AS "Kd_RenstraTujuan","tmRenstraTujuan"."Nm_RenstraTujuan","tmRenstraTujuan"."TA"'))
                                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmRenstraTujuan.PrioritasKabID')
                                                ->where('OrgID',$OrgID)
                                                ->where('Nm_RenstraTujuan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID","tmRenstraTujuan"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan") AS "Kd_RenstraTujuan","tmRenstraTujuan"."Nm_RenstraTujuan","tmRenstraTujuan"."TA"'))
                                        ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmRenstraTujuan.PrioritasKabID')
                                        ->where('OrgID',$OrgID)
                                        ->orderBy($column_order,$direction)
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstratujuan.index'));
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
        
        $this->setCurrentPageInsideSession('renstratujuan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                                                'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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
            case 'col-Kd_RenstraTujuan' :
                $column_name = 'Kd_RenstraTujuan';
            break;      
            case 'col-Nm_RenstraTujuan' :
                $column_name = 'Nm_RenstraTujuan';
            break;        
            default :
                $column_name = 'Nm_RenstraTujuan';
        }
        $this->putControllerStateSession('renstratujuan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstratujuan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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
            $this->destroyControllerStateSession('renstratujuan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstratujuan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstratujuan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',                                                            
                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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

        $filters=$this->getControllerStateSession('renstratujuan','filters');       
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;            
            $this->putControllerStateSession('renstratujuan','filters',$filters);
            
            $data = $this->populateData();

            $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',                                                                               
                                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                            'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                            'data'=>$data])->render();

            
            $json_data = ['success'=>true,'datatable'=>$datatable];
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
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('renstratujuan','filters');
        $roles=$auth->getRoleNames();   
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :     
            case 'tapd' :     
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);   
            break;
            case 'opd' :               
                $daftar_opd=\App\Models\UserOPD::getOPD();                      
                if (!(count($daftar_opd) > 0))
                {  
                    return view("pages.$theme.renstra.renstratujuan.error")->with(['page_active'=>'renstratujuan', 
                                                                                    'page_title'=>'RENSTRA TUJUAN',
                                                                                    'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                ]);
                }          
            break;
        }
        $search=$this->getControllerStateSession('renstratujuan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstratujuan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstratujuan',$data->currentPage());
        
        return view("pages.$theme.renstra.renstratujuan.index")->with(['page_active'=>'renstratujuan',
                                                                    'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                                    'filters'=>$filters,
                                                                    'daftar_opd'=>$daftar_opd,
                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                    'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                                    'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
                                                                    'data'=>$data]);               
    }
    public function getkodetujuan($id)
    {
        $Kd_RenstraTujuan = RENSTRATujuanModel::where('PrioritasKabID',$id)->count('Kd_RenstraTujuan')+1;
        return response()->json(['success'=>true,'Kd_RenstraTujuan'=>$Kd_RenstraTujuan],200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $filters=$this->getControllerStateSession('renstratujuan','filters');  
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {
            $daftar_misi=\App\Models\RPJMD\RPJMDMisiModel::select(\DB::raw('"PrioritasKabID",CONCAT(\'[\',"Kd_PrioritasKab",\']. \',"Nm_PrioritasKab") AS "Nm_PrioritasKab"'))
                                                            ->where('TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('Kd_PrioritasKab','ASC')
                                                            ->get()
                                                            ->pluck('Nm_PrioritasKab','PrioritasKabID')
                                                            ->toArray();

            
            return view("pages.$theme.renstra.renstratujuan.create")->with(['page_active'=>'renstratujuan',
                                                                        'daftar_misi'=>$daftar_misi
                                                                        ]);  
        }
        else
        {
            return view("pages.$theme.renstra.renstratujuan.error")->with(['page_active'=>'renstratujuan',
                                                                        'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu.'
                                                                    ]);  
        }
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
            'Kd_RenstraTujuan'=>[new CheckRecordIsExistValidation('tmRenstraTujuan',['where'=>['TA','=',\HelperKegiatan::getRENSTRATahunMulai()]]),
                            'required'
                        ],
            'PrioritasKabID'=>'required',
            'Nm_RenstraTujuan'=>'required',
        ]);
        
        $renstratujuan = RENSTRATujuanModel::create([
            'RenstraTujuanID'=> uniqid ('uid'),
            'PrioritasKabID' => $request->input('PrioritasKabID'),
            'OrgID' => $this->getControllerStateSession('renstratujuan','filters.OrgID'),
            'Kd_RenstraTujuan' => $request->input('Kd_RenstraTujuan'),
            'Nm_RenstraTujuan' => $request->input('Nm_RenstraTujuan'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getRENSTRATahunMulai()
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
            return redirect(route('renstratujuan.show',['id'=>$renstratujuan->RenstraTujuanID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID",
                                                    "tmPrioritasKab"."Kd_PrioritasKab",
                                                    "tmPrioritasKab"."Nm_PrioritasKab",
                                                    "tmRenstraTujuan"."Kd_RenstraTujuan",
                                                    "tmRenstraTujuan"."Nm_RenstraTujuan",
                                                    "tmRenstraTujuan"."Descr",
                                                    "tmRenstraTujuan"."TA",
                                                    "tmRenstraTujuan"."created_at",
                                                    "tmRenstraTujuan"."updated_at"'))
                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmRenstraTujuan.PrioritasKabID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstratujuan.show")->with(['page_active'=>'renstratujuan',
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
        
        $data = RENSTRATujuanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_misi=\App\Models\RPJMD\RPJMDMisiModel::select(\DB::raw('"PrioritasKabID",CONCAT(\'[\',"Kd_PrioritasKab",\']. \',"Nm_PrioritasKab") AS "Nm_PrioritasKab"'))
                                                            ->where('TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('Kd_PrioritasKab','ASC')
                                                            ->get()
                                                            ->pluck('Nm_PrioritasKab','PrioritasKabID')
                                                            ->toArray();

            return view("pages.$theme.renstra.renstratujuan.edit")->with(['page_active'=>'renstratujuan',
                                                                        'daftar_misi'=>$daftar_misi,
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
        $renstratujuan = RENSTRATujuanModel::find($id);
        
        $this->validate($request, [
            'Kd_RenstraTujuan'=>['required',new IgnoreIfDataIsEqualValidation('tmRenstraTujuan',
                                                                        $renstratujuan->Kd_RenstraTujuan,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getRENSTRATahunMulai()]],
                                                                        'Kode Tujuan')],
            'PrioritasKabID'=>'required',
            'Nm_RenstraTujuan'=>'required',
        ]);
               
        $renstratujuan->PrioritasKabID = $request->input('PrioritasKabID');
        $renstratujuan->Kd_RenstraTujuan = $request->input('Kd_RenstraTujuan');
        $renstratujuan->Nm_RenstraTujuan = $request->input('Nm_RenstraTujuan');
        $renstratujuan->Descr = $request->input('Descr');
        $renstratujuan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstratujuan.show',['id'=>$renstratujuan->RenstraTujuanID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstratujuan = RENSTRATujuanModel::find($id);
        $result=$renstratujuan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstratujuan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstratujuan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
