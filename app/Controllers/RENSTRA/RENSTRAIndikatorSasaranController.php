<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRAIndikatorSasaranModel;
use App\Models\RENSTRA\RENSTRAKebijakanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRAIndikatorKinerjaController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstraindikatorkinerja','orderby')) 
        {            
           $this->putControllerStateSession('renstraindikatorkinerja','orderby',['column_name'=>'NamaIndikator','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('renstraindikatorkinerja','search')) 
        {
            $search=$this->getControllerStateSession('renstraindikatorkinerja','search');
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
            $data = RENSTRAIndikatorSasaranModel::where('TA_N',config('eplanning.renstra_tahun_mulai'))
                                                ->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstraindikatorkinerja.index'));
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
        
        $this->setCurrentPageInsideSession('renstraindikatorkinerja',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstraindikatorkinerja.datatable")->with(['page_active'=>'renstraindikatorkinerja',
                                                                                'search'=>$this->getControllerStateSession('renstraindikatorkinerja','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'),
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
        $this->putControllerStateSession('renstraindikatorkinerja','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstraindikatorkinerja');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.renstra.renstraindikatorkinerja.datatable")->with(['page_active'=>'renstraindikatorkinerja',
                                                            'search'=>$this->getControllerStateSession('renstraindikatorkinerja','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstraindikatorkinerja',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstraindikatorkinerja.datatable")->with(['page_active'=>'renstraindikatorkinerja',
                                                                            'search'=>$this->getControllerStateSession('renstraindikatorkinerja','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'),
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
            $this->destroyControllerStateSession('renstraindikatorkinerja','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstraindikatorkinerja','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstraindikatorkinerja',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstraindikatorkinerja.datatable")->with(['page_active'=>'renstraindikatorkinerja',                                                            
                                                            'search'=>$this->getControllerStateSession('renstraindikatorkinerja','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'),
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
        $json_data = [];
        //create
        if ($request->exists('UrsID') && $request->exists('create') )
        {
            $UrsID = $request->input('UrsID')==''?'none':$request->input('UrsID');            
            $daftar_program=\App\Models\DMaster\ProgramModel::getDaftarProgram(config('eplanning.tahun_perencanaan'),false,$UrsID);
            $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('eplanning.tahun_perencanaan'),false,$UrsID);
            $json_data = ['success'=>true,'daftar_program'=>$daftar_program,'daftar_opd'=>$daftar_opd];
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
        $theme = \Auth::user()->theme;

        $search=$this->getControllerStateSession('renstraindikatorkinerja','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstraindikatorkinerja'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstraindikatorkinerja',$data->currentPage());
        
        return view("pages.$theme.renstra.renstraindikatorkinerja.index")->with(['page_active'=>'renstraindikatorkinerja',
                                                'search'=>$this->getControllerStateSession('renstraindikatorkinerja','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'),
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
        $daftar_kebijakan = RENSTRAKebijakanModel::getDaftarKebijakan(config('eplanning.tahun_perencanaan'),false);
        $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);
        return view("pages.$theme.renstra.renstraindikatorkinerja.create")->with(['page_active'=>'renstraindikatorkinerja',
                                                                                'daftar_kebijakan'=>$daftar_kebijakan,
                                                                                'daftar_urusan'=>$daftar_urusan
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
        
        $renstraindikatorkinerja = RENSTRAIndikatorSasaranModel::create([
            'IndikatorKinerjaID' => uniqid ('uid'),
            'PrioritasKebijakanKabID' => $request->input('PrioritasKebijakanKabID'),
            'UrsID' => $request->input('UrsID'),
            'PrgID' => $request->input('PrgID'),
            'NamaIndikator' => $request->input('NamaIndikator'),
            'TA_N'=>config('eplanning.renstra_tahun_mulai'),
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
            'TA' => config('eplanning.tahun_perencanaan')            
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
            return redirect(route('renstraindikatorkinerja.show',['id'=>$renstraindikatorkinerja->IndikatorKinerjaID]))->with('success','Data ini telah berhasil disimpan.');
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
            return view("pages.$theme.renstra.renstraindikatorkinerja.show")->with(['page_active'=>'renstraindikatorkinerja',
                                                                                'data'=>$data
                                                                                ]);
        }
        else
        {
            return view("pages.$theme.renstra.renstraindikatorkinerja.error")->with(['page_active'=>'renstraindikatorkinerja',
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
            $daftar_kebijakan = RENSTRAKebijakanModel::getDaftarKebijakan(config('eplanning.tahun_perencanaan'),false);
            $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);
            $daftar_program=\App\Models\DMaster\ProgramModel::getDaftarProgram(config('eplanning.tahun_perencanaan'),false,$data['UrsID']);
            $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('eplanning.tahun_perencanaan'),false,$data['UrsID']);
            return view("pages.$theme.renstra.renstraindikatorkinerja.edit")->with(['page_active'=>'renstraindikatorkinerja',
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
        $renstraindikatorkinerja = RENSTRAIndikatorSasaranModel::find($id);
        
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
        
        $renstraindikatorkinerja->PrioritasKebijakanKabID = $request->input('PrioritasKebijakanKabID');
        $renstraindikatorkinerja->UrsID = $request->input('UrsID');
        $renstraindikatorkinerja->PrgID = $request->input('PrgID');
        $renstraindikatorkinerja->NamaIndikator = $request->input('NamaIndikator');
        $renstraindikatorkinerja->TargetAwal = $request->input('TargetAwal');
        $renstraindikatorkinerja->OrgID = $request->input('OrgID');
        $renstraindikatorkinerja->OrgID2 = $request->input('OrgID2');
        $renstraindikatorkinerja->PaguDanaN1 = $request->input('PaguDanaN1');
        $renstraindikatorkinerja->PaguDanaN2 = $request->input('PaguDanaN2');
        $renstraindikatorkinerja->PaguDanaN3 = $request->input('PaguDanaN3');
        $renstraindikatorkinerja->PaguDanaN4 = $request->input('PaguDanaN4');
        $renstraindikatorkinerja->PaguDanaN5 = $request->input('PaguDanaN5');
        $renstraindikatorkinerja->TargetN1 = $request->input('TargetN1');
        $renstraindikatorkinerja->TargetN2 = $request->input('TargetN2');
        $renstraindikatorkinerja->TargetN3 = $request->input('TargetN3');
        $renstraindikatorkinerja->TargetN4 = $request->input('TargetN4');
        $renstraindikatorkinerja->TargetN5 = $request->input('TargetN5');
        $renstraindikatorkinerja->Descr = $request->input('Descr');

        $renstraindikatorkinerja->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstraindikatorkinerja.show',['id'=>$id]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $renstraindikatorkinerja = RENSTRAIndikatorSasaranModel::find($id);
        $result=$renstraindikatorkinerja->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstraindikatorkinerja'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstraindikatorkinerja.datatable")->with(['page_active'=>'renstraindikatorkinerja',
                                                            'search'=>$this->getControllerStateSession('renstraindikatorkinerja','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstraindikatorkinerja.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstraindikatorkinerja.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
