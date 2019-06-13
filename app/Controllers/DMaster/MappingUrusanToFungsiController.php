<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\MappingUrusanToFungsiModel;

class MappingUrusanToFungsiController extends Controller {
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
        //if (!$this->checkStateIsExistSession('mappingurusantofungsi','orderby')) 
        //{            
        //    $this->putControllerStateSession('mappingurusantofungsi','orderby',['column_name'=>'replace_it','order'=>'asc']);
        //}
        //$column_order=$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'); 
        //$direction=$this->getControllerStateSession('mappingurusantofungsi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('mappingurusantofungsi','search')) 
        {
            $search=$this->getControllerStateSession('mappingurusantofungsi','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = MappingUrusanToFungsiModel::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = MappingUrusanToFungsiModel::where('replaceit', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = MappingUrusanToFungsiModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('mappingurusantofungsi.index'));
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
        
        $this->setCurrentPageInsideSession('mappingurusantofungsi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingurusantofungsi.datatable")->with(['page_active'=>'mappingurusantofungsi',
                                                                                'search'=>$this->getControllerStateSession('mappingurusantofungsi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','order'),
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
        $this->putControllerStateSession('mappingurusantofungsi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingurusantofungsi.datatable")->with(['page_active'=>'mappingurusantofungsi',
                                                            'search'=>$this->getControllerStateSession('mappingurusantofungsi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','order'),
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

        $this->setCurrentPageInsideSession('mappingurusantofungsi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.mappingurusantofungsi.datatable")->with(['page_active'=>'mappingurusantofungsi',
                                                                            'search'=>$this->getControllerStateSession('mappingurusantofungsi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','order'),
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
            $this->destroyControllerStateSession('mappingurusantofungsi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('mappingurusantofungsi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('mappingurusantofungsi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingurusantofungsi.datatable")->with(['page_active'=>'mappingurusantofungsi',                                                            
                                                            'search'=>$this->getControllerStateSession('mappingurusantofungsi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','order'),
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

        $search=$this->getControllerStateSession('mappingurusantofungsi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('mappingurusantofungsi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('mappingurusantofungsi',$data->currentPage());
        
        return view("pages.$theme.dmaster.mappingurusantofungsi.index")->with(['page_active'=>'mappingurusantofungsi',
                                                'search'=>$this->getControllerStateSession('mappingurusantofungsi','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','order'),
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

        return view("pages.$theme.dmaster.mappingurusantofungsi.create")->with(['page_active'=>'mappingurusantofungsi',
                                                                    
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
        
        $mappingurusantofungsi = MappingUrusanToFungsiModel::create([
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
            return redirect(route('mappingurusantofungsi.show',['id'=>$mappingurusantofungsi->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = MappingUrusanToFungsiModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.mappingurusantofungsi.show")->with(['page_active'=>'mappingurusantofungsi',
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
        
        $data = MappingUrusanToFungsiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.dmaster.mappingurusantofungsi.edit")->with(['page_active'=>'mappingurusantofungsi',
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
        $mappingurusantofungsi = MappingUrusanToFungsiModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $mappingurusantofungsi->replaceit = $request->input('replaceit');
        $mappingurusantofungsi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('mappingurusantofungsi.show',['id'=>$mappingurusantofungsi->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $mappingurusantofungsi = MappingUrusanToFungsiModel::find($id);
        $result=$mappingurusantofungsi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('mappingurusantofungsi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.mappingurusantofungsi.datatable")->with(['page_active'=>'mappingurusantofungsi',
                                                            'search'=>$this->getControllerStateSession('mappingurusantofungsi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingurusantofungsi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('mappingurusantofungsi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}