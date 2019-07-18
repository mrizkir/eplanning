<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRAIndikatorSasaranModel;
use App\Models\RENSTRA\RENSTRAKebijakanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRAIndikatorSasaranController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstraindikatorsasaran','orderby')) 
        {            
           $this->putControllerStateSession('renstraindikatorsasaran','orderby',['column_name'=>'NamaIndikator','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('renstraindikatorsasaran','search')) 
        {
            $search=$this->getControllerStateSession('renstraindikatorsasaran','search');
            switch ($search['kriteria']) 
            {                
                case 'NamaIndikator' :
                    $data = RENSTRAIndikatorSasaranModel::where('NamaIndikator', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRAIndikatorSasaranModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                ->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstraindikatorsasaran.index'));
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
        
        $this->setCurrentPageInsideSession('renstraindikatorsasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstraindikatorsasaran.datatable")->with(['page_active'=>'renstraindikatorsasaran',
                                                                                'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'),
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
            case 'col-NamaIndikator' :
                $column_name = 'NamaIndikator';
            break;           
            default :
                $column_name = 'NamaIndikator';
        }
        $this->putControllerStateSession('renstraindikatorsasaran','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstraindikatorsasaran');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.renstra.renstraindikatorsasaran.datatable")->with(['page_active'=>'renstraindikatorsasaran',
                                                            'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstraindikatorsasaran',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstraindikatorsasaran.datatable")->with(['page_active'=>'renstraindikatorsasaran',
                                                                            'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'),
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
            $this->destroyControllerStateSession('renstraindikatorsasaran','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstraindikatorsasaran','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstraindikatorsasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstraindikatorsasaran.datatable")->with(['page_active'=>'renstraindikatorsasaran',                                                            
                                                            'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'),
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

        $filters=$this->getControllerStateSession('renstraindikatorsasaran','filters');       
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;            
            $this->putControllerStateSession('renstraindikatorsasaran','filters',$filters);
            
            $data = $this->populateData();

            $datatable = view("pages.$theme.renstra.renstraindikatorsasaran.datatable")->with(['page_active'=>'renstraindikatorsasaran',                                                                               
                                                                            'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
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

        $filters=$this->getControllerStateSession('renstraindikatorsasaran','filters');
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
                    return view("pages.$theme.renstra.renstraindikatorsasaran.error")->with(['page_active'=>'renstraindikatorsasaran', 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle('renstraindikatorsasaran'),
                                                                                    'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                    ]);
                }          
            break;
        }

        $search=$this->getControllerStateSession('renstraindikatorsasaran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstraindikatorsasaran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstraindikatorsasaran',$data->currentPage());
        
        return view("pages.$theme.renstra.renstraindikatorsasaran.index")->with(['page_active'=>'renstraindikatorsasaran',
                                                                                    'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
                                                                                    'filters'=>$filters,
                                                                                    'daftar_opd'=>$daftar_opd,
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                    'column_order'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'),
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
        $filters=$this->getControllerStateSession('renstraindikatorsasaran','filters');  
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {            
            $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusanByOPD(\HelperKegiatan::getTahunPerencanaan(),$filters['OrgID'],false);   
            $daftar_urusan['all']='SEMUA URUSAN';            
            $organisasi=\App\Models\DMaster\OrganisasiModel::find($filters['OrgID']);            
            $UrsID=$organisasi->UrsID;
            $daftar_program=[];
            if (isset($daftar_urusan[$UrsID]))
            {
                $daftar_program = \App\Models\DMaster\ProgramModel::getDaftarProgram(\HelperKegiatan::getTahunPerencanaan(),false,$UrsID);
            }            

            $daftar_kebijakan=\App\Models\RENSTRA\RENSTRAKebijakanModel::select(\DB::raw('"RenstraKebijakanID",CONCAT(\'[\',"Kd_RenstraKebijakan",\']. \',"Nm_RenstraKebijakan") AS "Nm_RenstraKebijakan"'))
                                                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                                ->orderBy('Kd_RenstraKebijakan','ASC')
                                                                ->get()
                                                                ->pluck('Nm_RenstraKebijakan','RenstraKebijakanID')
                                                                ->toArray();
            

            return view("pages.$theme.renstra.renstraindikatorsasaran.create")->with(['page_active'=>'renstraindikatorsasaran',
                                                                        'daftar_urusan'=>$daftar_urusan,
                                                                        'daftar_kebijakan'=>$daftar_kebijakan,
                                                                        'daftar_program'=>$daftar_program,
                                                                        'UrsID_selected'=>$UrsID,
                                                                    ]);  
        }
        else
        {
            return view("pages.$theme.renstra.renstraindikatorsasaran.error")->with(['page_active'=>'renstraindikatorsasaran',
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
            'PrioritasKebijakanKabID'=>'required',
            'UrsID'=>'required',
            'PrgID'=>'required',
            'NamaIndikator'=>'required',
            'OrgID'=>'required',
            'OrgID2'=>'required',
            'TargetAwal'=>'required',
            'PaguDanaN1'=>'required',
            'PaguDanaN2'=>'required',
            'PaguDanaN3'=>'required',
            'PaguDanaN4'=>'required',
            'PaguDanaN5'=>'required',
            'TargetN1'=>'required',
            'TargetN2'=>'required',
            'TargetN3'=>'required',
            'TargetN4'=>'required',
            'TargetN5'=>'required'
        ]);
        
        $renstraindikatorsasaran = RENSTRAIndikatorSasaranModel::create([
            'IndikatorKinerjaID' => uniqid ('uid'),
            'PrioritasKebijakanKabID' => $request->input('PrioritasKebijakanKabID'),
            'UrsID' => $request->input('UrsID'),
            'PrgID' => $request->input('PrgID'),
            'NamaIndikator' => $request->input('NamaIndikator'),
            'TA'=>HelperKegiatan::getTahunPerencanaan(),
            'OrgID' => $request->input('OrgID'),
            'OrgID2' => $request->input('OrgID2'),
            'TargetAwal' => $request->input('TargetAwal'),
            'PaguDanaN1' => $request->input('PaguDanaN1'),
            'PaguDanaN2' => $request->input('PaguDanaN2'),
            'PaguDanaN3' => $request->input('PaguDanaN3'),
            'PaguDanaN4' => $request->input('PaguDanaN4'),
            'PaguDanaN5' => $request->input('PaguDanaN5'),
            'TargetN1' => $request->input('TargetN1'),
            'TargetN2' => $request->input('TargetN2'),
            'TargetN3' => $request->input('TargetN3'),
            'TargetN4' => $request->input('TargetN4'),
            'TargetN5' => $request->input('TargetN5'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getTahunPerencanaan()            
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
            return redirect(route('renstraindikatorsasaran.show',['id'=>$renstraindikatorsasaran->IndikatorKinerjaID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = \DB::table('v_indikator_kinerja2')
                    ->where('IndikatorKinerjaID',$id)
                    ->first();

        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstraindikatorsasaran.show")->with(['page_active'=>'renstraindikatorsasaran',
                                                                                'data'=>$data
                                                                                ]);
        }
        else
        {
            return view("pages.$theme.renstra.renstraindikatorsasaran.error")->with(['page_active'=>'renstraindikatorsasaran',
                                                                                'errormessage'=>"ID Indikator Kinerja ($id) tidak ditemukan."
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
        
        $data = RENSTRAIndikatorSasaranModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_kebijakan = RENSTRAKebijakanModel::getDaftarKebijakan(\HelperKegiatan::getTahunPerencanaan(),false);
            $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusan(\HelperKegiatan::getTahunPerencanaan(),false);
            $daftar_program=\App\Models\DMaster\ProgramModel::getDaftarProgram(\HelperKegiatan::getTahunPerencanaan(),false,$data['UrsID']);
            $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false,$data['UrsID']);
            return view("pages.$theme.renstra.renstraindikatorsasaran.edit")->with(['page_active'=>'renstraindikatorsasaran',
                                                                                'data'=>$data,
                                                                                'daftar_kebijakan'=>$daftar_kebijakan,
                                                                                'daftar_urusan'=>$daftar_urusan,
                                                                                'daftar_program'=>$daftar_program,
                                                                                'daftar_opd'=>$daftar_opd
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
        $renstraindikatorsasaran = RENSTRAIndikatorSasaranModel::find($id);
        
        $this->validate($request, [
            'PrioritasKebijakanKabID'=>'required',
            'UrsID'=>'required',
            'PrgID'=>'required',
            'NamaIndikator'=>'required',
            'OrgID'=>'required',
            'OrgID2'=>'required',
            'TargetAwal'=>'required',
            'PaguDanaN1'=>'required',
            'PaguDanaN2'=>'required',
            'PaguDanaN3'=>'required',
            'PaguDanaN4'=>'required',
            'PaguDanaN5'=>'required',
            'TargetN1'=>'required',
            'TargetN2'=>'required',
            'TargetN3'=>'required',
            'TargetN4'=>'required',
            'TargetN5'=>'required'
        ]);
        
        $renstraindikatorsasaran->PrioritasKebijakanKabID = $request->input('PrioritasKebijakanKabID');
        $renstraindikatorsasaran->UrsID = $request->input('UrsID');
        $renstraindikatorsasaran->PrgID = $request->input('PrgID');
        $renstraindikatorsasaran->NamaIndikator = $request->input('NamaIndikator');
        $renstraindikatorsasaran->TargetAwal = $request->input('TargetAwal');
        $renstraindikatorsasaran->OrgID = $request->input('OrgID');
        $renstraindikatorsasaran->OrgID2 = $request->input('OrgID2');
        $renstraindikatorsasaran->PaguDanaN1 = $request->input('PaguDanaN1');
        $renstraindikatorsasaran->PaguDanaN2 = $request->input('PaguDanaN2');
        $renstraindikatorsasaran->PaguDanaN3 = $request->input('PaguDanaN3');
        $renstraindikatorsasaran->PaguDanaN4 = $request->input('PaguDanaN4');
        $renstraindikatorsasaran->PaguDanaN5 = $request->input('PaguDanaN5');
        $renstraindikatorsasaran->TargetN1 = $request->input('TargetN1');
        $renstraindikatorsasaran->TargetN2 = $request->input('TargetN2');
        $renstraindikatorsasaran->TargetN3 = $request->input('TargetN3');
        $renstraindikatorsasaran->TargetN4 = $request->input('TargetN4');
        $renstraindikatorsasaran->TargetN5 = $request->input('TargetN5');
        $renstraindikatorsasaran->Descr = $request->input('Descr');

        $renstraindikatorsasaran->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstraindikatorsasaran.show',['id'=>$id]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $renstraindikatorsasaran = RENSTRAIndikatorSasaranModel::find($id);
        $result=$renstraindikatorsasaran->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstraindikatorsasaran'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstraindikatorsasaran.datatable")->with(['page_active'=>'renstraindikatorsasaran',
                                                            'search'=>$this->getControllerStateSession('renstraindikatorsasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstraindikatorsasaran.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstraindikatorsasaran.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
