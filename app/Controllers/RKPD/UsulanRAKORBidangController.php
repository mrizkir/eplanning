<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\UsulanRAKORBidangModel;
use App\Models\RKPD\RenjaIndikatorModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;

class UsulanRAKORBidangController extends Controller {
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
    private function populateRincianKegiatan($RenjaID)
    {
        $data = RenjaRincianModel::leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc.PmKecamatanID')
                                    ->leftJoin('trPokPir','trPokPir.PokPirID','trRenjaRinc.PokPirID')
                                    ->leftJoin('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                    ->where('trRenjaRinc.EntryLvl',1)
                                    ->where('RenjaID',$RenjaID)
                                    ->orderBy('Prioritas','ASC')
                                    ->get(['trRenjaRinc.RenjaRincID','trRenjaRinc.RenjaID','trRenjaRinc.RenjaID','trRenjaRinc.UsulanKecID','Nm_Kecamatan','trRenjaRinc.Uraian','trRenjaRinc.No','trRenjaRinc.Sasaran_Angka2','trRenjaRinc.Sasaran_Uraian2','trRenjaRinc.Target2','trRenjaRinc.Jumlah2','trRenjaRinc.Prioritas','isSKPD','isReses','isReses_Uraian']);
        
        return $data;
    }
    private function populateIndikatorKegiatan($RenjaID)
    {
      
        $data = RenjaIndikatorModel::join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRenjaIndikator.IndikatorKinerjaID')
                                                            ->where('RenjaID',$RenjaID)
                                                            ->get();

        return $data;
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('usulanrakorbidang','orderby')) 
        {            
           $this->putControllerStateSession('usulanrakorbidang','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'); 
        $direction=$this->getControllerStateSession('usulanrakorbidang.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('usulanrakorbidang','filters')) 
        {            
            $this->putControllerStateSession('usulanrakorbidang','filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $SOrgID= $this->getControllerStateSession('usulanrakorbidang.filters','SOrgID');        

        if ($this->checkStateIsExistSession('usulanrakorbidang','search')) 
        {
            $search=$this->getControllerStateSession('usulanrakorbidang','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = UsulanRAKORBidangModel::where(['kode_kegiatan'=>$search['isikriteria']])                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = UsulanRAKORBidangModel::where('KgtNm', 'like', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = UsulanRAKORBidangModel::where('Uraian', 'like', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
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
                                            ->where('TA', config('globalsettings.tahun_perencanaan'))                                            
                                            ->orderBy('Prioritas','ASC')
                                            ->orderBy($column_order,$direction)                                            
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }        
        $data->setPath(route('usulanrakorbidang.index'));        
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
        
        $this->setCurrentPageInsideSession('usulanrakorbidang',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',
                                                                                'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
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
            case 'col-status' :
                $column_name = 'status';
            break;
            case 'col-Prioritas' :
                $column_name = 'Prioritas';
            break;
            default :
                $column_name = 'kode_kegiatan';
        }
        $this->putControllerStateSession('usulanrakorbidang','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanrenjaopd');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',
                                                                                    'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
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

        $this->setCurrentPageInsideSession('usulanrakorbidang',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',
                                                                            'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
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
            $this->destroyControllerStateSession('usulanrakorbidang','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('usulanrakorbidang','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('usulanrakorbidang',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',                                                            
                                                            'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
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

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters');
        $daftar_unitkerja=[];
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$OrgID);  
            
            $this->putControllerStateSession('usulanrakorbidang','filters',$filters);

            $data = [];

            $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',                                                            
                                                                                    'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
                                                                                    'data'=>$data])->render();

            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('usulanrakorbidang','filters',$filters);
            $this->setCurrentPageInsideSession('usulanrakorbidang',1);

            $data = $this->populateData();

            $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',                                                            
                                                                                    'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
                                                                                    'data'=>$data])->render();                                                                                       
                                                                                    
            $json_data = ['success'=>true,'datatable'=>$datatable];            
        } 

        //create2
        if ($request->exists('PmKecamatanID') && $request->exists('create2') )
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');           
            $RenjaID = $request->input('RenjaID');
            $subquery = \DB::table('trRenjaRinc')
                            ->select('UsulanKecID')
                            ->where('TA',config('globalsettings.tahun_perencanaan'));
            $data=\App\Models\Musrenbang\AspirasiMusrenKecamatanModel::select('trUsulanKec.*')
                                                                        ->leftJoinSub($subquery,'rinciankegiatan',function($join){
                                                                            $join->on('trUsulanKec.UsulanKecID','=','rinciankegiatan.UsulanKecID');
                                                                        })
                                                                        ->where('trUsulanKec.TA', config('globalsettings.tahun_perencanaan'))
                                                                        ->where('trUsulanKec.PmKecamatanID',$PmKecamatanID)                                                
                                                                        ->where('trUsulanKec.Privilege',1)       
                                                                        ->whereNull('rinciankegiatan.UsulanKecID')       
                                                                        ->orderBY('trUsulanKec.NamaKegiatan','ASC')
                                                                        ->get(); 
            $daftar_uraian = [];
            foreach ($data as $v)
            {
                $daftar_uraian[$v->UsulanKecID]=$v->NamaKegiatan . ' [Rp.'.\App\Helpers\Helper::formatUang($v->NilaiUsulan).']';
            }
            $json_data = ['success'=>true,'Data'=>$data,'daftar_uraian'=>$daftar_uraian];            
        } 
        //create2
        if ($request->exists('UsulanKecID') && $request->exists('create2') )
        {
            $UsulanKecID = $request->input('UsulanKecID')==''?'none':$request->input('UsulanKecID');   
            $data=\App\Models\Musrenbang\AspirasiMusrenKecamatanModel::find($UsulanKecID);

            $data_kegiatan['PmDesaID']=$data->PmDesaID;
            $data_kegiatan['Uraian']=$data->NamaKegiatan;
            $data_kegiatan['NilaiUsulan']=\App\Helpers\Helper::formatUang($data->NilaiUsulan);
            $data_kegiatan['Sasaran_Angka2']=\App\Helpers\Helper::formatAngka($data->Target_Angka);
            $data_kegiatan['Sasaran_Uraian2']=$data->Target_Uraian;
            $data_kegiatan['Prioritas']=$data->Prioritas > 6 ? 6 : $data->Prioritas;
            $json_data = ['success'=>true,'data_kegiatan'=>$data_kegiatan];
        }
        //create3
        if ($request->exists('PemilikPokokID') && $request->exists('create3') )
        {
            $PemilikPokokID = $request->input('PemilikPokokID')==''?'none':$request->input('PemilikPokokID');           
            $RenjaID = $request->input('RenjaID');

            $subquery = \DB::table('trRenjaRinc')
                            ->select('PokPirID')
                            ->where('TA',config('globalsettings.tahun_perencanaan'));

            $data=\App\Models\Pokir\PokokPikiranModel::select('trPokPir.*')
                                                    ->leftJoinSub($subquery,'rinciankegiatan',function($join){
                                                        $join->on('trPokPir.PokPirID','=','rinciankegiatan.PokPirID');
                                                    })
                                                    ->where('trPokPir.TA', config('globalsettings.tahun_perencanaan'))
                                                    ->where('trPokPir.PemilikPokokID',$PemilikPokokID)                                                
                                                    ->whereNull('rinciankegiatan.PokPirID')
                                                    ->where('trPokPir.Privilege',1)  
                                                    ->where('trPokPir.OrgID',$filters['OrgID'])   
                                                    ->orderBY('NamaUsulanKegiatan','ASC')
                                                    ->get(); 
            $daftar_pokir = [];
            foreach ($data as $v)
            {
                $daftar_pokir[$v->PokPirID]=$v->NamaUsulanKegiatan;
            }

            $json_data = ['success'=>true,'OrgID'=>$filters['OrgID'],'daftar_pokir'=>$daftar_pokir];            
        }
        //create3
        if ($request->exists('PokPirID') && $request->exists('create3') )
        {
            $PokPirID = $request->input('PokPirID')==''?'none':$request->input('PokPirID');   
            $data=\App\Models\Pokir\PokokPikiranModel::find($PokPirID);
            
            $data_kegiatan['Uraian']=$data->NamaUsulanKegiatan;
            $data_kegiatan['NilaiUsulan']=\App\Helpers\Helper::formatUang($data->NilaiUsulan);
            $data_kegiatan['Sasaran_Angka2']=\App\Helpers\Helper::formatAngka($data->Sasaran_Uraian);
            $data_kegiatan['Sasaran_Uraian2']=$data->Sasaran_Uraian;
            $data_kegiatan['Prioritas']=$data->Prioritas > 6 ? 6 : $data->Prioritas;
            $json_data = ['success'=>true,'data_kegiatan'=>$data_kegiatan];
        }
        //create4
        if ($request->exists('PmKecamatanID') && $request->exists('create4') )
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$PmKecamatanID,false);
                                                                                    
            $json_data = ['success'=>true,'daftar_desa'=>$daftar_desa];            
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
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters');
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
                $daftar_opd=OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false,NULL,$auth->OrgID);  
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
                $this->putControllerStateSession('usulanrakorbidang','filters',$filters);
            break;
        }

        $search=$this->getControllerStateSession('usulanrakorbidang','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('usulanrakorbidang'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('usulanrakorbidang',$data->currentPage());

        return view("pages.$theme.rkpd.usulanrakorbidang.index")->with(['page_active'=>'usulanrakorbidang',
                                                                        'daftar_opd'=>$daftar_opd,
                                                                        'daftar_unitkerja'=>$daftar_unitkerja,
                                                                        'filters'=>$filters,
                                                                        'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                        'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
                                                                        'data'=>$data]);
    }
    public function pilihusulankegiatan(Request $request)
    {
        $json_data=[];
        if ($request->exists('UrsID'))
        {
            $UrsID = $request->input('UrsID')==''?'none':$request->input('UrsID');
            $daftar_program = ProgramModel::getDaftarProgram(config('globalsettings.tahun_perencanaan'),false,$UrsID);
            $json_data['success']=true;
            $json_data['daftar_program']=$daftar_program;
        }

        if ($request->exists('PrgID'))
        {
            $PrgID = $request->input('PrgID')==''?'none':$request->input('PrgID');
            $r=\DB::table('v_program_kegiatan')
                    ->where('TA',config('globalsettings.tahun_perencanaan'))
                    ->where('PrgID',$PrgID)
                    ->WhereNotIn('KgtID',function($query) {
                        $query->select('KgtID')
                                ->from('trRenja')
                                ->where('TA', config('globalsettings.tahun_perencanaan'));
                    }) 
                    ->orderBy('Kd_Keg')
                    ->orderBy('kode_kegiatan')
                    ->get();
            $daftar_kegiatan=[];        
            foreach ($r as $k=>$v)
            {
                if ($v->Jns)
                {
                    $daftar_kegiatan[$v->KgtID]=$v->kode_kegiatan.'. '.$v->KgtNm;
                }
                else
                {
                    $daftar_kegiatan[$v->KgtID]=$v->kode_kegiatan.'. '.$v->KgtNm;
                }
                
            }            
            $json_data['success']=true;
            $json_data['daftar_kegiatan']=$daftar_kegiatan;
        }
        return response()->json($json_data,200);  
    }
    public function pilihindikatorkinerja(Request $request)
    {
        $IndikatorKinerjaID = $request->input('IndikatorKinerjaID');
        $json_data=\App\Models\RPJMD\RpjmdIndikatorKinerjaModel::getIndikatorKinerjaByID($IndikatorKinerjaID,config('globalsettings.tahun_perencanaan'));
        if (is_null($json_data))
        {
            $json_data=[
                'NamaIndikator'=>'-',
                'TargetAngka'=>'-',
                'PaguDana'=>'-'
            ];
        }
        return response()->json($json_data,200);  
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters'); 

        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $SOrgID=$filters['SOrgID'];            
            $OrgID=$filters['OrgID'];

            $organisasi=\App\Models\DMaster\OrganisasiModel::find($OrgID);            
            $UrsID=$organisasi->UrsID;

            $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusan(config('globalsettings.tahun_perencanaan'),false);   
            $daftar_program = \App\Models\DMaster\ProgramModel::getDaftarProgram(config('globalsettings.tahun_perencanaan'),false,$UrsID);
            $sumber_dana = \App\Models\DMaster\SumberDanaModel::getDaftarSumberDana(config('globalsettings.tahun_perencanaan'),false);     
            
            return view("pages.$theme.rkpd.usulanrakorbidang.create")->with(['page_active'=>'usulanrakorbidang',
                                                                            'daftar_urusan'=>$daftar_urusan,
                                                                            'daftar_program'=>$daftar_program,
                                                                            'UrsID_selected'=>$UrsID,
                                                                            'sumber_dana'=>$sumber_dana
                                                                        ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.error")->with(['page_active'=>'usulanrakorbidang',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }  
    }    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create1($renjaid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $OrgID=$filters['OrgID'];
            $SOrgID=$filters['SOrgID'];

            $renja=RenjaModel::select(\DB::raw('"RenjaID","KgtID"'))
                                ->where('OrgID',$OrgID)
                                ->where('SOrgID',$SOrgID)
                                ->findOrFail($renjaid);
            
            
            $kegiatan=\App\Models\DMaster\ProgramKegiatanModel::select(\DB::raw('"trUrsPrg"."UrsID","trUrsPrg"."PrgID"'))
                                            ->join('trUrsPrg','trUrsPrg.PrgID','tmKgt.PrgID')
                                            ->find($renja->KgtID);                                            
            $UrsID=$kegiatan->UrsID;    
            $PrgID=$kegiatan->PrgID;          
            $daftar_indikatorkinerja = \DB::table('trIndikatorKinerja')
                                        ->where('UrsID',$UrsID)
                                        ->where('PrgID',$PrgID)
                                        ->orWhere('OrgID',$OrgID)
                                        ->orWhere('OrgID2',$OrgID)
                                        ->orWhere('OrgID3',$OrgID)
                                        ->where('TA_N',config('globalsettings.rpjmd_tahun_mulai'))
                                        ->WhereNotIn('IndikatorKinerjaID',function($query) use ($renjaid){
                                            $query->select('IndikatorKinerjaID')
                                                    ->from('trRenjaIndikator')
                                                    ->where('RenjaID', $renjaid);
                                        })
                                        ->get()
                                        ->pluck('NamaIndikator','IndikatorKinerjaID')
                                        ->toArray();     
            
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($renjaid);

            return view("pages.$theme.rkpd.usulanrakorbidang.create1")->with(['page_active'=>'usulanrakorbidang',
                                                                            'daftar_indikatorkinerja'=>$daftar_indikatorkinerja,
                                                                            'renja'=>$renja,
                                                                            'dataindikatorkinerja'=>$dataindikatorkinerja
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.error")->with(['page_active'=>'usulanrakorbidang',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create2($renjaid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $renja=RenjaModel::findOrFail($renjaid);

            $datarinciankegiatan = $this->populateRincianKegiatan($renjaid);
            
            //lokasi
            $daftar_provinsi = ['uidF1847004D8F547BF'=>'KEPULAUAN RIAU'];
            $daftar_kota_kab = ['uidE4829D1F21F44ECA'=>'BINTAN'];        
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(config('globalsettings.tahun_perencanaan'),config('globalsettings.defaul_kota_atau_kab'),false);
            $nomor_rincian = RenjaRincianModel::where('RenjaID',$renjaid)->max('No')+1;
            return view("pages.$theme.rkpd.usulanrakorbidang.create2")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan,
                                                                            'nomor_rincian'=>$nomor_rincian,
                                                                            'daftar_provinsi'=> $daftar_provinsi,
                                                                            'daftar_kota_kab'=> $daftar_kota_kab,
                                                                            'daftar_kecamatan'=>$daftar_kecamatan
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.error")->with(['page_active'=>'usulanrakorbidang',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create3($renjaid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $renja=RenjaModel::findOrFail($renjaid);
            
            $datarinciankegiatan = $this->populateRincianKegiatan($renjaid);

            $nomor_rincian = RenjaRincianModel::where('RenjaID',$renjaid)->max('No')+1;
            $daftar_pemilik= \App\Models\Pokir\PemilikPokokPikiranModel::where('TA',config('globalsettings.tahun_perencanaan')) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')                                                                        
                                                                        ->toArray();
            //lokasi
            $PMProvID = 'uidF1847004D8F547BF';
            $PmKotaID = 'uidE4829D1F21F44ECA';
            return view("pages.$theme.rkpd.usulanrakorbidang.create3")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan,
                                                                            'daftar_pemilik'=>$daftar_pemilik, 
                                                                            'nomor_rincian'=>$nomor_rincian,
                                                                            'PMProvID'=>$PMProvID,
                                                                            'PmKotaID'=>$PmKotaID
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.error")->with(['page_active'=>'usulanrakorbidang',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create4($renjaid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanrakorbidang','filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $renja=RenjaModel::findOrFail($renjaid);            
            $datarinciankegiatan = $this->populateRincianKegiatan($renjaid);
            //lokasi
            $daftar_provinsi = ['uidF1847004D8F547BF'=>'KEPULAUAN RIAU'];
            $daftar_kota_kab = ['uidE4829D1F21F44ECA'=>'BINTAN'];        
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(config('globalsettings.tahun_perencanaan'),config('globalsettings.defaul_kota_atau_kab'),false);
            $nomor_rincian = RenjaRincianModel::where('RenjaID',$renjaid)->max('No')+1;
            return view("pages.$theme.rkpd.usulanrakorbidang.create4")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'nomor_rincian'=>$nomor_rincian,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan,
                                                                            'daftar_provinsi'=> $daftar_provinsi,
                                                                            'daftar_kota_kab'=> $daftar_kota_kab,
                                                                            'daftar_kecamatan'=>$daftar_kecamatan
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.error")->with(['page_active'=>'usulanrakorbidang',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
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
            'UrsID'=>'required',
            'PrgID'=>'required',
            'KgtID'=>'required',
            'SumberDanaID'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2' => 'required',
            'Sasaran_AngkaSetelah'=>'required',
            'Sasaran_UraianSetelah'=>'required',
            'Target2'=>'required',
            'NilaiSebelum'=>'required',
            'NilaiUsulan2'=>'required',
            'NilaiSetelah'=>'required',
            'NamaIndikator'=>'required'
        ]);
        $filters=$this->getControllerStateSession('usulanrakorbidang','filters'); 
        $RenjaID=uniqid ('uid');
        $data=[            
            'RenjaID' => $RenjaID,            
            'OrgID' => $filters['OrgID'],
            'SOrgID' => $filters['SOrgID'],
            'KgtID' => $request->input('KgtID'),
            'SumberDanaID' => $request->input('SumberDanaID'),
            'Sasaran_Angka2' => $request->input('Sasaran_Angka2'),
            'Sasaran_Uraian2' => $request->input('Sasaran_Uraian2'),
            'Sasaran_AngkaSetelah' => $request->input('Sasaran_AngkaSetelah'),
            'Sasaran_UraianSetelah' => $request->input('Sasaran_UraianSetelah'),
            'Target2' => $request->input('Target2'),
            'NilaiSebelum' => $request->input('NilaiSebelum'),
            'NilaiUsulan2' => $request->input('NilaiUsulan2'),
            'NilaiSetelah' => $request->input('NilaiSetelah'),
            'NamaIndikator' => $request->input('NamaIndikator'),            
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan'),
            'EntryLvl'=>1
        ];
        $usulanrakorbidang = RenjaModel::create($data);        
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.create1',['id'=>$RenjaID]))->with('success','Data kegiatan telah berhasil disimpan. Selanjutnya isi Indikator Kinerja Kegiatan dari RPMJD');
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {
        $this->validate($request, [
            'IndikatorKinerjaID'=>'required',
            'Target_Angka'=>'required',
            'Target_Uraian'=>'required',           
        ]);
        
        $data=[  
            'RenjaIndikatorID' => uniqid ('uid'),           
            'RenjaID' => $request->input('RenjaID'),            
            'IndikatorKinerjaID' => $request->input('IndikatorKinerjaID'),           
            'Target_Angka' => $request->input('Target_Angka'),
            'Target_Uraian' => $request->input('Target_Uraian'),                       
            'Tahun' => (config('globalsettings.tahun_perencanaan')-config('globalsettings.rpjmd_tahun_mulai'))+1,                       
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan')
        ];

        $indikatorkinjera = RenjaIndikatorModel::create($data);
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.create1',['id'=>$request->input('RenjaID')]))->with('success','Data Indikator kegiatan telah berhasil disimpan. Selanjutnya isi Rincian Kegiatan');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store2(Request $request)
    {
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2'=>'required',
            'Target2'=>'required',
            'Jumlah2'=>'required',
            'Prioritas' => 'required'            
        ]);
        $renjaid=$request->input('RenjaID');
        $nomor_rincian = RenjaRincianModel::where('RenjaID',$renjaid)->max('No')+1;
        $rinciankegiatan= RenjaRincianModel::create([
            'RenjaRincID' => uniqid ('uid'),           
            'RenjaID' => $renjaid,            
            'PMProvID' => $request->input('PMProvID'),           
            'PmKotaID' => $request->input('PmKotaID'),           
            'PmKecamatanID' => $request->input('PmKecamatanID'),  
            'PmDesaID' => $request->input('PmDesaID'),         
            'UsulanKecID' => $request->input('UsulanKecID'),    
            'No' => $nomor_rincian,           
            'Uraian' => $request->input('Uraian'),
            'Sasaran_Angka2' => $request->input('Sasaran_Angka2'),                       
            'Sasaran_Uraian2' => $request->input('Sasaran_Uraian2'),                       
            'Target2' => $request->input('Target2'),                       
            'Jumlah2' => $request->input('Jumlah2'),                       
            'Prioritas' => $request->input('Prioritas'),              
            'Status' => 0,  
            'EntryLvl' => 1,                                       
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan')
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
            return redirect(route('usulanrakorbidang.create2',['id'=>$request->input('RenjaID')]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store3(Request $request)
    {
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2'=>'required',
            'Target2'=>'required',
            'Jumlah2'=>'required',
            'Prioritas' => 'required'            
        ]);

        $renjaid=$request->input('RenjaID');
        $PokPirID=$request->input('PokPirID');
        
        $pokok_pikiran = \App\Models\Pokir\PokokPikiranModel::join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                                            ->where('PokPirID',$PokPirID)
                                                            ->first(['trPokPir.PmDesaID','trPokPir.PmKecamatanID','trPokPir.PmKecamatanID','tmPemilikPokok.Kd_PK']);
        
        $nomor_rincian = RenjaRincianModel::where('RenjaID',$renjaid)->max('No')+1;
        $rinciankegiatan= RenjaRincianModel::create([
            'RenjaRincID' => uniqid ('uid'),           
            'RenjaID' => $renjaid,            
            'PMProvID' => $request->input('PMProvID'),           
            'PmKotaID' => $request->input('PmKotaID'),           
            'PmKecamatanID' => $pokok_pikiran->PmKecamatanID,           
            'PmDesaID' => $pokok_pikiran->PmDesaID,    
            'PokPirID' => $PokPirID, 
            'No' => $nomor_rincian,           
            'Uraian' => $request->input('Uraian'),
            'Sasaran_Angka2' => $request->input('Sasaran_Angka2'),                       
            'Sasaran_Uraian2' => $request->input('Sasaran_Uraian2'),                       
            'Target2' => $request->input('Target2'),                       
            'Jumlah2' => $request->input('Jumlah2'),                       
            'Prioritas' => $request->input('Prioritas'),  
            'isReses' => true,     
            'isReses_Uraian' => $pokok_pikiran->Kd_PK,
            'Status' => 0,                             
            'EntryLvl' => 1,             
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan')
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
            return redirect(route('usulanrakorbidang.create3',['id'=>$request->input('RenjaID')]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store4(Request $request)
    {
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2'=>'required',
            'Target2'=>'required',
            'Jumlah2'=>'required',
            'Prioritas' => 'required'            
        ]);
        $renjaid=$request->input('RenjaID');
        $nomor_rincian = RenjaRincianModel::where('RenjaID',$renjaid)->max('No')+1;
        $rinciankegiatan= RenjaRincianModel::create([
            'RenjaRincID' => uniqid ('uid'),           
            'RenjaID' => $renjaid,            
            'PMProvID' => $request->input('PMProvID'),           
            'PmKotaID' => $request->input('PmKotaID'),           
            'PmKecamatanID' => $request->input('PmKecamatanID'),           
            'PmDesaID' => $request->input('PmDesaID'),    
            'No' => $nomor_rincian,           
            'Uraian' => $request->input('Uraian'),
            'Sasaran_Angka2' => $request->input('Sasaran_Angka2'),                       
            'Sasaran_Uraian2' => $request->input('Sasaran_Uraian2'),                       
            'Target2' => $request->input('Target2'),                       
            'Jumlah2' => $request->input('Jumlah2'),                       
            'Prioritas' => $request->input('Prioritas'),  
            'isSKPD' => true,     
            'Status' => 0,                               
            'EntryLvl' => 1,           
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan')
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
            return redirect(route('usulanrakorbidang.create4',['id'=>$request->input('RenjaID')]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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

        $renja = RenjaModel::join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja.SumberDanaID')                       
                            ->findOrFail($id);
        if (!is_null($renja) )  
        {
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($id);
            $datarinciankegiatan = $this->populateRincianKegiatan($id);            
            return view("pages.$theme.rkpd.usulanrakorbidang.show")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'dataindikatorkinerja'=>$dataindikatorkinerja,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan
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
        
        $renja = RenjaModel::select(\DB::raw('"trRenja"."RenjaID","tmUrs"."Nm_Bidang","tmPrg"."PrgNm","tmKgt"."KgtNm","trRenja"."Sasaran_Angka2","trRenja"."Sasaran_Uraian2","trRenja"."Sasaran_AngkaSetelah","trRenja"."Sasaran_UraianSetelah","trRenja"."Target2","trRenja"."NilaiSebelum","trRenja"."NilaiUsulan2","trRenja"."NilaiSetelah","trRenja"."NamaIndikator","trRenja"."SumberDanaID","trRenja"."Descr"'))
                            ->join('tmKgt','tmKgt.KgtID','trRenja.KgtID')
                            ->join('tmPrg','tmPrg.PrgID','tmKgt.PrgID')
                            ->join('trUrsPrg','trUrsPrg.PrgID','tmPrg.PrgID')
                            ->join('tmUrs','tmUrs.UrsID','trUrsPrg.UrsID')
                            ->findOrFail($id);        
        if (!is_null($renja) ) 
        {
            $sumber_dana = \App\Models\DMaster\SumberDanaModel::getDaftarSumberDana(config('globalsettings.tahun_perencanaan'),false);     
            return view("pages.$theme.rkpd.usulanrakorbidang.edit")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'sumber_dana'=>$sumber_dana
                                                                            ]);
        }        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit1($id)
    {
        $theme = \Auth::user()->theme;

        $renja = RenjaIndikatorModel::select(\DB::raw('"trRenjaIndikator"."RenjaIndikatorID","trRenjaIndikator"."IndikatorKinerjaID","trRenjaIndikator"."RenjaID","trRenjaIndikator"."Target_Angka","Target_Uraian","trRenjaIndikator"."TA"'))                                   
                                    ->join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRenjaIndikator.IndikatorKinerjaID')
                                    ->findOrFail($id);        
        if (!is_null($renja) ) 
        {    
            $dataindikator_rpjmd = \App\Models\RPJMD\RpjmdIndikatorKinerjaModel::getIndikatorKinerjaByID($renja->IndikatorKinerjaID,$renja->TA);            
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($renja->RenjaID);
            return view("pages.$theme.rkpd.usulanrakorbidang.edit1")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'dataindikator_rpjmd'=>$dataindikator_rpjmd,
                                                                            'dataindikatorkinerja'=>$dataindikatorkinerja
                                                                            ]);
        }        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit2($id)
    {
        $theme = \Auth::user()->theme;

        $auth=\Auth::user();
        $theme = $auth->theme;
        $roles = $auth->getRoleNames();        
        switch ($roles[0])
        {
            case 'superadmin' :
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","tmPMProv"."Nm_Prov","tmPmKota"."Nm_Kota","tmPmKecamatan"."Nm_Kecamatan","trRenjaRinc"."RenjaID","trRenjaRinc"."No","trRenjaRinc"."No","trUsulanKec"."NamaKegiatan","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                            
                                            ->join('trUsulanKec','trUsulanKec.UsulanKecID','trRenjaRinc.UsulanKecID')                                                                                        
                                            ->join('tmPMProv','tmPMProv.PMProvID','trRenjaRinc.PMProvID')
                                            ->join('tmPmKota','tmPmKota.PmKotaID','trRenjaRinc.PmKotaID')
                                            ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc.PmKecamatanID')                                            
                                            ->findOrFail($id);        
            break;
            case 'opd' :
                $OrgID = $auth->OrgID;
                $SOrgID = empty($auth->SOrgID)? $SOrgID= $this->getControllerStateSession('usulanrakorbidang.filters','SOrgID'):$auth->SOrgID;
                $renja = empty($SOrgID)?RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","tmPMProv"."Nm_Prov","tmPmKota"."Nm_Kota","tmPmKecamatan"."Nm_Kecamatan","trRenjaRinc"."RenjaID","trRenjaRinc"."No","trRenjaRinc"."No","trUsulanKec"."NamaKegiatan","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                            
                                                        ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                                        ->join('trUsulanKec','trUsulanKec.UsulanKecID','trRenjaRinc.UsulanKecID')                                                                                        
                                                        ->join('tmPMProv','tmPMProv.PMProvID','trRenjaRinc.PMProvID')
                                                        ->join('tmPmKota','tmPmKota.PmKotaID','trRenjaRinc.PmKotaID')
                                                        ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc.PmKecamatanID')                                            
                                                        ->where('trRenja.SOrgID',$SOrgID)->findOrFail($id)
                                     :RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","tmPMProv"."Nm_Prov","tmPmKota"."Nm_Kota","tmPmKecamatan"."Nm_Kecamatan","trRenjaRinc"."RenjaID","trRenjaRinc"."No","trRenjaRinc"."No","trUsulanKec"."NamaKegiatan","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                            
                                                        ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                                        ->join('trUsulanKec','trUsulanKec.UsulanKecID','trRenjaRinc.UsulanKecID')                                                                                        
                                                        ->join('tmPMProv','tmPMProv.PMProvID','trRenjaRinc.PMProvID')
                                                        ->join('tmPmKota','tmPmKota.PmKotaID','trRenjaRinc.PmKotaID')
                                                        ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc.PmKecamatanID')                                            
                                                        ->where('trRenja.OrgID',$OrgID)
                                                        ->findOrFail($id);        
            break;
        }
        if (!is_null($renja) ) 
        {               
            $datarinciankegiatan = $this->populateRincianKegiatan($renja->RenjaID);
            
            return view("pages.$theme.rkpd.usulanrakorbidang.edit2")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan
                                                                            ]);
        }     
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit3($id)
    {   
        $auth=\Auth::user();
        $theme = $auth->theme;
        $roles = $auth->getRoleNames();        
        switch ($roles[0])
        {
            case 'superadmin' :
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."No","tmPemilikPokok"."Kd_PK","tmPemilikPokok"."NmPk","trPokPir"."NamaUsulanKegiatan","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                            
                                            ->join('trPokPir','trPokPir.PokPirID','trRenjaRinc.PokPirID')
                                            ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                                                        
                                            ->findOrFail($id);        
            break;
            case 'opd' :
                $OrgID = $auth->OrgID;
                $SOrgID = empty($auth->SOrgID)? $SOrgID= $this->getControllerStateSession('usulanrakorbidang.filters','SOrgID'):$auth->SOrgID;
                $renja = empty($SOrgID)?RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."No","tmPemilikPokok"."Kd_PK","tmPemilikPokok"."NmPk","trPokPir"."NamaUsulanKegiatan","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                            
                                                        ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                                        ->join('trPokPir','trPokPir.PokPirID','trRenjaRinc.PokPirID')
                                                        ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                                                        
                                                        ->where('trRenja.SOrgID',$SOrgID)->findOrFail($id)
                                     :RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",trRenjaRinc"."RenjaID","trRenjaRinc"."No","tmPemilikPokok"."Kd_PK","tmPemilikPokok"."NmPk","trPokPir"."NamaUsulanKegiatan","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                            
                                                        ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')
                                                        ->join('trPokPir','trPokPir.PokPirID','trRenjaRinc.PokPirID')
                                                        ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                                                        
                                                        ->where('trRenja.OrgID',$OrgID)
                                                        ->findOrFail($id);        
            break;
        }        
        if (!is_null($renja) ) 
        {               
            $datarinciankegiatan = $this->populateRincianKegiatan($renja->RenjaID);

            return view("pages.$theme.rkpd.usulanrakorbidang.edit3")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan
                                                                            ]);
        }        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit4($id)
    {   
        $auth=\Auth::user();
        $theme = $auth->theme;
        $roles = $auth->getRoleNames();        
        switch ($roles[0])
        {
            case 'superadmin' :
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."PmKecamatanID","trRenjaRinc"."PmDesaID","trRenjaRinc"."No","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                                                                        
                                            ->findOrFail($id);        
            break;
            case 'opd' :
                $OrgID = $auth->OrgID;
                $SOrgID = empty($auth->SOrgID)? $SOrgID= $this->getControllerStateSession('usulanrakorbidang.filters','SOrgID'):$auth->SOrgID;
                $renja = empty($SOrgID)?RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."PmKecamatanID","trRenjaRinc"."PmDesaID","trRenjaRinc"."No","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))
                                                        ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')                                                        
                                                        ->where('trRenja.SOrgID',$SOrgID)->findOrFail($id)
                                     :RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."PmKecamatanID","trRenjaRinc"."PmDesaID","trRenjaRinc"."No","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))
                                                        ->join('trRenja','trRenja.RenjaID','trRenjaRinc.RenjaID')                                                        
                                                        ->where('trRenja.OrgID',$OrgID)
                                                        ->findOrFail($id);        
            break;
        }        
        if (!is_null($renja) ) 
        {          
            $datarinciankegiatan = $this->populateRincianKegiatan($renja->RenjaID);
            //lokasi
            $daftar_provinsi = ['uidF1847004D8F547BF'=>'KEPULAUAN RIAU'];
            $daftar_kota_kab = ['uidE4829D1F21F44ECA'=>'BINTAN'];        
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(config('globalsettings.tahun_perencanaan'),$renja->PmKotaID,false);
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),$renja->PmKecamatanID,false);
            return view("pages.$theme.rkpd.usulanrakorbidang.edit4")->with(['page_active'=>'usulanrakorbidang',
                                                                            'renja'=>$renja,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan,
                                                                            'daftar_provinsi'=> $daftar_provinsi,
                                                                            'daftar_kota_kab'=> $daftar_kota_kab,
                                                                            'daftar_kecamatan'=>$daftar_kecamatan,
                                                                            'daftar_desa'=>$daftar_desa
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
        $usulanrakorbidang = RenjaModel::find($id);
        
        $this->validate($request, [            
            'SumberDanaID'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2' => 'required',
            'Sasaran_AngkaSetelah'=>'required',
            'Sasaran_UraianSetelah'=>'required',
            'Target2'=>'required',
            'NilaiSebelum'=>'required',
            'NilaiUsulan2'=>'required',
            'NilaiSetelah'=>'required',
            'NamaIndikator'=>'required'
        ]);
        
        $usulanrakorbidang->SumberDanaID = $request->input('SumberDanaID');
        $usulanrakorbidang->Sasaran_Angka2 = $request->input('Sasaran_Angka2');
        $usulanrakorbidang->Sasaran_Uraian2 = $request->input('Sasaran_Uraian2');
        $usulanrakorbidang->Sasaran_AngkaSetelah = $request->input('Sasaran_AngkaSetelah');
        $usulanrakorbidang->Sasaran_UraianSetelah = $request->input('Sasaran_UraianSetelah');
        $usulanrakorbidang->Target2 = $request->input('Target2');
        $usulanrakorbidang->NilaiSebelum = $request->input('NilaiSebelum');
        $usulanrakorbidang->NilaiUsulan2 = $request->input('NilaiUsulan2');
        $usulanrakorbidang->NilaiSetelah = $request->input('NilaiSetelah');
        $usulanrakorbidang->NamaIndikator = $request->input('NamaIndikator');
        $usulanrakorbidang->Descr = $request->input('Descr');
        $usulanrakorbidang->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.show',['id'=>$usulanrakorbidang->RenjaID]))->with('success','Data ini telah berhasil disimpan.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update1(Request $request, $id)
    {
        $this->validate($request, [
            'Target_Angka'=>'required',
            'Target_Uraian'=>'required',           
        ]);
        
        $data=[        
            'Target_Angka' => $request->input('Target_Angka'),
            'Target_Uraian' => $request->input('Target_Uraian'),                                   
        ];

        $indikatorkinjera = RenjaIndikatorModel::find($id);
        $indikatorkinjera->Target_Angka = $request->input('Target_Angka'); 
        $indikatorkinjera->Target_Uraian = $request->input('Target_Uraian');       
        $indikatorkinjera->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.show',['id'=>$indikatorkinjera->RenjaID]))->with('success','Data Indikator kegiatan telah berhasil disimpan. Selanjutnya isi Rincian Kegiatan');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update2(Request $request, $id)
    {
        $rinciankegiatan = RenjaRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2'=>'required',
            'Target2'=>'required',
            'Jumlah2'=>'required',
            'Prioritas' => 'required'            
        ]);
        
        $rinciankegiatan->Uraian = $request->input('Uraian');
        $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka2'); 
        $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian2');
        $rinciankegiatan->Target2 = $request->input('Target2');
        $rinciankegiatan->Jumlah2 = $request->input('Jumlah2');  
        $rinciankegiatan->Prioritas = $request->input('Prioritas');  
        $rinciankegiatan->Descr = $request->input('Descr');
        $rinciankegiatan->save();
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.show',['id'=>$rinciankegiatan->RenjaID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
        } 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update3(Request $request, $id)
    {
        $rinciankegiatan = RenjaRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2'=>'required',
            'Target2'=>'required',
            'Jumlah2'=>'required',
            'Prioritas' => 'required'            
        ]);
        
        $rinciankegiatan->Uraian = $request->input('Uraian');
        $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka2'); 
        $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian2');
        $rinciankegiatan->Target2 = $request->input('Target2');
        $rinciankegiatan->Jumlah2 = $request->input('Jumlah2');  
        $rinciankegiatan->Prioritas = $request->input('Prioritas');  
        $rinciankegiatan->Descr = $request->input('Descr');
        $rinciankegiatan->save();
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.show',['id'=>$rinciankegiatan->RenjaID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
        } 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update4(Request $request, $id)
    {
        $rinciankegiatan = RenjaRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka2'=>'required',
            'Sasaran_Uraian2'=>'required',
            'Target2'=>'required',
            'Jumlah2'=>'required',
            'Prioritas' => 'required'            
        ]);
        $rinciankegiatan->PmKecamatanID = $request->input('PmKecamatanID');
        $rinciankegiatan->PmDesaID = $request->input('PmDesaID');
        $rinciankegiatan->Uraian = $request->input('Uraian');
        $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka2'); 
        $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian2');
        $rinciankegiatan->Target2 = $request->input('Target2');
        $rinciankegiatan->Jumlah2 = $request->input('Jumlah2');  
        $rinciankegiatan->Prioritas = $request->input('Prioritas');  
        $rinciankegiatan->Descr = $request->input('Descr');
        $rinciankegiatan->save();
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanrakorbidang.show',['id'=>$rinciankegiatan->RenjaID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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
        
        if ($request->exists('indikatorkinerja'))
        {
            $indikatorkinerja = RenjaIndikatorModel::find($id);
            $renjaid=$indikatorkinerja->RenjaID;
            $result=$indikatorkinerja->delete();
            if ($request->ajax()) 
            {
                $data = $this->populateIndikatorKegiatan($renjaid);

                $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatableindikatorkinerja")->with(['dataindikatorkinerja'=>$data])->render();     
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('usulanrakorbidang.create1',['id'=>$renjaid]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }
        else  if ($request->exists('rinciankegiatan'))
        {
            $rinciankegiatan = RenjaRincianModel::find($id);
            $renjaid=$rinciankegiatan->RenjaID;
            $result=$rinciankegiatan->delete();
            if ($request->ajax()) 
            {
                $data = $this->populateRincianKegiatan($renjaid);
                        
                $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatablerinciankegiatan")->with(['datarinciankegiatan'=>$data])->render();     
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('usulanrakorbidang.show',['id'=>$renjaid]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }
        else if ($request->exists('pid'))
        {

            $renja = $request->input('pid') == 'rincian' ?RenjaRincianModel::find($id) :RenjaModel::find($id);
            $result=$renja->delete();
            if ($request->ajax()) 
            {
                $currentpage=$this->getCurrentPageInsideSession('usulanrakorbidang'); 
                $data=$this->populateData($currentpage);
               
                $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',
                                                                'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('usulanrakorbidang.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('usulanrakorbidang.orderby','order'),
                                                                'data'=>$data])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('usulanrakorbidang.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }      
        }  
    }
}