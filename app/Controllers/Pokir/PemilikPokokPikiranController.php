<?php

namespace App\Controllers\Pokir;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Pokir\PemilikPokokPikiranModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class PemilikPokokPikiranController extends Controller {
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
        if (!$this->checkStateIsExistSession('pemilikpokokpikiran','orderby')) 
        {            
           $this->putControllerStateSession('pemilikpokokpikiran','orderby',['column_name'=>'Kd_PK','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('pemilikpokokpikiran','search')) 
        {
            $search=$this->getControllerStateSession('pemilikpokokpikiran','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_PK' :
                    $data = PemilikPokokPikiranModel::where(['Kd_PK'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'NmPk' :
                    $data = PemilikPokokPikiranModel::where('NmPk', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = PemilikPokokPikiranModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('pemilikpokokpikiran.index'));
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
        
        $this->setCurrentPageInsideSession('pemilikpokokpikiran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.pokir.pemilikpokokpikiran.datatable")->with(['page_active'=>'pemilikpokokpikiran',
                                                                                'search'=>$this->getControllerStateSession('pemilikpokokpikiran','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'),
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
            case 'col-Kd_PK' :
                $column_name = 'Kd_PK';
            break;           
            case 'col-NmPk' :
                $column_name = 'NmPk';
            break;           
            default :
                $column_name = 'Kd_PK';
        }
        $this->putControllerStateSession('pemilikpokokpikiran','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.pokir.pemilikpokokpikiran.datatable")->with(['page_active'=>'pemilikpokokpikiran',
                                                            'search'=>$this->getControllerStateSession('pemilikpokokpikiran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'),
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

        $this->setCurrentPageInsideSession('pemilikpokokpikiran',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.pokir.pemilikpokokpikiran.datatable")->with(['page_active'=>'pemilikpokokpikiran',
                                                                            'search'=>$this->getControllerStateSession('pemilikpokokpikiran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'),
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
            $this->destroyControllerStateSession('pemilikpokokpikiran','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pemilikpokokpikiran','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pemilikpokokpikiran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.pokir.pemilikpokokpikiran.datatable")->with(['page_active'=>'pemilikpokokpikiran',                                                            
                                                            'search'=>$this->getControllerStateSession('pemilikpokokpikiran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'),
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

        $search=$this->getControllerStateSession('pemilikpokokpikiran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pemilikpokokpikiran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pemilikpokokpikiran',$data->currentPage());
        
        return view("pages.$theme.pokir.pemilikpokokpikiran.index")->with(['page_active'=>'pemilikpokokpikiran',
                                                                            'search'=>$this->getControllerStateSession('pemilikpokokpikiran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'),
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

        return view("pages.$theme.pokir.pemilikpokokpikiran.create")->with(['page_active'=>'pemilikpokokpikiran',
                                                                    
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
            'Kd_PK'=>[new CheckRecordIsExistValidation('tmPemilikPokok',['where'=>['TA','=',config('eplanning.tahun_perencanaan')]]),
                        'required',
                        'min:2'
                    ],
            'NmPk'=>'required|min:2'
        ]);
        
        $pemilikpokokpikiran = PemilikPokokPikiranModel::create([
            'PemilikPokokID'=> uniqid ('uid'),
            'Kd_PK' => $request->input('Kd_PK'),
            'NmPk' => $request->input('NmPk'),
            'Jumlah_Kegiatan1' => 0,
            'Jumlah_Kegiatan2' => 0,
            'Jumlah_Kegiatan3' => 0,
            'Jumlah_Kegiatan4' => 0,
            'Jumlah_Kegiatan5' => 0,
            'Jumlah1' => 0,
            'Jumlah2' => 0,
            'Jumlah3' => 0,
            'Jumlah4' => 0,
            'Jumlah5' => 0,
            'Descr' => $request->input('Descr'),
            'TA'=>config('eplanning.tahun_perencanaan')
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
            return redirect(route('pemilikpokokpikiran.show',['id'=>$pemilikpokokpikiran->PemilikPokokID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = PemilikPokokPikiranModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.pokir.pemilikpokokpikiran.show")->with(['page_active'=>'pemilikpokokpikiran',
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
        
        $data = PemilikPokokPikiranModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.pokir.pemilikpokokpikiran.edit")->with(['page_active'=>'pemilikpokokpikiran',
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
        $pemilikpokokpikiran = PemilikPokokPikiranModel::find($id);
        
        $this->validate($request, [
            'Kd_PK'=>[new IgnoreIfDataIsEqualValidation('tmPemilikPokok',$pemilikpokokpikiran->Kd_PK,['where'=>['TA','=',config('eplanning.tahun_perencanaan')]]),
                        'required',
                        'min:2'
                    ],
            'NmPk'=>'required|min:2'
        ]);
        
        $pemilikpokokpikiran->Kd_PK = $request->input('Kd_PK');
        $pemilikpokokpikiran->NmPk = $request->input('NmPk');
        $pemilikpokokpikiran->Descr = $request->input('Descr');
        $pemilikpokokpikiran->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('pemilikpokokpikiran.show',['id'=>$pemilikpokokpikiran->PemilikPokokID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $pemilikpokokpikiran = PemilikPokokPikiranModel::find($id);
        $result=$pemilikpokokpikiran->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('pemilikpokokpikiran'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.pokir.pemilikpokokpikiran.datatable")->with(['page_active'=>'pemilikpokokpikiran',
                                                            'search'=>$this->getControllerStateSession('pemilikpokokpikiran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pemilikpokokpikiran.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('pemilikpokokpikiran.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}