<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRAStrategiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRAStrategiController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstrastrategi','orderby')) 
        {            
           $this->putControllerStateSession('renstrastrategi','orderby',['column_name'=>'Nm_RenstraStrategi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstrastrategi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstrastrategi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');  
        
        //filter
        if (!$this->checkStateIsExistSession('renstrastrategi','filters')) 
        {            
            $this->putControllerStateSession('renstrastrategi','filters',[
                                                                    'OrgID'=>'none'
                                                                    ]);
        }        
        $OrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgID');

        if ($this->checkStateIsExistSession('renstrastrategi','search')) 
        {
            $search=$this->getControllerStateSession('renstrastrategi','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_RenstraStrategi' :
                    $data = RENSTRAStrategiModel::where(['Kd_RenstraStrategi'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_RenstraStrategi' :
                    $data = RENSTRAStrategiModel::where('Nm_RenstraStrategi', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->where('OrgID',$OrgID)->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRAStrategiModel::where('OrgID',$OrgID)
                                        ->orderBy($column_order,$direction)
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstrastrategi.index'));
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
        
        $this->setCurrentPageInsideSession('renstrastrategi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                                                'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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
            case 'Nm_RenstraStrategi' :
                $column_name = 'Nm_RenstraStrategi';
            break;           
            default :
                $column_name = 'Nm_RenstraStrategi';
        }
        $this->putControllerStateSession('renstrastrategi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstrastrategi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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
            $this->destroyControllerStateSession('renstrastrategi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstrastrategi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstrastrategi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',                                                            
                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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

        $filters=$this->getControllerStateSession('renstrastrategi','filters');       
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;            
            $this->putControllerStateSession('renstrastrategi','filters',$filters);
            
            $data = $this->populateData();

            $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',                                                                               
                                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
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

        $filters=$this->getControllerStateSession('renstrastrategi','filters');
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
                    return view("pages.$theme.renstra.renstrastrategi.error")->with(['page_active'=>'renstrastrategi', 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle('renstrastrategi'),
                                                                                    'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                    ]);
                }          
            break;
        }

        $search=$this->getControllerStateSession('renstrastrategi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstrastrategi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstrastrategi',$data->currentPage());
        
        return view("pages.$theme.renstra.renstrastrategi.index")->with(['page_active'=>'renstrastrategi',
                                                'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                'filters'=>$filters,
                                                'daftar_opd'=>$daftar_opd,
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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
        $filters=$this->getControllerStateSession('renstrastrategi','filters');  
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {
            $daftar_sasaran=\App\Models\RENSTRA\RENSTRASasaranModel::select(\DB::raw('"RenstraSasaranID",CONCAT(\'[\',"Kd_RenstraSasaran",\']. \',"Nm_RenstraSasaran") AS "Nm_RenstraSasaran"'))
                                                                ->where('TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                ->orderBy('Kd_RenstraSasaran','ASC')
                                                                ->get()
                                                                ->pluck('Nm_RenstraSasaran','RenstraSasaranID')
                                                                ->toArray();

            return view("pages.$theme.renstra.renstrastrategi.create")->with(['page_active'=>'renstrastrategi',
                                                                        'daftar_sasaran'=>$daftar_sasaran
                                                                    ]);  
        }
        else
        {
            return view("pages.$theme.renstra.renstrastrategi.error")->with(['page_active'=>'renstrastrategi',
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
            'Kd_RenstraStrategi'=>[new CheckRecordIsExistValidation('tmRenstraStrategi',['where'=>['TA','=',\HelperKegiatan::getRENSTRATahunMulai()]]),
                            'required'
                        ],
            'RenstraSasaranID'=>'required',
            'Nm_RenstraStrategi'=>'required',
        ]);
        
        $renstrastrategi = RENSTRAStrategiModel::create([
            'RenstraStrategiID'=> uniqid ('uid'),
            'RenstraSasaranID' => $request->input('RenstraSasaranID'),
            'OrgID' => $this->getControllerStateSession('renstrastrategi','filters.OrgID'),
            'Kd_RenstraStrategi' => $request->input('Kd_RenstraStrategi'),
            'Nm_RenstraStrategi' => $request->input('Nm_RenstraStrategi'),
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
            return redirect(route('renstrastrategi.show',['id'=>$renstrastrategi->RenstraStrategiID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRAStrategiModel::select(\DB::raw('"tmRenstraStrategi"."RenstraStrategiID",
                                                    "tmRenstraSasaran"."Kd_RenstraSasaran",
                                                    "tmRenstraSasaran"."Nm_RenstraSasaran",
                                                    "tmRenstraStrategi"."Kd_RenstraStrategi",
                                                    "tmRenstraStrategi"."Nm_RenstraStrategi",
                                                    "tmRenstraStrategi"."Descr",
                                                    "tmRenstraStrategi"."created_at",
                                                    "tmRenstraStrategi"."updated_at"'))
                                ->join('tmRenstraSasaran','tmRenstraSasaran.RenstraSasaranID','tmRenstraStrategi.RenstraSasaranID')
                                ->findOrFail($id);

        if (!is_null($data) )  
        {            
            return view("pages.$theme.renstra.renstrastrategi.show")->with(['page_active'=>'renstrastrategi',
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
        
        $data = RENSTRAStrategiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_sasaran=\App\Models\RENSTRA\RENSTRASasaranModel::select(\DB::raw('"RenstraSasaranID",CONCAT(\'[\',"Kd_RenstraSasaran",\']. \',"Nm_RenstraSasaran") AS "Nm_RenstraSasaran"'))
                                                                ->where('TA',\HelperKegiatan::getRENSTRATahunMulai())
                                                                ->orderBy('Kd_RenstraSasaran','ASC')
                                                                ->get()
                                                                ->pluck('Nm_RenstraSasaran','RenstraSasaranID')
                                                                ->toArray();

            return view("pages.$theme.renstra.renstrastrategi.edit")->with(['page_active'=>'renstrastrategi',
                                                                    'data'=>$data,
                                                                    'daftar_sasaran'=>$daftar_sasaran
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
        $renstrastrategi = RENSTRAStrategiModel::find($id);
        
        $this->validate($request, [
            'Kd_RenstraStrategi'=>['required',new IgnoreIfDataIsEqualValidation('tmRenstraStrategi',
                                                                        $renstrastrategi->Kd_RenstraStrategi,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getRENSTRATahunMulai()]],
                                                                        'Kode Strategi')],
            'RenstraSasaranID'=>'required',
            'Nm_RenstraStrategi'=>'required',
        ]);
               
        $renstrastrategi->RenstraSasaranID = $request->input('RenstraSasaranID');
        $renstrastrategi->Kd_RenstraStrategi = $request->input('Kd_RenstraStrategi');
        $renstrastrategi->Nm_RenstraStrategi = $request->input('Nm_RenstraStrategi');
        $renstrastrategi->Descr = $request->input('Descr');
        $renstrastrategi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstrastrategi.show',['id'=>$renstrastrategi->RenstraStrategiID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstrastrategi = RENSTRAStrategiModel::find($id);
        $result=$renstrastrategi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstrastrategi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstrastrategi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
