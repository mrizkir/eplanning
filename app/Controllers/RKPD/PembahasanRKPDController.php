<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RKPDViewRincianModel;
use App\Models\RKPD\RKPDIndikatorModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;

class PembahasanRKPDController extends Controller 
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
    private function populateRincianKegiatan($RKPDID)
    {
        switch ($this->NameOfPage) 
        {
            case 'pembahasanrkpd' :
                $data = RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                            "trRKPDRinc"."RKPDID",
                                                            "trRKPDRinc"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRKPDRinc"."Uraian",
                                                            "trRKPDRinc"."No",
                                                            "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                            "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                            "trRKPDRinc"."Target2" AS "Target",
                                                            "trRKPDRinc"."NilaiUsulan1" AS "Jumlah",
                                                            "trRKPDRinc"."NilaiUsulan2" AS "Jumlah2",
                                                            "trRKPDRinc"."Status",
                                                            "trRKPDRinc"."Privilege",
                                                            "trRKPDRinc"."EntryLvl",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRKPDRinc"."Descr"'));
                                        
            break;
        }
        $data = $data->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRKPDRinc.PmKecamatanID')
                        ->leftJoin('trPokPir','trPokPir.PokPirID','trRKPDRinc.PokPirID')
                        ->leftJoin('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                        
                        ->where('RKPDID',$RKPDID)
                        ->get();
        
        return $data;
    }
    private function populateIndikatorKegiatan($RKPDID)
    {
      
        $data = RKPDIndikatorModel::join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRKPDIndikator.IndikatorKinerjaID')
                                                            ->where('RKPDID',$RKPDID)
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
        
        //filter
        if (!$this->checkStateIsExistSession($this->SessionName,'filters')) 
        {            
            $this->putControllerStateSession($this->SessionName,'filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $SOrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'SOrgID');        

        if ($this->checkStateIsExistSession($this->SessionName,'search')) 
        {
            $search=$this->getControllerStateSession($this->SessionName,'search');
            switch ($search['kriteria']) 
            {
                case 'RKPDID' :
                $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                            ->where('SOrgID',$SOrgID)                                            
                                            ->where('TA', \HelperKegiatan::getTahunPerencanaan())    
                                            ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                            ->where(['RKPDID'=>$search['isikriteria']])                                                    
                                            ->orderBy($column_order,$direction);                                            
                break;
                case 'kode_kegiatan' :
                    $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                                ->where('SOrgID',$SOrgID)                                            
                                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())    
                                                ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                                ->where(['kode_kegiatan'=>$search['isikriteria']])                                                                                             
                                                ->orderBy($column_order,$direction);                                       
                break;
                case 'KgtNm' :                                                    
                    $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                                ->where('SOrgID',$SOrgID)                                            
                                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())    
                                                ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                                ->where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                ->orderBy($column_order,$direction);                                            
                break;
                case 'Uraian' :                     
                    $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                                ->where('SOrgID',$SOrgID)                                            
                                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())    
                                                ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                                ->where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                ->orderBy($column_order,$direction);                                            
                break;
            }       
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                        ->where('SOrgID',$SOrgID)                                            
                                        ->where('TA', \HelperKegiatan::getTahunPerencanaan())  
                                        ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                                                           
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

        $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage,
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
        $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage, 
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

        $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage,
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

            $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                            'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                            'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                            'data'=>$data])->render();

            $totalpaguopd = RKPDRincianModel::getTotalPaguByOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']);            
                  
            $totalpaguunitkerja['murni']=0;
            $totalpaguunitkerja['perubahan']=0;
            $totalpaguunitkerja['selisih']=0;
            
            $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah2')
                                                                        ->where('OrgID',$filters['OrgID'])
                                                                        ->value('Jumlah2');

            $json_data = ['success'=>true,'paguanggaranopd'=>$paguanggaranopd,'totalpaguopd'=>$totalpaguopd,'totalpaguunitkerja'=>$totalpaguunitkerja,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession($this->SessionName,'filters',$filters);
            $this->setCurrentPageInsideSession($this->SessionName,1);

            $data = $this->populateData();            
            $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                'data'=>$data])->render();                                                                                       
                        
            $totalpaguunitkerja = RKPDRincianModel::getTotalPaguByUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']);            
            
            $json_data = ['success'=>true,'totalpaguunitkerja'=>$totalpaguunitkerja,'datatable'=>$datatable];            
        } 

        //create2
        if ($request->exists('PmKecamatanID') && $request->exists('create2') )
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');           
            $RKPDID = $request->input('RKPDID');
            $subquery = \DB::table('trRKPDRinc')
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
            $RKPDID = $request->input('RKPDID');

            $subquery = \DB::table('trRKPDRinc')
                            ->select('PokPirID')
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan());

            $data=\App\Models\Pokir\PokokPikiranModel::select('trPokPir.*')
                                                    ->leftJoinSub($subquery,'rinciankegiatan',function($join){
                                                        $join->on('trPokPir.PokPirID','=','rinciankegiatan.PokPirID');
                                                    })
                                                    ->where('trPokPir.TA', \HelperKegiatan::getTahunPerencanaan())
                                                    ->where('trPokPir.PemilikPokokID',$PemilikPokokID)                                                
                                                    ->whereNull('rinciankegiatan.PokPirID')
                                                    ->where('trPokPir.Privilege',1)  
                                                    ->where('trPokPir.OrgID',$filters['OrgID'])   
                                                    ->orderBY('trPokPir.Prioritas','ASC')
                                                    ->orderBY('NamaUsulanKegiatan','ASC')
                                                    ->get(); 
            $daftar_pokir=[];
            foreach ($data as $v)
            {
                $daftar_pokir[$v->PokPirID]=$v->NamaUsulanKegiatan;
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

        $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage, 
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
        
        $filters=$this->getControllerStateSession($this->SessionName,'filters');
        $roles=$auth->getRoleNames();   
        
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :     
            case 'tapd' :     
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);  
                $daftar_unitkerja=array();           
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
                }    
            break;
            case 'opd' :               
                $daftar_opd=\App\Models\UserOPD::getOPD();    
                $daftar_unitkerja=array();      
                $daftar_unitkerja=array();      
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

                    return view("pages.$theme.rkpd.pembahasanrkpd.error")->with(['page_active'=>$this->NameOfPage, 
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
        $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah2')
                                                                    ->where('OrgID',$filters['OrgID'])                                                    
                                                                    ->value('Jumlah2');
        
        return view("pages.$theme.rkpd.pembahasanrkpd.index")->with(['page_active'=>$this->NameOfPage, 
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'daftar_opd'=>$daftar_opd,
                                                                'daftar_unitkerja'=>$daftar_unitkerja,
                                                                'filters'=>$filters,
                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                'paguanggaranopd'=>$paguanggaranopd,
                                                                'totalpaguopd'=>RKPDRincianModel::getTotalPaguByOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']),
                                                                'totalpaguunitkerja' => RKPDRincianModel::getTotalPaguByUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']),            
                                                                'data'=>$data]);
    }   
    public function pilihusulankegiatan(Request $request)
    {
        $json_data=[];
        if ($request->exists('UrsID'))
        {
            $UrsID = $request->input('UrsID')==''?'none':$request->input('UrsID');
            $daftar_program = \App\Models\DMaster\ProgramModel::getDaftarProgram(\HelperKegiatan::getRPJMDTahunMulai(),false,$UrsID);
            $json_data['success']=true;
            $json_data['UrsID']=$UrsID;
            $json_data['daftar_program']=$daftar_program;
        }

        if ($request->exists('PrgID'))
        {
            $PrgID = $request->input('PrgID')==''?'none':$request->input('PrgID');
            $r=\DB::table('v_program_kegiatan')
                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                    ->where('PrgID',$PrgID)
                    ->WhereNotIn('KgtID',function($query) {
                        $OrgID=$this->getControllerStateSession($this->SessionName,'filters.OrgID');
                        $query->select('KgtID')
                                ->from('trRKPD')
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->where('OrgID', $OrgID);
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
        $json_data=\App\Models\RPJMD\RpjmdIndikatorKinerjaModel::getIndikatorKinerjaByID($IndikatorKinerjaID,\HelperKegiatan::getTahunPerencanaan());
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

        $filters=$this->getControllerStateSession($this->SessionName,'filters');         
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $SOrgID=$filters['SOrgID'];            
            $OrgID=$filters['OrgID'];

            $organisasi=\App\Models\DMaster\SubOrganisasiModel::select(\DB::raw('"v_suborganisasi"."OrgID","v_suborganisasi"."OrgIDRPJMD","v_suborganisasi"."UrsID","v_suborganisasi"."OrgNm","v_suborganisasi"."SOrgNm","v_suborganisasi"."kode_organisasi","v_suborganisasi"."kode_suborganisasi"'))
                                                            ->join('v_suborganisasi','tmSOrg.OrgID','v_suborganisasi.OrgID')
                                                            ->find($SOrgID);  
        
            $daftar_program = \App\Models\DMaster\ProgramModel::getDaftarProgram(\HelperKegiatan::getRPJMDTahunMulai(),false,$organisasi->UrsID);            
            $sumber_dana = \App\Models\DMaster\SumberDanaModel::getDaftarSumberDana(\HelperKegiatan::getTahunPerencanaan(),false);     
            
            return view("pages.$theme.rkpd.pembahasanrkpd.create")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'organisasi'=>$organisasi,
                                                                    'daftar_program'=>$daftar_program,                                                                    
                                                                    'sumber_dana'=>$sumber_dana
                                                                ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.pembahasanrkpd.error")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                ]);  
        }  
    }    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create1($rkpdid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession($this->SessionName,'filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $OrgID=$filters['OrgID'];
            $SOrgID=$filters['SOrgID'];

            $rkpd=RKPDModel::select(\DB::raw('"RKPDID","KgtID","OrgIDRPJMD"'))
                            ->join('tmOrg','tmOrg.OrgID','trRKPD.OrgID')
                            ->where('trRKPD.OrgID',$OrgID)
                            ->where('trRKPD.SOrgID',$SOrgID)
                            ->findOrFail($rkpdid);
            
            
            $kegiatan=\App\Models\DMaster\ProgramKegiatanModel::select(\DB::raw('"trUrsPrg"."UrsID","trUrsPrg"."PrgID"'))
                                                                ->join('trUrsPrg','trUrsPrg.PrgID','tmKgt.PrgID')
                                                                ->find($rkpd->KgtID);                                            
            
            $UrsID=$kegiatan->UrsID;    
            $PrgID=$kegiatan->PrgID;          
            $daftar_indikatorkinerja = \DB::table('trIndikatorKinerja')
                                        ->where('UrsID',$UrsID)
                                        ->where('PrgID',$PrgID)
                                        ->Where('OrgIDRPJMD',$rkpd->OrgIDRPJMD)                                        
                                        ->where('TA',\HelperKegiatan::getRPJMDTahunMulai())
                                        ->WhereNotIn('IndikatorKinerjaID',function($query) use ($rkpdid){
                                            $query->select('IndikatorKinerjaID')
                                                    ->from('trRKPDIndikator')
                                                    ->where('RKPDID', $rkpdid);
                                        })
                                        ->get()
                                        ->pluck('NamaIndikator','IndikatorKinerjaID')
                                        ->toArray();     
            
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($rkpdid);

            return view("pages.$theme.rkpd.pembahasanrkpd.create1")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'daftar_indikatorkinerja'=>$daftar_indikatorkinerja,
                                                                    'rkpd'=>$rkpd,
                                                                    'dataindikatorkinerja'=>$dataindikatorkinerja
                                                                    ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.pembahasanrkpd.error")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                ]);  
        }
    }    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create3($rkpdid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession($this->SessionName,'filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $rkpd=RKPDModel::findOrFail($rkpdid);
            
            $datarinciankegiatan = $this->populateRincianKegiatan($rkpdid);

            $nomor_rincian = RKPDRincianModel::where('RKPDID',$rkpdid)->count('No')+1;
            $daftar_pemilik= \App\Models\Pokir\PemilikPokokPikiranModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT(\'[\',"Kd_PK",\'] \', "NmPk") AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')                                                                        
                                                                        ->toArray();
            
            return view("pages.$theme.rkpd.pembahasanrkpd.create3")->with(['page_active'=>$this->NameOfPage,
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                            'rkpd'=>$rkpd,
                                                                            'datarinciankegiatan'=>$datarinciankegiatan,
                                                                            'daftar_pemilik'=>$daftar_pemilik, 
                                                                            'nomor_rincian'=>$nomor_rincian                                                                           
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.pembahasanrkpd.error")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                ]);  
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create4($rkpdid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession($this->SessionName,'filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $rkpd=RKPDModel::findOrFail($rkpdid);            
            $datarinciankegiatan = $this->populateRincianKegiatan($rkpdid);            
            //lokasi
            $daftar_provinsi = ['uidF1847004D8F547BF'=>'KEPULAUAN RIAU'];
            $daftar_kota_kab = ['uidE4829D1F21F44ECA'=>'BINTAN'];        
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),config('eplanning.defaul_kota_atau_kab'),false);
            $nomor_rincian = RKPDRincianModel::where('RKPDID',$rkpdid)->count('No')+1;
            return view("pages.$theme.rkpd.pembahasanrkpd.create4")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'rkpd'=>$rkpd,
                                                                    'nomor_rincian'=>$nomor_rincian,
                                                                    'datarinciankegiatan'=>$datarinciankegiatan,
                                                                    'daftar_provinsi'=> $daftar_provinsi,
                                                                    'daftar_kota_kab'=> $daftar_kota_kab,
                                                                    'daftar_kecamatan'=>$daftar_kecamatan
                                                                    ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.pembahasanrkpd.error")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
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
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian' => 'required',
            'Sasaran_AngkaSetelah'=>'required',
            'Sasaran_UraianSetelah'=>'required',
            'Target'=>'required',
            'NilaiSebelum'=>'required',
            'NilaiSetelah'=>'required',
            'NamaIndikator'=>'required'
        ]);
        $filters=$this->getControllerStateSession($this->SessionName,'filters');
        $RKPDID=uniqid ('uid');
        $tanggal_posting=\Carbon\Carbon::now();
        switch ($this->NameOfPage) 
        {  
            case 'pembahasanrkpd' :
               $data=[            
                    'RKPDID' => $RKPDID,            
                    'OrgID' => $filters['OrgID'],
                    'SOrgID' => $filters['SOrgID'],
                    'KgtID' => $request->input('KgtID'),
                    'SumberDanaID' => $request->input('SumberDanaID'),
                    'Sasaran_Angka1' => 0,
                    'Sasaran_Angka2' => $request->input('Sasaran_Angka'),
                    'Sasaran_Uraian1' => '-',
                    'Sasaran_Uraian2' => $request->input('Sasaran_Uraian'),
                    'Sasaran_AngkaSetelah' => $request->input('Sasaran_AngkaSetelah'),
                    'Sasaran_UraianSetelah' => $request->input('Sasaran_UraianSetelah'),
                    'Target1' => 0,
                    'Target2' => $request->input('Target'),
                    'NilaiUsulan1' => 0,            
                    'NilaiSebelum' => $request->input('NilaiSebelum'),            
                    'NilaiSetelah' => $request->input('NilaiSetelah'),
                    'NamaIndikator' => $request->input('NamaIndikator'),            
                    'Tgl_Posting' => $tanggal_posting,            
                    'Descr' => $request->input('Descr'),
                    'TA' => \HelperKegiatan::getTahunPerencanaan(),
                    'Status'=>1,
                    'Privilege'=>0,
                    'EntryLvl'=>2,
                ];
            break;
            
        }
        $pembahasanrkpd = RKPDModel::create($data);        
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('create1'),['id'=>$RKPDID]))->with('success','Data kegiatan telah berhasil disimpan. Selanjutnya isi Indikator Kinerja Kegiatan dari RPMJD');
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
            'RKPDIndikatorID' => uniqid ('uid'),           
            'RKPDID' => $request->input('RKPDID'),            
            'IndikatorKinerjaID' => $request->input('IndikatorKinerjaID'),           
            'Target_Angka' => $request->input('Target_Angka'),
            'Target_Uraian' => $request->input('Target_Uraian'),                       
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getTahunPerencanaan()
        ];

        $indikatorkinerja = RKPDIndikatorModel::create($data);
        $rkpd = $indikatorkinerja->rkpd;
        $rkpd->Status_Indikator=RKPDIndikatorModel::where('RKPDID',$indikatorkinerja->RKPDID)->count() > 0;
        $rkpd->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('create1'),['id'=>$request->input('RKPDID')]))->with('success','Data Indikator kegiatan telah berhasil disimpan. Selanjutnya isi Rincian Kegiatan');
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
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'Target'=>'required',
            'Jumlah'=>'required',
            'Prioritas' => 'required'            
        ]);
        \DB::transaction(function () use ($request) {
            $rkpdid=$request->input('RKPDID');            
            $nomor_rincian = RKPDRincianModel::where('RKPDID',$rkpdid)->count('No')+1;

            $PokPirID=$request->input('PokPirID');
            
            $pokok_pikiran = \App\Models\Pokir\PokokPikiranModel::select(\DB::raw('"trPokPir"."PmDesaID",
                                                                                "trPokPir"."PmKecamatanID",
                                                                                "tmPmKecamatan"."PmKotaID",
                                                                                "tmPmKota"."PMProvID",
                                                                                "tmPemilikPokok"."NmPk",
                                                                                "tmPemilikPokok"."Kd_PK"'))
                                                                ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                                                ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trPokPir.PmKecamatanID')
                                                                ->leftJoin('tmPmKota','tmPmKecamatan.PmKotaID','tmPmKota.PmKotaID')
                                                                ->where('PokPirID',$PokPirID)
                                                                ->first();
            $tanggal_posting=\Carbon\Carbon::now();            
            switch ($this->NameOfPage) 
            {            
                case 'pembahasanrkpd' :                    
                    $data=[
                        'RKPDRincID' => uniqid ('uid'),           
                        'RKPDID' => $rkpdid,            
                        'PMProvID' => $pokok_pikiran->PMProvID,           
                        'PmKotaID' => $pokok_pikiran->PmKotaID,           
                        // 'PmKecamatanID' => $pokok_pikiran->PmKecamatanID,           
                        'PmKecamatanID' => NULL,           
                        // 'PmDesaID' => $pokok_pikiran->PmDesaID,    
                        'PmDesaID' => NULL,    
                        'PokPirID' => $PokPirID, 
                        'No' => $nomor_rincian,           
                        'Uraian' => $request->input('Uraian'),
                        'Sasaran_Angka1' => 0,                       
                        'Sasaran_Angka2' => $request->input('Sasaran_Angka'),                       
                        'Sasaran_Uraian1' => '',                       
                        'Sasaran_Uraian2' => $request->input('Sasaran_Uraian'),                       
                        'Target1' => 0,                       
                        'Target2' => $request->input('Target'),                       
                        'NilaiUsulan1' => 0,                       
                        'NilaiUsulan2' => $request->input('Jumlah'),                       
                        'Prioritas' => $request->input('Prioritas'),  
                        'Tgl_Posting' => $tanggal_posting,                       
                        'isReses' => true,     
                        'isReses_Uraian' => '['.$pokok_pikiran->Kd_PK . '] '.$pokok_pikiran->NmPk,
                        'Status' => 1,        
                        'EntryLvl' => 2,                                     
                        'Descr' => $request->input('Descr'),
                        'TA' => \HelperKegiatan::getTahunPerencanaan()
                    ];

                    $rinciankegiatan= RKPDRincianModel::create($data);
                    $rkpd = $rinciankegiatan->rkpd;            
                    $rkpd->NilaiUsulan2=RKPDRincianModel::where('RKPDID',$rkpd->RKPDID)->sum('NilaiUsulan2');            
                    $rkpd->save();
                break;                
            }
        });
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('create3'),['id'=>$request->input('RKPDID')]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'Target'=>'required',
            'Jumlah'=>'required',            
        ]);

        \DB::transaction(function () use ($request) {        
            $rkpdid=$request->input('RKPDID');
            $nomor_rincian = RKPDRincianModel::where('RKPDID',$rkpdid)->count('No')+1;
            $tanggal_posting=\Carbon\Carbon::now();
            switch ($this->NameOfPage) 
            {  
                case 'pembahasanrkpd' :
                    $data = [
                        'RKPDRincID' => uniqid ('uid'),           
                        'RKPDID' => $rkpdid,            
                        'PMProvID' => $request->input('PMProvID'),           
                        'PmKotaID' => $request->input('PmKotaID'),           
                        'PmKecamatanID' => $request->input('PmKecamatanID'),           
                        'PmDesaID' => $request->input('PmDesaID'),    
                        'No' => $nomor_rincian,           
                        'Uraian' => $request->input('Uraian'),
                        'Sasaran_Angka1' => 0,                       
                        'Sasaran_Angka2' => $request->input('Sasaran_Angka'),                       
                        'Sasaran_Uraian1' => '-',                       
                        'Sasaran_Uraian2' => $request->input('Sasaran_Uraian'),                       
                        'Target1' => 0,                       
                        'Target2' => $request->input('Target'),                       
                        'NilaiUsulan1' => 0,                       
                        'NilaiUsulan2' => $request->input('Jumlah'),                       
                        'Tgl_Posting' => $tanggal_posting,                       
                        'isSKPD' => true,     
                        'Status' => 1,                               
                        'EntryLvl' => 2,           
                        'Descr' => $request->input('Descr'),
                        'TA' => \HelperKegiatan::getTahunPerencanaan()
                    ];

                    $rinciankegiatan= RKPDRincianModel::create($data);
                    $rkpd = $rinciankegiatan->rkpd;            
                    $rkpd->NilaiUsulan2=RKPDRincianModel::where('RKPDID',$rkpd->RKPDID)->sum('NilaiUsulan2');            
                    $rkpd->save();
                break;                
            }   
            
        });
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('create4'),['id'=>$request->input('RKPDID')]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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

        switch ($this->NameOfPage) 
        {                        
            case 'pembahasanrkpd' :
                $rkpd = RKPDModel::select(\DB::raw('"trRKPD"."RKPDID",
                                            "v_program_kegiatan"."Kd_Urusan",
                                            "v_program_kegiatan"."Nm_Urusan",
                                            "v_program_kegiatan"."Kd_Bidang",
                                            "v_program_kegiatan"."Nm_Bidang",
                                            "v_suborganisasi"."kode_organisasi",
                                            "v_suborganisasi"."OrgNm",
                                            "v_suborganisasi"."kode_suborganisasi",
                                            "v_suborganisasi"."SOrgNm",
                                            "v_program_kegiatan"."Kd_Prog",
                                            "v_program_kegiatan"."PrgNm",
                                            "v_program_kegiatan"."Kd_Keg",
                                            "v_program_kegiatan"."kode_kegiatan",
                                            "v_program_kegiatan"."KgtNm",
                                            "NamaIndikator",
                                            "Sasaran_Angka2" AS "Sasaran_Angka",
                                            "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target2" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan2" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRKPD"."Privilege",
                                            "trRKPD"."Status",
                                            "trRKPD"."EntryLvl",
                                            "trRKPD"."created_at",
                                            "trRKPD"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRKPD.SOrgID')                       
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRKPD.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRKPD.SumberDanaID')
                            ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                            ->findOrFail($id);
            break;                
        }           
        if (!is_null($rkpd) )  
        {
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($id);            
            $datarinciankegiatan = $this->populateRincianKegiatan($id);               
            return view("pages.$theme.rkpd.pembahasanrkpd.show")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'rkpd'=>$rkpd,
                                                                'dataindikatorkinerja'=>$dataindikatorkinerja,
                                                                'datarinciankegiatan'=>$datarinciankegiatan
                                                            ]);
        }        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showrincian($id)
    {
        
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
        
        switch ($this->NameOfPage) 
        {   
            case 'pembahasanrkpd' :                
                $rkpd = RKPDModel::select(\DB::raw('"trRKPD"."RKPDID",
                                                    "tmUrs"."UrsID",
                                                    "tmUrs"."Nm_Bidang",
                                                    "tmPrg"."PrgID",
                                                    "tmPrg"."PrgNm",
                                                    "tmKgt"."KgtID",
                                                    "tmKgt"."KgtNm",
                                                    "trRKPD"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                    "trRKPD"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                    "trRKPD"."Sasaran_AngkaSetelah",
                                                    "trRKPD"."Sasaran_UraianSetelah",
                                                    "trRKPD"."Target2" AS "Target",
                                                    "trRKPD"."NilaiSebelum",
                                                    "trRKPD"."NilaiUsulan2" AS "NilaiUsulan",
                                                    "trRKPD"."NilaiSetelah",
                                                    "trRKPD"."NamaIndikator",
                                                    "trRKPD"."SumberDanaID",
                                                    "trRKPD"."Descr"'))
                            ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                            ->leftJoin('tmPrg','tmPrg.PrgID','tmKgt.PrgID')
                            ->leftJoin('trUrsPrg','trUrsPrg.PrgID','tmPrg.PrgID')
                            ->leftJoin('tmUrs','tmUrs.UrsID','trUrsPrg.UrsID')
                            ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                            ->findOrFail($id);      
                
            break;                     
        }   
        
        if (!is_null($rkpd) ) 
        {
            $sumber_dana = \App\Models\DMaster\SumberDanaModel::getDaftarSumberDana(\HelperKegiatan::getTahunPerencanaan(),false);     
            return view("pages.$theme.rkpd.pembahasanrkpd.edit")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'rkpd'=>$rkpd,
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

        $rkpd = RKPDIndikatorModel::select(\DB::raw('"trRKPDIndikator"."RKPDIndikatorID",
                                                        "trRKPDIndikator"."IndikatorKinerjaID",
                                                        "trRKPDIndikator"."RKPDID",
                                                        "trRKPDIndikator"."Target_Angka",
                                                        "Target_Uraian",
                                                        "trRKPDIndikator"."TA"'))                                   
                                    ->join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRKPDIndikator.IndikatorKinerjaID')
                                    ->findOrFail($id);        
        if (!is_null($rkpd) ) 
        {    
            $dataindikator_rpjmd = \App\Models\RPJMD\RpjmdIndikatorKinerjaModel::getIndikatorKinerjaByID($rkpd->IndikatorKinerjaID,$rkpd->TA);            
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($rkpd->RKPDID);
            return view("pages.$theme.rkpd.pembahasanrkpd.edit1")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'rkpd'=>$rkpd,
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
        
        switch ($this->NameOfPage) 
        {            
            case 'pembahasanrkpd' :
                switch ($roles[0])
                {
                    case 'superadmin' :
                        $rkpd = RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                    "tmPMProv"."Nm_Prov",
                                                                    "tmPmKota"."Nm_Kota",
                                                                    "tmPmKecamatan"."Nm_Kecamatan",
                                                                    "trRKPDRinc"."RKPDID",
                                                                    "trRKPDRinc"."No",
                                                                    "tmKgt"."KgtNm" AS "NamaKegiatan",
                                                                    "trRKPDRinc"."Uraian",
                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                    "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                    "trRKPDRinc"."Descr",
                                                                    "trRKPDRinc"."isSKPD",
                                                                    "trRKPDRinc"."EntryLvl",
                                                                    "trRKPDRinc"."isReses"'))                                            
                                                    ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')
                                                    ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                    ->join('trUsulanKec','trUsulanKec.UsulanKecID','trRKPDRinc.UsulanKecID')
                                                    ->join('tmPMProv','tmPMProv.PMProvID','trRKPDRinc.PMProvID')
                                                    ->join('tmPmKota','tmPmKota.PmKotaID','trRKPDRinc.PmKotaID')
                                                    ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRKPDRinc.PmKecamatanID')                                            
                                                    ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                                                    ->find($id);                                                     
                    break;
                    case 'opd' :
                        $filters=$this->getControllerStateSession($this->SessionName,'filters');
                        $OrgID = $filters['OrgID'];
                        $SOrgID = $filters['SOrgID'];
                        $rkpd = empty($SOrgID)?RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                    "tmPMProv"."Nm_Prov",
                                                                    "tmPmKota"."Nm_Kota",
                                                                    "tmPmKecamatan"."Nm_Kecamatan",
                                                                    "trRKPDRinc"."RKPDID",
                                                                    "trRKPDRinc"."No",
                                                                    "tmKgt"."KgtNm" AS "NamaKegiatan",
                                                                    "trRKPDRinc"."Uraian",
                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                    "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                    "trRKPDRinc"."Descr",
                                                                    "trRKPDRinc"."isSKPD",
                                                                    "trRKPDRinc"."EntryLvl",
                                                                    "trRKPDRinc"."isReses"'))                                            
                                                                ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')
                                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                                ->join('trUsulanKec','trUsulanKec.UsulanKecID','trRKPDRinc.UsulanKecID')                                                                                        
                                                                ->join('tmPMProv','tmPMProv.PMProvID','trRKPDRinc.PMProvID')
                                                                ->join('tmPmKota','tmPmKota.PmKotaID','trRKPDRinc.PmKotaID')
                                                                ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRKPDRinc.PmKecamatanID')                                            
                                                                ->where('trRKPD.SOrgID',$SOrgID)
                                                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                                                                ->find($id)
                                            :RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                    "tmPMProv"."Nm_Prov",
                                                                    "tmPmKota"."Nm_Kota",
                                                                    "tmPmKecamatan"."Nm_Kecamatan",
                                                                    "trRKPDRinc"."RKPDID",
                                                                    "trRKPDRinc"."No",
                                                                    "tmKgt"."KgtNm" AS "NamaKegiatan",
                                                                    "trRKPDRinc"."Uraian",
                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                    "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                    "trRKPDRinc"."Descr",
                                                                    "trRKPDRinc"."isSKPD",
                                                                    "trRKPDRinc"."EntryLvl",
                                                                    "trRKPDRinc"."isReses"'))                                               
                                                                ->join('trRKPD','trRKPD.RenjaID','trRKPDRinc.RKPDID')
                                                                ->join('tmKgt','tmKgt.KgtID','trRKPD.KgtID')
                                                                ->join('trUsulanKec','trUsulanKec.UsulanKecID','trRKPDRinc.UsulanKecID')                                                                                        
                                                                ->join('tmPMProv','tmPMProv.PMProvID','trRKPDRinc.PMProvID')
                                                                ->join('tmPmKota','tmPmKota.PmKotaID','trRKPDRinc.PmKotaID')
                                                                ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc.PmKecamatanID')                                            
                                                                ->where('trRKPD.OrgID',$OrgID)
                                                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                                                                ->find($id);        
                    break;
                }    
            break;            
        }           
        if (is_null($rkpd) )
        {
            return redirect(route(\Helper::getNameOfPage('edit4'),['id'=>$id]))->with('error',"Data rincian kegiatan dari musrenbang Kec dengan ID ($id)  gagal diperoleh, diarahkan menjadi rincian usulan OPD / SKPD .");
        } 
        else 
        {               
            $datarinciankegiatan = $this->populateRincianKegiatan($rkpd->RKPDID);

            return view("pages.$theme.rkpd.pembahasanrkpd.edit2")->with(['page_active'=>$this->NameOfPage,
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                            'rkpd'=>$rkpd,
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
        
        switch ($this->NameOfPage) 
        {            
            case 'pembahasanrkpd' :
                switch ($roles[0])
                {
                    case 'superadmin' :
                    case 'bapelitbang' :
                    case 'tapd' :     
                        $rkpd = RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                    "trRKPDRinc"."RKPDID",
                                                                    "trRKPDRinc"."No",
                                                                    "tmPemilikPokok"."Kd_PK",
                                                                    "tmPemilikPokok"."NmPk",
                                                                    "trPokPir"."NamaUsulanKegiatan",                                                            
                                                                    "trRKPDRinc"."Uraian",
                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                    "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                    "trRKPDRinc"."Descr",
                                                                    "trRKPDRinc"."isSKPD",
                                                                    "trRKPDRinc"."isReses"'))                                            
                                                    ->join('trPokPir','trPokPir.PokPirID','trRKPDRinc.PokPirID')
                                                    ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                                                        
                                                    ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                                                    ->find($id);        
                    break;
                    case 'opd' :
                        $OrgID = $auth->OrgID;
                        $SOrgID = empty($auth->SOrgID)? $SOrgID= $this->getControllerStateSession('pembahasanrkpd.filters','SOrgID'):$auth->SOrgID;
                        $rkpd = empty($SOrgID)?RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                                    "trRKPDRinc"."RKPDID",
                                                                                    "trRKPDRinc"."No",
                                                                                    "tmPemilikPokok"."Kd_PK",
                                                                                    "tmPemilikPokok"."NmPk",
                                                                                    "trPokPir"."NamaUsulanKegiatan",
                                                                                    "trRKPDRinc"."Uraian",
                                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                                    "trRKPDRinc"."Jumlah2" AS "Jumlah",
                                                                                    "trRKPDRinc"."Prioritas",
                                                                                    "trRKPDRinc"."Descr",
                                                                                    "trRKPDRinc"."isSKPD",
                                                                                    "trRKPDRinc"."isReses"'))                                            
                                                                ->join('trRKPD','trRKPD.RKPDID','trRKPDRinc.RKPDID')
                                                                ->join('trPokPir','trPokPir.PokPirID','trRKPDRinc.PokPirID')
                                                                ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')     
                                                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                                   
                                                                ->where('trRKPD.SOrgID',$SOrgID)->find($id)
                                            :RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                                    "trRKPDRinc"."RKPDID",
                                                                                    "trRKPDRinc"."No",
                                                                                    "tmPemilikPokok"."Kd_PK",
                                                                                    "tmPemilikPokok"."NmPk",
                                                                                    "trPokPir"."NamaUsulanKegiatan",
                                                                                    "trRKPDRinc"."Uraian",
                                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                                    "trRKPDRinc"."Jumlah2" AS "Jumlah",
                                                                                    "trRKPDRinc"."Prioritas",
                                                                                    "trRKPDRinc"."Descr",
                                                                                    "trRKPDRinc"."isSKPD",
                                                                                    "trRKPDRinc"."isReses"'))                                            
                                                                ->join('trRKPD','trRKPD.RKPDID','trRKPDRinc.RKPDID')
                                                                ->join('trPokPir','trPokPir.PokPirID','trRKPDRinc.PokPirID')
                                                                ->join('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')     
                                                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                                   
                                                                ->where('trRKPD.OrgID',$OrgID)
                                                                ->find($id);        
                    break;
                }
            break;            
        }
        if (is_null($rkpd) )
        {
            return redirect(route(\Helper::getNameOfPage('edit4'),['id'=>$id]))->with('error',"Data rincian kegiatan dari Pokok Pikiran Anggota dengan ID ($id)  gagal diperoleh, diarahkan menjadi rincian usulan OPD / SKPD .");
        } 
        else
        {               
            $datarinciankegiatan = $this->populateRincianKegiatan($rkpd->RKPDID);

            return view("pages.$theme.rkpd.pembahasanrkpd.edit3")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'rkpd'=>$rkpd,
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
        switch ($this->NameOfPage) 
        {     
            case 'pembahasanrkpd' :
                    switch ($roles[0])
                {
                    case 'superadmin' :
                    case 'bapelitbang' :
                    case 'tapd' :     
                        $rkpd = RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                    "trRKPDRinc"."RKPDID",
                                                                    "trRKPDRinc"."PmKecamatanID",
                                                                    "trRKPDRinc"."PmDesaID",
                                                                    "trRKPDRinc"."No",
                                                                    "trRKPDRinc"."Uraian",
                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                    "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                    "trRKPDRinc"."Descr",
                                                                    "trRKPDRinc"."isSKPD",
                                                                    "trRKPDRinc"."EntryLvl",
                                                                    "trRKPDRinc"."isReses"'))     
                                                    ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))
                                                    ->findOrFail($id);        
                    break;
                    case 'opd' :
                        $filters=$this->getControllerStateSession($this->SessionName,'filters');
                        $OrgID = $filters['OrgID'];
                        $SOrgID = $filters['SOrgID'];
                        $rkpd = empty($SOrgID)?RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                    "trRKPDRinc"."RKPDID",
                                                                    "trRKPDRinc"."PmKecamatanID",
                                                                    "trRKPDRinc"."PmDesaID",
                                                                    "trRKPDRinc"."No",
                                                                    "trRKPDRinc"."Uraian",
                                                                    "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                    "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                    "trRKPDRinc"."Target2" AS "Target",
                                                                    "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                    "trRKPDRinc"."Descr",
                                                                    "trRKPDRinc"."isSKPD",
                                                                    "trRKPDRinc"."isReses"'))
                                                                ->join('trRKPD','trRKPD.RKPDID','trRKPDRinc.RKPDID')   
                                                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                                     
                                                                ->where('trRKPD.SOrgID',$SOrgID)->findOrFail($id)
                                            :RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                                                "trRKPDRinc"."RKPDID",
                                                                                "trRKPDRinc"."PmKecamatanID",
                                                                                "trRKPDRinc"."PmDesaID",
                                                                                "trRKPDRinc"."No",
                                                                                "trRKPDRinc"."Uraian",
                                                                                "trRKPDRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                                                "trRKPDRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                                                "trRKPDRinc"."Target2" AS "Target",
                                                                                "trRKPDRinc"."NilaiUsulan2" AS "Jumlah",
                                                                                "trRKPDRinc"."Descr",
                                                                                "trRKPDRinc"."isSKPD",
                                                                                "trRKPDRinc"."isReses"'))

                                                                ->join('trRKPD','trRKPD.RKPDID','trRKPDRinc.RKPDID')            
                                                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                            
                                                                ->where('trRKPD.OrgID',$OrgID)
                                                                ->findOrFail($id);        
                    break;
                }
            break;
            default :
                $dbViewName = null;
        }   
        if (!is_null($rkpd) ) 
        {               
            $datarinciankegiatan = $this->populateRincianKegiatan($rkpd->RKPDID);
            //lokasi
            $daftar_provinsi = ['uidF1847004D8F547BF'=>'KEPULAUAN RIAU'];
            $daftar_kota_kab = ['uidE4829D1F21F44ECA'=>'BINTAN'];        
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),$rkpd->PmKotaID,false);
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$rkpd->PmKecamatanID,false);
            return view("pages.$theme.rkpd.pembahasanrkpd.edit4")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'rkpd'=>$rkpd,
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
        $rkpd = RKPDModel::find($id);
        
        $this->validate($request, [            
            'SumberDanaID'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian' => 'required',
            'Sasaran_AngkaSetelah'=>'required',
            'Sasaran_UraianSetelah'=>'required',
            'Target'=>'required',
            'NilaiSebelum'=>'required',
            'NilaiUsulan'=>'required',
            'NilaiSetelah'=>'required',
            'NamaIndikator'=>'required'
        ]);
        
        switch ($this->NameOfPage) 
        {   
            case 'pembahasanrkpd' :
                $rkpd->SumberDanaID = $request->input('SumberDanaID');
                $rkpd->Sasaran_Angka2 = $request->input('Sasaran_Angka');
                $rkpd->Sasaran_Uraian2 = $request->input('Sasaran_Uraian');
                $rkpd->Sasaran_AngkaSetelah = $request->input('Sasaran_AngkaSetelah');
                $rkpd->Sasaran_UraianSetelah = $request->input('Sasaran_UraianSetelah');
                $rkpd->Target2 = $request->input('Target');
                $rkpd->NilaiSebelum = $request->input('NilaiSebelum');
                $rkpd->NilaiSetelah = $request->input('NilaiSetelah');
                $rkpd->NamaIndikator = $request->input('NamaIndikator');
                $rkpd->Descr = $request->input('Descr');
                $rkpd->save();
            break;                
        }   

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('show'),['id'=>$rkpd->RKPDID]))->with('success','Data ini telah berhasil disimpan.');
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

        $indikatorkinerja = RKPDIndikatorModel::find($id);
        $indikatorkinerja->Target_Angka = $request->input('Target_Angka'); 
        $indikatorkinerja->Target_Uraian = $request->input('Target_Uraian');       
        $indikatorkinerja->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('show'),['id'=>$indikatorkinerja->RKPDID]))->with('success','Data Indikator kegiatan telah berhasil disimpan. Selanjutnya isi Rincian Kegiatan');
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
        $rinciankegiatan = RKPDRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'Target'=>'required',
            'Jumlah'=>'required',
        ]);
        
        \DB::transaction(function () use ($request,$rinciankegiatan) {
            switch ($this->NameOfPage) 
            {            
                case 'pembahasanrkpd' :
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target2 = $request->input('Target');
                    $rinciankegiatan->NilaiUsulan2 = $request->input('Jumlah');  
                    $rinciankegiatan->Descr = $request->input('Descr');                   
                    $rinciankegiatan->save();
                    $rkpd = $rinciankegiatan->rkpd;            
                    $rkpd->NilaiUsulan2=RKPDRincianModel::where('RKPDID',$rkpd->RKPDID)->sum('NilaiUsulan2');            
                    $rkpd->save();
                break;                
            }   
        });
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('show'),['id'=>$rinciankegiatan->RKPDID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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
        $rinciankegiatan = RKPDRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'Target'=>'required',
            'Jumlah'=>'required',
        ]);

        \DB::transaction(function () use ($request,$rinciankegiatan) {
            switch ($this->NameOfPage) 
            {            
                case 'pembahasanrkpd' :
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target2 = $request->input('Target');
                    $rinciankegiatan->NilaiUsulan2 = $request->input('Jumlah');  
                    $rinciankegiatan->Descr = $request->input('Descr');
                    $rinciankegiatan->save();

                    $rkpd = $rinciankegiatan->rkpd;            
                    $rkpd->NilaiUsulan2=RKPDRincianModel::where('RKPDID',$rkpd->RKPDID)->sum('NilaiUsulan2');            
                    $rkpd->save();
                break;                
            }   
        });
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('show'),['id'=>$rinciankegiatan->RKPDID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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
        $rinciankegiatan = RKPDRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'Target'=>'required',
            'Jumlah'=>'required'            
        ]);        
        \DB::transaction(function () use ($request,$rinciankegiatan) { 

            switch ($this->NameOfPage) 
            {            
                case 'pembahasanrkpd' :                    
                    $rinciankegiatan->PmKecamatanID = $request->input('PmKecamatanID');
                    $rinciankegiatan->PmDesaID = $request->input('PmDesaID');
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target2 = $request->input('Target');
                    $rinciankegiatan->NilaiUsulan2 = $request->input('Jumlah');  
                    $rinciankegiatan->Descr = $request->input('Descr');                                                                                 
                    $rinciankegiatan->save();                                          
        
                    $rkpd = $rinciankegiatan->rkpd;   
                    $rkpd->Status=$rinciankegiatan->Status;         
                    $rkpd->EntryLvl=$rinciankegiatan->EntryLvl;
                    $rkpd->NilaiUsulan2=RKPDRincianModel::where('RKPDID',$rkpd->RKPDID)->sum('NilaiUsulan2');            
                    $rkpd->save();
                break;                
            }               
            
        });
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('show'),['id'=>$rinciankegiatan->RKPDID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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
            $indikatorkinerja = RKPDIndikatorModel::find($id);
            $rkpdid=$indikatorkinerja->RKPDID;
            $result=$indikatorkinerja->delete();
            
            $rkpd = $indikatorkinerja->rkpd;
            $rkpd->Status_Indikator=RKPDIndikatorModel::where('RKPDID',$indikatorkinerja->RKPDID)->count() > 0;
            $rkpd->save();
            
            if ($request->ajax()) 
            {
                $data = $this->populateIndikatorKegiatan($rkpdid);

                $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatableindikatorkinerja")
                                ->with([
                                    'page_active'=>$this->NameOfPage,
                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                    'rkpd'=>$rkpd,
                                    'dataindikatorkinerja'=>$data])
                                ->render();       
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route(\Helper::getNameOfPage('create1'),['id'=>$rkpdid]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }
        else  if ($request->exists('rinciankegiatan'))
        {
            $rinciankegiatan = RKPDRincianModel::find($id);
            $rkpdid=$rinciankegiatan->RKPDID;
            $rkpd = $rinciankegiatan->rkpd;
            $NilaiUsulan = \DB::transaction(function () use ($rinciankegiatan,$rkpd) {                                                                             
                $rinciankegiatan->delete();
                switch ($this->NameOfPage) 
                {            
                    case 'pembahasanrkpd' :
                        $rkpd->NilaiUsulan2=RKPDRincianModel::where('RKPDID',$rkpd->RKPDID)->sum('NilaiUsulan2');  
                        $NilaiUsulan=$rkpd->NilaiUsulan2;          
                    break;                    
                }   
                $rkpd->save();
                return $NilaiUsulan;
            });
            if ($request->ajax()) 
            {
                $data = $this->populateRincianKegiatan($rkpdid);
                        
                $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatablerinciankegiatan")
                                ->with([
                                    'page_active'=>$this->NameOfPage,
                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                    'rkpd'=>$rkpd,
                                    'datarinciankegiatan'=>$data])
                                ->render();     
                
                return response()->json(['success'=>true,'NilaiUsulan2'=>$NilaiUsulan,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route(\Helper::getNameOfPage('show'),['id'=>$rkpdid]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }//akhir penyeleksian kondisi bila rincian kegiatan
        else if ($request->exists('pid'))
        {
            $rkpd = $request->input('pid') == 'rincian' ?RKPDRincianModel::find($id) :RKPDModel::find($id);
            $result=$rkpd->delete();
            if ($request->ajax()) 
            {
                $currentpage=$this->getCurrentPageInsideSession('pembahasanrkpd'); 
                $data=$this->populateData($currentpage);
               
                $datatable = view("pages.$theme.rkpd.pembahasanrkpd.datatable")->with(['page_active'=>$this->NameOfPage,
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage), 
                                                                                'search'=>$this->getControllerStateSession('pembahasanrkpd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanrkpd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanrkpd.orderby','order'),
                                                                                'data'=>$data])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route(\Helper::getNameOfPage('index')))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }      
        }  
        
    }
    /**
     * Print to Excel
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexcel()
    {       
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession($this->SessionName,'filters');   
        $generate_date=date('Y-m-d_H_m_s');
        $OrgID=$filters['OrgID'];        
        $SOrgID=$filters['SOrgID'];   

        if ($SOrgID != 'none'&&$SOrgID != ''&&$SOrgID != null) 
        {   
            $unitkerja = \DB::table('v_suborganisasi')
                            ->where('SOrgID',$SOrgID)->first();              
            $data_report['OrgID']=$unitkerja->OrgID;
            $data_report['SOrgID']=$SOrgID;
            $data_report['Kd_Urusan']=$unitkerja->Kd_Urusan;
            $data_report['Nm_Urusan']=$unitkerja->Nm_Urusan;
            $data_report['Kd_Bidang']=$unitkerja->Kd_Bidang;
            $data_report['Nm_Bidang']=$unitkerja->Nm_Bidang;
            $data_report['kode_organisasi']=$unitkerja->kode_organisasi;
            $data_report['OrgNm']=$unitkerja->OrgNm;
            $data_report['SOrgID']=$SOrgID;
            $data_report['kode_suborganisasi']=$unitkerja->kode_suborganisasi;
            $data_report['SOrgNm']=$unitkerja->SOrgNm;
            $data_report['NamaKepalaSKPD']=$unitkerja->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$unitkerja->NIPKepalaSKPD;
            $data_report['mode']='pembahasanrkpd';
            
            $report= new \App\Models\Report\ReportRKPDPerubahanModel ($data_report);
            return $report->download("rkpdp_$generate_date.xlsx");
        }
        else if ($OrgID != 'none'&&$OrgID != ''&&$OrgID != null)       
        {   
            $opd = \DB::table('v_urusan_organisasi')
                        ->where('OrgID',$OrgID)->first();  
            
            $data_report['OrgID']=$opd->OrgID;
            $data_report['SOrgID']=$SOrgID;
            $data_report['Kd_Urusan']=$opd->Kd_Urusan;
            $data_report['Nm_Urusan']=$opd->Nm_Urusan;
            $data_report['Kd_Bidang']=$opd->Kd_Bidang;
            $data_report['Nm_Bidang']=$opd->Nm_Bidang;
            $data_report['kode_organisasi']=$opd->kode_organisasi;
            $data_report['OrgNm']=$opd->OrgNm;
            $data_report['NamaKepalaSKPD']=$opd->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$opd->NIPKepalaSKPD;
            $data_report['mode']='pembahasanrkpd';
            
            $report= new \App\Models\Report\ReportRKPDPerubahanModel($data_report);
            return $report->download("rkpdp_$generate_date.xlsx");
        }
        else
        {
            return view("pages.$theme.rkpd.pembahasanrkpd.error")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu. bila sudah terpilih ternyata tidak bisa, berarti saudara tidak diperkenankan menambah kegiatan karena telah dikunci.'
                                                                ]);  
        }
    }
}