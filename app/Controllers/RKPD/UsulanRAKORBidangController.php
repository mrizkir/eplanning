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
                                    ->get(['trRenjaRinc.RenjaRincID','trRenjaRinc.RenjaID','trRenjaRinc.RenjaID','trRenjaRinc.UsulanKecID','Nm_Kecamatan','trRenjaRinc.Uraian','trRenjaRinc.No','trRenjaRinc.Sasaran_Angka1','trRenjaRinc.Sasaran_Uraian1','trRenjaRinc.Target1','trRenjaRinc.Jumlah1','trRenjaRinc.Prioritas','isSKPD','isReses','isReses_Uraian']);
        
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
                                                                                'column_order'=>$this->getControllerStateSession('rakorbidang.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rakorbidang.orderby','order'),
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
        $this->putControllerStateSession('usulanrakorbidang','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('usulanrakorbidang');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',
                                                            'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rakorbidang.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rakorbidang.orderby','order'),
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
                                                                            'column_order'=>$this->getControllerStateSession('rakorbidang.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rakorbidang.orderby','order'),
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
                                                            'column_order'=>$this->getControllerStateSession('rakorbidang.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rakorbidang.orderby','order'),
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
                            ->where('RenjaID',$RenjaID);
            $data=\App\Models\Musrenbang\AspirasiMusrenKecamatanModel::select('trUsulanKec.*')
                                                                        ->leftJoinSub($subquery,'trRenja',function($join){
                                                                            $join->on('trUsulanKec.UsulanKecID','=','trRenja.UsulanKecID');
                                                                        })
                                                                        ->where('trUsulanKec.TA', config('globalsettings.tahun_perencanaan'))
                                                                        ->where('trUsulanKec.PmKecamatanID',$PmKecamatanID)                                                
                                                                        ->where('trUsulanKec.Privilege',1)       
                                                                        ->whereNull('trRenja.UsulanKecID')       
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
            $data_kegiatan['Sasaran_Angka1']=\App\Helpers\Helper::formatAngka($data->Target_Angka);
            $data_kegiatan['Sasaran_Uraian1']=$data->Target_Uraian;
            $data_kegiatan['Prioritas']=$data->Prioritas > 6 ? 6 : $data->Prioritas;
            $json_data = ['success'=>true,'data_kegiatan'=>$data_kegiatan];
        }
        //create3
        if ($request->exists('PemilikPokokID') && $request->exists('create3') )
        {
            $PemilikPokokID = $request->input('PemilikPokokID')==''?'none':$request->input('PemilikPokokID');           
            $RenjaID = $request->input('RenjaID');

            $daftar_pokir = [];
            $data=\App\Models\Pokir\PokokPikiranModel::where('trPokPir.TA', config('globalsettings.tahun_perencanaan'))
                                                    ->where('trPokPir.PemilikPokokID',$PemilikPokokID)                                                
                                                    ->where('trPokPir.Privilege',1)  
                                                    ->where('trPokPir.OrgID',$filters['OrgID'])       
                                                    ->WhereNotIn('PokPirID',function($query) use ($RenjaID){
                                                        $query->select('PokPirID')
                                                                ->from('trRenjaRinc')
                                                                ->where('RenjaID', $RenjaID);
                                                    })                                          
                                                    ->orderBY('NamaUsulanKegiatan','ASC')
                                                    ->get(); 
            $daftar_pokir = [];
            foreach ($data as $v)
            {
                $daftar_pokir[$v->PokPirID]=$v->NamaUsulanKegiatan;
            }

            $json_data = ['success'=>true,'daftar_pokir'=>$daftar_pokir];            
        }
        //create3
        if ($request->exists('PokPirID') && $request->exists('create3') )
        {
            $PokPirID = $request->input('PokPirID')==''?'none':$request->input('PokPirID');   
            $data=\App\Models\Pokir\PokokPikiranModel::find($PokPirID);
            
            $data_kegiatan['Uraian']=$data->NamaUsulanKegiatan;
            $data_kegiatan['NilaiUsulan']=\App\Helpers\Helper::formatUang($data->NilaiUsulan);
            $data_kegiatan['Sasaran_Angka1']=\App\Helpers\Helper::formatAngka($data->Sasaran_Uraian);
            $data_kegiatan['Sasaran_Uraian1']=$data->Sasaran_Uraian;
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
            'Sasaran_Angka1'=>'required',
            'Sasaran_Uraian1' => 'required',
            'Sasaran_AngkaSetelah'=>'required',
            'Sasaran_UraianSetelah'=>'required',
            'Target1'=>'required',
            'NilaiSebelum'=>'required',
            'NilaiUsulan1'=>'required',
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
            'Sasaran_Angka1' => $request->input('Sasaran_Angka1'),
            'Sasaran_Uraian1' => $request->input('Sasaran_Uraian1'),
            'Sasaran_AngkaSetelah' => $request->input('Sasaran_AngkaSetelah'),
            'Sasaran_UraianSetelah' => $request->input('Sasaran_UraianSetelah'),
            'Target1' => $request->input('Target1'),
            'NilaiSebelum' => $request->input('NilaiSebelum'),
            'NilaiUsulan1' => $request->input('NilaiUsulan1'),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $theme = \Auth::user()->theme;

        $data = UsulanRAKORBidangModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.show")->with(['page_active'=>'usulanrakorbidang',
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
        
        $data = UsulanRAKORBidangModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.rkpd.usulanrakorbidang.edit")->with(['page_active'=>'usulanrakorbidang',
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
        $rakorbidang = UsulanRAKORBidangModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $rakorbidang->replaceit = $request->input('replaceit');
        $rakorbidang->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ],200);
        }
        else
        {
            return redirect(route('rakorbidang.show',['id'=>$rakorbidang->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $rakorbidang = UsulanRAKORBidangModel::find($id);
        $result=$rakorbidang->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('usulanrakorbidang'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rkpd.usulanrakorbidang.datatable")->with(['page_active'=>'usulanrakorbidang',
                                                            'search'=>$this->getControllerStateSession('usulanrakorbidang','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('rakorbidang.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rakorbidang.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rakorbidang.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}