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
                                                                    'OrgIDRPJMD'=>'none'
                                                                    ]);
        }        
        $OrgIDRPJMD= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgIDRPJMD');        
        
        if ($this->checkStateIsExistSession('renstratujuan','search')) 
        {
            $search=$this->getControllerStateSession('renstratujuan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_RenstraTujuan' :
                    $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID","tmRenstraTujuan"."PrioritasKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan") AS "Kd_RenstraTujuan","tmRenstraTujuan"."Nm_RenstraTujuan","tmRenstraTujuan"."TA"'))
                                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                                ->where('OrgIDRPJMD',$OrgIDRPJMD)
                                                ->where(['Kd_RenstraTujuan'=>$search['isikriteria']])
                                                ->orderBy('Kd_Sasaran','ASC')
                                                ->orderBy('Kd_RenstraTujuan','ASC');
                break;
                case 'Nm_RenstraTujuan' :
                    $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID","tmRenstraTujuan"."PrioritasSasaranKabID",CONCAT("tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan") AS "Kd_RenstraTujuan","tmRenstraTujuan"."Nm_RenstraTujuan","tmRenstraTujuan"."TA"'))
                                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                                ->where('OrgIDRPJMD',$OrgIDRPJMD)
                                                ->where('Nm_RenstraTujuan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                ->orderBy('Kd_Sasaran','ASC')
                                                ->orderBy('Kd_RenstraTujuan','ASC');
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRATujuanModel::select(\DB::raw('"tmRenstraTujuan"."RenstraTujuanID","tmRenstraTujuan"."PrioritasSasaranKabID",CONCAT("tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan") AS "Kd_RenstraTujuan","tmRenstraTujuan"."Nm_RenstraTujuan","tmRenstraTujuan"."TA"'))
                                        ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                        ->where('OrgIDRPJMD',$OrgIDRPJMD)
                                        ->orderBy('Kd_Sasaran','ASC')
                                        ->orderBy('Kd_RenstraTujuan','ASC')
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
        if ($request->exists('OrgIDRPJMD'))
        {
            $OrgIDRPJMD = $request->input('OrgIDRPJMD')==''?'none':$request->input('OrgIDRPJMD');
            $filters['OrgIDRPJMD']=$OrgIDRPJMD;            
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
                $daftar_opd=\App\Models\DMaster\OrganisasiRPJMDModel::getDaftarOPDMaster(\HelperKegiatan::getRENSTRATahunMulai(),false);   
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
    public function getdaftarsasaranrpjmd($id)
    {
        $OrgIDRPJMD=$this->getControllerStateSession('renstratujuan','filters.OrgIDRPJMD');
        $daftar_sasaran=\App\Models\RPJMD\RPJMDSasaranModel::select(\DB::raw('"PrioritasSasaranKabID",CONCAT(\'[\',"Kd_PrioritasKab",\'.\',"Kd_Tujuan",\'.\',"Kd_Sasaran",\']. \',"Nm_Sasaran") AS "Nm_Sasaran"'))
                                                        ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                                        ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                                        ->where('tmPrioritasSasaranKab.PrioritasTujuanKabID',$id)   
                                                        ->whereNotIn('PrioritasSasaranKabID', function ($query) use ($OrgIDRPJMD){
                                                            $query->select('PrioritasSasaranKabID')
                                                                ->from('tmRenstraTujuan')
                                                                ->where('OrgIDRPJMD',$OrgIDRPJMD);
                                                        })                                                     
                                                        ->orderBy('Kd_Tujuan','ASC')
                                                        ->orderBy('Kd_Sasaran','ASC')
                                                        ->get()
                                                        ->pluck('Nm_Sasaran','PrioritasSasaranKabID')
                                                        ->toArray();
        return response()->json(['success'=>true,'daftar_sasaran'=>$daftar_sasaran],200);
    } 
    public function getkodetujuan($id)
    {
        $Kd_RenstraTujuan = RENSTRATujuanModel::where('PrioritasSasaranKabID',$id)
                                                ->where('OrgIDRPJMD',$this->getControllerStateSession('renstratujuan','filters.OrgIDRPJMD'))
                                                ->count('Kd_RenstraTujuan')+1;

        $data_sasaran = \App\Models\RPJMD\RPJMDSasaranModel::where('PrioritasSasaranKabID',$id)
                                                            ->first();
        return response()->json(['success'=>true,'Kd_RenstraTujuan'=>$Kd_RenstraTujuan,'Nm_Sasaran'=>$data_sasaran->Nm_Sasaran],200);
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
        if ($filters['OrgIDRPJMD'] != 'none'&&$filters['OrgIDRPJMD'] != ''&&$filters['OrgIDRPJMD'] != null)
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
            'Kd_RenstraTujuan'=>[new CheckRecordIsExistValidation('tmRenstraTujuan',['where'=>['PrioritasSasaranKabID','=',$request->input('PrioritasSasaranKabID')]]),
                            'required'
                        ],
            'PrioritasSasaranKabID'=>'required',
            'Nm_RenstraTujuan'=>'required',
        ]);
        
        $renstratujuan = RENSTRATujuanModel::create([
            'RenstraTujuanID'=> uniqid ('uid'),
            'PrioritasSasaranKabID' => $request->input('PrioritasSasaranKabID'),
            'OrgIDRPJMD' => $this->getControllerStateSession('renstratujuan','filters.OrgIDRPJMD'),
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
                                                    "tmPrioritasSasaranKab"."Kd_Sasaran",
                                                    "tmPrioritasSasaranKab"."Nm_Sasaran",
                                                    "tmRenstraTujuan"."Kd_RenstraTujuan",
                                                    "tmRenstraTujuan"."Nm_RenstraTujuan",
                                                    "tmRenstraTujuan"."Descr",
                                                    "tmRenstraTujuan"."TA",
                                                    "tmRenstraTujuan"."created_at",
                                                    "tmRenstraTujuan"."updated_at"'))
                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
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
            $daftar_sasaran=\App\Models\RPJMD\RPJMDSasaranModel::select(\DB::raw('"PrioritasSasaranKabID",CONCAT(\'[\',"Kd_Sasaran",\']. \',"Nm_Sasaran") AS "Nm_Sasaran"'))
                                                            ->where('TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('Kd_Sasaran','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Sasaran','PrioritasSasaranKabID')
                                                            ->toArray();

            return view("pages.$theme.renstra.renstratujuan.edit")->with(['page_active'=>'renstratujuan',
                                                                        'daftar_sasaran'=>$daftar_sasaran,
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
                                                                        ['where'=>['PrioritasSasaranKabID','=',$renstratujuan->PrioritasSasaranKabID]],
                                                                        'Kode Tujuan')],
            'Nm_RenstraTujuan'=>'required',
        ]);
               
        $renstratujuan->Kd_RenstraTujuan = $request->input('Kd_RenstraTujuan');
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
