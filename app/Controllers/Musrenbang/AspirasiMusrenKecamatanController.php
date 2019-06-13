<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\SumberDanaModel;
use App\Models\DMaster\UrusanModel;
use App\Models\DMaster\OrganisasiModel;
use App\Models\DMaster\KecamatanModel;
use App\Models\DMaster\DesaModel;
use App\Models\Musrenbang\AspirasiMusrenKecamatanModel;
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
        $this->middleware(['auth','role:superadmin|bapelitbang|kecamatan']);
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
           $this->putControllerStateSession('aspirasimusrenkecamatan','orderby',['column_name'=>'trUsulanKec.No_usulan','order'=>'asc']);
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
                    $data = AspirasiMusrenKecamatanModel::select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","trUsulanKec"."No_usulan","tmPmDesa"."Nm_Desa","tmPmKecamatan"."Nm_Kecamatan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Privilege"'))
                                                        ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                                        ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                                                        ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')                                                        
                                                        ->where('trUsulanKec.TA', config('eplanning.tahun_perencanaan'))
                                                        ->where(['No_usulan'=>(int)$search['isikriteria']])
                                                        ->orderBy('trUsulanKec.Prioritas','ASC')
                                                        ->orderBy($column_order,$direction); 
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenKecamatanModel::select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","trUsulanKec"."No_usulan","tmPmDesa"."Nm_Desa","tmPmKecamatan"."Nm_Kecamatan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Privilege"'))
                                                        ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                                        ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                                                        ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')
                                                        ->where('trUsulanKec.TA', config('eplanning.tahun_perencanaan'))
                                                        ->where('NamaKegiatan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                        ->orderBy('trUsulanKec.Prioritas','ASC')
                                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenKecamatanModel::select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","trUsulanKec"."No_usulan","tmPmDesa"."Nm_Desa","tmPmKecamatan"."Nm_Kecamatan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Privilege"'))
                                                ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')    
                                                ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')        
                                                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')
                                                ->where('trUsulanKec.TA', config('eplanning.tahun_perencanaan'))
                                                ->orderBy('trUsulanKec.Prioritas','ASC')
                                                ->orderBy("$column_order",$direction)
                                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
                                                
            
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
            case 'col-No_usulan' :
                $column_name = 'trUsulanKec.No_usulan';
            break;
            case 'col-Nm_Desa' :
                $column_name = 'tmPmDesa.Nm_Desa';
            break;
            case 'col-Nm_Kecamatan' :
                $column_name = 'tmPmKecamatan.Nm_Kecamatan';
            break;
            case 'col-NamaKegiatan' :
                $column_name = 'trUsulanKec.NamaKegiatan';
            break;
            case 'col-NilaiUsulan' :
                $column_name = 'trUsulanKec.NilaiUsulan';
            break;        
            default :
                $column_name = 'trUsulanKec.No_usulan';
        }
        $this->putControllerStateSession('aspirasimusrenkecamatan','orderby',['column_name'=>$column_name,'order'=>$orderby]);     

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('aspirasimusrenkecamatan'); 
        $data = $this->populateData($currentpage);
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
    /**
     * digunakan untuk mengurutkan record 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderbypilihusulankegiatan (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $orderby = $request->input('orderbypilihusulankegiatan') == 'asc'?'desc':'asc';
        $column=$request->input('column_name');
        switch($column) 
        {       
            case 'col-NamaKegiatan' :
                $column_name = 'trUsulanDesa.NamaKegiatan';
            break;
            case 'col-NilaiUsulan' :
                $column_name = 'trUsulanDesa.NilaiUsulan';
            break;   
            case 'col-Prioritas' :
                $column_name = 'trUsulanDesa.Prioritas';
            break;       
            default :
                $column_name = 'trUsulanDesa.No_usulan';
        }
        $this->putControllerStateSession('aspirasimusrenkecamatan','orderbypilihusulankegiatan',['column_name'=>$column_name,'order'=>$orderby]);     

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('aspirasimusrenkecamatan'); 
        $data = $this->populateDataUsulanKegiatan($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateDataUsulanKegiatan($data->lastPage());
        }

        $datatable = view("pages.$theme.musrenbang.aspirasimusrenkecamatan.datatablepilihusulankegiatan")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                                        'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                                        'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderbypilihusulankegiatan','column_name'),
                                                                                                        'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderbypilihusulankegiatan','order'),
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

            $daftar_desa=DesaModel::getDaftarDesa(config('eplanning.tahun_perencanaan'),$filters['PmKecamatanID']);
            $view = 'datatable';
        }       

        if ($request->exists('PmKecamatanIDPilihUsulan'))
        {
            $PmKecamatanID = $request->input('PmKecamatanIDPilihUsulan')==''?'none':$request->input('PmKecamatanIDPilihUsulan');
            $filters['PmKecamatanID']=$PmKecamatanID;

            $daftar_desa=DesaModel::getDaftarDesa(config('eplanning.tahun_perencanaan'),$PmKecamatanID);

            $this->putControllerStateSession('aspirasimusrenkecamatan','filters',$filters);
            $data=$this->populateDataUsulanKegiatan(); 

            $view = 'datatablepilihusulankegiatan';
        } 
        if ($request->exists('PmDesaIDPilihUsulan'))
        {
            $PmDesaIDPilihUsulan = $request->input('PmDesaIDPilihUsulan')==''?'none':$request->input('PmDesaIDPilihUsulan');
            $filters['PmDesaID']=$PmDesaIDPilihUsulan;

            $daftar_desa=DesaModel::getDaftarDesa(config('eplanning.tahun_perencanaan'),$filters['PmKecamatanID']);
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
        
        $daftar_desa=DesaModel::getDaftarDesa(config('eplanning.tahun_perencanaan'),$filters['PmKecamatanID'],false);
        return response()->json(['success'=>true,'filters'=>$filters,'view'=>$view,'daftar_desa'=>$daftar_desa,'datatable'=>$datatable],200);         

    }
    /**
     * filter urusan resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filterurusan (Request $request) 
    {
        $UrsID = $request->input('UrsID')==''?'none':$request->input('UrsID');
        $daftar_organisasi=OrganisasiModel::getDaftarOPD(config('eplanning.tahun_perencanaan'),false,$UrsID);
        return response()->json(['success'=>true,'daftar_organisasi'=>$daftar_organisasi],200);         
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
        
        $daftar_usulan_kec_id=\App\Models\Musrenbang\AspirasiMusrenKecamatanModel::select('UsulanKecID')
                                                                                ->where('Privilege',1)
                                                                                ->whereExists(function($query){
                                                                                    $query->select(\DB::raw(1))
                                                                                        ->from('trRenjaRinc')
                                                                                        ->whereRaw('"trRenjaRinc"."UsulanKecID"="trUsulanKec"."UsulanKecID"');
                                                                                })
                                                                                ->where('TA',config('eplanning.tahun_perencanaan'))
                                                                                ->get()->pluck('UsulanKecID','UsulanKecID')->toArray();

        return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.index")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                    'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                    'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderby','order'),
                                                                                    'daftar_usulan_kec_id'=>$daftar_usulan_kec_id,
                                                                                    'data'=>$data]);               
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateDataUsulanKegiatan ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('aspirasimusrenkecamatan','orderbypilihusulankegiatan')) 
        {            
           $this->putControllerStateSession('aspirasimusrenkecamatan','orderbypilihusulankegiatan',['column_name'=>'No_usulan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('aspirasimusrenkecamatan.orderbypilihusulankegiatan','column_name'); 
        $direction=$this->getControllerStateSession('aspirasimusrenkecamatan.orderbypilihusulankegiatan','order'); 
        
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
                $data = AspirasiMusrenDesaModel::select(\DB::raw('"trUsulanDesa"."UsulanDesaID","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot"'))
                                                    ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                                                    ->where('trUsulanDesa.TA', config('eplanning.tahun_perencanaan'))
                                                    ->where('trUsulanDesa.PmDesaID',$filter_desa)
                                                    ->where(['trUsulanDesa.No_usulan'=>(int)$search['isikriteria']])
                                                    ->where('trUsulanDesa.Privilege',1)
                                                    ->whereNull('trUsulanKec.UsulanDesaID')
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy("trUsulanDesa.NamaKegiatan",$direction);
                break;
                case 'NamaKegiatan' :
                $data = AspirasiMusrenDesaModel::select(\DB::raw('"trUsulanDesa"."UsulanDesaID","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot"'))
                                                    ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                                                    ->where('trUsulanDesa.trUsulanDesa.TA', config('eplanning.tahun_perencanaan'))
                                                    ->where('trUsulanDesa.PmDesaID',$filter_desa)
                                                    ->where('trUsulanDesa.NamaKegiatan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                    ->where('trUsulanDesa.Privilege',1)
                                                    ->whereNull('trUsulanKec.UsulanDesaID')
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy("trUsulanDesa.NamaKegiatan",$direction);
            break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenDesaModel::select(\DB::raw('"trUsulanDesa"."UsulanDesaID","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot"'))
                                            ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                                            ->where('trUsulanDesa.TA', config('eplanning.tahun_perencanaan'))
                                            ->where('trUsulanDesa.PmDesaID',$filter_desa)
                                            ->where('trUsulanDesa.Privilege',1)
                                            ->whereNull('trUsulanKec.UsulanDesaID')
                                            ->orderBy('Prioritas','ASC')
                                            ->orderBy("trUsulanDesa.NamaKegiatan",$direction)
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
    public function pilihusulankegiatan(Request $request)
    {        
        $theme = \Auth::user()->theme;       
        
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('aspirasimusrenkecamatan'); 
        $data = $this->populateDataUsulanKegiatan($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateDataUsulanKegiatan($data->lastPage());
        }
        $this->setCurrentPageInsideSession('aspirasimusrenkecamatan',$data->currentPage());

        $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters');   
        
        $daftar_kecamatan=KecamatanModel::getDaftarKecamatan(config('eplanning.tahun_perencanaan'),false);
        $daftar_desa=DesaModel::getDaftarDesa(config('eplanning.tahun_perencanaan'),$filters['PmKecamatanID'],false);         
        
        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);
        
        return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.pilihusulankegiatan")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                                'search'=>$this->getControllerStateSession('aspirasimusrenkecamatan','search'),
                                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                                'filters'=>$filters,
                                                                                                'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                                'daftar_desa'=>$daftar_desa,
                                                                                                'daftar_urusan'=>$daftar_urusan,
                                                                                                'column_order'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderbypilihusulankegiatan','column_name'),
                                                                                                'direction'=>$this->getControllerStateSession('aspirasimusrenkecamatan.orderbypilihusulankegiatan','order'),
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
        $theme = \Auth::user()->theme;

        $sumber_dana = SumberDanaModel::getDaftarSumberDana(config('eplanning.tahun_perencanaan'),false);
        $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters');   
        $daftar_kecamatan=KecamatanModel::getDaftarKecamatan(config('eplanning.tahun_perencanaan'),false);
        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);
        return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.create")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                    'filters'=>$filters,
                                                                                    'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                    'daftar_urusan'=>$daftar_urusan,
                                                                                    'sumber_dana'=>$sumber_dana
                                                                                    ]);     
       
        
    }
    /**
     * Store a newly usulan kecamatan created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeusulankecamatan(Request $request)
    {     
        $this->validate($request, [
            'OrgID'=>'required',
            'PmKecamatanID'=>'required',
            'NamaKegiatan'=>'required',
            'Output'=>'required',
            'Lokasi'=>'required',
            'NilaiUsulan'=>'required',
            'Target_Angka'=>'required',
            'Prioritas'=>'required|not_in:none'            
        ]);
        $aspirasimusrenkecamatan = AspirasiMusrenKecamatanModel::create([
            'UsulanKecID' =>uniqid ('uid'),
            'PmKecamatanID'=>$request->input('PmKecamatanID'),
            'OrgID'=>$request->input('OrgID'),
            'SumberDanaID' => $request->input('SumberDanaID'),
            'No_usulan' => AspirasiMusrenDesaModel::max('No_usulan')+1,
            'NamaKegiatan' => $request->input('NamaKegiatan'),
            'Output' => $request->input('Output'),
            'Lokasi' => $request->input('Lokasi'),
            'NilaiUsulan' => $request->input('NilaiUsulan'),
            'Target_Angka' => $request->input('Target_Angka'),
            'Target_Uraian' => $request->input('Target_Uraian'),
            'Jeniskeg' => $request->exists('Jeniskeg')?1:0,
            'Prioritas' => $request->input('Prioritas'),            
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
            return redirect(route('aspirasimusrenkecamatan.show',['id'=>$aspirasimusrenkecamatan->UsulanKecID]))->with('success','Data ini usulan kegiatan Musrenbang Desa telah berhasil disimpan.');
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
            'OrgID'=>'required',
            'UsulanDesaID.*'=>'required',
        ]);
        
        $OrgID=$request->input('OrgID');
        $ListUsulanDesaID = $request->input('UsulanDesaID');
        
        $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters'); 
        $PmKecamatanID = $filters['PmKecamatanID'];
        $PmDesaID = $filters['PmDesaID'];
        foreach ($ListUsulanDesaID as $UsulanDesaID)
        {
            $aspirasimusrendesa = AspirasiMusrenDesaModel::find($UsulanDesaID);
            $aspirasimusrenkecamatan = AspirasiMusrenKecamatanModel::create([
                'UsulanKecID' =>uniqid ('uid'),
                'UsulanDesaID'=>$UsulanDesaID,
                'PmKecamatanID'=>$PmKecamatanID,
                'PmDesaID'=>$PmDesaID,
                'OrgID'=>$OrgID,
                'SumberDanaID' => $aspirasimusrendesa->SumberDanaID,
                'No_usulan'=>$aspirasimusrendesa->No_usulan,
                'NamaKegiatan'=>$aspirasimusrendesa->NamaKegiatan,
                'Output'=>$aspirasimusrendesa->Output,
                'Lokasi'=>$aspirasimusrendesa->Lokasi,
                'NilaiUsulan'=>$aspirasimusrendesa->NilaiUsulan,
                'Jeniskeg'=>$aspirasimusrendesa->Jeniskeg,
                'Target_Uraian'=>$aspirasimusrendesa->Target_Uraian,
                'Target_Angka'=>$aspirasimusrendesa->Target_Angka,
                'Prioritas'=>$aspirasimusrendesa->Prioritas,
                'Descr'=>$aspirasimusrendesa->Descr,
                'TA'=>$aspirasimusrendesa->TA
            ]);
        }                
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('aspirasimusrenkecamatan.show',['id'=>$aspirasimusrenkecamatan->UsulanKecID]))->with('success','Data ini usulan kegiatan Musrenbang Desa telah berhasil disimpan.');
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

        $data = AspirasiMusrenKecamatanModel::leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')
                                            ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                            ->findOrFail($id);
        if (!is_null($data) )  
        {            
            return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.show")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                        'data'=>$data,                                                                                        
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
        
        $data = AspirasiMusrenKecamatanModel::leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')
                                            ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                            ->leftJoin('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                                            ->findOrFail($id);
        if (!is_null($data) ) 
        {                       
            $sumber_dana = SumberDanaModel::getDaftarSumberDana(config('eplanning.tahun_perencanaan'),false);
            $filters=$this->getControllerStateSession('aspirasimusrenkecamatan','filters');   
            $daftar_kecamatan=KecamatanModel::getDaftarKecamatan(config('eplanning.tahun_perencanaan'),false);
            $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);

            $UrsID = $data->UrsID;
            $daftar_organisasi=OrganisasiModel::getDaftarOPD(config('eplanning.tahun_perencanaan'),false,$UrsID);
            return view("pages.$theme.musrenbang.aspirasimusrenkecamatan.edit")->with(['page_active'=>'aspirasimusrenkecamatan',
                                                                                        'data'=>$data,
                                                                                        'filters'=>$filters,
                                                                                        'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                        'daftar_urusan'=>$daftar_urusan,
                                                                                        'daftar_opd'=>$daftar_organisasi,
                                                                                        'sumber_dana'=>$sumber_dana
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
            'OrgID'=>'required',
            'PmKecamatanID'=>'required',
            'NamaKegiatan'=>'required',
            'Output'=>'required',
            'Lokasi'=>'required',
            'NilaiUsulan'=>'required',
            'Target_Uraian'=>'required',
            'Target_Angka'=>'required',
            'Prioritas'=>'required|not_in:none',
            'SumberDanaID'=>'required|not_in:none',
        ]);
        
        $aspirasimusrenkecamatan->OrgID = $request->input('OrgID');
        $aspirasimusrenkecamatan->SumberDanaID = $request->input('SumberDanaID');
        $aspirasimusrenkecamatan->PmKecamatanID = $request->input('PmKecamatanID');
        $aspirasimusrenkecamatan->NamaKegiatan = $request->input('NamaKegiatan');
        $aspirasimusrenkecamatan->Output = $request->input('Output');
        $aspirasimusrenkecamatan->Lokasi = $request->input('Lokasi');
        $aspirasimusrenkecamatan->NilaiUsulan = $request->input('NilaiUsulan');
        $aspirasimusrenkecamatan->Target_Uraian = $request->input('Target_Uraian');
        $aspirasimusrenkecamatan->Target_Angka = $request->input('Target_Angka');        
        $aspirasimusrenkecamatan->Jeniskeg = $request->exists('Jeniskeg')?1:0;
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
            return redirect(route('aspirasimusrenkecamatan.show',['id'=>$id]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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