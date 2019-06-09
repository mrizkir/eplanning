<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRAMisiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRAMisiController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstramisi','Nm_RenstraMisi')) 
        {            
           $this->putControllerStateSession('renstramisi','orderby',['column_name'=>'Nm_RenstraMisi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstramisi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstramisi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');       
        
        //filter
        if (!$this->checkStateIsExistSession('renstramisi','filters')) 
        {            
            $this->putControllerStateSession('renstramisi','filters',[
                                                                    'OrgID'=>'none'
                                                                    ]);
        }        
        $OrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgID');        

        if ($this->checkStateIsExistSession('renstramisi','search')) 
        {
            $search=$this->getControllerStateSession('renstramisi','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_RenstraMisi' :
                    $data = RENSTRAMisiModel::where('OrgID',$OrgID)
                                            ->where(['Kd_RenstraMisi'=>$search['isikriteria']])
                                            ->orderBy($column_order,$direction); 
                break;
                case 'Nm_RenstraMisi' :
                    $data = RENSTRAMisiModel::where('OrgID',$OrgID)
                                            ->where('Nm_RenstraMisi', 'ilike', '%' . $search['isikriteria'] . '%')
                                            ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRAMisiModel::where('OrgID',$OrgID)
                                    ->orderBy($column_order,$direction)
                                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstramisi.index'));
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
        
        $this->setCurrentPageInsideSession('renstramisi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstramisi.datatable")->with(['page_active'=>'renstramisi',
                                                                                'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstramisi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstramisi.orderby','order'),
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
            case 'col-Nm_RenstraMisi' :
                $column_name = 'Nm_RenstraMisi';
            break;           
            default :
                $column_name = 'Nm_RenstraMisi';
        }
        $this->putControllerStateSession('renstramisi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstramisi.datatable")->with(['page_active'=>'renstramisi',
                                                            'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstramisi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstramisi.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstramisi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstramisi.datatable")->with(['page_active'=>'renstramisi',
                                                                            'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstramisi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstramisi.orderby','order'),
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
            $this->destroyControllerStateSession('renstramisi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstramisi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstramisi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstramisi.datatable")->with(['page_active'=>'renstramisi',                                                            
                                                            'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstramisi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstramisi.orderby','order'),
                                                            'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) 
    {
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('renstramisi','filters');
        $daftar_unitkerja=[];
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;            
            $this->putControllerStateSession('renstramisi','filters',$filters);
            
            $data = $this->populateData();

            $datatable = view("pages.$theme.renstra.renstramisi.datatable")->with(['page_active'=>'renstramisi',                                                                               
                                                                            'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                            'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                            'data'=>$data])->render();

            
            $json_data = ['success'=>true,'datatable'=>$datatable];
        } 
        return response()->json($json_data,200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;
        $filters=$this->getControllerStateSession('renstramisi','filters');

        $search=$this->getControllerStateSession('renstramisi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstramisi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstramisi',$data->currentPage());
        
        $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('eplanning.renstra_tahun_mulai'),false);  
        return view("pages.$theme.renstra.renstramisi.index")->with(['page_active'=>'renstramisi',
                                                                    'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                                    'filters'=>$filters,
                                                                    'daftar_opd'=>$daftar_opd,
                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                    'column_order'=>$this->getControllerStateSession('renstramisi.orderby','column_name'),
                                                                    'direction'=>$this->getControllerStateSession('renstramisi.orderby','order'),
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
        $filters=$this->getControllerStateSession('renstramisi','filters');       
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {
            $daftar_visi = \App\Models\RENSTRA\RENSTRAVisiModel::select(\DB::raw('"Kd_Visi","Nm_Visi"'))
                                                                ->where()
                                                                ->get()
                                                                ->pluck('Nm_Visi','Kd_Visi')
                                                                ->toArray();
            return view("pages.$theme.renstra.renstramisi.create")->with(['page_active'=>'renstramisi',
                                                                            'daftar_visi'=>$daftar_visi
                                                                        ]);  
        }
        else
        {
            return view("pages.$theme.renstra.renstramisi.error")->with(['page_active'=>'renstramisi',
                                                                    'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu.'
                                                                ]);  
        }
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
            'Kd_RenstraMisi'=>[new CheckRecordIsExistValidation('tmRenstraMisi',['where'=>['TA','=',config('eplanning.renstra_tahun_mulai')]]),
                        'required'
                    ],
            'Nm_RenstraMisi'=>'required',
        ]);
        
        $renstramisi = RENSTRAMisiModel::create([
            'RenstraMisiID'=> uniqid ('uid'),
            'OrgID' => $this->getControllerStateSession('renstramisi','filters.OrgID'),
            'Kd_RenstraMisi' => $request->input('Kd_RenstraMisi'),
            'Nm_RenstraMisi' => $request->input('Nm_RenstraMisi'),
            'Descr' => $request->input('Descr'),
            'TA' => config('eplanning.renstra_tahun_mulai')
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
            return redirect(route('renstramisi.show',['id'=>$renstramisi->RenstraMisiID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRAMisiModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstramisi.show")->with(['page_active'=>'renstramisi',
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
        
        $data = RENSTRAMisiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.renstra.renstramisi.edit")->with(['page_active'=>'renstramisi',
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
        $renstramisi = RENSTRAMisiModel::find($id);
        
        $this->validate($request, [
            'Kd_RenstraMisi'=>[new IgnoreIfDataIsEqualValidation('tmRenstraMisi',$renstramisi->Kd_PK,['where'=>['TA','=',config('eplanning.renstra_tahun_mulai')]]),
                        'required'
                    ],
            'Nm_RenstraMisi'=>'required|min:2'
        ]);
        
        $renstramisi->Kd_RenstraMisi = $request->input('Kd_RenstraMisi');
        $renstramisi->Nm_RenstraMisi = $request->input('Nm_RenstraMisi');
        $renstramisi->Descr = $request->input('Descr');
        $renstramisi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstramisi.show',['id'=>$renstramisi->RenstraMisiID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstramisi = RENSTRAMisiModel::find($id);
        $result=$renstramisi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstramisi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstramisi.datatable")->with(['page_active'=>'renstramisi',
                                                            'search'=>$this->getControllerStateSession('renstramisi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstramisi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstramisi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstramisi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
