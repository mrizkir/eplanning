<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDSasaranModel;
use App\Models\RPJMD\RPJMDSasaranIndikatorModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RPJMDSasaranController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang']);
    }
    private function populateIndikatorSasaran($PrioritasSasaranKabID)
    {
      
        $data = RPJMDSasaranIndikatorModel::where('PrioritasSasaranKabID',$PrioritasSasaranKabID)
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
        if (!$this->checkStateIsExistSession('rpjmdsasaran','orderby')) 
        {            
           $this->putControllerStateSession('rpjmdsasaran','orderby',['column_name'=>'Nm_Sasaran','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rpjmdsasaran.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rpjmdsasaran','search')) 
        {
            $search=$this->getControllerStateSession('rpjmdsasaran','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Sasaran' :
                    $data = RPJMDSasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID","tmPrioritasSasaranKab"."PrioritasTujuanKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran") AS "Kd_Sasaran","tmPrioritasSasaranKab"."Nm_Sasaran","tmPrioritasSasaranKab"."TA"'))
                                            ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')              
                                            ->where(['Kd_Sasaran'=>$search['isikriteria']])
                                            ->orderBy('Kd_PrioritasKab','ASC')
                                            ->orderBy('Kd_Tujuan','ASC')
                                            ->orderBy('Kd_Sasaran','ASC'); 
                break;
                case 'Nm_Sasaran' :
                    $data = RPJMDSasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID","tmPrioritasSasaranKab"."PrioritasTujuanKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran") AS "Kd_Sasaran","tmPrioritasSasaranKab"."Nm_Sasaran","tmPrioritasSasaranKab"."TA"'))
                                            ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')                                    
                                            ->where('Nm_Sasaran', 'ilike', '%' . $search['isikriteria'] . '%')
                                            ->orderBy('Kd_PrioritasKab','ASC')
                                            ->orderBy('Kd_Tujuan','ASC')
                                            ->orderBy('Kd_Sasaran','ASC');                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RPJMDSasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID","tmPrioritasSasaranKab"."PrioritasTujuanKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran") AS "Kd_Sasaran","tmPrioritasSasaranKab"."Nm_Sasaran","tmPrioritasSasaranKab"."TA"'))
                                    ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                    ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')                                    
                                    ->orderBy('Kd_PrioritasKab','ASC')
                                    ->orderBy('Kd_Tujuan','ASC')
                                    ->orderBy('Kd_Sasaran','ASC')
                                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rpjmdsasaran.index'));
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
        
        $this->setCurrentPageInsideSession('rpjmdsasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdsasaran.datatable")->with(['page_active'=>'rpjmdsasaran',
                                                                                'search'=>$this->getControllerStateSession('rpjmdsasaran','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rpjmdsasaran.orderby','order'),
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
            case 'col-Kd_Sasaran' :
                $column_name = 'Kd_Sasaran';
            break;           
            case 'col-Nm_Sasaran' :
                $column_name = 'Nm_Sasaran';
            break;           
            default :
                $column_name = 'Nm_Sasaran';
        }
        $this->putControllerStateSession('rpjmdsasaran','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdsasaran.datatable")->with(['page_active'=>'rpjmdsasaran',
                                                            'search'=>$this->getControllerStateSession('rpjmdsasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdsasaran.orderby','order'),
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

        $this->setCurrentPageInsideSession('rpjmdsasaran',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rpjmd.rpjmdsasaran.datatable")->with(['page_active'=>'rpjmdsasaran',
                                                                            'search'=>$this->getControllerStateSession('rpjmdsasaran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rpjmdsasaran.orderby','order'),
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
            $this->destroyControllerStateSession('rpjmdsasaran','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rpjmdsasaran','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rpjmdsasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdsasaran.datatable")->with(['page_active'=>'rpjmdsasaran',                                                            
                                                            'search'=>$this->getControllerStateSession('rpjmdsasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdsasaran.orderby','order'),
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

        $search=$this->getControllerStateSession('rpjmdsasaran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rpjmdsasaran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rpjmdsasaran',$data->currentPage());
        
        return view("pages.$theme.rpjmd.rpjmdsasaran.index")->with(['page_active'=>'rpjmdsasaran',
                                                'search'=>$this->getControllerStateSession('rpjmdsasaran','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rpjmdsasaran.orderby','order'),
                                                'data'=>$data]);               
    }
    public function getkodesasaran($id)
    {
        $Kd_Sasaran = RPJMDSasaranModel::where('PrioritasTujuanKabID',$id)->count('Kd_Sasaran')+1;
        return response()->json(['success'=>true,'Kd_Sasaran'=>$Kd_Sasaran],200);
    }  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $daftar_tujuan=\App\Models\RPJMD\RPJMDTujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID",CONCAT(\'[\',"tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\']. \',"tmPrioritasTujuanKab"."Nm_Tujuan") AS "Nm_Tujuan"'))
                                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                                            ->where('tmPrioritasTujuanKab.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('tmPrioritasKab.Kd_PrioritasKab','ASC')
                                                            ->orderBy('tmPrioritasTujuanKab.Kd_Tujuan','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Tujuan','PrioritasTujuanKabID')
                                                            ->toArray();
        
        return view("pages.$theme.rpjmd.rpjmdsasaran.create")->with(['page_active'=>'rpjmdsasaran',
                                                                    'daftar_tujuan'=>$daftar_tujuan
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
            'Kd_Sasaran'=>[new CheckRecordIsExistValidation('tmPrioritasSasaranKab',['where'=>['PrioritasTujuanKabID','=',$request->input('PrioritasTujuanKabID')]]),
                            'required'
                        ],
            'PrioritasTujuanKabID'=>'required',
            'Nm_Sasaran'=>'required',
        ]);
        
        $rpjmdsasaran = RPJMDSasaranModel::create([
            'PrioritasSasaranKabID'=> uniqid ('uid'),
            'PrioritasTujuanKabID' => $request->input('PrioritasTujuanKabID'),
            'Kd_Sasaran' => $request->input('Kd_Sasaran'),
            'Nm_Sasaran' => $request->input('Nm_Sasaran'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getRPJMDTahunMulai()
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
            return redirect(route('rpjmdsasaran.show',['id'=>$rpjmdsasaran->PrioritasSasaranKabID]))->with('success','Data ini telah berhasil disimpan.');
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
            'KondisiAwal'=>'required',
            'N1'=>'required',
            'N2'=>'required',
            'N3'=>'required',
            'N4'=>'required',
            'N5'=>'required',
            'KondisiAkhir'=>'required',
            'Satuan'=>'required',
        ]);
        
        $rpjmdindikatorsasaran = RPJMDSasaranIndikatorModel::create([
            'PrioritasIndikatorSasaranID'=> uniqid ('uid'),
            'PrioritasSasaranKabID' => $request->input('PrioritasSasaranKabID'),
            'NamaIndikator' => $request->input('NamaIndikator'),
            'KondisiAwal' => $request->input('KondisiAwal'),
            'N1' => $request->input('N1'),
            'N2' => $request->input('N2'),
            'N3' => $request->input('N3'),
            'N4' => $request->input('N4'),
            'N5' => $request->input('N5'),
            'KondisiAkhir' => $request->input('KondisiAkhir'),
            'Satuan' => $request->input('Satuan'),
            'Operator' => $request->input('Operator'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getRPJMDTahunMulai()
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
            return redirect(route('rpjmdsasaran.show',['id'=>$rpjmdindikatorsasaran->PrioritasSasaranKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RPJMDSasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID",
                                                    "tmPrioritasTujuanKab"."Kd_Tujuan",
                                                    "tmPrioritasTujuanKab"."Nm_Tujuan",
                                                    "tmPrioritasSasaranKab"."Kd_Sasaran",
                                                    "tmPrioritasSasaranKab"."Nm_Sasaran",
                                                    "tmPrioritasSasaranKab"."Descr",
                                                    "tmPrioritasSasaranKab"."PrioritasSasaranKabID_Src",
                                                    "tmPrioritasSasaranKab"."created_at",
                                                    "tmPrioritasSasaranKab"."updated_at"'))
                                ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            $dataindikatorsasaran=$this->populateIndikatorSasaran($id);
            return view("pages.$theme.rpjmd.rpjmdsasaran.show")->with(['page_active'=>'rpjmdsasaran',
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
        
        $data = RPJMDSasaranModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_tujuan=\App\Models\RPJMD\RPJMDTujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID",CONCAT(\'[\',"tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\']. \',"tmPrioritasTujuanKab"."Nm_Tujuan") AS "Nm_Tujuan"'))
                                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                                            ->where('tmPrioritasTujuanKab.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('tmPrioritasKab.Kd_PrioritasKab','ASC')
                                                            ->orderBy('tmPrioritasTujuanKab.Kd_Tujuan','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Tujuan','PrioritasTujuanKabID')
                                                            ->toArray();

            return view("pages.$theme.rpjmd.rpjmdsasaran.edit")->with(['page_active'=>'rpjmdsasaran',
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
        
        $data_indikator = RPJMDSasaranIndikatorModel::findOrFail($id);
        $data = RPJMDSasaranModel::find($data_indikator->PrioritasSasaranKabID);
        if (!is_null($data) ) 
        {        
            $dataindikatorsasaran=$this->populateIndikatorSasaran($data_indikator->PrioritasSasaranKabID);
            return view("pages.$theme.rpjmd.rpjmdsasaran.edit1")->with(['page_active'=>'rpjmdsasaran',
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
        $rpjmdsasaran = RPJMDSasaranModel::find($id);
        
        $this->validate($request, [
            'Kd_Sasaran'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasSasaranKab',
                                                                        $rpjmdsasaran->Kd_Sasaran,
                                                                        ['where'=>['PrioritasTujuanKabID','=',$request->input('PrioritasTujuanKabID')]],
                                                                        'Kode Sasaran')],
            'PrioritasTujuanKabID'=>'required',
            'Nm_Sasaran'=>'required',
        ]);
               
        $rpjmdsasaran->PrioritasTujuanKabID = $request->input('PrioritasTujuanKabID');
        $rpjmdsasaran->Kd_Sasaran = $request->input('Kd_Sasaran');
        $rpjmdsasaran->Nm_Sasaran = $request->input('Nm_Sasaran');
        $rpjmdsasaran->Descr = $request->input('Descr');
        $rpjmdsasaran->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdsasaran.show',['id'=>$rpjmdsasaran->PrioritasSasaranKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        $rpjmdindikatorsasaran = RPJMDSasaranIndikatorModel::find($id);

        $this->validate($request, [            
            'NamaIndikator'=>'required',
            'KondisiAwal'=>'required',
            'N1'=>'required',
            'N2'=>'required',
            'N3'=>'required',
            'N4'=>'required',
            'N5'=>'required',
            'KondisiAkhir'=>'required',
            'Satuan'=>'required',
        ]);
        
        $rpjmdindikatorsasaran->NamaIndikator = $request->input('NamaIndikator');
        $rpjmdindikatorsasaran->KondisiAwal = $request->input('KondisiAwal');
        $rpjmdindikatorsasaran->N1 = $request->input('KondisiAwal');
        $rpjmdindikatorsasaran->N2 = $request->input('N2');
        $rpjmdindikatorsasaran->N3 = $request->input('N3');
        $rpjmdindikatorsasaran->N4 = $request->input('N4');
        $rpjmdindikatorsasaran->N5 = $request->input('N5');
        $rpjmdindikatorsasaran->KondisiAkhir = $request->input('KondisiAkhir');
        $rpjmdindikatorsasaran->Satuan = $request->input('Satuan');
        $rpjmdindikatorsasaran->Operator = $request->input('Operator');
        $rpjmdindikatorsasaran->Descr = $request->input('Descr');
        $rpjmdindikatorsasaran->save();
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdsasaran.show',['id'=>$rpjmdindikatorsasaran->PrioritasSasaranKabID]))->with('success','Data ini telah berhasil disimpan.');
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
            $rpjmdsasaran = RPJMDSasaranIndikatorModel::find($id);
            $PrioritasSasaranKabID=$rpjmdsasaran->PrioritasSasaranKabID;
            $result=$rpjmdsasaran->delete();
            if ($request->ajax()) 
            {                
                $dataindikatorsasaran = $this->populateIndikatorSasaran($PrioritasSasaranKabID);                
                $datatable = view("pages.$theme.rpjmd.rpjmdsasaran.datatableindikatorsasaran")->with(['page_active'=>'rpjmdsasaran',                                                                                    
                                                                                                    'dataindikatorsasaran'=>$dataindikatorsasaran])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('rpjmdsasaran.show',['id'=>$PrioritasSasaranKabID]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }       
        }
        else
        {
            $rpjmdsasaran = RPJMDSasaranModel::find($id);
            $result=$rpjmdsasaran->delete();
            if ($request->ajax()) 
            {
                $currentpage=$this->getCurrentPageInsideSession('rpjmdsasaran'); 
                $data=$this->populateData($currentpage);
                if ($currentpage > $data->lastPage())
                {            
                    $data = $this->populateData($data->lastPage());
                }
                $datatable = view("pages.$theme.rpjmd.rpjmdsasaran.datatable")->with(['page_active'=>'rpjmdsasaran',
                                                                'search'=>$this->getControllerStateSession('rpjmdsasaran','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('rpjmdsasaran.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('rpjmdsasaran.orderby','order'),
                                                                'data'=>$data])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('rpjmdsasaran.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }        
    }
}
