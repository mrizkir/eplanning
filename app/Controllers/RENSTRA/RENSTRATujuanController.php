<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRATujuanModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRATujuanController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstratujuan','orderby')) 
        {            
           $this->putControllerStateSession('renstratujuan','orderby',['column_name'=>'Nm_Tujuan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstratujuan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstratujuan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('renstratujuan','search')) 
        {
            $search=$this->getControllerStateSession('renstratujuan','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Tujuan' :
                    $data = RENSTRATujuanModel::where(['Kd_Tujuan'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_Tujuan' :
                    $data = RENSTRATujuanModel::where('Nm_Tujuan', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRATujuanModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstratujuan.index'));
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
        
        $this->setCurrentPageInsideSession('renstratujuan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                                                'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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
            case 'col-Kd_Tujuan' :
                $column_name = 'Kd_Tujuan';
            break;      
            case 'col-Nm_Tujuan' :
                $column_name = 'Nm_Tujuan';
            break;        
            default :
                $column_name = 'Nm_Tujuan';
        }
        $this->putControllerStateSession('renstratujuan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstratujuan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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
            $this->destroyControllerStateSession('renstratujuan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstratujuan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstratujuan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',                                                            
                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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

        $search=$this->getControllerStateSession('renstratujuan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstratujuan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstratujuan',$data->currentPage());
        
        return view("pages.$theme.renstra.renstratujuan.index")->with(['page_active'=>'renstratujuan',
                                                'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
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
        $daftar_misi=\App\Models\RENSTRA\RENSTRAMisiModel::select(\DB::raw('"PrioritasKabID",CONCAT(\'[\',"Kd_PrioritasKab",\']. \',"Nm_PrioritasKab") AS "Nm_PrioritasKab"'))
                                                        ->where('TA',config('eplanning.tahun_perencanaan'))
                                                        ->orderBy('Kd_PrioritasKab','ASC')
                                                        ->get()
                                                        ->pluck('Nm_PrioritasKab','PrioritasKabID')
                                                        ->toArray();

        
        return view("pages.$theme.renstra.renstratujuan.create")->with(['page_active'=>'renstratujuan',
                                                                    'daftar_misi'=>$daftar_misi
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
            'Kd_Tujuan'=>[new CheckRecordIsExistValidation('tmPrioritasTujuanKab',['where'=>['TA','=',config('eplanning.renstra_tahun_mulai')]]),
                            'required'
                        ],
            'PrioritasKabID'=>'required',
            'Nm_Tujuan'=>'required',
        ]);
        
        $renstratujuan = RENSTRATujuanModel::create([
            'PrioritasTujuanKabID'=> uniqid ('uid'),
            'PrioritasKabID' => $request->input('PrioritasKabID'),
            'Kd_Tujuan' => $request->input('Kd_Tujuan'),
            'Nm_Tujuan' => $request->input('Nm_Tujuan'),
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
            return redirect(route('renstratujuan.show',['id'=>$renstratujuan->PrioritasTujuanKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRATujuanModel::select(\DB::raw('"tmPrioritasTujuanKab"."PrioritasTujuanKabID",
                                                    "tmPrioritasKab"."Kd_PrioritasKab",
                                                    "tmPrioritasKab"."Nm_PrioritasKab",
                                                    "tmPrioritasTujuanKab"."Kd_Tujuan",
                                                    "tmPrioritasTujuanKab"."Nm_Tujuan",
                                                    "tmPrioritasTujuanKab"."Descr",
                                                    "tmPrioritasTujuanKab"."PrioritasTujuanKabID_Src",
                                                    "tmPrioritasTujuanKab"."created_at",
                                                    "tmPrioritasTujuanKab"."updated_at"'))
                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstratujuan.show")->with(['page_active'=>'renstratujuan',
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
        
        $data = RENSTRATujuanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_misi=\App\Models\RENSTRA\RENSTRAMisiModel::select(\DB::raw('"PrioritasKabID",CONCAT(\'[\',"Kd_PrioritasKab",\']. \',"Nm_PrioritasKab") AS "Nm_PrioritasKab"'))
                                                            ->where('TA',config('eplanning.tahun_perencanaan'))
                                                            ->orderBy('Kd_PrioritasKab','ASC')
                                                            ->get()
                                                            ->pluck('Nm_PrioritasKab','PrioritasKabID')
                                                            ->toArray();

            return view("pages.$theme.renstra.renstratujuan.edit")->with(['page_active'=>'renstratujuan',
                                                                        'daftar_misi'=>$daftar_misi,
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
        $renstratujuan = RENSTRATujuanModel::find($id);
        
        $this->validate($request, [
            'Kd_Tujuan'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasTujuanKab',
                                                                        $renstratujuan->Kd_Tujuan,
                                                                        ['where'=>['TA','=',config('eplanning.renstra_tahun_mulai')]],
                                                                        'Kode Tujuan')],
            'PrioritasKabID'=>'required',
            'Nm_Tujuan'=>'required',
        ]);
               
        $renstratujuan->PrioritasKabID = $request->input('PrioritasKabID');
        $renstratujuan->Kd_Tujuan = $request->input('Kd_Tujuan');
        $renstratujuan->Nm_Tujuan = $request->input('Nm_Tujuan');
        $renstratujuan->Descr = $request->input('Descr');
        $renstratujuan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstratujuan.show',['id'=>$renstratujuan->PrioritasTujuanKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstratujuan = RENSTRATujuanModel::find($id);
        $result=$renstratujuan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstratujuan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstratujuan.datatable")->with(['page_active'=>'renstratujuan',
                                                            'search'=>$this->getControllerStateSession('renstratujuan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstratujuan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstratujuan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstratujuan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
