<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\TAModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class TAController extends Controller {    
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
        if (!$this->checkStateIsExistSession('ta','orderby')) 
        {            
           $this->putControllerStateSession('ta','orderby',['column_name'=>'TACd','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('ta.orderby','column_name'); 
        $direction=$this->getControllerStateSession('ta.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
  
        $data = TAModel::orderBy($column_order,$direction)
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        
        $data->setPath(route('ta.index'));
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
        
        $this->setCurrentPageInsideSession('ta',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.ta.datatable")->with(['page_active'=>'ta',
                                                                                        'search'=>$this->getControllerStateSession('ta','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('ta.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('ta.orderby','order'),
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
            case 'col-TACd' :
                $column_name = 'TACd';
            break;     
            case 'col-TANm' :
                $column_name = 'TANm';
            break;       
            default :
                $column_name = 'TACd';
        }
        $this->putControllerStateSession('ta','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('ta'); 
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $datatable = view("pages.$theme.dmaster.ta.datatable")->with(['page_active'=>'ta',
                                                                                        'search'=>$this->getControllerStateSession('ta','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('ta.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('ta.orderby','order'),
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

        $this->setCurrentPageInsideSession('ta',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.ta.datatable")->with(['page_active'=>'ta',
                                                                                        'search'=>$this->getControllerStateSession('ta','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('ta.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('ta.orderby','order'),
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
            $this->destroyControllerStateSession('ta','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('ta','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('ta',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.ta.datatable")->with(['page_active'=>'ta',                                                            
                                                                                        'search'=>$this->getControllerStateSession('ta','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('ta.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('ta.orderby','order'),
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

        $search=$this->getControllerStateSession('ta','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('ta'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('ta',$data->currentPage());
        
        return view("pages.$theme.dmaster.ta.index")->with(['page_active'=>'ta',
                                                                                'search'=>$this->getControllerStateSession('ta','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession('ta.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('ta.orderby','order'),
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

        return view("pages.$theme.dmaster.ta.create")->with(['page_active'=>'ta',
                                                                    
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
        $this->validate($request,
        [
            'TACd'=>[new CheckRecordIsExistValidation('tmTA'),
                            'required',
                            'min:4'
                        ],   
            'TANm'=>'required', 
        ],
        [
            'TACd.required'=>'Mohon Kode Kelompok Urusan untuk di isi karena ini diperlukan',
            'TACd.min'=>'Mohon Kode Kelompok Urusan untuk di isi minimal 4 digit',
            'TANm.required'=>'Mohon Nama Kelompok Urusan untuk di isi karena ini diperlukan',
            'TANm.min'=>'Mohon Nama Kelompok Urusan di isi minimal 5 karakter'
        ]
        );

        $ta = TAModel::create ([
            'TAID'=> uniqid ('uid'),
            'TACd'=>$request->input('TACd'),
            'TANm'=>$request->input('TANm'),
            'Descr'=>$request->input('Descr')
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
            return redirect(route('ta.show',['id'=>$ta->TAID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = TAModel::where('TAID',$id)
                        ->firstOrFail();

        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.ta.show")->with(['page_active'=>'ta',
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
        
        $data = TAModel::where('TAID',$id)
                        ->firstOrFail();

        if (!is_null($data) ) 
        {
            return view("pages.$theme.dmaster.ta.edit")->with(['page_active'=>'ta',
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
        $ta = TAModel::find($id);
        $this->validate($request, 
        [
            'TACd'=>[new IgnoreIfDataIsEqualValidation('tmTA',$ta->TACd),
                            'required',
                            'min:4'
                        ],   
            'TANm'=>'required|min:5', 
        ],
        [
            'TACd.required'=>'Mohon Kode Kelompok Urusan untuk di isi karena ini diperlukan',
            'TACd.min'=>'Mohon Kode Kelompok Urusan untuk di isi minimal 1 digit',
            'TANm.required'=>'Mohon Nama Kelompok Urusan untuk di isi karena ini diperlukan',
            'TANm.min'=>'Mohon Nama Kelompok Urusan di isi minimal 5 karakter'        
        ]);        
        
        $ta->TACd = $request->input('TACd');
        $ta->TANm = $request->input('TANm');
        $ta->Descr = $request->input('Descr');
        $ta->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('ta.show',['id'=>$ta->TAID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        $ta = TAModel::find($id);        
        $result=$ta->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('ta'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.ta.datatable")->with(['page_active'=>'ta',
                                                            'search'=>$this->getControllerStateSession('ta','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('ta.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('ta.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('ta.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}