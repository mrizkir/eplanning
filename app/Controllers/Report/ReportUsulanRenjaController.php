<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RenjaIndikatorModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;
use App\Models\RKPD\RKPDModel;

class ReportUsulanRenjaController extends Controller {    
    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        //set nama halaman saat ini
        $this->NameOfPage = \Helper::getNameOfPage();
        if ($this->NameOfPage == 'verifikasirenja')
        {
            $this->middleware(['auth','role:superadmin|bapelitbang|tapd']);
        }
        else
        {
            $this->middleware(['auth','role:superadmin|bapelitbang|tapd|opd']);
        }
        //set nama session 
        $this->SessionName=$this->getNameForSession();    
    }     
    private function populateRincianKegiatan($RenjaID)
    {
        switch ($this->NameOfPage) 
        {
            case 'reportusulanprarenjaopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Sasaran_Angka1" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian1" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target1" AS "Target",
                                                            "trRenjaRinc"."Jumlah1" AS "Jumlah",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));
            break;
            case 'pembahasanrakorbidang' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target2" AS "Target",
                                                            "trRenjaRinc"."Jumlah2" AS "Jumlah",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
            case 'pembahasanforumopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Sasaran_Angka3" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian3" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target3" AS "Target",
                                                            "trRenjaRinc"."Jumlah3" AS "Jumlah",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
            case 'pembahasanmusrenkab' :
                 $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Sasaran_Angka4" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian4" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target4" AS "Target",
                                                            "trRenjaRinc"."Jumlah4" AS "Jumlah",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
            case 'verifikasirenja' :
                 $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Sasaran_Angka5" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian5" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target5" AS "Target",
                                                            "trRenjaRinc"."Jumlah5" AS "Jumlah",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
        }
        $data = $data->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc.PmKecamatanID')
                        ->leftJoin('trPokPir','trPokPir.PokPirID','trRenjaRinc.PokPirID')
                        ->leftJoin('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                        
                        ->where('RenjaID',$RenjaID)
                        ->orderBy('Prioritas','ASC')
                        ->get();
        
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
     * collect OPD data from resources for index view
     *
     * @return resources
     */
    public function populateDataOPD ()
    {

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
        
          
        $SOrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'SOrgID');        

        if ($this->checkStateIsExistSession($this->SessionName,'search')) 
        {
            $search=$this->getControllerStateSession($this->SessionName,'search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = \DB::table(\HelperKegiatan::getViewName($this->NameOfPage))
                                ->select(\HelperKegiatan::getField($this->NameOfPage))
                                ->where('kode_kegiatan',$search['isikriteria'])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = \DB::table(\HelperKegiatan::getViewName($this->NameOfPage))
                                ->select(\HelperKegiatan::getField($this->NameOfPage))
                                ->where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = \DB::table(\HelperKegiatan::getViewName($this->NameOfPage))
                                ->select(\HelperKegiatan::getField($this->NameOfPage))
                                ->where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table(\HelperKegiatan::getViewName($this->NameOfPage))
                        ->select(\HelperKegiatan::getField($this->NameOfPage))
                        ->where('SOrgID',$SOrgID)                                     
                        ->whereNotNull('RenjaRincID')       
                        ->where('TA', \HelperKegiatan::getTahunPerencanaan())                                            
                        ->orderBy('Prioritas','ASC')
                        ->orderBy($column_order,$direction)                                            
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }        
        $data->setPath(route(\Helper::getNameOfPage('index')));          
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
        
        $this->setCurrentPageInsideSession($this->SessionName,1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.report.reportusulanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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
            case 'col-Status' :
                $column_name = 'Status';
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
        
        $datatable = view("pages.$theme.report.reportusulanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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
        $datatable = view("pages.$theme.report.reportusulanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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

        $datatable = view("pages.$theme.report.reportusulanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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
        
        // //index        
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$OrgID);  
            
            $this->putControllerStateSession($this->SessionName,'filters',$filters);

            $data = [];

            $datatable = view("pages.$theme.report.reportusulanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage), 
                                                                                    'label_transfer'=>$this->LabelTransfer,                                                                       
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

            $json_data = ['success'=>true,'paguanggaranopd'=>$paguanggaranopd,'daftar_unitkerja'=>$daftar_unitkerja,'totalpaguindikatifopd'=>$totalpaguindikatifopd,'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession($this->SessionName,'filters',$filters);
            $this->setCurrentPageInsideSession($this->SessionName,1);

            $data = $this->populateData();

            $datatable = view("pages.$theme.report.reportusulanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                    'label_transfer'=>$this->LabelTransfer,
                                                                                    'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                    'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                    'data'=>$data])->render(); 
                                                                                    
            $totalpaguindikatifunitkerja = RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']);            
                                                                                    
            $json_data = ['success'=>true,'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,'datatable'=>$datatable];    
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

        $roles=$auth->getRoleNames();
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :
            case 'tapd' :  
                $daftar_opd=\DB::table('v_urusan_organisasi')
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('kode_organisasi','ASC')
                                ->get();

            break;  
            case 'opd' :
                $daftar_opd=\DB::table('usersopd')
                                ->join('v_urusan_organisasi','v_urusan_organisasi.OrgID','usersopd.OrgID')
                                ->where('id',$auth->id)  
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('kode_organisasi','ASC')
                                ->get();
                
                if (!count($daftar_opd) > 0)
                {
                    return view("pages.$theme.report.reportusulanrenja.error")->with(['page_active'=>$this->NameOfPage, 
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                        'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                        ]);
                }       
            break;
        }
        return view("pages.$theme.report.reportusulanrenja.index")->with(['page_active'=>$this->NameOfPage, 
                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                            
                                                                        'label_transfer'=>$this->LabelTransfer,
                                                                        'daftar_opd'=>$daftar_opd,
                                                                    ]);
        // //filter
        // if (!$this->checkStateIsExistSession($this->SessionName,'filters')) 
        // {            
        //     $this->putControllerStateSession($this->SessionName,'filters',[
        //                                                                     'OrgID'=>'none',
        //                                                                     'SOrgID'=>'none',
        //                                                                     ]);
        // }      
        // $filters=$this->getControllerStateSession($this->SessionName,'filters');
        // $roles=$auth->getRoleNames();
        // $daftar_unitkerja=array();           
        // switch ($roles[0])
        // {
        //     case 'superadmin' :     
        //     case 'bapelitbang' :
        //     case 'tapd' :     
        //         $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);                  
        //         if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        //         {
        //             $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
        //         }    
        //     break;
        //     case 'opd' :
        //         $daftar_opd=\App\Models\UserOPD::getOPD();      
        //         if (count($daftar_opd) > 0)
        //         {                    
        //             if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        //             {
        //                 $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
        //             }  
        //         }      
        //         else
        //         {
        //             $filters['OrgID']='none';
        //             $filters['SOrgID']='none';
        //             $this->putControllerStateSession($this->SessionName,'filters',$filters);

        //             return view("pages.$theme.report.reportusulanrenja.error")->with(['page_active'=>$this->NameOfPage, 
        //                                                                                 'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
        //                                                                                 'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
        //                                                                                 ]);
        //         }       
        //     break;

        // }
        // $search=$this->getControllerStateSession($this->SessionName,'search'); 
        // $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession($this->SessionName);
        // $data = $this->populateData($currentpage);
        // if ($currentpage > $data->lastPage())
        // {            
        //     $data = $this->populateData($data->lastPage());
        // }

        // $this->setCurrentPageInsideSession($this->SessionName,$data->currentPage());
        // $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
        //                                                             ->where('OrgID',$filters['OrgID'])                                                    
        //                                                             ->value('Jumlah1');
        
        // return view("pages.$theme.report.reportusulanrenja.index")->with(['page_active'=>$this->NameOfPage, 
        //                                                                 'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                            
        //                                                                 'label_transfer'=>$this->LabelTransfer,
        //                                                                 'daftar_opd'=>$daftar_opd,
        //                                                                 'daftar_unitkerja'=>$daftar_unitkerja,
        //                                                                 'filters'=>$filters,
        //                                                                 'search'=>$this->getControllerStateSession($this->SessionName,'search'),
        //                                                                 'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
        //                                                                 'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
        //                                                                 'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
        //                                                                 'paguanggaranopd'=>$paguanggaranopd,
        //                                                                 'totalpaguindikatifopd'=>RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']),
        //                                                                 'totalpaguindikatifunitkerja' => RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']),            
        //                                                                 'data'=>$data]);             
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
            case 'pembahasanprarenjaopd' :
                $renja = RenjaModel::select(\DB::raw('"trRenja"."RenjaID",
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
                                            "Sasaran_Angka1" AS "Sasaran_Angka",
                                            "Sasaran_Uraian1" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target1" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan1" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRenja"."Privilege",
                                            "trRenja"."created_at",
                                            "trRenja"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja.SOrgID')  
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja.SumberDanaID')                       
                            ->findOrFail($id);                
            break;
            case 'pembahasanrakorbidang' :
                $renja = RenjaModel::select(\DB::raw('"trRenja"."RenjaID",
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
                                            "Sasaran_Angka2" AS "Sasaran_Angka",
                                            "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target2" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan2" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRenja"."Privilege",
                                            "trRenja"."created_at",
                                            "trRenja"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja.SOrgID')  
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja.SumberDanaID')                       
                            ->findOrFail($id);
            break;
            case 'pembahasanforumopd' :
                $renja = RenjaModel::select(\DB::raw('"trRenja"."RenjaID",                
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
                                            "Sasaran_Angka3" AS "Sasaran_Angka",
                                            "Sasaran_Uraian3" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target3" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan3" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRenja"."Privilege",
                                            "trRenja"."created_at",
                                            "trRenja"."updated_at"
                                    '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja.SOrgID')  
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja.SumberDanaID')                       
                            ->findOrFail($id);
            break;
            case 'pembahasanmusrenkab' :
                $renja = RenjaModel::select(\DB::raw('"trRenja"."RenjaID",
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
                                            "Sasaran_Angka4" AS "Sasaran_Angka",
                                            "Sasaran_Uraian4" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target4" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan4" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRenja"."Privilege",
                                            "trRenja"."created_at",
                                            "trRenja"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja.SOrgID')  
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja.SumberDanaID')                       
                            ->findOrFail($id);
            break;                
            case 'verifikasirenja' :
                $renja = RenjaModel::select(\DB::raw('"trRenja"."RenjaID",
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
                                            "Sasaran_Angka5" AS "Sasaran_Angka",
                                            "Sasaran_Uraian5" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target4" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan5" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRenja"."Privilege",
                                            "trRenja"."created_at",
                                            "trRenja"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja.SOrgID')  
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRenja.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja.SumberDanaID')                       
                            ->findOrFail($id);
            break;                
        }           
        if (!is_null($renja) )  
        {
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($id);            
            $datarinciankegiatan = $this->populateRincianKegiatan($id);               
            return view("pages.$theme.report.reportusulanrenja.show")->with(['page_active'=>$this->NameOfPage,
                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                'renja'=>$renja,
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
        $theme = \Auth::user()->theme;
        switch ($this->NameOfPage) 
        {            
            case 'pembahasanprarenjaopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",                                                            
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."Sasaran_Angka1" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian1" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target1" AS "Target",
                                                            "trRenjaRinc"."Jumlah1" AS "Jumlah",
                                                            "trRenjaRinc"."Prioritas",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Descr",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."created_at",
                                                            "trRenjaRinc"."updated_at"'))    
                                            ->findOrFail($id);
            break;
            case 'pembahasanrakorbidang' :
                $data = RenjaRincianModel::select(\DB::raw('
                "trRenjaRinc"."RenjaRincID",                                                            
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target2" AS "Target",
                                                            "trRenjaRinc"."Jumlah2" AS "Jumlah",
                                                            "trRenjaRinc"."Prioritas",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Descr",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."created_at",
                                                            "trRenjaRinc"."updated_at"'))    
                                            ->findOrFail($id);
            break;
            case 'pembahasanforumopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",                                                            
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."Sasaran_Angka3" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian3" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target3" AS "Target",
                                                            "trRenjaRinc"."Jumlah3" AS "Jumlah",
                                                            "trRenjaRinc"."Prioritas",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Descr",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."created_at",
                                                            "trRenjaRinc"."updated_at"'))    
                                            ->findOrFail($id);
            break;
            case 'pembahasanmusrenkab' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",                                                            
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."Sasaran_Angka4" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian4" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target4" AS "Target",
                                                            "trRenjaRinc"."Jumlah4" AS "Jumlah",
                                                            "trRenjaRinc"."Prioritas",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Descr",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."created_at",
                                                            "trRenjaRinc"."updated_at"'))    
                                            ->findOrFail($id);
            break;                
            case 'verifikasirenja' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID",                                                            
                                                            "trRenjaRinc"."RenjaID",
                                                            "trRenjaRinc"."No",
                                                            "trRenjaRinc"."Uraian",
                                                            "trRenjaRinc"."Sasaran_Angka5" AS "Sasaran_Angka",
                                                            "trRenjaRinc"."Sasaran_Uraian5" AS "Sasaran_Uraian",
                                                            "trRenjaRinc"."Target5" AS "Target",
                                                            "trRenjaRinc"."Jumlah5" AS "Jumlah",
                                                            "trRenjaRinc"."Prioritas",
                                                            "trRenjaRinc"."Status",
                                                            "trRenjaRinc"."Descr",
                                                            "trRenjaRinc"."Privilege",
                                                            "trRenjaRinc"."created_at",
                                                            "trRenjaRinc"."updated_at"'))    
                                            ->findOrFail($id);
            break;                
        }        
       
        if (!is_null($data) )  
        {            
            return view("pages.$theme.report.reportusulanrenja.showrincian")->with(['page_active'=>$this->NameOfPage,
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                'label_transfer'=>$this->LabelTransfer,
                                                                                'renja'=>$data,
                                                                                'item'=>$data
                                                                            ]);
        }          
    }
   
    /**
     * Print to Excel
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexcel ($id)
    {  
        $generate_date=date('Y-m-d_H_m_s');
        $organisasi = \DB::table('v_urusan_organisasi')
                        ->where('OrgID',$id)->first();  
        if (!is_null($organisasi))
        {
            $data_report['NameOfPage']=$this->NameOfPage;
            $data_report['OrgID']=$id;
            $data_report['OrgNm']=$organisasi->OrgNm;
            $data_report['kode_organisasi']=$organisasi->kode_organisasi;
            $data_report['NamaKepalaSKPD']=$organisasi->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$organisasi->NIPKepalaSKPD; 
            $data_report['SOrgID']='none';

            switch ($this->NameOfPage) 
            {            
                case 'reportusulanprarenjaopd' :
                    $data_report['sheetname']='PRA Renja OPD';
                    $data_report['EntryLvl']=0;
                    
                    $filename="usulanprarenja_$generate_date.xlsx";
                break;
            }
            $report= new \App\Models\Report\ReportUsulanRenjaModel ($data_report);
            return $report->download($filename);
        }
        else
        {
            return view("pages.$theme.report.reportusulanrenja.error")->with(['page_active'=>$this->NameOfPage, 
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                        'errormessage'=>"ID OPD ($id) tidak terdaftar di Database",
                                                                                        ]);
        }
    }
    /**
     * Print to Excel dengan rinci
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexceldetail ($id)
    {  
        $generate_date=date('Y-m-d_H_m_s');
        $organisasi = \DB::table('v_urusan_organisasi')
                        ->where('OrgID',$id)->first();  
        if (!is_null($organisasi))
        {
            $data_report['NameOfPage']=$this->NameOfPage;
            $data_report['OrgID']=$id;
            $data_report['OrgNm']=$organisasi->OrgNm;
            $data_report['kode_organisasi']=$organisasi->kode_organisasi;
            $data_report['NamaKepalaSKPD']=$organisasi->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$organisasi->NIPKepalaSKPD; 
            $data_report['SOrgID']='none';

            switch ($this->NameOfPage) 
            {            
                case 'reportusulanprarenjaopd' :
                    $data_report['sheetname']='PRA Renja OPD';
                    $data_report['EntryLvl']=0;
                    
                    $filename="usulanprarenja_$generate_date.xlsx";
                break;
            }
            $report= new \App\Models\Report\ReportUsulanRenjaModel ($data_report);
            // return $report->download($filename);
        }
        else
        {
            return view("pages.$theme.report.reportusulanrenja.error")->with(['page_active'=>$this->NameOfPage, 
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                        'errormessage'=>"ID OPD ($id) tidak terdaftar di Database",
                                                                                        ]);
        }
    }
}