<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRASasaranModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRASasaranController extends Controller {
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
        if (!$this->checkStateIsExistSession('renstrasasaran','orderby')) 
        {            
           $this->putControllerStateSession('renstrasasaran','orderby',['column_name'=>'Nm_Sasaran','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstrasasaran.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstrasasaran.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('renstrasasaran','search')) 
        {
            $search=$this->getControllerStateSession('renstrasasaran','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Sasaran' :
                    $data = RENSTRASasaranModel::where(['Kd_Sasaran'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_Sasaran' :
                    $data = RENSTRASasaranModel::where('Nm_Sasaran', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRASasaranModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstrasasaran.index'));
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
        
        $this->setCurrentPageInsideSession('renstrasasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                                                'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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
            case 'col-Kd_Sasaran' :
                $column_name = 'Kd_Sasaran';
            break;           
            case 'col-Nm_Sasaran' :
                $column_name = 'Nm_Sasaran';
            break;           
            default :
                $column_name = 'Nm_Sasaran';
        }
        $this->putControllerStateSession('renstrasasaran','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstrasasaran',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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
            $this->destroyControllerStateSession('renstrasasaran','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstrasasaran','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstrasasaran',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',                                                            
                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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

        $search=$this->getControllerStateSession('renstrasasaran','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstrasasaran'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstrasasaran',$data->currentPage());
        
        return view("pages.$theme.renstra.renstrasasaran.index")->with(['page_active'=>'renstrasasaran',
                                                'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
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
        $daftar_tujuan=\App\Models\RENSTRA\RENSTRATujuanModel::select(\DB::raw('"PrioritasTujuanKabID",CONCAT(\'[\',"Kd_Tujuan",\']. \',"Nm_Tujuan") AS "Nm_Tujuan"'))
                                                            ->where('TA',config('eplanning.tahun_perencanaan'))
                                                            ->orderBy('Kd_Tujuan','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Tujuan','PrioritasTujuanKabID')
                                                            ->toArray();

        return view("pages.$theme.renstra.renstrasasaran.create")->with(['page_active'=>'renstrasasaran',
                                                                    'daftar_tujuan'=>$daftar_tujuan
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
            'Kd_Sasaran'=>[new CheckRecordIsExistValidation('tmPrioritasSasaranKab',['where'=>['TA','=',config('eplanning.tahun_perencanaan')]]),
                            'required'
                        ],
            'PrioritasTujuanKabID'=>'required',
            'Nm_Sasaran'=>'required',
        ]);
        
        $renstrasasaran = RENSTRASasaranModel::create([
            'PrioritasSasaranKabID'=> uniqid ('uid'),
            'PrioritasTujuanKabID' => $request->input('PrioritasTujuanKabID'),
            'Kd_Sasaran' => $request->input('Kd_Sasaran'),
            'Nm_Sasaran' => $request->input('Nm_Sasaran'),
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
            return redirect(route('renstrasasaran.show',['id'=>$renstrasasaran->PrioritasSasaranKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRASasaranModel::select(\DB::raw('"tmPrioritasSasaranKab"."PrioritasSasaranKabID",
                                                    "tmPrioritasTujuanKab"."Kd_Tujuan",
                                                    "tmPrioritasTujuanKab"."Nm_Tujuan",
                                                    "tmPrioritasSasaranKab"."Kd_Sasaran",
                                                    "tmPrioritasSasaranKab"."Nm_Sasaran",
                                                    "tmPrioritasSasaranKab"."Descr",
                                                    "tmPrioritasSasaranKab"."PrioritasSasaranKabID_Src",
                                                    "tmPrioritasSasaranKab"."created_at",
                                                    "tmPrioritasSasaranKab"."updated_at"'))
                                ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstrasasaran.show")->with(['page_active'=>'renstrasasaran',
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
        
        $data = RENSTRASasaranModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_tujuan=\App\Models\RENSTRA\RENSTRATujuanModel::select(\DB::raw('"PrioritasTujuanKabID",CONCAT(\'[\',"Kd_Tujuan",\']. \',"Nm_Tujuan") AS "Nm_Tujuan"'))
                                                            ->where('TA',config('eplanning.tahun_perencanaan'))
                                                            ->orderBy('Kd_Tujuan','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Tujuan','PrioritasTujuanKabID')
                                                            ->toArray();
            return view("pages.$theme.renstra.renstrasasaran.edit")->with(['page_active'=>'renstrasasaran',
                                                                        'data'=>$data,
                                                                        'daftar_tujuan'=>$daftar_tujuan
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
        $renstrasasaran = RENSTRASasaranModel::find($id);
        
        $this->validate($request, [
            'Kd_Sasaran'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasSasaranKab',
                                                                        $renstrasasaran->Kd_Sasaran,
                                                                        ['where'=>['TA','=',config('eplanning.tahun_perencanaan')]],
                                                                        'Kode Sasaran')],
            'PrioritasTujuanKabID'=>'required',
            'Nm_Sasaran'=>'required',
        ]);
               
        $renstrasasaran->PrioritasTujuanKabID = $request->input('PrioritasTujuanKabID');
        $renstrasasaran->Kd_Sasaran = $request->input('Kd_Sasaran');
        $renstrasasaran->Nm_Sasaran = $request->input('Nm_Sasaran');
        $renstrasasaran->Descr = $request->input('Descr');
        $renstrasasaran->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstrasasaran.show',['id'=>$renstrasasaran->PrioritasSasaranKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstrasasaran = RENSTRASasaranModel::find($id);
        $result=$renstrasasaran->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstrasasaran'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstrasasaran.datatable")->with(['page_active'=>'renstrasasaran',
                                                            'search'=>$this->getControllerStateSession('renstrasasaran','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstrasasaran.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrasasaran.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstrasasaran.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
