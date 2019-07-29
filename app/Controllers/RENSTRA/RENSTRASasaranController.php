<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRASasaranModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRASasaranController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstrasasaran','orderby')) 
        {            
           $this->putControllerStateSession('renstrasasaran','orderby',['column_name'=>'Nm_RenstraSasaran','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstrasasaran.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstrasasaran.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        

        //filter
        if (!$this->checkStateIsExistSession('renstrasasaran','filters')) 
        {            
            $this->putControllerStateSession('renstrasasaran','filters',[
                                                                    'OrgID'=>'none'
                                                                    ]);
        }        
        $OrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgID');        

        if ($this->checkStateIsExistSession('renstrasasaran','search')) 
        {
            $search=$this->getControllerStateSession('renstrasasaran','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_RenstraSasaran' :
                    $data = RENSTRASasaranModel::where(['Kd_RenstraSasaran'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_RenstraSasaran' :
                    $data = RENSTRASasaranModel::where('Nm_RenstraSasaran', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->where('OrgID',$OrgID)->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRASasaranModel::where('OrgID',$OrgID)
                                        ->orderBy($column_order,$direction)
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstrasasaran.index'));
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
        
        $this->setCurrentPageInsideSession('renstrasasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                                                'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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
            case 'col-Kd_RenstraSasaran' :
                $column_name = 'Kd_RenstraSasaran';
            break;           
            case 'col-Nm_RenstraSasaran' :
                $column_name = 'Nm_RenstraSasaran';
            break;           
            default :
                $column_name = 'Nm_RenstraSasaran';
        }
        $this->putControllerStateSession('renstrasasaran','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                                                    'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstrasasaran',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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
            $this->destroyControllerStateSession('renstrasasaran','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstrasasaran','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstrasasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',                                                            
                                                                                    'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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

        $filters=$this->getControllerStateSession('renstrasasaran','filters');       
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;            
            $this->putControllerStateSession('renstrasasaran','filters',$filters);
            
            $data = $this->populateData();

            $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',                                                                               
                                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
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

        $filters=$this->getControllerStateSession('renstrasasaran','filters');
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
                    return view("pages.$theme.renstra.renstrasasaran.error")->with(['page_active'=>'renstrasasaran', 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle('renstrasasaran'),
                                                                                    'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                    ]);
                }          
            break;
        }
        $search=$this->getControllerStateSession('renstrasasaran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstrasasaran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstrasasaran',$data->currentPage());
        
        return view("pages.$theme.renstra.renstrasasaran.index")->with(['page_active'=>'renstrasasaran',
                                                                        'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                        'filters'=>$filters,
                                                                        'daftar_opd'=>$daftar_opd,
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                        'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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
        $filters=$this->getControllerStateSession('renstrasasaran','filters');  
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {
            $daftar_tujuan=\App\Models\RENSTRA\RENSTRATujuanModel::select(\DB::raw('"RenstraTujuanID",CONCAT(\'[\',"Kd_RenstraTujuan",\']. \',"Nm_RenstraTujuan") AS "Nm_RenstraTujuan"'))
                                                                ->where('TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                ->orderBy('Kd_RenstraTujuan','ASC')
                                                                ->get()
                                                                ->pluck('Nm_RenstraTujuan','RenstraTujuanID')
                                                                ->toArray();

            return view("pages.$theme.renstra.renstrasasaran.create")->with(['page_active'=>'renstrasasaran',
                                                                        'daftar_tujuan'=>$daftar_tujuan
                                                                        ]);  
        }
        else
        {
            return view("pages.$theme.renstra.renstrasasaran.error")->with(['page_active'=>'renstrasasaran',
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
            'Kd_RenstraSasaran'=>[new CheckRecordIsExistValidation('tmRenstraSasaran',['where'=>['TA','=',\HelperKegiatan::getRENSTRATahunMulai()]]),
                            'required'
                        ],
            'RenstraTujuanID'=>'required',
            'Nm_RenstraSasaran'=>'required',
        ]);
        
        $renstrasasaran = RENSTRASasaranModel::create([
            'RenstraSasaranID'=> uniqid ('uid'),
            'RenstraTujuanID' => $request->input('RenstraTujuanID'),
            'OrgID' => $this->getControllerStateSession('renstrasasaran','filters.OrgID'),
            'Kd_RenstraSasaran' => $request->input('Kd_RenstraSasaran'),
            'Nm_RenstraSasaran' => $request->input('Nm_RenstraSasaran'),
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
            return redirect(route('renstrasasaran.show',['id'=>$renstrasasaran->RenstraSasaranID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRASasaranModel::select(\DB::raw('"tmRenstraSasaran"."RenstraSasaranID",
                                                    "tmRenstraTujuan"."Kd_RenstraTujuan",
                                                    "tmRenstraTujuan"."Nm_RenstraTujuan",
                                                    "tmRenstraSasaran"."Kd_RenstraSasaran",
                                                    "tmRenstraSasaran"."Nm_RenstraSasaran",
                                                    "tmRenstraSasaran"."Descr",
                                                    "tmRenstraSasaran"."created_at",
                                                    "tmRenstraSasaran"."updated_at"'))
                                ->join('tmRenstraTujuan','tmRenstraTujuan.RenstraTujuanID','tmRenstraSasaran.RenstraTujuanID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstrasasaran.show")->with(['page_active'=>'renstrasasaran',
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
        
        $data = RENSTRASasaranModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_tujuan=\App\Models\RENSTRA\RENSTRATujuanModel::select(\DB::raw('"RenstraTujuanID",CONCAT(\'[\',"Kd_RenstraTujuan",\']. \',"Nm_RenstraTujuan") AS "Nm_RenstraTujuan"'))
                                                            ->where('TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                            ->orderBy('Kd_RenstraTujuan','ASC')
                                                            ->get()
                                                            ->pluck('Nm_RenstraTujuan','RenstraTujuanID')
                                                            ->toArray();
            return view("pages.$theme.renstra.renstrasasaran.edit")->with(['page_active'=>'renstrasasaran',
                                                                        'data'=>$data,
                                                                        'daftar_tujuan'=>$daftar_tujuan
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
        $renstrasasaran = RENSTRASasaranModel::find($id);
        
        $this->validate($request, [
            'Kd_RenstraSasaran'=>['required',new IgnoreIfDataIsEqualValidation('tmRenstraSasaran',
                                                                        $renstrasasaran->Kd_RenstraSasaran,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getRENSTRATahunMulai()]],
                                                                        'Kode Sasaran')],
            'RenstraTujuanID'=>'required',
            'Nm_RenstraSasaran'=>'required',
        ]);
               
        $renstrasasaran->RenstraTujuanID = $request->input('RenstraTujuanID');
        $renstrasasaran->Kd_RenstraSasaran = $request->input('Kd_RenstraSasaran');
        $renstrasasaran->Nm_RenstraSasaran = $request->input('Nm_RenstraSasaran');
        $renstrasasaran->Descr = $request->input('Descr');
        $renstrasasaran->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstrasasaran.show',['id'=>$renstrasasaran->RenstraSasaranID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstrasasaran = RENSTRASasaranModel::find($id);
        $result=$renstrasasaran->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstrasasaran'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstrasasaran.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
