<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\UrusanModel;
use App\Models\DMaster\OrganisasiModel;
use App\Models\DMaster\MappingProgramToOPDModel;

class MappingProgramToOPDController extends Controller {
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
        if (!$this->checkStateIsExistSession('mappingprogramtoopd','orderby')) 
        {            
           $this->putControllerStateSession('mappingprogramtoopd','orderby',['column_name'=>'OrgID','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'); 
        $direction=$this->getControllerStateSession('mappingprogramtoopd.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');      
        
         //filter
         if (!$this->checkStateIsExistSession('mappingprogramtoopd','filters')) 
         {            
             $this->putControllerStateSession('mappingprogramtoopd','filters',['UrsID'=>'none']);
         }
         $filter_ursid=$this->getControllerStateSession('program.filters','UrsID');

        if ($this->checkStateIsExistSession('mappingprogramtoopd','search')) 
        {
            $search=$this->getControllerStateSession('mappingprogramtoopd','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = MappingProgramToOPDModel::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = MappingProgramToOPDModel::where('replaceit', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = MappingProgramToOPDModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('mappingprogramtoopd.index'));
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
        
        $this->setCurrentPageInsideSession('mappingprogramtoopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                                                'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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
        $this->putControllerStateSession('mappingprogramtoopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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

        $this->setCurrentPageInsideSession('mappingprogramtoopd',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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
            $this->destroyControllerStateSession('mappingprogramtoopd','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('mappingprogramtoopd','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('mappingprogramtoopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',                                                            
                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
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

        $search=$this->getControllerStateSession('mappingprogramtoopd','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('mappingprogramtoopd'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('mappingprogramtoopd',$data->currentPage());
        
        return view("pages.$theme.dmaster.mappingprogramtoopd.index")->with(['page_active'=>'mappingprogramtoopd',
                                                'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
                                                'data'=>$data]);               
    }

    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateDataProgram ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('program','orderby')) 
        {            
           $this->putControllerStateSession('program','orderby',['column_name'=>'kode_program','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('program.orderby','column_name'); 
        $direction=$this->getControllerStateSession('program.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');   
        
        //filter
        if (!$this->checkStateIsExistSession('program','filters')) 
        {            
            $this->putControllerStateSession('program','filters',['UrsID'=>'none']);
        }
        $filter_ursid=$this->getControllerStateSession('program.filters','UrsID'); 

        if ($this->checkStateIsExistSession('program','search')) 
        {
            $search=$this->getControllerStateSession('program','search');
            switch ($search['kriteria']) 
            {
                case 'kode_program' :
                    $data = \DB::table('v_urusan_program')
                                ->where('TA',config('globalsettings.tahun_perencanaan'))
                                ->where(['kode_program'=>$search['isikriteria']])
                                ->orderBy($column_order,$direction); 
                break;
                case 'PrgNm' :
                    $data = \DB::table('v_urusan_program')
                            ->where('TA',config('globalsettings.tahun_perencanaan'))
                            ->where('PrgNm', SQL::like(), '%' . $search['isikriteria'] . '%')
                            ->orderBy($column_order,$direction);                                        
                break;
            }     
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data =$filter_ursid == 'none' ? 
                                            \DB::table('v_urusan_program')
                                                        ->where('TA',config('globalsettings.tahun_perencanaan'))
                                                        ->orderBy($column_order,$direction)                                                        
                                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage)
                                            :
                                            \DB::table('v_urusan_program')
                                                        ->where('TA',config('globalsettings.tahun_perencanaan'))
                                                        ->orderBy($column_order,$direction)                                                        
                                                        ->where('UrsID',$filter_ursid)
                                                        ->orWhereNull('UrsID')
                                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
        }           
        $data->setPath(route('program.index'));
        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        $theme = \Auth::user()->theme;

        $daftar_urusan=UrusanModel::getDaftarUrusan(config('globalsettings.tahun_perencanaan'));
        $daftar_urusan['none']='SELURUH URUSAN';       

        $search=$this->getControllerStateSession('program','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('program'); 
        $data = $this->populateDataProgram($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateDataProgram($data->lastPage());
        }
        $this->setCurrentPageInsideSession('program',$data->currentPage());
        $filter_kode_urusan_selected=UrusanModel::getKodeUrusanByUrsID($this->getControllerStateSession('program.filters','UrsID'));

        return view("pages.$theme.dmaster.mappingprogramtoopd.create")->with(['page_active'=>'mappingprogramtoopd',     
                                                                'daftar_opd'=>[],                                                           
                                                                'search'=>$this->getControllerStateSession('program','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('program.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('program.orderby','order'),
                                                                'daftar_urusan'=>$daftar_urusan,
                                                                'filter_ursid_selected'=>$this->getControllerStateSession('program.filters','UrsID'), 
                                                                'filter_kode_urusan_selected'=>$filter_kode_urusan_selected,
                                                                'data'=>$data]);               

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
        
        $mappingprogramtoopd = MappingProgramToOPDModel::create([
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
            return redirect(route('mappingprogramtoopd.index'))->with('success','Data ini telah berhasil disimpan.');
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

        $data = MappingProgramToOPDModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.mappingprogramtoopd.show")->with(['page_active'=>'mappingprogramtoopd',
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
        
        $data = MappingProgramToOPDModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.dmaster.mappingprogramtoopd.edit")->with(['page_active'=>'mappingprogramtoopd',
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
        $mappingprogramtoopd = MappingProgramToOPDModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $mappingprogramtoopd->replaceit = $request->input('replaceit');
        $mappingprogramtoopd->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('mappingprogramtoopd.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $mappingprogramtoopd = MappingProgramToOPDModel::find($id);
        $result=$mappingprogramtoopd->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('mappingprogramtoopd'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.mappingprogramtoopd.datatable")->with(['page_active'=>'mappingprogramtoopd',
                                                            'search'=>$this->getControllerStateSession('mappingprogramtoopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('mappingprogramtoopd.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('mappingprogramtoopd.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}