<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RenjaIndikatorModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;


class ReportDaftarUraianController extends Controller 
{    
    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|opd']);
        //set nama session 
        $this->SessionName=$this->getNameForSession();      
        //set nama halaman saat ini
        $this->NameOfPage = \Helper::getNameOfPage();        
    }    
    
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $rawSql = \DB::raw('"RenjaID",                                    
                            "KgtID",
                            "RenjaRincID",
                            "UsulanKecID",
                            "Nm_Kecamatan",
                            "kode_kegiatan",
                            "No",
                            "KgtNm",
                            "NamaIndikator",
                            "Sasaran_AngkaSetelah",
                            "Sasaran_UraianSetelah",
                            "NilaiSetelah",
                            "NilaiSebelum",
                            "Uraian",
                            "Sasaran_Angka",
                            "Sasaran_Uraian",
                            "Target",
                            "Jumlah",
                            "Nm_SumberDana",
                            "Prioritas",
                            "isSKPD",
                            "isReses",
                            "isReses_Uraian",
                            "Status",
                            "Privilege",
                            "Locked",
                            "Status_Indikator",
                            nama_level,
                            "Descr"');
                                    
        $columns=['*'];       
        if (!$this->checkStateIsExistSession($this->SessionName,'orderby')) 
        {            
           $this->putControllerStateSession($this->SessionName,'orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'); 
        $direction=$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        $SOrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'SOrgID');        

        if ($this->checkStateIsExistSession($this->SessionName,'search')) 
        {
            $search=$this->getControllerStateSession($this->SessionName,'search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = \DB::table('v_daftar_uraian_renja')
                                ->select($rawSql)
                                ->where(['kode_kegiatan'=>$search['isikriteria']])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = \DB::table('v_daftar_uraian_renja')
                                ->select($rawSql)
                                ->where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = \DB::table('v_daftar_uraian_renja')
                                    ->select($rawSql)
                                    ->where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                    ->where('SOrgID',$SOrgID)
                                    ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                    ->orderBy('Prioritas','ASC')
                                    ->orderBy($column_order,$direction);                                        
                break;
                case 'KgtID' :
                    $data = \DB::table('v_daftar_uraian_renja')
                                ->select($rawSql)
                                ->where(['KgtID'=>$search['isikriteria']])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_daftar_uraian_renja')
                            ->select($rawSql)
                            ->where('SOrgID',$SOrgID)                                            
                            ->where('TA', \HelperKegiatan::getTahunPerencanaan())                                                                        
                            ->orderBy($column_order,$direction)                                            
                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
        }                
        $data->setPath(route(\Helper::getNameOfPage('index')));                 
        return $data;
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
            case 'col-Sasaran_Angka' :
                $column_name = 'Sasaran_Angka';
            break;  
            case 'col-Jumlah' :
                $column_name = 'Jumlah';
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
        $this->putControllerStateSession($this->SessionName,'orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession($this->SessionName);         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.report.reportdaftaruraian.datatable")->with(['page_active'=>$this->NameOfPage,
                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                           
                                                                        'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                        'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                        'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
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

        $this->setCurrentPageInsideSession($this->SessionName,$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.report.reportdaftaruraian.datatable")->with(['page_active'=>$this->NameOfPage, 
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                'label_transfer'=>$this->LabelTransfer,
                                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                'data'=>$data])->render(); 

        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
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
        
        $this->setCurrentPageInsideSession($this->SessionName,1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.report.reportdaftaruraian.datatable")->with(['page_active'=>$this->NameOfPage,
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                           
                                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
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

        $filters=$this->getControllerStateSession($this->SessionName,'filters');
        $daftar_unitkerja=[];
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$OrgID);  
            
            $this->putControllerStateSession($this->SessionName,'filters',$filters);

            $data = [];

            $datatable = view("pages.$theme.report.reportdaftaruraian.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                            'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                            'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                            'data'=>$data])->render();

            $totalpaguindikatifopd = RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']);            
                  
            $totalpaguindikatifunitkerja[0]=0;
            $totalpaguindikatifunitkerja[1]=0;
            $totalpaguindikatifunitkerja[2]=0;
            $totalpaguindikatifunitkerja[3]=0;  
            
            $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                        ->where('OrgID',$filters['OrgID'])
                                                                        ->value('Jumlah1');

            $json_data = ['success'=>true,'paguanggaranopd'=>$paguanggaranopd,'totalpaguindikatifopd'=>$totalpaguindikatifopd,'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession($this->SessionName,'filters',$filters);
            $this->setCurrentPageInsideSession($this->SessionName,1);

            $data = $this->populateData();            
            $datatable = view("pages.$theme.report.reportdaftaruraian.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                'data'=>$data])->render();                                                                                       
                        
            $totalpaguindikatifunitkerja = RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']);            
            
            $json_data = ['success'=>true,'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,'datatable'=>$datatable];            
        } 

        //create2
        if ($request->exists('PmKecamatanID') && $request->exists('create2') )
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');           
            $RenjaID = $request->input('RenjaID');
            $subquery = \DB::table('trRenjaRinc')
                            ->select('UsulanKecID')
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan());
            $data=\App\Models\Musrenbang\AspirasiMusrenKecamatanModel::select('trUsulanKec.*')
                                                                        ->leftJoinSub($subquery,'rinciankegiatan',function($join){
                                                                            $join->on('trUsulanKec.UsulanKecID','=','rinciankegiatan.UsulanKecID');
                                                                        })
                                                                        ->where('trUsulanKec.TA', \HelperKegiatan::getTahunPerencanaan())
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
            $data_kegiatan['Sasaran_Angka']=\App\Helpers\Helper::formatAngka($data->Target_Angka);
            $data_kegiatan['Sasaran_Uraian']=$data->Target_Uraian;
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
                            ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan());

            $data=\App\Models\Pokir\PokokPikiranModel::select('trPokPir.*')
                                                    ->leftJoinSub($subquery,'rinciankegiatan',function($join){
                                                        $join->on('trPokPir.PokPirID','=','rinciankegiatan.PokPirID');
                                                    })
                                                    ->where('trPokPir.TA', \HelperKegiatan::getTahunPerencanaan())
                                                    ->where('trPokPir.PemilikPokokID',$PemilikPokokID)                                                
                                                    ->whereNull('rinciankegiatan.PokPirID')
                                                    ->where('trPokPir.OrgID',$filters['OrgID'])   
                                                    ->orderBY('trPokPir.Prioritas','ASC')
                                                    ->orderBY('NamaUsulanKegiatan','ASC')
                                                    ->get(); 
            $daftar_pokir=[];
            foreach ($data as $v)
            {
                $daftar_pokir[$v->PokPirID]=$v->PokPirID.' - '.$v->NamaUsulanKegiatan;
            }

            $json_data = ['success'=>true,'daftar_pokir'=>$daftar_pokir,'message'=>'bila daftar_pokir kosong mohon dicek Privilege apakah bernilai 1'];                        
        }
        //create3
        if ($request->exists('PokPirID') && $request->exists('create3') )
        {
            $PokPirID = $request->input('PokPirID')==''?'none':$request->input('PokPirID');   
            $data=\App\Models\Pokir\PokokPikiranModel::find($PokPirID);
            
            $data_kegiatan['Uraian']=$data->NamaUsulanKegiatan;
            $data_kegiatan['NilaiUsulan']=\App\Helpers\Helper::formatUang($data->NilaiUsulan);
            $data_kegiatan['Sasaran_Angka']=\App\Helpers\Helper::formatAngka($data->Sasaran_Uraian);
            $data_kegiatan['Sasaran_Uraian']=$data->Sasaran_Uraian;
            $data_kegiatan['Prioritas']=$data->Prioritas > 6 ? 6 : $data->Prioritas;
            $json_data = ['success'=>true,'data_kegiatan'=>$data_kegiatan];
        }
        //create4
        if ($request->exists('PmKecamatanID') && $request->exists('create4') )
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$PmKecamatanID,false);
                                                                                    
            $json_data = ['success'=>true,'daftar_desa'=>$daftar_desa];            
        } 

        return response()->json($json_data,200);  
    }
    /**
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filtershowrincian(Request $request) 
    {
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession($this->SessionName,'filters');
        $daftar_unitkerja=[];
        $json_data = [];

        //show rincian
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$OrgID);  
         
            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja];
        } 
        //show rincian
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $daftar_renja = [];                        
            $q = \DB::table('trRenja')
                        ->select(\DB::raw('"trRenja"."RenjaID","v_program_kegiatan"."kode_kegiatan","v_program_kegiatan"."KgtNm"'))
                        ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')
                        ->where('trRenja.SOrgID',$SOrgID)
                        ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                        ->orderBy('v_program_kegiatan.KgtNm','ASC')
                        ->get();
            
            foreach ($q as $k=>$v)
            {
                $daftar_renja[$v->RenjaID]='['.$v->kode_kegiatan.'] '.$v->KgtNm .' ('.$v->RenjaID.')';
            } 
            $json_data = ['success'=>true,'daftar_renja'=>$daftar_renja];            
        } 
        return response()->json($json_data,200);  
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
            $this->destroyControllerStateSession($this->SessionName,'search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession($this->SessionName,'search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession($this->SessionName,1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.report.reportdaftaruraian.datatable")->with(['page_active'=>$this->NameOfPage, 
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                            
                                                                            'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                            'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
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
        $auth = \Auth::user();    
        $theme = $auth->theme;
        
        //filter
        if (!$this->checkStateIsExistSession($this->SessionName,'filters')) 
        {            
            $this->putControllerStateSession($this->SessionName,'filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $filters=$this->getControllerStateSession($this->SessionName,'filters');
        $roles=$auth->getRoleNames();   
        $daftar_unitkerja=array();           
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :     
            case 'tapd' :     
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);                  
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
                }    
            break;
            case 'opd' :               
                $daftar_opd=\App\Models\UserOPD::getOPD();                      
                if (count($daftar_opd) > 0)
                {                    
                    if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                    {
                        $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
                    }  
                }      
                else
                {
                    $filters['OrgID']='none';
                    $filters['SOrgID']='none';
                    $this->putControllerStateSession($this->SessionName,'filters',$filters);

                    return view("pages.$theme.report.reportdaftaruraian.error")->with(['page_active'=>$this->NameOfPage, 
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                        'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                        ]);
                }          
            break;
        }        
        $search=$this->getControllerStateSession($this->SessionName,'search');        
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession($this->SessionName); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession($this->SessionName,$data->currentPage());
        $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                    ->where('OrgID',$filters['OrgID'])                                                    
                                                                    ->value('Jumlah1');

        return view("pages.$theme.report.reportdaftaruraian.index")->with(['page_active'=>$this->NameOfPage, 
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'daftar_opd'=>$daftar_opd,
                                                                'daftar_unitkerja'=>$daftar_unitkerja,
                                                                'filters'=>$filters,
                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                'paguanggaranopd'=>$paguanggaranopd,
                                                                'totalpaguindikatifopd'=>RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']),
                                                                'totalpaguindikatifunitkerja' => RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']),            
                                                                'data'=>$data]);
    }   
    
}