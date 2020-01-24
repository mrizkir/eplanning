<?php

namespace App\Controllers\Aspirasi;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Aspirasi\PemilikBansosHibahModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class PemilikBansosHibahController extends Controller {
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
        if (!$this->checkStateIsExistSession('pemilikbansoshibah','orderby')) 
        {            
           $this->putControllerStateSession('pemilikbansoshibah','orderby',['column_name'=>'Kd_PK','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pemilikbansoshibah.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('pemilikbansoshibah','search')) 
        {
            $search=$this->getControllerStateSession('pemilikbansoshibah','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_PK' :
                    $data = PemilikBansosHibahModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where(['Kd_PK'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'NmPk' :
                    $data = PemilikBansosHibahModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                    ->where('NmPk', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = PemilikBansosHibahModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                            ->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('pemilikbansoshibah.index'));
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
        
        $this->setCurrentPageInsideSession('pemilikbansoshibah',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.pemilikbansoshibah.datatable")->with(['page_active'=>'pemilikbansoshibah',
                                                                                'search'=>$this->getControllerStateSession('pemilikbansoshibah','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','order'),
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
        $this->putControllerStateSession('pemilikbansoshibah','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.pemilikbansoshibah.datatable")->with(['page_active'=>'pemilikbansoshibah',
                                                            'search'=>$this->getControllerStateSession('pemilikbansoshibah','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','order'),
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

        $this->setCurrentPageInsideSession('pemilikbansoshibah',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.aspirasi.pemilikbansoshibah.datatable")->with(['page_active'=>'pemilikbansoshibah',
                                                                            'search'=>$this->getControllerStateSession('pemilikbansoshibah','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','order'),
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
            $this->destroyControllerStateSession('pemilikbansoshibah','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pemilikbansoshibah','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pemilikbansoshibah',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.pemilikbansoshibah.datatable")->with(['page_active'=>'pemilikbansoshibah',                                                            
                                                            'search'=>$this->getControllerStateSession('pemilikbansoshibah','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','order'),
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

        $search=$this->getControllerStateSession('pemilikbansoshibah','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pemilikbansoshibah'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pemilikbansoshibah',$data->currentPage());
        
        return view("pages.$theme.aspirasi.pemilikbansoshibah.index")->with(['page_active'=>'pemilikbansoshibah',
                                                                            'search'=>$this->getControllerStateSession('pemilikbansoshibah','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','order'),
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

        return view("pages.$theme.aspirasi.pemilikbansoshibah.create")->with(['page_active'=>'pemilikbansoshibah',
                                                                    
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
            'Kd_PK'=>[new CheckRecordIsExistValidation('tmPemilikBansosHibah',['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]]),
                        'required',
                        'min:2'
                    ],
            'NmPk'=>'required|min:2'
        ]);
        
        $pemilikbansoshibah = PemilikBansosHibahModel::create([
            'PemilikBansosHibahID'=> uniqid ('uid'),
            'Kd_PK' => $request->input('Kd_PK'),
            'NmPk' => $request->input('NmPk'),
            'Jumlah_Kegiatan1' => 0,
            'Jumlah1' => 0,
            'Descr' => $request->input('Descr'),
            'TA'=>\HelperKegiatan::getTahunPerencanaan()
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
            return redirect(route('pemilikbansoshibah.show',['uuid'=>$pemilikbansoshibah->PemilikBansosHibahID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = PemilikBansosHibahModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.aspirasi.pemilikbansoshibah.show")->with(['page_active'=>'pemilikbansoshibah',
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
        
        $data = PemilikBansosHibahModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.aspirasi.pemilikbansoshibah.edit")->with(['page_active'=>'pemilikbansoshibah',
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
        $pemilikbansoshibah = PemilikBansosHibahModel::find($id);
        
        $this->validate($request, [
            'Kd_PK'=>[new IgnoreIfDataIsEqualValidation('tmPemilikBansosHibah',$pemilikbansoshibah->Kd_PK,['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]]),
                        'required',
                        'min:2'
                    ],
            'NmPk'=>'required|min:2'
        ]);
        
        $pemilikbansoshibah->Kd_PK = $request->input('Kd_PK');
        $pemilikbansoshibah->NmPk = $request->input('NmPk');
        $pemilikbansoshibah->Descr = $request->input('Descr');
        $pemilikbansoshibah->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('pemilikbansoshibah.show',['uuid'=>$pemilikbansoshibah->PemilikBansosHibahID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $pemilikbansoshibah = PemilikBansosHibahModel::find($id);
        $result=$pemilikbansoshibah->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('pemilikbansoshibah'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.aspirasi.pemilikbansoshibah.datatable")->with(['page_active'=>'pemilikbansoshibah',
                                                            'search'=>$this->getControllerStateSession('pemilikbansoshibah','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pemilikbansoshibah.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('pemilikbansoshibah.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}