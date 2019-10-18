<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\ProvinsiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class ProvinsiController extends Controller {
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
        if (!$this->checkStateIsExistSession('provinsi','orderby')) 
        {            
           $this->putControllerStateSession('provinsi','orderby',['column_name'=>'Kd_Prov','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('provinsi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('provinsi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('provinsi','search')) 
        {
            $search=$this->getControllerStateSession('provinsi','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Prov' :
                    $data = ProvinsiModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where(['Kd_Prov'=>$search['isikriteria']])
                                        ->orderBy($column_order,$direction); 
                break;
                case 'Nm_Prov' :
                    $data = ProvinsiModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->where('Nm_Prov', 'ILIKE', '%' . $search['isikriteria'] . '%')
                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = ProvinsiModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }
        $data->setPath(route('provinsi.index'));
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
        
        $this->setCurrentPageInsideSession('provinsi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.provinsi.datatable")->with(['page_active'=>'provinsi',
                                                                                'search'=>$this->getControllerStateSession('provinsi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('provinsi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('provinsi.orderby','order'),
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
            case 'col-Kd_Prov' :
                $column_name = 'Kd_Prov';
            break;  
            case 'col-Nm_Prov' :
                $column_name = 'Nm_Prov';
            break;          
            default :
                $column_name = 'Kd_Prov';
        }
        $this->putControllerStateSession('provinsi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('provinsi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.provinsi.datatable")->with(['page_active'=>'provinsi',
                                                            'search'=>$this->getControllerStateSession('provinsi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('provinsi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('provinsi.orderby','order'),
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

        $this->setCurrentPageInsideSession('provinsi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.provinsi.datatable")->with(['page_active'=>'provinsi',
                                                                            'search'=>$this->getControllerStateSession('provinsi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('provinsi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('provinsi.orderby','order'),
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
            $this->destroyControllerStateSession('provinsi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('provinsi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('provinsi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.provinsi.datatable")->with(['page_active'=>'provinsi',                                                            
                                                                        'search'=>$this->getControllerStateSession('provinsi','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                        'column_order'=>$this->getControllerStateSession('provinsi.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('provinsi.orderby','order'),
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

        $search=$this->getControllerStateSession('provinsi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('provinsi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('provinsi',$data->currentPage());

        return view("pages.$theme.dmaster.provinsi.index")->with(['page_active'=>'provinsi',
                                                            'search'=>$this->getControllerStateSession('provinsi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('provinsi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('provinsi.orderby','order'),
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
        return view("pages.$theme.dmaster.provinsi.create")->with(['page_active'=>'provinsi',
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
                'Kd_Prov' => [
                                new CheckRecordIsExistValidation('tmPMProv', ['where' => ['TA', '=', \HelperKegiatan::getTahunPerencanaan()]]),
                                'required',
                                'min:1',
                                'regex:/^[0-9]+$/'
                ],               
                'Nm_Prov'=>'required|min:5', 
            ],
            [   
                'Kd_Prov.required'=>'Mohon Kode Provinsi untuk di isi karena ini diperlukan',
                'Kd_Prov.min'=>'Mohon Kode Provinsi untuk di isi minimal 1 digit',

                'Nm_Prov.required'=>'Mohon Nama Provinsi untuk di isi karena ini diperlukan',
                'Nm_Prov.min'=>'Mohon Nama Provinsi di isi minimal 5 karakter'
            ]
        );
        
        $provinsi = ProvinsiModel::create ([
            'PMProvID'=> uniqid ('uid'),
            'Kd_Prov'=>$request->input('Kd_Prov'),        
            'Nm_Prov'=>$request->input('Nm_Prov'),
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
            return redirect(route('provinsi.show',['id'=>$provinsi->PMProvID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = ProvinsiModel::findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.dmaster.provinsi.show")->with(['page_active'=>'provinsi',
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
        
        $data = ProvinsiModel::findOrFail($id);
        if (!is_null($data) ) 
        {   
            return view("pages.$theme.dmaster.provinsi.edit")->with(['page_active'=>'provinsi',
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
        $provinsi = ProvinsiModel::find($id);

        $this->validate($request,
        [
            'Kd_Prov'=>['required',
                        new IgnoreIfDataIsEqualValidation('tmPMProv',
                                                            $provinsi->Kd_Prov,
                                                            ['where'=>['TA','=',\HelperKegiatan::getRPJMDTahunMulai()]],
                                                            'Kd_Prov'),
                        'min:1',
                        'regex:/^[0-9]+$/'

                    ],   
             
            'Nm_Prov'=>'required|min:5', 
        ],
        [            
            'Kd_Prov.required'=>'Mohon Kode Provinsi untuk di isi karena ini diperlukan',
            'Kd_Prov.min'=>'Mohon Kode Provinsi untuk di isi minimal 1 digit',

            'Nm_Prov.required'=>'Mohon Nama Provinsi untuk di isi karena ini diperlukan',
            'Nm_Prov.min'=>'Mohon Nama Provinsi di isi minimal 5 karakter'
        ]
        );

        $provinsi->Kd_Prov = $request->input('Kd_Prov');
        $provinsi->Nm_Prov = $request->input('Nm_Prov');
        $provinsi->Descr = $request->input('Descr');
        
        $provinsi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('provinsi.show',['id'=>$provinsi->PMProvID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $provinsi = ProvinsiModel::find($id);        
        $result=$provinsi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('provinsi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.provinsi.datatable")->with(['page_active'=>'provinsi',
                                                                            'search'=>$this->getControllerStateSession('provinsi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('provinsi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('provinsi.orderby','order'),
                                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('provinsi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}