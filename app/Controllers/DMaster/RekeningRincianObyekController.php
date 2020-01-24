<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\RekeningRincianObyekModel;

class RekeningRincianObyekController extends Controller {
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
        //if (!$this->checkStateIsExistSession('rekeningrincianobyek','orderby')) 
        //{            
        //    $this->putControllerStateSession('rekeningrincianobyek','orderby',['column_name'=>'replace_it','order'=>'asc']);
        //}
        //$column_order=$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'); 
        //$direction=$this->getControllerStateSession('rekeningrincianobyek.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rekeningrincianobyek','search')) 
        {
            $search=$this->getControllerStateSession('rekeningrincianobyek','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = RekeningRincianObyekModel::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = RekeningRincianObyekModel::where('replaceit', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RekeningRincianObyekModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rekeningrincianobyek.index'));
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
        
        $this->setCurrentPageInsideSession('rekeningrincianobyek',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.rekeningrincianobyek.datatable")->with(['page_active'=>'rekeningrincianobyek',
                                                                                'search'=>$this->getControllerStateSession('rekeningrincianobyek','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','order'),
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
        $this->putControllerStateSession('rekeningrincianobyek','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.rekeningrincianobyek.datatable")->with(['page_active'=>'rekeningrincianobyek',
                                                            'search'=>$this->getControllerStateSession('rekeningrincianobyek','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','order'),
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

        $this->setCurrentPageInsideSession('rekeningrincianobyek',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.rekeningrincianobyek.datatable")->with(['page_active'=>'rekeningrincianobyek',
                                                                            'search'=>$this->getControllerStateSession('rekeningrincianobyek','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','order'),
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
            $this->destroyControllerStateSession('rekeningrincianobyek','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rekeningrincianobyek','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rekeningrincianobyek',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.rekeningrincianobyek.datatable")->with(['page_active'=>'rekeningrincianobyek',                                                            
                                                            'search'=>$this->getControllerStateSession('rekeningrincianobyek','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','order'),
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

        $search=$this->getControllerStateSession('rekeningrincianobyek','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rekeningrincianobyek'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rekeningrincianobyek',$data->currentPage());
        
        return view("pages.$theme.dmaster.rekeningrincianobyek.index")->with(['page_active'=>'rekeningrincianobyek',
                                                'search'=>$this->getControllerStateSession('rekeningrincianobyek','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','order'),
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

        return view("pages.$theme.dmaster.rekeningrincianobyek.create")->with(['page_active'=>'rekeningrincianobyek',
                                                                    
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
        
        $rekeningrincianobyek = RekeningRincianObyekModel::create([
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
            return redirect(route('rekeningrincianobyek.show',['uuid'=>$rekeningrincianobyek->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RekeningRincianObyekModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.rekeningrincianobyek.show")->with(['page_active'=>'rekeningrincianobyek',
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
        
        $data = RekeningRincianObyekModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.dmaster.rekeningrincianobyek.edit")->with(['page_active'=>'rekeningrincianobyek',
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
        $rekeningrincianobyek = RekeningRincianObyekModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $rekeningrincianobyek->replaceit = $request->input('replaceit');
        $rekeningrincianobyek->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rekeningrincianobyek.show',['uuid'=>$rekeningrincianobyek->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $rekeningrincianobyek = RekeningRincianObyekModel::find($id);
        $result=$rekeningrincianobyek->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('rekeningrincianobyek'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.rekeningrincianobyek.datatable")->with(['page_active'=>'rekeningrincianobyek',
                                                            'search'=>$this->getControllerStateSession('rekeningrincianobyek','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rekeningrincianobyek.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rekeningrincianobyek.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}