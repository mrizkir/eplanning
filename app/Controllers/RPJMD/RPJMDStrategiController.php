<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDStrategiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RPJMDStrategiController extends Controller {
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
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('rpjmdstrategi','orderby')) 
        {            
           $this->putControllerStateSession('rpjmdstrategi','orderby',['column_name'=>'Nm_Strategi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rpjmdstrategi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rpjmdstrategi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rpjmdstrategi','search')) 
        {
            $search=$this->getControllerStateSession('rpjmdstrategi','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Strategi' :
                    $data = RPJMDStrategiModel::select(\DB::raw('"tmPrioritasStrategiKab"."PrioritasStrategiKabID","tmPrioritasStrategiKab"."PrioritasSasaranKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmPrioritasStrategiKab"."Kd_Strategi") AS "Kd_Strategi","tmPrioritasStrategiKab"."Nm_Strategi","tmPrioritasStrategiKab"."TA"'))
                                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                                                ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')  
                                                ->where(['Kd_Strategi'=>$search['isikriteria']]);
                break;
                case 'Nm_Strategi' :
                    $data = RPJMDStrategiModel::select(\DB::raw('"tmPrioritasStrategiKab"."PrioritasStrategiKabID","tmPrioritasStrategiKab"."PrioritasSasaranKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmPrioritasStrategiKab"."Kd_Strategi") AS "Kd_Strategi","tmPrioritasStrategiKab"."Nm_Strategi","tmPrioritasStrategiKab"."TA"'))
                                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                                                ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')  
                                                ->where('Nm_Strategi', 'ilike', '%' . $search['isikriteria'] . '%');                                        
                break;
            }           
            $data = $data->orderBy('Kd_PrioritasKab','ASC')
                        ->orderBy('Kd_Tujuan','ASC')
                        ->orderBy('Kd_Sasaran','ASC')         
                        ->orderBy('Kd_Strategi','ASC')   
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RPJMDStrategiModel::select(\DB::raw('"tmPrioritasStrategiKab"."PrioritasStrategiKabID","tmPrioritasStrategiKab"."PrioritasSasaranKabID",CONCAT("tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran",\'.\',"tmPrioritasStrategiKab"."Kd_Strategi") AS "Kd_Strategi","tmPrioritasStrategiKab"."Nm_Strategi","tmPrioritasStrategiKab"."TA"'))
                                        ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                                        ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                        ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')  
                                        ->orderBy('Kd_PrioritasKab','ASC')
                                        ->orderBy('Kd_Tujuan','ASC')
                                        ->orderBy('Kd_Sasaran','ASC')         
                                        ->orderBy('Kd_Strategi','ASC')         
                                        ->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rpjmdstrategi.index'));
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
        
        $this->setCurrentPageInsideSession('rpjmdstrategi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdstrategi.datatable")->with(['page_active'=>'rpjmdstrategi',
                                                                                'search'=>$this->getControllerStateSession('rpjmdstrategi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rpjmdstrategi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rpjmdstrategi.orderby','order'),
                                                                                'data'=>$data])->render();      
        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * digunakan untuk mengurutkan record 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        $this->setCurrentPageInsideSession('rpjmdstrategi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rpjmd.rpjmdstrategi.datatable")->with(['page_active'=>'rpjmdstrategi',
                                                                            'search'=>$this->getControllerStateSession('rpjmdstrategi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rpjmdstrategi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rpjmdstrategi.orderby','order'),
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
            $this->destroyControllerStateSession('rpjmdstrategi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rpjmdstrategi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rpjmdstrategi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdstrategi.datatable")->with(['page_active'=>'rpjmdstrategi',                                                            
                                                            'search'=>$this->getControllerStateSession('rpjmdstrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdstrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdstrategi.orderby','order'),
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

        $search=$this->getControllerStateSession('rpjmdstrategi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rpjmdstrategi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rpjmdstrategi',$data->currentPage());
        
        return view("pages.$theme.rpjmd.rpjmdstrategi.index")->with(['page_active'=>'rpjmdstrategi',
                                                'search'=>$this->getControllerStateSession('rpjmdstrategi','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rpjmdstrategi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rpjmdstrategi.orderby','order'),
                                                'data'=>$data]);               
    }
    public function getkodestrategi($id)
    {
        $Kd_Strategi = RPJMDStrategiModel::where('PrioritasSasaranKabID',$id)->count('Kd_Strategi')+1;
        return response()->json(['success'=>true,'Kd_Strategi'=>$Kd_Strategi],200);
    }  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;

        $daftar_sasaran=\App\Models\RPJMD\RPJMDSasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID",CONCAT(\'[\',"tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran",\']. \',"tmPrioritasSasaranKab"."Nm_Sasaran") AS "Nm_Sasaran"'))
                                                            ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')                                    
                                                            ->where('tmPrioritasSasaranKab.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('Kd_PrioritasKab','ASC')
                                                            ->orderBy('Kd_Tujuan','ASC')
                                                            ->orderBy('Kd_Sasaran','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Sasaran','PrioritasSasaranKabID')
                                                            ->toArray();

        return view("pages.$theme.rpjmd.rpjmdstrategi.create")->with(['page_active'=>'rpjmdstrategi',
                                                                    'daftar_sasaran'=>$daftar_sasaran
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
            'Kd_Strategi'=>[new CheckRecordIsExistValidation('tmPrioritasStrategiKab',['where'=>['PrioritasSasaranKabID','=',$request->input('PrioritasSasaranKabID')]]),
                            'required'
                        ],
            'PrioritasSasaranKabID'=>'required',
            'Nm_Strategi'=>'required',
        ]);
        
        $rpjmdstrategi = RPJMDStrategiModel::create([
            'PrioritasStrategiKabID'=> uniqid ('uid'),
            'PrioritasSasaranKabID' => $request->input('PrioritasSasaranKabID'),
            'Kd_Strategi' => $request->input('Kd_Strategi'),
            'Nm_Strategi' => $request->input('Nm_Strategi'),
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
            return redirect(route('rpjmdstrategi.show',['id'=>$rpjmdstrategi->PrioritasStrategiKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RPJMDStrategiModel::select(\DB::raw('"tmPrioritasStrategiKab"."PrioritasStrategiKabID",
                                                    "tmPrioritasSasaranKab"."Kd_Sasaran",
                                                    "tmPrioritasSasaranKab"."Nm_Sasaran",
                                                    "tmPrioritasStrategiKab"."Kd_Strategi",
                                                    "tmPrioritasStrategiKab"."Nm_Strategi",
                                                    "tmPrioritasStrategiKab"."Descr",
                                                    "tmPrioritasStrategiKab"."PrioritasStrategiKabID_Src",
                                                    "tmPrioritasStrategiKab"."created_at",
                                                    "tmPrioritasStrategiKab"."updated_at"'))
                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                                ->findOrFail($id);

        if (!is_null($data) )  
        {
            return view("pages.$theme.rpjmd.rpjmdstrategi.show")->with(['page_active'=>'rpjmdstrategi',
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
        
        $data = RPJMDStrategiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_sasaran=\App\Models\RPJMD\RPJMDSasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID",CONCAT(\'[\',"tmPrioritasKab"."Kd_PrioritasKab",\'.\',"tmPrioritasTujuanKab"."Kd_Tujuan",\'.\',"tmPrioritasSasaranKab"."Kd_Sasaran",\']. \',"tmPrioritasSasaranKab"."Nm_Sasaran") AS "Nm_Sasaran"'))
                                                            ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')                                    
                                                            ->where('tmPrioritasSasaranKab.TA',\HelperKegiatan::getRPJMDTahunMulai())
                                                            ->orderBy('tmPrioritasKab.Kd_PrioritasKab','ASC')
                                                            ->orderBy('tmPrioritasTujuanKab.Kd_Tujuan','ASC')
                                                            ->orderBy('tmPrioritasSasaranKab.Kd_Sasaran','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Sasaran','PrioritasSasaranKabID')
                                                            ->toArray();

            return view("pages.$theme.rpjmd.rpjmdstrategi.edit")->with(['page_active'=>'rpjmdstrategi',
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
        $rpjmdstrategi = RPJMDStrategiModel::find($id);
        
        $this->validate($request, [
            'Kd_Strategi'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasStrategiKab',
                                                                        $rpjmdstrategi->Kd_Strategi,
                                                                        ['where'=>['PrioritasSasaranKabID','=',$request->input('PrioritasSasaranKabID')]],
                                                                        'Kode Strategi')],
            'PrioritasSasaranKabID'=>'required',
            'Nm_Strategi'=>'required',
        ]);
               
        $rpjmdstrategi->PrioritasSasaranKabID = $request->input('PrioritasSasaranKabID');
        $rpjmdstrategi->Kd_Strategi = $request->input('Kd_Strategi');
        $rpjmdstrategi->Nm_Strategi = $request->input('Nm_Strategi');
        $rpjmdstrategi->Descr = $request->input('Descr');
        $rpjmdstrategi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdstrategi.show',['id'=>$rpjmdstrategi->PrioritasStrategiKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $rpjmdstrategi = RPJMDStrategiModel::find($id);
        $result=$rpjmdstrategi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('rpjmdstrategi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rpjmd.rpjmdstrategi.datatable")->with(['page_active'=>'rpjmdstrategi',
                                                            'search'=>$this->getControllerStateSession('rpjmdstrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('rpjmdstrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdstrategi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rpjmdstrategi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
