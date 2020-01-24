<?php

namespace App\Controllers\Aspirasi;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Aspirasi\BansosHibahModel;

class BansosHibahController extends Controller {
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
        if (!$this->checkStateIsExistSession('bansoshibah','orderby')) 
        {            
           $this->putControllerStateSession('bansoshibah','orderby',['column_name'=>'NamaUsulanKegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('bansoshibah.orderby','column_name'); 
        $direction=$this->getControllerStateSession('bansoshibah.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');       
        //filter
        if (!$this->checkStateIsExistSession('bansoshibah','filters')) 
        {            
            $this->putControllerStateSession('bansoshibah','filters',[
                                                                        'PemilikBansosHibahID'=>'none',
                                                                    ]);
        }        
        $PemilikBansosHibahID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'PemilikBansosHibahID');      
        if ($this->checkStateIsExistSession('bansoshibah','search')) 
        {
            $search=$this->getControllerStateSession('bansoshibah','search');
            switch ($search['kriteria'])    
            {
                case 'NamaUsulanKegiatan' :
                    $data = BansosHibahModel::select(\DB::raw('"trBansosHibah"."BansosHibahID",
                                                        "tmPemilikBansosHibah"."NmPk",
                                                        "trBansosHibah"."NamaUsulanKegiatan",
                                                        "trBansosHibah"."NilaiUsulan",
                                                        "tmPmKecamatan"."Nm_Kecamatan",
                                                        "tmPmDesa"."Nm_Desa",
                                                        "trBansosHibah"."Lokasi",
                                                        "trBansosHibah"."Prioritas",
                                                        "trBansosHibah"."Privilege",
                                                        "trBansosHibah".created_at,
                                                        "trBansosHibah".updated_at
                                                    '))      
                                                ->join('tmPemilikBansosHibah','tmPemilikBansosHibah.PemilikBansosHibahID','trBansosHibah.PemilikBansosHibahID')
                                                ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trBansosHibah.PmKecamatanID')
                                                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trBansosHibah.PmDesaID')
                                                ->where('trBansosHibah.TA',\HelperKegiatan::getTahunPerencanaan())                                        
                                                ->where('trBansosHibah.NamaUsulanKegiatan', 'ilike', '%' . $search['isikriteria'] . '%');
                                                
                                               
                    if ($PemilikBansosHibahID!='all')
                    {
                        $data = $data->where('trBansosHibah.PemilikBansosHibahID',$PemilikBansosHibahID);
                    }
                    $data = $data->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);
                break;
            }           

            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);              
        }
        else
        {
            $data = BansosHibahModel::select(\DB::raw('"trBansosHibah"."BansosHibahID",
                                                        "tmPemilikBansosHibah"."NmPk",
                                                        "trBansosHibah"."NamaUsulanKegiatan",
                                                        "trBansosHibah"."NilaiUsulan",
                                                        "tmPmKecamatan"."Nm_Kecamatan",
                                                        "tmPmDesa"."Nm_Desa",
                                                        "trBansosHibah"."Lokasi",
                                                        "trBansosHibah"."Prioritas",
                                                        "trBansosHibah"."Privilege",
                                                        "trBansosHibah".created_at,
                                                        "trBansosHibah".updated_at
                                                    '))            
                                    ->join('tmPemilikBansosHibah','tmPemilikBansosHibah.PemilikBansosHibahID','trBansosHibah.PemilikBansosHibahID')
                                    ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trBansosHibah.PmKecamatanID')
                                    ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trBansosHibah.PmDesaID')
                                    ->where('trBansosHibah.TA',\HelperKegiatan::getTahunPerencanaan());
            if ($PemilikBansosHibahID!='all')
            {
                $data = $data->where('trBansosHibah.PemilikBansosHibahID',$PemilikBansosHibahID);
            }
            $data = $data->orderBy('Prioritas','ASC')
                        ->orderBy($column_order,$direction)
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('bansoshibah.index'));
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
        $roles=\Auth::user()->getRoleNames();

        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('bansoshibah',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.bansoshibah.datatable")->with(['page_active'=>'bansoshibah',
                                                                                'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                                                'role'=>$roles[0],
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
        $roles=\Auth::user()->getRoleNames();
        $orderby = $request->input('orderby') == 'asc'?'desc':'asc';
        $column=$request->input('column_name');
        switch($column) 
        {
            case 'col-KdPK' :
                $column_name = 'KdPK';
            break;           
            case 'col-NmPk' :
                $column_name = 'NmPk';
            break;           
            case 'col-NamaUsulanKegiatan' :
                $column_name = 'NamaUsulanKegiatan';
            break;           
            case 'col-Prioritas' :
                $column_name = 'Prioritas';
            break;                            
            default :
                $column_name = 'KdPK';
        }
        $this->putControllerStateSession('bansoshibah','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.bansoshibah.datatable")->with(['page_active'=>'bansoshibah',
                                                            'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                            'role'=>$roles[0],
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
        $roles=\Auth::user()->getRoleNames();

        $this->setCurrentPageInsideSession('bansoshibah',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.aspirasi.bansoshibah.datatable")->with(['page_active'=>'bansoshibah',
                                                                            'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                                            'role'=>$roles[0],
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
        $roles=$auth->getRoleNames();

        $filters=$this->getControllerStateSession('bansoshibah','filters');

        $json_data = [];     
        //index
        if ($request->exists('PemilikBansosHibahID'))
        {
            $PemilikBansosHibahID = $request->input('PemilikBansosHibahID')==''?'none':$request->input('PemilikBansosHibahID');
            $filters['PemilikBansosHibahID']=$PemilikBansosHibahID;
            $this->putControllerStateSession('bansoshibah','filters',$filters);
            $this->setCurrentPageInsideSession('bansoshibah',1);

            $data=$this->populateData();
            $datatable = view("pages.$theme.aspirasi.bansoshibah.datatable")->with(['page_active'=>'bansoshibah',
                                                                                    'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                                                    'role'=>$roles[0],
                                                                                    'data'=>$data])->render();      
            return response()->json(['success'=>true,'datatable'=>$datatable],200);       
        }           
        //create4
        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$PmKecamatanID,false);
                                                                                    
            $json_data = ['success'=>true,'daftar_desa'=>$daftar_desa];            
        } 

        return response()->json($json_data,200);  
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
        $roles=\Auth::user()->getRoleNames();
        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('bansoshibah','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('bansoshibah','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('bansoshibah',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.bansoshibah.datatable")->with(['page_active'=>'bansoshibah',                                                            
                                                            'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                            'role'=>$roles[0],
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
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('bansoshibah','filters');

        $roles=$auth->getRoleNames(); 
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :                     
                $daftar_pemilikbansoshibah=\App\Models\Aspirasi\PemilikBansosHibahModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                        ->select(\DB::raw('"PemilikBansosHibahID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikBansosHibahID')              
                                                                        ->prepend('SELURUH BANSOS DAN HIBAH','all')                                                          
                                                                        ->toArray();                  
            break;
            case 'dewan' :               
                $daftar_pemilikbansoshibah=\App\Models\UserDewan::select(\DB::raw('"PemilikBansosHibahID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                    ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('id',$auth->id)
                                                    ->get()
                                                    ->pluck('NmPk','PemilikBansosHibahID')
                                                    ->toArray(); 
            break;
        }        
        $search=$this->getControllerStateSession('bansoshibah','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('bansoshibah'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('bansoshibah',$data->currentPage());

        return view("pages.$theme.aspirasi.bansoshibah.index")->with(['page_active'=>'bansoshibah',
                                                                    'filters'=>$filters,
                                                                    'daftar_pemilikbansoshibah'=>$daftar_pemilikbansoshibah,
                                                                    'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                    'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                                    'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                                    'role'=>$roles[0],
                                                                    'data'=>$data]);               
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $auth = \Auth::user(); 
        $theme =  $auth->theme;
        $filters = $this->getControllerStateSession('bansoshibah','filters');

        if ($filters['PemilikBansosHibahID']=='none' || $filters['PemilikBansosHibahID']=='all')
        {
            return view("pages.$theme.aspirasi.bansoshibah.error")->with(['page_active'=>'bansoshibah',
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon Pemilik Bansos dan Hibah untuk di pilih terlebih dahulu.'
                                                                ]);  
        }
        else
        {
            $roles=$auth->getRoleNames();
            switch ($roles[0])
            {
                case 'superadmin' :     
                case 'bapelitbang' :     
                    $daftar_pemilik= \App\Models\Aspirasi\PemilikBansosHibahModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                            ->select(\DB::raw('"PemilikBansosHibahID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                            ->get()
                                                                            ->pluck('NmPk','PemilikBansosHibahID')   
                                                                            ->prepend('DAFTAR BANSOS DAN HIBAH','none')                                                                     
                                                                            ->toArray();                  
                break;
                case 'dewan' :               
                    $daftar_pemilik=\App\Models\UserDewan::select(\DB::raw('"PemilikBansosHibahID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                        ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                        ->where('id',$auth->id)
                                                        ->get()
                                                        ->pluck('NmPk','PemilikBansosHibahID')
                                                        ->prepend('DAFTAR BANSOS DAN HIBAH','none')
                                                        ->toArray(); 
                break;
            }       
            
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),NULL,false);
            return view("pages.$theme.aspirasi.bansoshibah.create")->with(['page_active'=>'bansoshibah',
                                                                        'filters'=>$this->getControllerStateSession('bansoshibah','filters'),
                                                                        'daftar_pemilik'=>$daftar_pemilik,
                                                                        'daftar_kecamatan'=>$daftar_kecamatan,
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
            'PemilikBansosHibahID'=>'required',
            'PmKecamatanID'=>'required',
            'NamaUsulanKegiatan'=>'required',
            'Lokasi'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',            
            'Output'=>'required',
            'NilaiUsulan'=>'required',
            'Prioritas'=>'required',
        ]);
        $jeniskeg=$request->input('Jeniskeg');
        $bansoshibah = BansosHibahModel::create([
            'BansosHibahID' => uniqid ('uid'),
            'PemilikBansosHibahID' => $request->input('PemilikBansosHibahID'),
            'PmKecamatanID' => $request->input('PmKecamatanID'),
            'PmDesaID' => $request->input('PmDesaID'),
            'SumberDanaID' => NULL,
            'NamaUsulanKegiatan' => $request->input('NamaUsulanKegiatan'),
            'Lokasi' => $request->input('Lokasi'),
            'Sasaran_Angka' => $request->input('Sasaran_Angka'),
            'Sasaran_Uraian' => $request->input('Sasaran_Uraian'),
            'NilaiUsulan' => $request->input('NilaiUsulan'),
            'Output' => $request->input('Output'),
            'Jeniskeg' => $jeniskeg,
            'Prioritas' => $request->input('Prioritas'),
            'Privilege' => 0,
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getTahunPerencanaan(),
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
            return redirect(route('bansoshibah.show',['uuid'=>$bansoshibah->BansosHibahID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = BansosHibahModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.aspirasi.bansoshibah.show")->with(['page_active'=>'bansoshibah',                                                                        
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
        $auth = \Auth::user(); 
        $theme =  $auth->theme;
        
        $roles=$auth->getRoleNames();
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :     
                $daftar_pemilik= \App\Models\Aspirasi\PemilikBansosHibahModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                                    ->select(\DB::raw('"PemilikBansosHibahID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                                    ->get()
                                                                                    ->pluck('NmPk','PemilikBansosHibahID')   
                                                                                    ->prepend('DAFTAR BANSOS DAN HIBAH','none')                                                                     
                                                                                    ->toArray();                  
                $data = BansosHibahModel::findOrFail($id);
            break;
            case 'dewan' :               
                $daftar_pemilik=\App\Models\UserDewan::select(\DB::raw('"PemilikBansosHibahID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                    ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('id',$auth->id)
                                                    ->get()
                                                    ->pluck('NmPk','PemilikBansosHibahID')
                                                    ->prepend('DAFTAR BANSOS DAN HIBAH','none')
                                                    ->toArray(); 

                $data = BansosHibahModel::where('Privilege',0)
                                        ->findOrFail($id);    
            break;
        }       
        
            
        if (!is_null($data) ) 
        {           
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),NULL,false);
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$data->PmKecamatanID,false);
            return view("pages.$theme.aspirasi.bansoshibah.edit")->with(['page_active'=>'bansoshibah',
                                                                        'daftar_pemilik'=>$daftar_pemilik,
                                                                        'daftar_kecamatan'=>$daftar_kecamatan,
                                                                        'daftar_desa'=>$daftar_desa,
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
        $bansoshibah = BansosHibahModel::find($id);
        
        $this->validate($request, [
            'PemilikBansosHibahID'=>'required',
            'PmKecamatanID'=>'required',
            'NamaUsulanKegiatan'=>'required',
            'Lokasi'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'NilaiUsulan'=>'required',
            'Output'=>'required',
            'Prioritas'=>'required',
        ]);
        
        $bansoshibah->PmKecamatanID = $request->input('PmKecamatanID');
        $bansoshibah->PmDesaID = $request->input('PmDesaID');
        $bansoshibah->NamaUsulanKegiatan = $request->input('NamaUsulanKegiatan');
        $bansoshibah->Lokasi = $request->input('Lokasi');
        $bansoshibah->Sasaran_Angka = $request->input('Sasaran_Angka');
        $bansoshibah->Sasaran_Uraian = $request->input('Sasaran_Uraian');
        $bansoshibah->NilaiUsulan = $request->input('NilaiUsulan');
        $bansoshibah->Output = $request->input('Output');
        $bansoshibah->Jeniskeg = $request->input('Jeniskeg');
        $bansoshibah->Prioritas = $request->input('Prioritas');
        $bansoshibah->Descr = $request->input('Descr');
       
        $bansoshibah->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('bansoshibah.show',['uuid'=>$bansoshibah->BansosHibahID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $bansoshibah = BansosHibahModel::find($id);
        $result=$bansoshibah->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('bansoshibah'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.aspirasi.bansoshibah.datatable")->with(['page_active'=>'bansoshibah',
                                                            'search'=>$this->getControllerStateSession('bansoshibah','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('bansoshibah.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('bansoshibah.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('bansoshibah.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
    /**
     * Print to Excel
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexcel ()
    {
        $theme = \Auth::user()->theme;
        
        $data_report=$this->getControllerStateSession('bansoshibah','filters');

        if ($data_report['PemilikBansosHibahID'] == 'none')
        {
            return view("pages.$theme.aspirasi.bansoshibah.error")->with(['page_active'=>'bansoshibah',
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon Pemilik Bansos dan Hibah untuk di pilih terlebih dahulu.'
                                                                ]);  
        }   
        else
        {
            $generate_date=date('Y-m-d_H_m_s');        
            $report= new \App\Models\Report\ReportBansosHibahModel ($data_report);
            return $report->download("bansoshibah_$generate_date.xlsx");
        }     
    }
}