<?php

namespace App\Controllers\Pokir;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Pokir\PokokPikiranModel;

class PokokPikiranController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|dewan']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('pokokpikiran','orderby')) 
        {            
           $this->putControllerStateSession('pokokpikiran','orderby',['column_name'=>'NamaUsulanKegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pokokpikiran.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pokokpikiran.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');       
        //filter
        if (!$this->checkStateIsExistSession('pokokpikiran','filters')) 
        {            
            $this->putControllerStateSession('pokokpikiran','filters',[
                                                                        'PemilikPokokID'=>'none',
                                                                        ]);
        }        
        $PemilikPokokID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'PemilikPokokID');      
        if ($this->checkStateIsExistSession('pokokpikiran','search')) 
        {
            $search=$this->getControllerStateSession('pokokpikiran','search');
            switch ($search['kriteria']) 
            {
                case 'NamaUsulanKegiatan' :
                    $data = PokokPikiranModel::select(\DB::raw('"trPokPir"."PokPirID",
                                                        "tmPemilikPokok"."NmPk",
                                                        "trPokPir"."NamaUsulanKegiatan",
                                                        "trPokPir"."NilaiUsulan",
                                                        "tmOrg"."OrgNm",
                                                        "tmPmKecamatan"."Nm_Kecamatan",
                                                        "tmPmDesa"."Nm_Desa",
                                                        "trPokPir"."Lokasi",
                                                        "trPokPir"."Prioritas",
                                                        "trPokPir"."Privilege",
                                                        "trPokPir".created_at,
                                                        "trPokPir".updated_at
                                                    '))      
                                                ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                                ->join('tmOrg','tmOrg.OrgID','trPokPir.OrgID')
                                                ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trPokPir.PmKecamatanID')
                                                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trPokPir.PmDesaID')
                                                ->where('trPokPir.TA',\HelperKegiatan::getTahunPerencanaan())                                        
                                                ->where('trPokPir.NamaUsulanKegiatan', 'ilike', '%' . $search['isikriteria'] . '%');
                                                
                                               
                    if ($PemilikPokokID!='all')
                    {
                        $data = $data->where('trPokPir.PemilikPokokID',$PemilikPokokID);
                    }
                    $data = $data->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);
                break;
            }           

            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);              
        }
        else
        {
            $data = PokokPikiranModel::select(\DB::raw('"trPokPir"."PokPirID",
                                                        "tmPemilikPokok"."NmPk",
                                                        "trPokPir"."NamaUsulanKegiatan",
                                                        "trPokPir"."NilaiUsulan",
                                                        "tmOrg"."OrgNm",
                                                        "tmPmKecamatan"."Nm_Kecamatan",
                                                        "tmPmDesa"."Nm_Desa",
                                                        "trPokPir"."Lokasi",
                                                        "trPokPir"."Prioritas",
                                                        "trPokPir"."Privilege",
                                                        "trPokPir".created_at,
                                                        "trPokPir".updated_at
                                                    '))            
                                    ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                    ->join('tmOrg','tmOrg.OrgID','trPokPir.OrgID')
                                    ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trPokPir.PmKecamatanID')
                                    ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trPokPir.PmDesaID')
                                    ->where('trPokPir.TA',\HelperKegiatan::getTahunPerencanaan());
            if ($PemilikPokokID!='all')
            {
                $data = $data->where('trPokPir.PemilikPokokID',$PemilikPokokID);
            }
            $data = $data->orderBy('Prioritas','ASC')
                        ->orderBy($column_order,$direction)
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('pokokpikiran.index'));
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
        
        $this->setCurrentPageInsideSession('pokokpikiran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.pokir.pokokpikiran.datatable")->with(['page_active'=>'pokokpikiran',
                                                                                'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
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
        $this->putControllerStateSession('pokokpikiran','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.pokir.pokokpikiran.datatable")->with(['page_active'=>'pokokpikiran',
                                                            'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
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

        $this->setCurrentPageInsideSession('pokokpikiran',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.pokir.pokokpikiran.datatable")->with(['page_active'=>'pokokpikiran',
                                                                            'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
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

        $filters=$this->getControllerStateSession('pokokpikiran','filters');

        $json_data = [];     
        //index
        if ($request->exists('PemilikPokokID'))
        {
            $PemilikPokokID = $request->input('PemilikPokokID')==''?'none':$request->input('PemilikPokokID');
            $filters['PemilikPokokID']=$PemilikPokokID;
            $this->putControllerStateSession('pokokpikiran','filters',$filters);
            $this->setCurrentPageInsideSession('pokokpikiran',1);

            $data=$this->populateData();
            $datatable = view("pages.$theme.pokir.pokokpikiran.datatable")->with(['page_active'=>'pokokpikiran',
                                                                                    'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
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

        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('pokokpikiran','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pokokpikiran','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pokokpikiran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.pokir.pokokpikiran.datatable")->with(['page_active'=>'pokokpikiran',                                                            
                                                            'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
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

        $filters=$this->getControllerStateSession('pokokpikiran','filters');

        $roles=$auth->getRoleNames(); 
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :                     
                $daftar_dewan=\App\Models\Pokir\PemilikPokokPikiranModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')              
                                                                        ->prepend('SELURUH ANGGOTA DEWAN','all')                                                          
                                                                        ->toArray();                  
            break;
            case 'dewan' :               
                $daftar_dewan=\App\Models\UserDewan::select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                    ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('id',$auth->id)
                                                    ->get()
                                                    ->pluck('NmPk','PemilikPokokID')
                                                    ->toArray(); 
            break;
        }        
        $search=$this->getControllerStateSession('pokokpikiran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pokokpikiran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pokokpikiran',$data->currentPage());

        if ($filters['PemilikPokokID']=='all')
        {
            $paguanggota=\App\Models\DMaster\PaguAnggaranDewanModel::select('Jumlah1')
                                                                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())                                                    
                                                                    ->value('Jumlah1');
        }
        else
        {
            $paguanggota=\App\Models\DMaster\PaguAnggaranDewanModel::select('Jumlah1')
                                                                    ->where('PemilikPokokID',$filters['PemilikPokokID'])                                                    
                                                                    ->value('Jumlah1');
        }
        

        return view("pages.$theme.pokir.pokokpikiran.index")->with(['page_active'=>'pokokpikiran',
                                                                    'filters'=>$filters,
                                                                    'daftar_dewan'=>$daftar_dewan,
                                                                    'paguanggota'=>$paguanggota,
                                                                    'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                    'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                                    'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
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
        
        $roles=$auth->getRoleNames();
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :     
                $daftar_pemilik= \App\Models\Pokir\PemilikPokokPikiranModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')   
                                                                        ->prepend('DAFTAR ANGGOTA DEWAN','none')                                                                     
                                                                        ->toArray();                  
            break;
            case 'dewan' :               
                $daftar_pemilik=\App\Models\UserDewan::select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                    ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('id',$auth->id)
                                                    ->get()
                                                    ->pluck('NmPk','PemilikPokokID')
                                                    ->prepend('DAFTAR ANGGOTA DEWAN','none')
                                                    ->toArray(); 
            break;
        }       
        
        $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);  
        $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),NULL,false);
        return view("pages.$theme.pokir.pokokpikiran.create")->with(['page_active'=>'pokokpikiran',
                                                                    'filters'=>$this->getControllerStateSession('pokokpikiran','filters'),
                                                                    'daftar_pemilik'=>$daftar_pemilik,
                                                                    'daftar_opd'=>$daftar_opd,
                                                                    'daftar_kecamatan'=>$daftar_kecamatan,
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
            'PemilikPokokID'=>'required',
            'OrgID'=>'required',
            'PmKecamatanID'=>'required',
            'NamaUsulanKegiatan'=>'required',
            'Lokasi'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',            
            'Output'=>'required',
            'NilaiUsulan'=>'required',
            'Prioritas'=>'required',
        ]);
        $jeniskeg=$request->has('Jeniskeg')?$request->input('Jeniskeg'):0;
        $pokokpikiran = PokokPikiranModel::create([
            'PokPirID' => uniqid ('uid'),
            'PemilikPokokID' => $request->input('PemilikPokokID'),
            'OrgID' => $request->input('OrgID'),
            'SOrgID' => NULL,
            'PmKecamatanID' => $request->input('PmKecamatanID'),
            'PmDesaID' => $request->input('PmDesaID'),
            'SumberDanaID' => NULL,
            'NamaUsulanKegiatan' => $request->input('NamaUsulanKegiatan'),
            'Lokasi' => $request->input('Lokasi'),
            'Sasaran_Angka' => $request->input('Sasaran_Angka'),
            'Sasaran_Uraian' => $request->input('Sasaran_Uraian'),
            'NilaiUsulan' => $request->input('NilaiUsulan'),
            'Status' => 0,
            'EntryLvl' => 0,
            'Output' => $request->input('Output'),
            'Jeniskeg' => $jeniskeg,
            'Prioritas' => $request->input('Prioritas'),
            'Bobot' => 0,
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
            return redirect(route('pokokpikiran.show',['id'=>$pokokpikiran->PokPirID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = PokokPikiranModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.pokir.pokokpikiran.show")->with(['page_active'=>'pokokpikiran',
                                                                        
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
                $daftar_pemilik= \App\Models\Pokir\PemilikPokokPikiranModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')   
                                                                        ->prepend('DAFTAR ANGGOTA DEWAN','none')                                                                     
                                                                        ->toArray();                  
            break;
            case 'dewan' :               
                $daftar_pemilik=\App\Models\UserDewan::select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                    ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('id',$auth->id)
                                                    ->get()
                                                    ->pluck('NmPk','PemilikPokokID')
                                                    ->prepend('DAFTAR ANGGOTA DEWAN','none')
                                                    ->toArray(); 
            break;
        }       
        
        $data = PokokPikiranModel::findOrFail($id);        
        if (!is_null($data) ) 
        {           
            $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);  
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),NULL,false);
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$data->PmKecamatanID,false);
            return view("pages.$theme.pokir.pokokpikiran.edit")->with(['page_active'=>'pokokpikiran',
                                                                        'daftar_pemilik'=>$daftar_pemilik,
                                                                        'daftar_opd'=>$daftar_opd,
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
        $pokokpikiran = PokokPikiranModel::find($id);
        
        $this->validate($request, [
            'PemilikPokokID'=>'required',
            'OrgID'=>'required',
            'PmKecamatanID'=>'required',
            'NamaUsulanKegiatan'=>'required',
            'Lokasi'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'NilaiUsulan'=>'required',
            'Output'=>'required',
            'Prioritas'=>'required',
        ]);
        
        $pokokpikiran->OrgID = $request->input('OrgID');
        $pokokpikiran->PmKecamatanID = $request->input('PmKecamatanID');
        $pokokpikiran->PmDesaID = $request->input('PmDesaID');
        $pokokpikiran->NamaUsulanKegiatan = $request->input('NamaUsulanKegiatan');
        $pokokpikiran->Lokasi = $request->input('Lokasi');
        $pokokpikiran->Sasaran_Angka = $request->input('Sasaran_Angka');
        $pokokpikiran->Sasaran_Uraian = $request->input('Sasaran_Uraian');
        $pokokpikiran->NilaiUsulan = $request->input('NilaiUsulan');
        $pokokpikiran->Output = $request->input('Output');
        $pokokpikiran->Jeniskeg = $request->input('Jeniskeg');
        $pokokpikiran->Prioritas = $request->input('Prioritas');
        $pokokpikiran->Descr = $request->input('Descr');
       
        $pokokpikiran->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('pokokpikiran.show',['id'=>$pokokpikiran->PokPirID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $pokokpikiran = PokokPikiranModel::find($id);
        $result=$pokokpikiran->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('pokokpikiran'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.pokir.pokokpikiran.datatable")->with(['page_active'=>'pokokpikiran',
                                                            'search'=>$this->getControllerStateSession('pokokpikiran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('pokokpikiran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pokokpikiran.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('pokokpikiran.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
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
        
        $data_report=$this->getControllerStateSession('pokokpikiran','filters');

        if ($data_report['PemilikPokokID'] == 'none')
        {
            return view("pages.$theme.pokir.pokokpikiran.error")->with(['page_active'=>'pokokpikiran',
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon Pemilik Pokok Pikiran untuk di pilih terlebih dahulu.'
                                                                ]);  
        }   
        else
        {
            $generate_date=date('Y-m-d_H_m_s');        
            $report= new \App\Models\Report\ReportPokokPikiranModel ($data_report);
            return $report->download("pokokpikiran_$generate_date.xlsx");
        }     
    }
}