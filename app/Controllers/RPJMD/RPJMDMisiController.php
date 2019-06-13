<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDMisiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RPJMDMisiController extends Controller {
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
        if (!$this->checkStateIsExistSession('rpjmdmisi','orderby')) 
        {            
           $this->putControllerStateSession('rpjmdmisi','orderby',['column_name'=>'Nm_PrioritasKab','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rpjmdmisi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rpjmdmisi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rpjmdmisi','search')) 
        {
            $search=$this->getControllerStateSession('rpjmdmisi','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_PrioritasKab' :
                    $data = RPJMDMisiModel::where(['Kd_PrioritasKab'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_PrioritasKab' :
                    $data = RPJMDMisiModel::where('Nm_PrioritasKab', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RPJMDMisiModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rpjmdmisi.index'));
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
        
        $this->setCurrentPageInsideSession('rpjmdmisi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdmisi.datatable")->with(['page_active'=>'rpjmdmisi',
                                                                                'search'=>$this->getControllerStateSession('rpjmdmisi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rpjmdmisi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rpjmdmisi.orderby','order'),
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
            case 'col-Nm_PrioritasKab' :
                $column_name = 'Nm_PrioritasKab';
            break;           
            default :
                $column_name = 'Nm_PrioritasKab';
        }
        $this->putControllerStateSession('rpjmdmisi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdmisi.datatable")->with(['page_active'=>'rpjmdmisi',
                                                            'search'=>$this->getControllerStateSession('rpjmdmisi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdmisi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdmisi.orderby','order'),
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

        $this->setCurrentPageInsideSession('rpjmdmisi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rpjmd.rpjmdmisi.datatable")->with(['page_active'=>'rpjmdmisi',
                                                                            'search'=>$this->getControllerStateSession('rpjmdmisi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rpjmdmisi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rpjmdmisi.orderby','order'),
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
            $this->destroyControllerStateSession('rpjmdmisi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rpjmdmisi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rpjmdmisi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdmisi.datatable")->with(['page_active'=>'rpjmdmisi',                                                            
                                                            'search'=>$this->getControllerStateSession('rpjmdmisi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdmisi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdmisi.orderby','order'),
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

        $search=$this->getControllerStateSession('rpjmdmisi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rpjmdmisi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rpjmdmisi',$data->currentPage());
        
        return view("pages.$theme.rpjmd.rpjmdmisi.index")->with(['page_active'=>'rpjmdmisi',
                                                'search'=>$this->getControllerStateSession('rpjmdmisi','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rpjmdmisi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rpjmdmisi.orderby','order'),
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

        return view("pages.$theme.rpjmd.rpjmdmisi.create")->with(['page_active'=>'rpjmdmisi',
                                                                    
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
            'Kd_PrioritasKab'=>[new CheckRecordIsExistValidation('tmPrioritasKab',['where'=>['TA','=',config('eplanning.tahun_perencanaan')]]),
                        'required'
                    ],
            'Nm_PrioritasKab'=>'required',
        ]);
        
        $rpjmdmisi = RPJMDMisiModel::create([
            'PrioritasKabID'=> uniqid ('uid'),
            'Kd_PrioritasKab' => $request->input('Kd_PrioritasKab'),
            'Nm_PrioritasKab' => $request->input('Nm_PrioritasKab'),
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
            return redirect(route('rpjmdmisi.show',['id'=>$rpjmdmisi->PrioritasKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RPJMDMisiModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rpjmd.rpjmdmisi.show")->with(['page_active'=>'rpjmdmisi',
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
        
        $data = RPJMDMisiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.rpjmd.rpjmdmisi.edit")->with(['page_active'=>'rpjmdmisi',
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
        $rpjmdmisi = RPJMDMisiModel::find($id);
        
        $this->validate($request, [
            'Kd_PrioritasKab'=>[new IgnoreIfDataIsEqualValidation('tmPrioritasKab',$rpjmdmisi->Kd_PrioritasKab,['where'=>['TA','=',config('eplanning.tahun_perencanaan')]]),
                        'required'
                    ],
            'Nm_PrioritasKab'=>'required|min:2'
        ]);
        
        $rpjmdmisi->Kd_PrioritasKab = $request->input('Kd_PrioritasKab');
        $rpjmdmisi->Nm_PrioritasKab = $request->input('Nm_PrioritasKab');
        $rpjmdmisi->Descr = $request->input('Descr');
        $rpjmdmisi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdmisi.show',['id'=>$rpjmdmisi->PrioritasKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $rpjmdmisi = RPJMDMisiModel::find($id);
        $result=$rpjmdmisi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('rpjmdmisi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rpjmd.rpjmdmisi.datatable")->with(['page_active'=>'rpjmdmisi',
                                                            'search'=>$this->getControllerStateSession('rpjmdmisi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('rpjmdmisi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdmisi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rpjmdmisi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
