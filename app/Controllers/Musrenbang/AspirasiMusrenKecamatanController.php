<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Musrenbang\AspirasiMusrenKecamatanModel;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;
use App\Models\Musrenbang\AspirasiMusrenDesaModel;

class AspirasiMusrenKecamatanController extends Controller {
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
        if (!$this->checkStateIsExistSession('aspirasimusrenkecamatan','orderby')) 
        {            
           $this->putControllerStateSession('aspirasimusrenkecamatan','orderby',['column_name'=>'PmDesaID','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');   
        
        //filter
        if (!$this->checkStateIsExistSession('aspirasimusrenkecamatan','filters')) 
        {            
            $this->putControllerStateSession('aspirasimusrenkecamatan','filters',['PmKecamatanID'=>'none',
                                                                            'PmDesaID'=>'none']);
        }        
        $filter_desa = $this->getControllerStateSession('aspirasimusrenkecamatan.filters','PmDesaID');        
        
        if ($this->checkStateIsExistSession('aspirasimusrenkecamatan','search')) 
        {
            $search=$this->getControllerStateSession('aspirasimusrenkecamatan','search');
            switch ($search['kriteria']) 
            {
                case 'No_usulan' :
                    $data = AspirasiMusrenKecamatanModel::where(['No_usulan'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenKecamatanModel::where('NamaKegiatan', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenKecamatanModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
        }        
        $data->setPath(route('aspirasimusrenkecamatan.index'));
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
        
        $this->setCurrentPageInsideSession('aspirasimusrenkecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.datatable")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
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
        $this->putControllerStateSession('aspirasimusrenkecamatan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.datatable")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                            'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
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

        $this->setCurrentPageInsideSession('aspirasimusrenkecamatan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.datatable")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                            'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
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
            $this->destroyControllerStateSession('aspirasimusrenkecamatan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('aspirasimusrenkecamatan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('aspirasimusrenkecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.datatable")->with(['page_active'=>'aspirasimusrenkecamatan',                                                            
                                                            'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
                                                            'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters');

        if ($request->exists('PmDesaID'))
        {
            $PmDesaID = $request->input('PmDesaID')==''?'none':$request->input('PmDesaID');
            $filters['PmDesaID']=$PmDesaID;

            $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$filters['PmKecamatanID']);
            $view = 'datatable';
        }       

        if ($request->exists('PmKecamatanIDPilihUsulan'))
        {
            $PmKecamatanID = $request->input('PmKecamatanIDPilihUsulan')==''?'none':$request->input('PmKecamatanIDPilihUsulan');
            $filters['PmKecamatanID']=$PmKecamatanID;

            $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$PmKecamatanID);

            $this->putControllerStateSession('aspirasimusrenkecamatan','filters',$filters);
            $data=$this->populateDataUsulanKegiatan(); 

            $view = 'datatablepilihusulankegiatan';
        } 
        if ($request->exists('PmDesaIDPilihUsulan'))
        {
            $PmDesaIDPilihUsulan = $request->input('PmDesaIDPilihUsulan')==''?'none':$request->input('PmDesaIDPilihUsulan');
            $filters['PmDesaID']=$PmDesaIDPilihUsulan;

            $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$filters['PmKecamatanID']);
            $view = 'datatablepilihusulankegiatan';

            $this->putControllerStateSession('aspirasimusrenkecamatan','filters',$filters);
            $data=$this->populateDataUsulanKegiatan();  
        }           
        $this->setCurrentPageInsideSession('aspirasimusrenkecamatan',1);
              
        $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.$view")->with(['page_active'=>'aspirasimusrenkecamatan',                                                            
                                                                                        'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
                                                                                        'filters'=>$filters,
                                                                                        'data'=>$data])->render();      
        
        $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$filters['PmKecamatanID'],false);
        return response()->json(['success'=>true,'filters'=>$filters,'view'=>$view,'daftar_desa'=>$daftar_desa,'datatable'=>$datatable],200);         

    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;

        $search=$this->getControllerStateSession('aspirasimusrenkecamatan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('aspirasimusrenkecamatan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('aspirasimusrenkecamatan',$data->currentPage());
        
        return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.index")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                    'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                    'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
                                                                                    'data'=>$data]);               
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateDataUsulanKegiatan ($currentpage=1) 
    {        
        $columns=['UsulanDesaID','No_usulan','NamaKegiatan','Output','NilaiUsulan','Target_Angka','Target_Uraian','Jeniskeg','Prioritas','Bobot','Privilege'];       
        if (!$this->checkStateIsExistSession('aspirasimusrenkecamatan','orderby')) 
        {            
           $this->putControllerStateSession('aspirasimusrenkecamatan','orderby',['column_name'=>'No_usulan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'); 
        
        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');  
        
        //filter
        if (!$this->checkStateIsExistSession('aspirasimusrenkecamatan','filters')) 
        {            
            $this->putControllerStateSession('aspirasimusrenkecamatan','filters',['PmKecamatanID'=>'none',
                                                                                'PmDesaID'=>'none']);
        }        
        $filter_desa = $this->getControllerStateSession('aspirasimusrenkecamatan.filters','PmDesaID');      

        if ($this->checkStateIsExistSession('aspirasimusrenkecamatan','search')) 
        {
            $search=$this->getControllerStateSession('aspirasimusrenkecamatan','search');
            switch ($search['kriteria']) 
            {
                case 'No_usulan' :                    
                    $data = AspirasiMusrenDesaModel::where('trUsulanDesa.TA', config('globalsettings.tahun_perencanaan'))
                                                    ->where('PmDesaID',$filter_desa)
                                                    ->where(['No_usulan'=>(int)$search['isikriteria']])
                                                    ->where('Privilege',1)
                                                    ->orderBy($column_order,$direction);
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenDesaModel::where('trUsulanDesa.TA', config('globalsettings.tahun_perencanaan'))
                                                    ->where('PmDesaID',$filter_desa)
                                                    ->where('NamaKegiatan', 'like', '%' . $search['isikriteria'] . '%')
                                                    ->where('Privilege',1)
                                                    ->orderBy($column_order,$direction);                                        
            break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenDesaModel::where('TA', config('globalsettings.tahun_perencanaan'))
                                            ->where('PmDesaID',$filter_desa)
                                            ->where('Privilege',1)
                                            ->orderBy($column_order,$direction)
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('aspirasimusrenkecamatan.pilihusulankegiatan'));                             
        
        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pilihusulankegiatan()
    {        
        $theme = \Auth::user()->theme;
                
        $data = $this->populateDataUsulanKegiatan();

        $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters');   
        
        $daftar_kecamatan=KecamatanModel::getDaftarKecamatan(config('globalsettings.tahun_perencanaan'),false);
        $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$filters['PmKecamatanID'],false);         
                
        return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.pilihusulankegiatan")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                                'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                                'filters'=>$filters,
                                                                                                'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                                'daftar_desa'=>$daftar_desa,
                                                                                                'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                                                                'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
                                                                                                'data'=>$data
                                                                                                ]);  
    }    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        if ($this->checkStateIsExistSession('aspirasimusrenkecamatan','filters.UsulanDesaID'))
        {
            $theme = \Auth::user()->theme;
            
            return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.create")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                    
                                                                                    ]);     
        }    
        else
        {
            abort(404);
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
            'replaceit'=>'required',
        ]);
        
        $aspirasimusrenkecamatan = AspirasiMusrenKecamatanModel::create([
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
            return redirect(route('aspirasimusrenkecamatan.index'))->with('success','Data ini telah berhasil disimpan.');
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

        $data = AspirasiMusrenKecamatanModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.show")->with(['page_active'=>'aspirasimusrenkecamatan',
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
        
        $data = AspirasiMusrenKecamatanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.edit")->with(['page_active'=>'aspirasimusrenkecamatan',
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
        $aspirasimusrenkecamatan = AspirasiMusrenKecamatanModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $aspirasimusrenkecamatan->replaceit = $request->input('replaceit');
        $aspirasimusrenkecamatan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('aspirasimusrenkecamatan.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $aspirasimusrenkecamatan = AspirasiMusrenKecamatanModel::find($id);
        $result=$aspirasimusrenkecamatan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('aspirasimusrenkecamatan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.datatable")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                            'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('aspirasimusrenkecamatan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}