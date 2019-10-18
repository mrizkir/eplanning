<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\SumberDanaModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class SumberDanaController extends Controller {
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
        if (!$this->checkStateIsExistSession('sumberdana','orderby')) 
        {            
           $this->putControllerStateSession('sumberdana','orderby',['column_name'=>'Kd_SumberDana','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('sumberdana.orderby','column_name'); 
        $direction=$this->getControllerStateSession('sumberdana.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('sumberdana','search')) 
        {
            $search=$this->getControllerStateSession('sumberdana','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_SumberDana' :
                    $data = SumberDanaModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where(['Kd_SumberDana'=>$search['isikriteria']])
                                        ->orderBy($column_order,$direction); 
                break;
                case 'Nm_SumberDana' :
                    $data = SumberDanaModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where('Nm_SumberDana', 'ILIKE', '%' . $search['isikriteria'] . '%')
                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = SumberDanaModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }
        $data->setPath(route('sumberdana.index'));
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
        
        $this->setCurrentPageInsideSession('sumberdana',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.sumberdana.datatable")->with(['page_active'=>'sumberdana',
                                                                                'search'=>$this->getControllerStateSession('sumberdana','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('sumberdana.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('sumberdana.orderby','order'),
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
            case 'col-Kd_SumberDana' :
                $column_name = 'Kd_SumberDana';
            break;  
            case 'col-Nm_SumberDana' :
                $column_name = 'Nm_SumberDana';
            break;          
            default :
                $column_name = 'Kd_SumberDana';
        }
        $this->putControllerStateSession('sumberdana','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('sumberdana'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.sumberdana.datatable")->with(['page_active'=>'sumberdana',
                                                            'search'=>$this->getControllerStateSession('sumberdana','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('sumberdana.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('sumberdana.orderby','order'),
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

        $this->setCurrentPageInsideSession('sumberdana',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.sumberdana.datatable")->with(['page_active'=>'sumberdana',
                                                                            'search'=>$this->getControllerStateSession('sumberdana','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('sumberdana.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('sumberdana.orderby','order'),
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
            $this->destroyControllerStateSession('sumberdana','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('sumberdana','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('sumberdana',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.sumberdana.datatable")->with(['page_active'=>'sumberdana',                                                            
                                                                        'search'=>$this->getControllerStateSession('sumberdana','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                        'column_order'=>$this->getControllerStateSession('sumberdana.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('sumberdana.orderby','order'),
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

        $search=$this->getControllerStateSession('sumberdana','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('sumberdana'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('sumberdana',$data->currentPage());

        return view("pages.$theme.dmaster.sumberdana.index")->with(['page_active'=>'sumberdana',
                                                            'search'=>$this->getControllerStateSession('sumberdana','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('sumberdana.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('sumberdana.orderby','order'),
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
        return view("pages.$theme.dmaster.sumberdana.create")->with(['page_active'=>'sumberdana',
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
                'Kd_SumberDana' => [
                                new CheckRecordIsExistValidation('tmSumberDana', ['where' => ['TA', '=', \HelperKegiatan::getTahunPerencanaan()]]),
                                'required',
                                'min:1',
                                'regex:/^[0-9]+$/'
                ],               
                'Nm_SumberDana'=>'required|min:5', 
            ],
            [   
                'Kd_SumberDana.required'=>'Mohon Kode Sumber Dana untuk di isi karena ini diperlukan',
                'Kd_SumberDana.min'=>'Mohon Kode Sumber Dana untuk di isi minimal 1 digit',

                'Nm_SumberDana.required'=>'Mohon Nama Sumber Dana untuk di isi karena ini diperlukan',
                'Nm_SumberDana.min'=>'Mohon Nama Sumber Dana di isi minimal 5 karakter'
            ]
        );
        
        $sumberdana = SumberDanaModel::create ([
            'SumberDanaID'=> uniqid ('uid'),
            'Kd_SumberDana'=>$request->input('Kd_SumberDana'),        
            'Nm_SumberDana'=>$request->input('Nm_SumberDana'),
            'Descr'=>$request->input('Descr'),
            'TA'=>\HelperKegiatan::getTahunPerencanaan(),
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
            return redirect(route('sumberdana.show',['id'=>$sumberdana->SumberDanaID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = SumberDanaModel::findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.dmaster.sumberdana.show")->with(['page_active'=>'sumberdana',
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
        
        $data = SumberDanaModel::findOrFail($id);
        if (!is_null($data) ) 
        {   
            return view("pages.$theme.dmaster.sumberdana.edit")->with(['page_active'=>'sumberdana',
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
        $sumberdana = SumberDanaModel::find($id);
        $this->validate($request,
        [
            'Kd_SumberDana'=>['required',
                        new IgnoreIfDataIsEqualValidation('tmSumberDana',
                                                            $sumberdana->Kd_SumberDana,
                                                            ['where'=>['TA','=',\HelperKegiatan::getRPJMDTahunMulai()]],
                                                            'Kd_SumberDana'),
                        'min:1',
                        'regex:/^[0-9]+$/'

                    ],   
             
            'Nm_SumberDana'=>'required|min:5', 
        ],
        [            
            'Kd_SumberDana.required'=>'Mohon Kode Sumber Dana untuk di isi karena ini diperlukan',
            'Kd_SumberDana.min'=>'Mohon Kode Sumber Dana untuk di isi minimal 1 digit',

            'Nm_SumberDana.required'=>'Mohon Nama Sumber Dana untuk di isi karena ini diperlukan',
            'Nm_SumberDana.min'=>'Mohon Nama Sumber Dana di isi minimal 5 karakter'
        ]
        );

        $sumberdana->Kd_SumberDana = $request->input('Kd_SumberDana');
        $sumberdana->Nm_SumberDana = $request->input('Nm_SumberDana');
        $sumberdana->Descr = $request->input('Descr');
        
        $sumberdana->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('sumberdana.show',['id'=>$sumberdana->SumberDanaID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $sumberdana = SumberDanaModel::find($id);        
        $result=$sumberdana->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('sumberdana'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.sumberdana.datatable")->with(['page_active'=>'sumberdana',
                                                                            'search'=>$this->getControllerStateSession('sumberdana','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('sumberdana.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('sumberdana.orderby','order'),
                                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('sumberdana.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}