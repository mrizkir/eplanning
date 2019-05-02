<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;
use App\Models\Musrenbang\AspirasiMusrenDesaModel;

class PembahasanMusrenDesaController extends Controller {
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
        $columns=['trUsulanDesa.UsulanDesaID','trUsulanDesa.No_usulan','trUsulanDesa.NamaKegiatan','trUsulanDesa.Output','trUsulanDesa.NilaiUsulan','trUsulanDesa.Target_Angka','trUsulanDesa.Target_Uraian','trUsulanDesa.Jeniskeg','trUsulanDesa.Prioritas','trUsulanDesa.Bobot','trUsulanDesa.Privilege'];       
        if (!$this->checkStateIsExistSession('pembahasanmusrendesa','orderby')) 
        {            
           $this->putControllerStateSession('pembahasanmusrendesa','orderby',['column_name'=>'tmPmDesa.Nm_Desa','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');  
        
        //filter
        if (!$this->checkStateIsExistSession('pembahasanmusrendesa','filters')) 
        {            
            $this->putControllerStateSession('pembahasanmusrendesa','filters',['PmKecamatanID'=>'none',
                                                                            'PmDesaID'=>'none']);
        }        
        $filter_desa = $this->getControllerStateSession('pembahasanmusrendesa.filters','PmDesaID');        
        if ($this->checkStateIsExistSession('pembahasanmusrendesa','search')) 
        {
            $search=$this->getControllerStateSession('pembahasanmusrendesa','search');
            switch ($search['kriteria']) 
            {
                case 'No_usulan' :                    
                    $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                                    ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                                    ->where('trUsulanDesa.TA', config('globalsettings.tahun_perencanaan'))
                                                    ->where('trUsulanDesa.PmDesaID',$filter_desa)
                                                    ->where(['trUsulanDesa.No_usulan'=>(int)$search['isikriteria']])
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction);
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                                    ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                                    ->where('trUsulanDesa.TA', config('globalsettings.tahun_perencanaan'))
                                                    ->where('trUsulanDesa.PmDesaID',$filter_desa)
                                                    ->where('trUsulanDesa.NamaKegiatan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction);                                        
            break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                            ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                            ->where('trUsulanDesa.TA', config('globalsettings.tahun_perencanaan'))
                                            ->where('trUsulanDesa.PmDesaID',$filter_desa)                                            
                                            ->orderBy('Prioritas','ASC')
                                            ->orderBy($column_order,$direction)                                            
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('pembahasanmusrendesa.index'));                             
        
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
        
        $this->setCurrentPageInsideSession('pembahasanmusrendesa',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrendesa.datatable")->with(['page_active'=>'pembahasanmusrendesa',
                                                                                'search'=>$this->getControllerStateSession('pembahasanmusrendesa','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'),
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
            case 'col-No_usulan' :
                $column_name = 'trUsulanDesa.No_usulan';
            break;
            case 'col-Nm_Desa' :
                $column_name = 'tmPmDesa.Nm_Desa';
            break;
            case 'col-Nm_Kecamatan' :
                $column_name = 'tmPmKecamatan.Nm_Kecamatan';
            break;
            case 'col-NamaKegiatan' :
                $column_name = 'trUsulanDesa.NamaKegiatan';
            break;
            case 'col-NilaiUsulan' :
                $column_name = 'trUsulanDesa.NilaiUsulan';
            break;        
            default :
                $column_name = 'trUsulanDesa.No_usulan';
        }
        $this->putControllerStateSession('pembahasanmusrendesa','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanmusrendesa'); 
        $data = $this->populateData($currentpage);     
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrendesa.datatable")->with(['page_active'=>'pembahasanmusrendesa',
                                                            'search'=>$this->getControllerStateSession('pembahasanmusrendesa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'),
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

        $this->setCurrentPageInsideSession('pembahasanmusrendesa',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.musrenbang.pembahasanmusrendesa.datatable")->with(['page_active'=>'pembahasanmusrendesa',
                                                                            'search'=>$this->getControllerStateSession('pembahasanmusrendesa','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'),
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
            $this->destroyControllerStateSession('pembahasanmusrendesa','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pembahasanmusrendesa','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pembahasanmusrendesa',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrendesa.datatable")->with(['page_active'=>'pembahasanmusrendesa',                                                            
                                                            'search'=>$this->getControllerStateSession('pembahasanmusrendesa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'),
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

        $filters=$this->getControllerStateSession('pembahasanmusrendesa','filters');
        $daftar_desa=[];
        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $filters['PmKecamatanID']=$PmKecamatanID;

            $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$PmKecamatanID);
        }   
        if ($request->exists('PmDesaID'))
        {
            $PmDesaID = $request->input('PmDesaID')==''?'none':$request->input('PmDesaID');
            $filters['PmDesaID']=$PmDesaID;

            $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$filters['PmKecamatanID']);
        }

        $this->putControllerStateSession('pembahasanmusrendesa','filters',$filters);   
        $this->setCurrentPageInsideSession('pembahasanmusrendesa',1);

        $data=$this->populateData();        
        $datatable = view("pages.$theme.musrenbang.pembahasanmusrendesa.datatable")->with(['page_active'=>'pembahasanmusrendesa',                                                            
                                                                                        'search'=>$this->getControllerStateSession('pembahasanmusrendesa','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'),
                                                                                        'filters'=>$filters,
                                                                                        'data'=>$data])->render();      
        
        
        return response()->json(['success'=>true,'daftar_desa'=>$daftar_desa,'datatable'=>$datatable],200);         

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;
       
        $search=$this->getControllerStateSession('pembahasanmusrendesa','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanmusrendesa'); 
        $data = $this->populateData($currentpage);     
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pembahasanmusrendesa',$data->currentPage());
        $filters=$this->getControllerStateSession('pembahasanmusrendesa','filters');        
        $daftar_kecamatan=KecamatanModel::getDaftarKecamatan(config('globalsettings.tahun_perencanaan'),false);
        $daftar_desa=DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$filters['PmKecamatanID'],false);        
        return view("pages.$theme.musrenbang.pembahasanmusrendesa.index")->with(['page_active'=>'pembahasanmusrendesa',
                                                                                'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                'daftar_desa'=>$daftar_desa,
                                                                                'search'=>$this->getControllerStateSession('pembahasanmusrendesa','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanmusrendesa.orderby','order'),
                                                                                'filters'=>$filters,
                                                                                'data'=>$data]);               
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

        $data = AspirasiMusrenDesaModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.musrenbang.pembahasanmusrendesa.show")->with(['page_active'=>'pembahasanmusrendesa',
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
        $pembahasanmusrendesa = AspirasiMusrenDesaModel::find($id);        
        $pembahasanmusrendesa->Privilege = $request->input('Privilege');
        $pembahasanmusrendesa->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('pembahasanmusrendesa.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
        }
    }
}