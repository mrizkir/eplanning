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
        if ($this->checkStateIsExistSession('pokokpikiran','search')) 
        {
            $search=$this->getControllerStateSession('pokokpikiran','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = PokokPikiranModel::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = PokokPikiranModel::where('replaceit', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = PokokPikiranModel::select(\DB::raw('"trPokPir"."PokPirID",
                                                        "tmPemilikPokok"."Kd_PK",
                                                        "tmPemilikPokok"."NmPk",
                                                        "trPokPir"."NamaUsulanKegiatan",
                                                        "tmOrg"."OrgNm",
                                                        "trPokPir"."Prioritas"
                                                    '))            
                                    ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                    ->join('tmOrg','tmOrg.OrgID','trPokPir.OrgID')
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
            case 'replace_it' :
                $column_name = 'replace_it';
            break;           
            default :
                $column_name = 'replace_it';
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

        $json_data = [];               
        //create4
        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(config('eplanning.tahun_perencanaan'),$PmKecamatanID,false);
                                                                                    
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
        $theme = \Auth::user()->theme;

        $search=$this->getControllerStateSession('pokokpikiran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pokokpikiran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pokokpikiran',$data->currentPage());
        
        return view("pages.$theme.pokir.pokokpikiran.index")->with(['page_active'=>'pokokpikiran',
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
        $theme = \Auth::user()->theme;
        $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters');  
        $daftar_pemilik= \App\Models\Pokir\PemilikPokokPikiranModel::where('TA',config('eplanning.tahun_perencanaan')) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')   
                                                                        ->prepend('DAFTAR PEMILIK POKOK PIKIRAN','none')                                                                     
                                                                        ->toArray();
        $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('eplanning.tahun_perencanaan'),false);  
        $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(config('eplanning.tahun_perencanaan'),NULL,false);
        return view("pages.$theme.pokir.pokokpikiran.create")->with(['page_active'=>'pokokpikiran',
                                                                    'filters'=>$filters,
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
            'NilaiUsulan' => 0,
            'Status' => 0,
            'EntryLvl' => 0,
            'Output' => $request->input('Output'),
            'Jeniskeg' => $jeniskeg,
            'Prioritas' => $request->input('Prioritas'),
            'Bobot' => 0,
            'Descr' => $request->input('Descr'),
            'TA' => config('eplanning.tahun_perencanaan'),
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
            return redirect(route('pokokpikiran.index'))->with('success','Data ini telah berhasil disimpan.');
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
        $theme = \Auth::user()->theme;
        
        $data = PokokPikiranModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.pokir.pokokpikiran.edit")->with(['page_active'=>'pokokpikiran',
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
            'replaceit'=>'required',
        ]);
        
        $pokokpikiran->replaceit = $request->input('replaceit');
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
            return redirect(route('pokokpikiran.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
}