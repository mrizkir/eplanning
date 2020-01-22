<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRAKebijakanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRAKebijakanController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstrakebijakan','orderby')) 
        {            
           $this->putControllerStateSession('renstrakebijakan','orderby',['column_name'=>'Nm_RenstraKebijakan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstrakebijakan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstrakebijakan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        

        //filter
        if (!$this->checkStateIsExistSession('renstrakebijakan','filters')) 
        {            
            $this->putControllerStateSession('renstrakebijakan','filters',[
                                                                    'OrgIDRPJMD'=>'none'
                                                                    ]);
        }        
        $OrgIDRPJMD= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgIDRPJMD');        
 
        if ($this->checkStateIsExistSession('renstrakebijakan','search')) 
        {
            $search=$this->getControllerStateSession('renstrakebijakan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_RenstraKebijakan' :
                    $data = RENSTRAKebijakanModel::where(['Kd_RenstraKebijakan'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_RenstraKebijakan' :
                    $data = RENSTRAKebijakanModel::where('Nm_RenstraKebijakan', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->where('OrgIDRPJMD',$OrgIDRPJMD)->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRAKebijakanModel::select(\DB::raw('"tmRenstraKebijakan"."RenstraKebijakanID","tmRenstraStrategi"."RenstraStrategiID",CONCAT("tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmRenstraTujuan"."Kd_RenstraTujuan",\'.\',"tmRenstraSasaran"."Kd_RenstraSasaran",\'.\',"tmRenstraStrategi"."Kd_RenstraStrategi",\'.\',"tmRenstraKebijakan"."Kd_RenstraKebijakan") AS "Kd_RenstraKebijakan","tmRenstraKebijakan"."Nm_RenstraKebijakan","tmRenstraKebijakan"."TA"'))
                                        ->join('tmRenstraStrategi','tmRenstraStrategi.RenstraStrategiID','tmRenstraKebijakan.RenstraStrategiID')
                                        ->join('tmRenstraSasaran','tmRenstraSasaran.RenstraSasaranID','tmRenstraStrategi.RenstraSasaranID')
                                        ->join('tmRenstraTujuan','tmRenstraTujuan.RenstraTujuanID','tmRenstraSasaran.RenstraTujuanID')
                                        ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                        ->where('tmRenstraKebijakan.OrgIDRPJMD',$OrgIDRPJMD)
                                        ->orderBy('Kd_Sasaran','ASC')
                                        ->orderBy('Kd_RenstraTujuan','ASC')
                                        ->orderBy('Kd_RenstraSasaran','ASC')
                                        ->orderBy('Kd_RenstraStrategi','ASC')
                                        ->orderBy('Kd_RenstraKebijakan','ASC')
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstrakebijakan.index'));
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
        
        $this->setCurrentPageInsideSession('renstrakebijakan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrakebijakan.datatable")->with(['page_active'=>'renstrakebijakan',
                                                                                'search'=>$this->getControllerStateSession('renstrakebijakan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstrakebijakan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstrakebijakan.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstrakebijakan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstrakebijakan.datatable")->with(['page_active'=>'renstrakebijakan',
                                                                            'search'=>$this->getControllerStateSession('renstrakebijakan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstrakebijakan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstrakebijakan.orderby','order'),
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
            $this->destroyControllerStateSession('renstrakebijakan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstrakebijakan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstrakebijakan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrakebijakan.datatable")->with(['page_active'=>'renstrakebijakan',                                                            
                                                            'search'=>$this->getControllerStateSession('renstrakebijakan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrakebijakan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrakebijakan.orderby','order'),
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

        $filters=$this->getControllerStateSession('renstrakebijakan','filters');       
        $json_data = [];

        //index
        if ($request->exists('OrgIDRPJMD'))
        {
            $OrgIDRPJMD = $request->input('OrgIDRPJMD')==''?'none':$request->input('OrgIDRPJMD');
            $filters['OrgIDRPJMD']=$OrgIDRPJMD;            
            $this->putControllerStateSession('renstrakebijakan','filters',$filters);
            
            $data = $this->populateData();

            $datatable = view("pages.$theme.renstra.renstrakebijakan.datatable")->with(['page_active'=>'renstrakebijakan',                                                                               
                                                                            'search'=>$this->getControllerStateSession('renstrakebijakan','search'),
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

        $filters=$this->getControllerStateSession('renstrakebijakan','filters');
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
                    return view("pages.$theme.renstra.renstrakebijakan.error")->with(['page_active'=>'renstrakebijakan', 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle('renstrakebijakan'),
                                                                                    'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                    ]);
                }          
            break;
        }

        $search=$this->getControllerStateSession('renstrakebijakan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstrakebijakan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstrakebijakan',$data->currentPage());
        
        return view("pages.$theme.renstra.renstrakebijakan.index")->with(['page_active'=>'renstrakebijakan',
                                                                            'search'=>$this->getControllerStateSession('renstrakebijakan','search'),
                                                                            'filters'=>$filters,
                                                                            'daftar_opd'=>$daftar_opd,
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('renstrakebijakan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstrakebijakan.orderby','order'),
                                                                            'data'=>$data]);               
    }
    public function getkodekebijakan($id)
    {
        $Kd_RenstraKebijakan = RENSTRAKebijakanModel::where('RenstraStrategiID',$id)->count('Kd_RenstraKebijakan')+1;
        return response()->json(['success'=>true,'Kd_RenstraKebijakan'=>$Kd_RenstraKebijakan],200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $filters=$this->getControllerStateSession('renstrakebijakan','filters');  
        if ($filters['OrgIDRPJMD'] != 'none'&&$filters['OrgIDRPJMD'] != ''&&$filters['OrgIDRPJMD'] != null)
        {
            $daftar_strategi=\App\Models\RENSTRA\RENSTRAStrategiModel::select(\DB::raw('"RenstraStrategiID",CONCAT(\'[\',"Kd_Sasaran",\'.\',"Kd_RenstraTujuan",\'.\',"Kd_RenstraStrategi",\']. \',"Nm_RenstraStrategi") AS "Nm_RenstraStrategi"'))
                                                                    ->join('tmRenstraSasaran','tmRenstraStrategi.RenstraSasaranID','tmRenstraSasaran.RenstraSasaranID')
                                                                    ->join('tmRenstraTujuan','tmRenstraTujuan.RenstraTujuanID','tmRenstraSasaran.RenstraTujuanID')
                                                                    ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                                                    ->where('tmRenstraStrategi.TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                    ->orderBy('Kd_Sasaran','ASC')
                                                                    ->orderBy('Kd_RenstraTujuan','ASC')
                                                                    ->orderBy('Kd_RenstraSasaran','ASC')
                                                                    ->get()
                                                                    ->pluck('Nm_RenstraStrategi','RenstraStrategiID')
                                                                    ->toArray();

            return view("pages.$theme.renstra.renstrakebijakan.create")->with(['page_active'=>'renstrakebijakan',
                                                                        'daftar_strategi'=>$daftar_strategi
                                                                    ]);  
        }
        else
        {
            return view("pages.$theme.renstra.renstrakebijakan.error")->with(['page_active'=>'renstrakebijakan',
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
            'Kd_RenstraKebijakan'=>[new CheckRecordIsExistValidation('tmRenstraKebijakan',['where'=>['RenstraStrategiID','=',$request->input('RenstraStrategiID')]]),
                            'required'
                        ],
            'RenstraStrategiID'=>'required',
            'Nm_RenstraKebijakan'=>'required',
        ]);
        
        $renstrakebijakan = RENSTRAKebijakanModel::create([
            'RenstraKebijakanID'=> uniqid ('uid'),
            'RenstraStrategiID' => $request->input('RenstraStrategiID'),
            'OrgIDRPJMD' => $this->getControllerStateSession('renstrakebijakan','filters.OrgIDRPJMD'),
            'Kd_RenstraKebijakan' => $request->input('Kd_RenstraKebijakan'),
            'Nm_RenstraKebijakan' => $request->input('Nm_RenstraKebijakan'),
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
            return redirect(route('renstrakebijakan.show',['id'=>$renstrakebijakan->RenstraKebijakanID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRAKebijakanModel::select(\DB::raw('"tmRenstraKebijakan"."RenstraKebijakanID",
                                                    "tmRenstraStrategi"."Kd_RenstraStrategi",
                                                    "tmRenstraStrategi"."Nm_RenstraStrategi",
                                                    "tmRenstraKebijakan"."Kd_RenstraKebijakan",
                                                    "tmRenstraKebijakan"."Nm_RenstraKebijakan",
                                                    "tmRenstraKebijakan"."Descr",
                                                    "tmRenstraKebijakan"."created_at",
                                                    "tmRenstraKebijakan"."updated_at"'))
                                ->join('tmRenstraStrategi','tmRenstraStrategi.RenstraStrategiID','tmRenstraKebijakan.RenstraStrategiID')
                                ->findOrFail($id);

        if (!is_null($data) )  
        {            
            return view("pages.$theme.renstra.renstrakebijakan.show")->with(['page_active'=>'renstrakebijakan',
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
        
        $data = RENSTRAKebijakanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_strategi=\App\Models\RENSTRA\RENSTRAStrategiModel::select(\DB::raw('"RenstraStrategiID",CONCAT(\'[\',"Kd_Sasaran",\'.\',"Kd_RenstraTujuan",\'.\',"Kd_RenstraStrategi",\']. \',"Nm_RenstraStrategi") AS "Nm_RenstraStrategi"'))
                                                                    ->join('tmRenstraSasaran','tmRenstraStrategi.RenstraSasaranID','tmRenstraSasaran.RenstraSasaranID')
                                                                    ->join('tmRenstraTujuan','tmRenstraTujuan.RenstraTujuanID','tmRenstraSasaran.RenstraTujuanID')
                                                                    ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmRenstraTujuan.PrioritasSasaranKabID')
                                                                    ->where('tmRenstraStrategi.TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                    ->orderBy('Kd_Sasaran','ASC')
                                                                    ->orderBy('Kd_RenstraTujuan','ASC')
                                                                    ->orderBy('Kd_RenstraSasaran','ASC')
                                                                    ->get()
                                                                    ->pluck('Nm_RenstraStrategi','RenstraStrategiID')
                                                                    ->toArray();

            return view("pages.$theme.renstra.renstrakebijakan.edit")->with(['page_active'=>'renstrakebijakan',
                                                                    'data'=>$data,
                                                                    'daftar_strategi'=>$daftar_strategi
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
        $renstrakebijakan = RENSTRAKebijakanModel::find($id);
        
        $this->validate($request, [
            'Kd_RenstraKebijakan'=>['required',new IgnoreIfDataIsEqualValidation('tmRenstraKebijakan',
                                                                        $renstrakebijakan->Kd_RenstraKebijakan,
                                                                        ['where'=>['RenstraStrategiID','=',$request->input('RenstraStrategiID')]],
                                                                        'Kode Kebijakan')],
            'RenstraStrategiID'=>'required',
            'Nm_RenstraKebijakan'=>'required',
        ]);
               
        $renstrakebijakan->RenstraStrategiID = $request->input('RenstraStrategiID');
        $renstrakebijakan->Kd_RenstraKebijakan = $request->input('Kd_RenstraKebijakan');
        $renstrakebijakan->Nm_RenstraKebijakan = $request->input('Nm_RenstraKebijakan');
        $renstrakebijakan->Descr = $request->input('Descr');
        $renstrakebijakan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstrakebijakan.show',['id'=>$renstrakebijakan->RenstraKebijakanID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstrakebijakan = RENSTRAKebijakanModel::find($id);
        $result=$renstrakebijakan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstrakebijakan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstrakebijakan.datatable")->with(['page_active'=>'renstrakebijakan',
                                                            'search'=>$this->getControllerStateSession('renstrakebijakan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstrakebijakan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrakebijakan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstrakebijakan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
