<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\UsulanRAKORBidangModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;
use App\Models\RKPD\RenjaIndikatorModel;

class PembahasanRAKORBidangController extends Controller {
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
        if (!$this->checkStateIsExistSession('pembahasanrakorbidang','orderby')) 
        {            
           $this->putControllerStateSession('pembahasanrakorbidang','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('pembahasanrakorbidang','filters')) 
        {            
            $this->putControllerStateSession('pembahasanrakorbidang','filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $SOrgID= $this->getControllerStateSession('pembahasanrakorbidang.filters','SOrgID');        

        if ($this->checkStateIsExistSession('pembahasanrakorbidang','search')) 
        {
            $search=$this->getControllerStateSession('pembahasanrakorbidang','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = UsulanRAKORBidangModel::where(['kode_kegiatan'=>$search['isikriteria']])                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->whereNotNull('RenjaRincID')
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = UsulanRAKORBidangModel::where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->whereNotNull('RenjaRincID')
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = UsulanRAKORBidangModel::where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->whereNotNull('RenjaRincID')
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = UsulanRAKORBidangModel::where('SOrgID',$SOrgID)                                     
                                            ->whereNotNull('RenjaRincID')       
                                            ->where('TA', config('globalsettings.tahun_perencanaan'))                                            
                                            ->orderBy('Prioritas','ASC')
                                            ->orderBy($column_order,$direction)                                            
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }        
        $data->setPath(route('pembahasanrakorbidang.index'));  
        
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
        
        $this->setCurrentPageInsideSession('pembahasanrakorbidang',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',
                                                                                'label_transfer'=>'FORUM OPD / SKPD',
                                                                                'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
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
            case 'col-kode_kegiatan' :
                $column_name = 'kode_kegiatan';
            break;    
            case 'col-KgtNm' :
                $column_name = 'KgtNm';
            break;    
            case 'col-Uraian' :
                $column_name = 'Uraian';
            break;    
            case 'col-Sasaran_Angka2' :
                $column_name = 'Sasaran_Angka2';
            break;  
            case 'col-Jumlah2' :
                $column_name = 'Jumlah2';
            break;
            default :
                $column_name = 'kode_kegiatan';
        }
        $this->putControllerStateSession('pembahasanprarenjaopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanprarenjaopd');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.pembahasanprarenjaopd.datatable")->with(['page_active'=>'pembahasanprarenjaopd',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanprarenjaopd','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanprarenjaopd.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanprarenjaopd.orderby','order'),
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

        $this->setCurrentPageInsideSession('pembahasanrakorbidang',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',
                                                                            'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
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
            $this->destroyControllerStateSession('pembahasanrakorbidang','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pembahasanrakorbidang','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pembahasanrakorbidang',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',                                                            
                                                                                    'label_transfer'=>'FORUM OPD / SKPD',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
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

        $filters=$this->getControllerStateSession('pembahasanrakorbidang','filters');
        $daftar_unitkerja=[];
        $json_data = [];

        // //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$OrgID);  
            
            $this->putControllerStateSession('pembahasanrakorbidang','filters',$filters);

            $data = [];

            $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',                                                            
                                                                                    'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
                                                                                    'data'=>$data])->render();

            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('pembahasanrakorbidang','filters',$filters);
            $this->setCurrentPageInsideSession('pembahasanrakorbidang',1);

            $data = $this->populateData();

            $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',                                                            
                                                                                    'label_transfer'=>'FORUM OPD / SKPD',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
                                                                                    'data'=>$data])->render();                                                                                       
                                                                                    
            $json_data = ['success'=>true,'datatable'=>$datatable];    
        }
        return $json_data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('pembahasanrakorbidang','filters');
        $roles=$auth->getRoleNames();        
        switch ($roles[0])
        {
            case 'superadmin' :                 
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false);  
                $daftar_unitkerja=array();           
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$filters['OrgID']);        
                }    
            break;
            case 'opd' :
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false,NULL,$auth->OrgID);  
                $filters['OrgID']=$auth->OrgID;                
                if (empty($auth->SOrgID)) 
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$auth->OrgID);  
                    $filters['SOrgID']=empty($filters['SOrgID'])?'':$filters['SOrgID'];                    
                }   
                else
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$auth->OrgID,$auth->SOrgID);
                    $filters['SOrgID']=$auth->SOrgID;
                }                
                $this->putControllerStateSession('pembahasanrakorbidang','filters',$filters);
            break;
        }

        $search=$this->getControllerStateSession('pembahasanrakorbidang','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanrakorbidang'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pembahasanrakorbidang',$data->currentPage());

        return view("pages.$theme.rkpd.pembahasanrakorbidang.index")->with(['page_active'=>'pembahasanrakorbidang',
                                                                            'label_transfer'=>'FORUM OPD / SKPD',
                                                                            'daftar_opd'=>$daftar_opd,
                                                                            'daftar_unitkerja'=>$daftar_unitkerja,
                                                                            'filters'=>$filters,
                                                                            'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
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

        return view("pages.$theme.rkpd.pembahasanrakorbidang.create")->with(['page_active'=>'pembahasanrakorbidang',
                                                                    
                                                                           ]);  
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

        $data = PembahasanRAKORBidangModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rkpd.pembahasanrakorbidang.show")->with(['page_active'=>'pembahasanrakorbidang',
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
        $theme = \Auth::user()->theme;

        $pembahasanrakorbidang = RenjaRincianModel::find($id);        
        $pembahasanrakorbidang->Status = $request->input('Status');
        $pembahasanrakorbidang->save();

        $RenjaID = $pembahasanrakorbidang->RenjaID;
        if (RenjaRincianModel::where('RenjaID',$RenjaID)->where('Status',1)->count() > 0)
        {
            RenjaModel::where('RenjaID',$RenjaID)->update(['Status'=>1]);
            $a=0;
        }
        else
        {
            RenjaModel::where('RenjaID',$RenjaID)->update(['Status'=>0]);
            $a=1;
        }        
        if ($request->ajax()) 
        {
            $data = $this->populateData();

            $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',                                                            
                                                                                    'label_transfer'=>'FORUM OPD / SKPD',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
                                                                                    'data'=>$data])->render();
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.',
                'datatable'=>$datatable
            ],200);
        }
        else
        {
            return redirect(route('pembahasanrakorbidang.show',['id'=>$pembahasanrakorbidang->RenjaRincID]))->with('success','Data ini telah berhasil disimpan.');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        $theme = \Auth::user()->theme;

        if ($request->exists('RenjaRincID'))
        {
            $RenjaRincID=$request->input('RenjaRincID');                                    
            $rincian_kegiatan=\DB::transaction(function () use ($RenjaRincID) {
                $rincian_kegiatan = RenjaRincianModel::find($RenjaRincID);               

                //check renja id sudah ada belum di RenjaID_Old
                $old_renja = RenjaModel::select('RenjaID')
                                        ->where('RenjaID_Src',$rincian_kegiatan->RenjaID)
                                        ->get()
                                        ->pluck('RenjaID')->toArray();
                
                if (count($old_renja) > 0)
                {
                    $RenjaID=$old_renja[0];
                    $newRenjaID=$RenjaID;
                    $renja = RenjaModel::find($RenjaID);  
                }
                else
                {
                    $RenjaID=$rincian_kegiatan->RenjaID;
                    $renja = RenjaModel::find($RenjaID);   
                    $renja->Privilege=1;
                    $renja->save();
    
                    #new renja
                    $newRenjaID=uniqid ('uid');
                    $newrenja = $renja->replicate();
                    $newrenja->RenjaID = $newRenjaID;
                    $newrenja->Sasaran_Uraian3 = $newrenja->Sasaran_Uraian2;
                    $newrenja->Sasaran_Angka3 = $newrenja->Sasaran_Angka2;
                    $newrenja->Target3 = $newrenja->Target2;
                    $newrenja->NilaiUsulan3 = $newrenja->NilaiUsulan2;
                    $newrenja->EntryLvl = 2;
                    $newrenja->Status = 0;
                    $newrenja->Privilege = 0;
                    $newrenja->RenjaID_Src = $RenjaID;
                    $newrenja->save();
                }       

                $str_rinciankegiatan = '
                    INSERT INTO "trRenjaRinc" (
                        "RenjaRincID", 
                        "RenjaID",
                        "UsulanKecID",
                        "PMProvID",
                        "PmKotaID",
                        "PmKecamatanID",
                        "PmDesaID",
                        "PokPirID",
                        "Uraian",
                        "No",
                        "Sasaran_Uraian1",
                        "Sasaran_Uraian2",              
                        "Sasaran_Uraian3",              
                        "Sasaran_Angka1",
                        "Sasaran_Angka2",               
                        "Sasaran_Angka3",               
                        "Target1",
                        "Target2",                      
                        "Target3",                      
                        "Jumlah1", 
                        "Jumlah2", 
                        "Jumlah3", 
                        "isReses",
                        "isReses_Uraian",
                        "isSKPD",
                        "Status",
                        "EntryLvl",
                        "Prioritas",
                        "Descr",
                        "TA",
                        "RenjaRincID_Src",
                        "created_at", 
                        "updated_at"
                    ) 
                    SELECT 
                        REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaRincID",
                        \''.$newRenjaID.'\' AS "RenjaID",
                        "UsulanKecID",
                        "PMProvID",
                        "PmKotaID",
                        "PmKecamatanID",
                        "PmDesaID",
                        "PokPirID",
                        "Uraian",
                        "No",
                        "Sasaran_Uraian1",
                        "Sasaran_Uraian2",
                        "Sasaran_Uraian2" AS Sasaran_Uraian3,              
                        "Sasaran_Angka1",
                        "Sasaran_Angka2",
                        "Sasaran_Angka2" AS "Sasaran_Angka3",               
                        "Target1",
                        "Target2",
                        "Target2" AS "Target3",                      
                        "Jumlah1", 
                        "Jumlah2", 
                        "Jumlah2" AS "Jumlah3", 
                        "isReses",
                        "isReses_Uraian",
                        "isSKPD",
                        0 AS "Status",
                        2 AS "EntryLvl",
                        "Prioritas",
                        "Descr",
                        "TA",
                        "RenjaRincID" AS "RenjaID_Src",
                        NOW() AS created_at,
                        NOW() AS updated_at
                    FROM 
                        "trRenjaRinc" 
                    WHERE "RenjaRincID"=\''.$RenjaRincID.'\' AND
                        ("Status"=1 OR "Status"=2) AND
                        "Privilege"=0       
                ';
                \DB::statement($str_rinciankegiatan);       
                $str_kinerja='
                    INSERT INTO "trRenjaIndikator" (
                        "RenjaIndikatorID", 
                        "IndikatorKinerjaID",
                        "RenjaID",
                        "Target_Angka",
                        "Target_Uraian",  
                        "Tahun",      
                        "Descr",
                        "Privilege",
                        "TA",
                        "RenjaIndikatorID_Src", 
                        "created_at", 
                        "updated_at"
                    )
                    SELECT 
                        REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaIndikatorID",
                        "IndikatorKinerjaID",
                        \''.$newRenjaID.'\' AS "RenjaID",
                        "Target_Angka",
                        "Target_Uraian",
                        "Tahun",
                        "Descr",
                        1 AS "Privilege",
                        "TA",
                        "RenjaIndikatorID" AS "RenjaIndikatorID_Src",
                        NOW() AS created_at,
                        NOW() AS updated_at
                    FROM 
                        "trRenjaIndikator" 
                    WHERE 
                        "RenjaID"=\''.$RenjaID.'\' AND
                        "Privilege"=0  
                ';

                \DB::statement($str_kinerja);
                RenjaRincianModel::where('RenjaID',$RenjaID)
                                    ->update(['Privilege'=>1,'Status'=>1]);
                RenjaIndikatorModel::where('RenjaID',$RenjaID)
                                    ->update(['Privilege'=>1]);

                return $rincian_kegiatan;
            });            

            if ($request->ajax()) 
            {
                $data = $this->populateData();
                
                $datatable = view("pages.$theme.rkpd.pembahasanrakorbidang.datatable")->with(['page_active'=>'pembahasanrakorbidang',                                                            
                                                                                    'label_transfer'=>'FORUM OPD / SKPD',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanrakorbidang.orderby','order'),
                                                                                    'data'=>$data])->render();
                return response()->json([
                    'success'=>true,
                    'message'=>'Data ini telah berhasil diubah.',
                    'datatable'=>$datatable,
                    'rincian_kegiatan'=>$rincian_kegiatan
                ],200);
            }
            else
            {
                return redirect(route('pembahasanrakorbidang.show',['id'=>$pembahasanrakorbidang->RenjaRincID]))->with('success','Data ini telah berhasil disimpan.');
            }
        }
        else
        {
            if ($request->ajax()) 
            {
                return response()->json([
                    'success'=>0,
                    'message'=>'Data ini gagal diubah.'
                ],200);
            }
            else
            {
                return redirect(route('pembahasanrakorbidang.error'))->with('error','Data ini gagal diubah.');
            }
        }
    }
}