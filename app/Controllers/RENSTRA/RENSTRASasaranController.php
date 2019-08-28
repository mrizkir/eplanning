<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRASasaranModel;
use App\Models\RENSTRA\RENSTRASasaranIndikatorModel;
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
    private function populateIndikatorSasaran($RenstraSasaranID)
    {
      
        $data = RENSTRASasaranIndikatorModel::where('RenstraSasaranID',$RenstraSasaranID)
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
                                                                    'OrgIDRPJMD'=>'none'
                                                                    ]);
        }        
        $OrgIDRPJMD= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgIDRPJMD');        

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
            $data = $data->where('OrgIDRPJMD',$OrgIDRPJMD)->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRASasaranModel::select(\DB::raw('"tmRenstraSasaran"."RenstraSasaranID","tmRenstraSasaran"."RenstraTujuanID",CONCAT("tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan",\'.\',"tmRenstraSasaran"."Kd_RenstraSasaran") AS "Kd_RenstraSasaran","tmRenstraSasaran"."Nm_RenstraSasaran","tmRenstraSasaran"."TA"'))
                                        ->join('tmRenstraTujuan','tmRenstraTujuan.RenstraTujuanID','tmRenstraSasaran.RenstraTujuanID')
                                        ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                        ->where('tmRenstraSasaran.OrgIDRPJMD',$OrgIDRPJMD)                                        
                                        ->orderBy('Kd_Sasaran','ASC')
                                        ->orderBy('Kd_RenstraTujuan','ASC')
                                        ->orderBy('Kd_RenstraSasaran','ASC')
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
        if ($request->exists('OrgIDRPJMD'))
        {
            $OrgIDRPJMD = $request->input('OrgIDRPJMD')==''?'none':$request->input('OrgIDRPJMD');
            $filters['OrgIDRPJMD']=$OrgIDRPJMD;            
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
                $daftar_opd=\App\Models\DMaster\OrganisasiRPJMDModel::getDaftarOPDMaster(\HelperKegiatan::getRENSTRATahunMulai(),false);   
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
    public function getkodesasaran($id)
    {
        $Kd_RenstraSasaran = RENSTRASasaranModel::where('RenstraTujuanID',$id)->count('Kd_RenstraSasaran')+1;
        return response()->json(['success'=>true,'Kd_RenstraSasaran'=>$Kd_RenstraSasaran],200);
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
        if ($filters['OrgIDRPJMD'] != 'none'&&$filters['OrgIDRPJMD'] != ''&&$filters['OrgIDRPJMD'] != null)
        {
            $daftar_tujuan=\App\Models\RENSTRA\RENSTRATujuanModel::select(\DB::raw('"RenstraTujuanID",CONCAT(\'[\',"Kd_Sasaran",\'.\',"Kd_RenstraTujuan",\']. \',"Nm_RenstraTujuan") AS "Nm_RenstraTujuan"'))
                                                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                                                ->where('tmRenstraTujuan.TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                ->orderBy('Kd_Sasaran','ASC')
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
            'Kd_RenstraSasaran'=>[new CheckRecordIsExistValidation('tmRenstraSasaran',['where'=>['RenstraTujuanID','=',$request->input('RenstraTujuanID')]]),
                            'required'
                        ],
            'RenstraTujuanID'=>'required',
            'Nm_RenstraSasaran'=>'required',
        ]);
        
        $renstrasasaran = RENSTRASasaranModel::create([
            'RenstraSasaranID'=> uniqid ('uid'),
            'RenstraTujuanID' => $request->input('RenstraTujuanID'),
            'OrgIDRPJMD' => $this->getControllerStateSession('renstrasasaran','filters.OrgIDRPJMD'),
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {
        $this->validate($request, [            
            'NamaIndikator'=>'required',            
            'N1'=>'required',
            'N2'=>'required',
            'N3'=>'required',
            'N4'=>'required',
            'N5'=>'required'
        ]);
        
        $renstraindikatorsasaran = RENSTRASasaranIndikatorModel::create([
            'RenstraIndikatorSasaranID'=> uniqid ('uid'),
            'RenstraSasaranID' => $request->input('RenstraSasaranID'),
            'NamaIndikator' => $request->input('NamaIndikator'),
            'KondisiAwal' => 0,
            'N1' => $request->input('N1'),
            'N2' => $request->input('N2'),
            'N3' => $request->input('N3'),
            'N4' => $request->input('N4'),
            'N5' => $request->input('N5'),
            'KondisiAkhir' => 0,
            'Satuan' => '-',
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
            return redirect(route('renstrasasaran.show',['id'=>$renstraindikatorsasaran->RenstraSasaranID]))->with('success','Data ini telah berhasil disimpan.');
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
            $dataindikatorsasaran=$this->populateIndikatorSasaran($id);
            return view("pages.$theme.renstra.renstrasasaran.show")->with(['page_active'=>'renstrasasaran',
                                                                        'data'=>$data,
                                                                        'dataindikatorsasaran'=>$dataindikatorsasaran
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
            $daftar_tujuan=\App\Models\RENSTRA\RENSTRATujuanModel::select(\DB::raw('"RenstraTujuanID",CONCAT(\'[\',"Kd_Sasaran",\'.\',"Kd_RenstraTujuan",\']. \',"Nm_RenstraTujuan") AS "Nm_RenstraTujuan"'))
                                                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                                                ->where('tmRenstraTujuan.TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                ->orderBy('Kd_Sasaran','ASC')
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit1($id)
    {
        $theme = \Auth::user()->theme;
        
        $data_indikator = RENSTRASasaranIndikatorModel::findOrFail($id);
        $data = RENSTRASasaranModel::find($data_indikator->RenstraSasaranID);
        if (!is_null($data) ) 
        {        
            $dataindikatorsasaran=$this->populateIndikatorSasaran($data_indikator->RenstraSasaranID);
            return view("pages.$theme.renstra.renstrasasaran.edit1")->with(['page_active'=>'renstrasasaran',
                                                                        'dataindikatorsasaran'=>$dataindikatorsasaran,
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
        $renstrasasaran = RENSTRASasaranModel::find($id);
        
        $this->validate($request, [
            'Kd_RenstraSasaran'=>['required',new IgnoreIfDataIsEqualValidation('tmRenstraSasaran',
                                                                        $renstrasasaran->Kd_RenstraSasaran,
                                                                        ['where'=>['RenstraTujuanID','=',$request->input('RenstraTujuanID')]],
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update1(Request $request, $id)
    {
        $renstraindikatorsasaran = RENSTRASasaranIndikatorModel::find($id);

        $this->validate($request, [            
            'NamaIndikator'=>'required',
            'N1'=>'required',
            'N2'=>'required',
            'N3'=>'required',
            'N4'=>'required',
            'N5'=>'required',
        ]);
        
        $renstraindikatorsasaran->NamaIndikator = $request->input('NamaIndikator');
        $renstraindikatorsasaran->N1 = $request->input('N1');
        $renstraindikatorsasaran->N2 = $request->input('N2');
        $renstraindikatorsasaran->N3 = $request->input('N3');
        $renstraindikatorsasaran->N4 = $request->input('N4');
        $renstraindikatorsasaran->N5 = $request->input('N5');
        $renstraindikatorsasaran->Descr = $request->input('Descr');
        $renstraindikatorsasaran->save();
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('renstrasasaran.show',['id'=>$renstraindikatorsasaran->RenstraSasaranID]))->with('success','Data ini telah berhasil disimpan.');
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
        if ($request->exists('indikatorsasaran'))
        {
            $renstrasasaran = RENSTRASasaranIndikatorModel::find($id);
            $RenstraSasaranID=$renstrasasaran->RenstraSasaranID;
            $result=$renstrasasaran->delete();
            if ($request->ajax()) 
            {                
                $dataindikatorsasaran = $this->populateIndikatorSasaran($RenstraSasaranID);                
                $datatable = view("pages.$theme.renstra.renstrasasaran.datatableindikatorsasaran")->with(['page_active'=>'renstrasasaran',                                                                                    
                                                                                                    'dataindikatorsasaran'=>$dataindikatorsasaran])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('renstrasasaran.show',['id'=>$RenstraSasaranID]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }       
        }
        else
        {        
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
}
