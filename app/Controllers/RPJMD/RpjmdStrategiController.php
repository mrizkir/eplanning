<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDStrategiModel;

class RPJMDStrategiController extends Controller {
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
                    $data = RPJMDStrategiModel::where(['Kd_Strategi'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_Strategi' :
                    $data = RPJMDStrategiModel::where('Nm_Strategi', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RPJMDStrategiModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
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
    public function orderby (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $orderby = $request->input('orderby') == 'asc'?'desc':'asc';
        $column=$request->input('column_name');
        switch($column) 
        {
            case 'Nm_Strategi' :
                $column_name = 'Nm_Strategi';
            break;           
            default :
                $column_name = 'Nm_Strategi';
        }
        $this->putControllerStateSession('rpjmdstrategi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;

        return view("pages.$theme.rpjmd.rpjmdstrategi.create")->with(['page_active'=>'rpjmdstrategi',
                                                                    
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
            'replaceit'=>'required',
        ]);
        
        $rpjmdstrategi = RPJMDStrategiModel::create([
            'replaceit' => $request->input('replaceit'),
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
            return redirect(route('rpjmdstrategi.index'))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RPJMDStrategiModel::findOrFail($id);
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
            return view("pages.$theme.rpjmd.rpjmdstrategi.edit")->with(['page_active'=>'rpjmdstrategi',
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
        $rpjmdstrategi = RPJMDStrategiModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $rpjmdstrategi->replaceit = $request->input('replaceit');
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
            return redirect(route('rpjmdstrategi.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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