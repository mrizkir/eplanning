<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDStrategiModel;
use App\Models\RPJMD\RPJMDKebijakanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RPJMDKebijakanController extends Controller {
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
        if (!$this->checkStateIsExistSession('rpjmdkebijakan','orderby')) 
        {            
           $this->putControllerStateSession('rpjmdkebijakan','orderby',['column_name'=>'Nm_Kebijakan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rpjmdkebijakan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rpjmdkebijakan','search')) 
        {
            $search=$this->getControllerStateSession('rpjmdkebijakan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Kebijakan' :
                    $data = RPJMDKebijakanModel::where(['Kd_Kebijakan'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_Kebijakan' :
                    $data = RPJMDKebijakanModel::where('Nm_Kebijakan', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RPJMDKebijakanModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rpjmdkebijakan.index'));
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
        
        $this->setCurrentPageInsideSession('rpjmdkebijakan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdkebijakan.datatable")->with(['page_active'=>'rpjmdkebijakan',
                                                                                'search'=>$this->getControllerStateSession('rpjmdkebijakan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','order'),
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
            case 'col-Kd_Kebijakan' :
                $column_name = 'Kd_Kebijakan';
            break;           
            case 'col-Nm_Kebijakan' :
                $column_name = 'Nm_Kebijakan';
            break;           
            default :
                $column_name = 'Nm_Kebijakan';
        }
        $this->putControllerStateSession('rpjmdkebijakan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdkebijakan.datatable")->with(['page_active'=>'rpjmdkebijakan',
                                                            'search'=>$this->getControllerStateSession('rpjmdkebijakan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','order'),
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

        $this->setCurrentPageInsideSession('rpjmdkebijakan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rpjmd.rpjmdkebijakan.datatable")->with(['page_active'=>'rpjmdkebijakan',
                                                                            'search'=>$this->getControllerStateSession('rpjmdkebijakan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','order'),
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
            $this->destroyControllerStateSession('rpjmdkebijakan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rpjmdkebijakan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rpjmdkebijakan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdkebijakan.datatable")->with(['page_active'=>'rpjmdkebijakan',                                                            
                                                            'search'=>$this->getControllerStateSession('rpjmdkebijakan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','order'),
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

        $search=$this->getControllerStateSession('rpjmdkebijakan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rpjmdkebijakan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rpjmdkebijakan',$data->currentPage());
        
        return view("pages.$theme.rpjmd.rpjmdkebijakan.index")->with(['page_active'=>'rpjmdkebijakan',
                                                'search'=>$this->getControllerStateSession('rpjmdkebijakan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','order'),
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
        $daftar_strategi=RPJMDStrategiModel::getRPJDMStrategi(\HelperKegiatan::getTahunPerencanaan(),false);
        return view("pages.$theme.rpjmd.rpjmdkebijakan.create")->with(['page_active'=>'rpjmdkebijakan',
                                                                    'daftar_strategi'=>$daftar_strategi
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
            'Kd_Kebijakan'=>[new CheckRecordIsExistValidation('tmPrioritasKebijakanKab',['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]]),
                            'required'
                        ],
            'PrioritasStrategiKabID'=>'required',
            'Nm_Kebijakan'=>'required',
        ]);
        
        $rpjmdkebijakan = RPJMDKebijakanModel::create([
            'PrioritasKebijakanKabID'=> uniqid ('uid'),
            'PrioritasStrategiKabID' => $request->input('PrioritasStrategiKabID'),
            'Kd_Kebijakan' => $request->input('Kd_Kebijakan'),
            'Nm_Kebijakan' => $request->input('Nm_Kebijakan'),
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
            return redirect(route('rpjmdkebijakan.show',['id'=>$rpjmdkebijakan->PrioritasKebijakanKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RPJMDKebijakanModel::findOrFail($id);

         $data = RPJMDKebijakanModel::select(\DB::raw('"tmPrioritasKebijakanKab"."PrioritasKebijakanKabID",
                                                    "tmPrioritasStrategiKab"."Kd_Strategi",
                                                    "tmPrioritasStrategiKab"."Nm_Strategi",
                                                    "tmPrioritasKebijakanKab"."Kd_Kebijakan",
                                                    "tmPrioritasKebijakanKab"."Nm_Kebijakan",
                                                    "tmPrioritasKebijakanKab"."Descr",
                                                    "tmPrioritasKebijakanKab"."PrioritasKebijakanKabID_Src",
                                                    "tmPrioritasKebijakanKab"."created_at",
                                                    "tmPrioritasKebijakanKab"."updated_at"'))
                                ->join('tmPrioritasStrategiKab','tmPrioritasStrategiKab.PrioritasStrategiKabID','tmPrioritasKebijakanKab.PrioritasStrategiKabID')
                                ->findOrFail($id);

        if (!is_null($data) )  
        {
            return view("pages.$theme.rpjmd.rpjmdkebijakan.show")->with(['page_active'=>'rpjmdkebijakan',
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
        
        $data = RPJMDKebijakanModel::findOrFail($id);                
        if (!is_null($data) ) 
        {
            $daftar_strategi=RPJMDStrategiModel::getRPJDMStrategi($data->TA,false);
            return view("pages.$theme.rpjmd.rpjmdkebijakan.edit")->with(['page_active'=>'rpjmdkebijakan',
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
        $rpjmdkebijakan = RPJMDKebijakanModel::find($id);
        
        $this->validate($request, [
            'Kd_Kebijakan'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasKebijakanKab',
                                                                        $rpjmdkebijakan->Kd_Kebijakan,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]],
                                                                        'Kode Strategi')],
            'PrioritasStrategiKabID'=>'required',
            'Nm_Kebijakan'=>'required',
        ]);
               
        $rpjmdkebijakan->PrioritasStrategiKabID = $request->input('PrioritasStrategiKabID');
        $rpjmdkebijakan->Kd_Kebijakan = $request->input('Kd_Kebijakan');
        $rpjmdkebijakan->Nm_Kebijakan = $request->input('Nm_Kebijakan');
        $rpjmdkebijakan->Descr = $request->input('Descr');
        $rpjmdkebijakan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdkebijakan.show',['id'=>$rpjmdkebijakan->PrioritasKebijakanKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $rpjmdkebijakan = RPJMDKebijakanModel::find($id);
        $result=$rpjmdkebijakan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('rpjmdkebijakan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rpjmd.rpjmdkebijakan.datatable")->with(['page_active'=>'rpjmdkebijakan',
                                                            'search'=>$this->getControllerStateSession('rpjmdkebijakan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdkebijakan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rpjmdkebijakan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
