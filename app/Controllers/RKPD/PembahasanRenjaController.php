<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RenjaIndikatorModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;

class PembahasanRenjaController extends Controller {    
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

        //set nama halaman saat transfer
        $this->LabelTransfer = \HelperKegiatan::getLabelTransfer($this->NameOfPage);

    }     
    private function populateRincianKegiatan($RenjaID)
    {
        switch ($this->NameOfPage) 
        {
            case 'pembahasanprarenjaopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Sasaran_Angka1" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian1" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target1" AS "Target",
                                                            "trRenjaRinc90"."Jumlah1" AS "Jumlah",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc90"."Descr"'))
                                        ->where('trRenjaRinc90.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));
            break;
            case 'pembahasanrakorbidang' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target2" AS "Target",
                                                            "trRenjaRinc90"."Jumlah2" AS "Jumlah",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc90"."Descr"'))
                                        ->where('trRenjaRinc90.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
            case 'pembahasanforumopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Sasaran_Angka3" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian3" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target3" AS "Target",
                                                            "trRenjaRinc90"."Jumlah3" AS "Jumlah",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc90"."Descr"'))
                                        ->where('trRenjaRinc90.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
            case 'pembahasanmusrenkab' :
                 $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Sasaran_Angka4" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian4" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target4" AS "Target",
                                                            "trRenjaRinc90"."Jumlah4" AS "Jumlah",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc90"."Descr"'))
                                        ->where('trRenjaRinc90.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
            case 'verifikasirenja' :
                 $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."UsulanKecID",
                                                            "Nm_Kecamatan",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Sasaran_Angka5" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian5" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target5" AS "Target",
                                                            "trRenjaRinc90"."Jumlah5" AS "Jumlah",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "isSKPD",
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "trRenjaRinc90"."Descr"'))
                                        ->where('trRenjaRinc90.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage));  
            break;
        }
        $data = $data->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRenjaRinc90.PmKecamatanID')
                        ->leftJoin('trPokPir','trPokPir.PokPirID','trRenjaRinc90.PokPirID')
                        ->leftJoin('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')                        
                        ->where('RenjaID',$RenjaID)
                        ->orderBy('Prioritas','ASC')
                        ->get();
        
        return $data;
    }
    private function populateIndikatorKegiatan($RenjaID)
    {
      
        $data = RenjaIndikatorModel::join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRenjaIndikator90.IndikatorKinerjaID')
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
        if (!$this->checkStateIsExistSession($this->SessionName,'orderby')) 
        {            
           $this->putControllerStateSession($this->SessionName,'orderby',['column_name'=>'kode_subkegiatan','order'=>'asc']);
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
                case 'kode_subkegiatan' :
                    $data = \DB::table(\HelperKegiatan::getViewName($this->NameOfPage))
                                ->select(\HelperKegiatan::getField($this->NameOfPage))
                                ->where('kode_subkegiatan',$search['isikriteria'])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
                case 'SubKgtNm' :
                    $data = \DB::table(\HelperKegiatan::getViewName($this->NameOfPage))
                                ->select(\HelperKegiatan::getField($this->NameOfPage))
                                ->where('SubKgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
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

        $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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
            case 'col-kode_subkegiatan' :
                $column_name = 'kode_subkegiatan';
            break;    
            case 'col-SubKgtNm' :
                $column_name = 'SubKgtNm';
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
                $column_name = 'kode_subkegiatan';
        }
        $this->putControllerStateSession($this->SessionName,'orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession($this->SessionName);         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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
        $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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

        $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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

            $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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

            $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
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

                    return view("pages.$theme.rkpd.pembahasanrenja.error")->with(['page_active'=>$this->NameOfPage, 
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
        
        return view("pages.$theme.rkpd.pembahasanrenja.index")->with(['page_active'=>$this->NameOfPage, 
                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                            
                                                                        'label_transfer'=>$this->LabelTransfer,
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
                $renja = RenjaModel::select(\DB::raw('"trRenja90"."RenjaID",
                                            "v_sub_kegiatan"."Kd_Urusan",
                                            "v_sub_kegiatan"."Nm_Urusan",
                                            "v_sub_kegiatan"."Kd_Bidang",
                                            "v_sub_kegiatan"."Nm_Bidang",
                                            "v_suborganisasi"."kode_organisasi",
                                            "v_suborganisasi"."OrgNm",
                                            "v_suborganisasi"."kode_suborganisasi",
                                            "v_suborganisasi"."SOrgNm",
                                            "v_sub_kegiatan"."Kd_Prog",
                                            "v_sub_kegiatan"."PrgNm",
                                            "v_sub_kegiatan"."Kd_SubKeg",
                                            "v_sub_kegiatan"."kode_subkegiatan",
                                            "v_sub_kegiatan"."SubKgtNm",
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
                                            "trRenja90"."Privilege",
                                            "trRenja90"."created_at",
                                            "trRenja90"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja90.SOrgID')  
                            ->join('v_sub_kegiatan','v_sub_kegiatan.KgtID','trRenja90.SubKgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja90.SumberDanaID')                       
                            ->findOrFail($id);                
            break;
            case 'pembahasanrakorbidang' :
                $renja = RenjaModel::select(\DB::raw('"trRenja90"."RenjaID",
                                            "v_sub_kegiatan"."Kd_Urusan",
                                            "v_sub_kegiatan"."Nm_Urusan",
                                            "v_sub_kegiatan"."Kd_Bidang",
                                            "v_sub_kegiatan"."Nm_Bidang",
                                            "v_suborganisasi"."kode_organisasi",
                                            "v_suborganisasi"."OrgNm",
                                            "v_suborganisasi"."kode_suborganisasi",
                                            "v_suborganisasi"."SOrgNm",
                                            "v_sub_kegiatan"."Kd_Prog",
                                            "v_sub_kegiatan"."PrgNm",
                                            "v_sub_kegiatan"."Kd_SubKeg",
                                            "v_sub_kegiatan"."kode_subkegiatan",
                                            "v_sub_kegiatan"."SubKgtNm",
                                            "Sasaran_Angka2" AS "Sasaran_Angka",
                                            "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                            "Sasaran_AngkaSetelah",
                                            "Sasaran_UraianSetelah",
                                            "Target2" AS "Target",
                                            "NilaiSebelum",
                                            "NilaiUsulan2" AS "NilaiUsulan",
                                            "NilaiSetelah",
                                            "Nm_SumberDana",
                                            "trRenja90"."Privilege",
                                            "trRenja90"."created_at",
                                            "trRenja90"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja90.SOrgID')  
                            ->join('v_sub_kegiatan','v_sub_kegiatan.SubKgtID','trRenja90.SubKgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja90.SumberDanaID')                       
                            ->findOrFail($id);
            break;
            case 'pembahasanforumopd' :
                $renja = RenjaModel::select(\DB::raw('"trRenja90"."RenjaID",                
                                            "v_sub_kegiatan"."Kd_Urusan",
                                            "v_sub_kegiatan"."Nm_Urusan",
                                            "v_sub_kegiatan"."Kd_Bidang",
                                            "v_sub_kegiatan"."Nm_Bidang",
                                            "v_suborganisasi"."kode_organisasi",
                                            "v_suborganisasi"."OrgNm",
                                            "v_suborganisasi"."kode_suborganisasi",
                                            "v_suborganisasi"."SOrgNm",
                                            "v_sub_kegiatan"."Kd_Prog",
                                            "v_sub_kegiatan"."PrgNm",
                                            "v_sub_kegiatan"."Kd_SubKeg",
                                            "v_sub_kegiatan"."kode_subkegiatan",
                                            "v_sub_kegiatan"."SubKgtNm",
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
                                            "trRenja90"."Privilege",
                                            "trRenja90"."created_at",
                                            "trRenja90"."updated_at"
                                    '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja90.SOrgID')  
                            ->join('v_sub_kegiatan','v_sub_kegiatan.SubKgtID','trRenja90.SubKgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja90.SumberDanaID')                       
                            ->findOrFail($id);
            break;
            case 'pembahasanmusrenkab' :
                $renja = RenjaModel::select(\DB::raw('"trRenja90"."RenjaID",
                                            "v_sub_kegiatan"."Kd_Urusan",
                                            "v_sub_kegiatan"."Nm_Urusan",
                                            "v_sub_kegiatan"."Kd_Bidang",
                                            "v_sub_kegiatan"."Nm_Bidang",
                                            "v_suborganisasi"."kode_organisasi",
                                            "v_suborganisasi"."OrgNm",
                                            "v_suborganisasi"."kode_suborganisasi",
                                            "v_suborganisasi"."SOrgNm",
                                            "v_sub_kegiatan"."Kd_Prog",
                                            "v_sub_kegiatan"."PrgNm",
                                            "v_sub_kegiatan"."Kd_SubKeg",
                                            "v_sub_kegiatan"."kode_subkegiatan",
                                            "v_sub_kegiatan"."SubKgtNm",
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
                                            "trRenja90"."Privilege",
                                            "trRenja90"."created_at",
                                            "trRenja90"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja90.SOrgID')  
                            ->join('v_sub_kegiatan','v_sub_kegiatan.SubKgtID','trRenja90.SubKgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja90.SumberDanaID')                       
                            ->findOrFail($id);
            break;                
            case 'verifikasirenja' :
                $renja = RenjaModel::select(\DB::raw('"trRenja90"."RenjaID",
                                            "v_sub_kegiatan"."Kd_Urusan",
                                            "v_sub_kegiatan"."Nm_Urusan",
                                            "v_sub_kegiatan"."Kd_Bidang",
                                            "v_sub_kegiatan"."Nm_Bidang",
                                            "v_suborganisasi"."kode_organisasi",
                                            "v_suborganisasi"."OrgNm",
                                            "v_suborganisasi"."kode_suborganisasi",
                                            "v_suborganisasi"."SOrgNm",
                                            "v_sub_kegiatan"."Kd_Prog",
                                            "v_sub_kegiatan"."PrgNm",
                                            "v_sub_kegiatan"."Kd_SubKeg",
                                            "v_sub_kegiatan"."kode_subkegiatan",
                                            "v_sub_kegiatan"."SubKgtNm",
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
                                            "trRenja90"."Privilege",
                                            "trRenja90"."created_at",
                                            "trRenja90"."updated_at"
                                            '))
                            ->join('v_suborganisasi','v_suborganisasi.SOrgID','trRenja90.SOrgID')  
                            ->join('v_sub_kegiatan','v_sub_kegiatan.SubKgtID','trRenja90.SubKgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRenja90.SumberDanaID')                       
                            ->findOrFail($id);
            break;                
        }           
        if (!is_null($renja) )  
        {
            $dataindikatorkinerja = $this->populateIndikatorKegiatan($id);            
            $datarinciankegiatan = $this->populateRincianKegiatan($id);               
            return view("pages.$theme.rkpd.pembahasanrenja.show")->with(['page_active'=>$this->NameOfPage,
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
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",                                                            
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka1" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian1" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target1" AS "Target",
                                                            "trRenjaRinc90"."Jumlah1" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."created_at",
                                                            "trRenjaRinc90"."updated_at"'))    
                                            ->findOrFail($id);
            break;
            case 'pembahasanrakorbidang' :
                $data = RenjaRincianModel::select(\DB::raw('
                "trRenjaRinc90"."RenjaRincID",                                                            
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target2" AS "Target",
                                                            "trRenjaRinc90"."Jumlah2" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."created_at",
                                                            "trRenjaRinc90"."updated_at"'))    
                                            ->findOrFail($id);
            break;
            case 'pembahasanforumopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",                                                            
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka3" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian3" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target3" AS "Target",
                                                            "trRenjaRinc90"."Jumlah3" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."created_at",
                                                            "trRenjaRinc90"."updated_at"'))    
                                            ->findOrFail($id);
            break;
            case 'pembahasanmusrenkab' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",                                                            
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka4" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian4" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target4" AS "Target",
                                                            "trRenjaRinc90"."Jumlah4" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."created_at",
                                                            "trRenjaRinc90"."updated_at"'))    
                                            ->findOrFail($id);
            break;                
            case 'verifikasirenja' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",                                                            
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka5" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian5" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target5" AS "Target",
                                                            "trRenjaRinc90"."Jumlah5" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."Privilege",
                                                            "trRenjaRinc90"."created_at",
                                                            "trRenjaRinc90"."updated_at"'))    
                                            ->findOrFail($id);
            break;                
        }        
       
        if (!is_null($data) )  
        {            
            return view("pages.$theme.rkpd.pembahasanrenja.showrincian")->with(['page_active'=>$this->NameOfPage,
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                'label_transfer'=>$this->LabelTransfer,
                                                                                'renja'=>$data,
                                                                                'item'=>$data
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
        $auth=\Auth::user();
        $theme = $auth->theme;
        
        switch ($this->NameOfPage) 
        {            
            case 'pembahasanprarenjaopd' :               
                    $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                                "trRenjaRinc90"."RenjaID",
                                                                "trRenjaRinc90"."PmKecamatanID",
                                                                "trRenjaRinc90"."PmDesaID",
                                                                "trRenjaRinc90"."No",
                                                                "trRenjaRinc90"."Uraian",
                                                                "trRenjaRinc90"."Sasaran_Angka1" AS "Sasaran_Angka",
                                                                "trRenjaRinc90"."Sasaran_Uraian1" AS "Sasaran_Uraian",
                                                                "trRenjaRinc90"."Target1" AS "Target",
                                                                "trRenjaRinc90"."Jumlah1" AS "Jumlah",
                                                                "trRenjaRinc90"."Prioritas",
                                                                "trRenjaRinc90"."Status",
                                                                "trRenjaRinc90"."Descr",
                                                                "trRenjaRinc90"."isSKPD",
                                                                "trRenjaRinc90"."isReses"'))   
                                                ->where('Privilege',0)                                                                                     
                                                ->findOrFail($id);        
                    
            break;
            case 'pembahasanrakorbidang' :               
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."PmKecamatanID",
                                                            "trRenjaRinc90"."PmDesaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka2" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian2" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target2" AS "Target",
                                                            "trRenjaRinc90"."Jumlah2" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."isSKPD",
                                                            "trRenjaRinc90"."isReses"')) 
                                            ->where('Privilege',0)                                                                                       
                                            ->findOrFail($id);   
            break;
            case 'pembahasanforumopd' :               
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."PmKecamatanID",
                                                            "trRenjaRinc90"."PmDesaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka3" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian3" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target3" AS "Target",
                                                            "trRenjaRinc90"."Jumlah3" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."isSKPD",
                                                            "trRenjaRinc90"."isReses"'))   
                                            ->where('Privilege',0)                                                                                     
                                            ->findOrFail($id);        
                  
            break;
            case 'pembahasanmusrenkab' :                  
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."PmKecamatanID",
                                                            "trRenjaRinc90"."PmDesaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka4" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian4" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target4" AS "Target",
                                                            "trRenjaRinc90"."Jumlah4" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."isSKPD",
                                                            "trRenjaRinc90"."isReses"'))  
                                            ->where('Privilege',0)
                                            ->findOrFail($id);        
                  
            break;
            case 'verifikasirenja' :                  
                $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc90"."RenjaRincID",
                                                            "trRenjaRinc90"."RenjaID",
                                                            "trRenjaRinc90"."PmKecamatanID",
                                                            "trRenjaRinc90"."PmDesaID",
                                                            "trRenjaRinc90"."No",
                                                            "trRenjaRinc90"."Uraian",
                                                            "trRenjaRinc90"."Sasaran_Angka5" AS "Sasaran_Angka",
                                                            "trRenjaRinc90"."Sasaran_Uraian5" AS "Sasaran_Uraian",
                                                            "trRenjaRinc90"."Target5" AS "Target",
                                                            "trRenjaRinc90"."Jumlah5" AS "Jumlah",
                                                            "trRenjaRinc90"."Prioritas",
                                                            "trRenjaRinc90"."Status",
                                                            "trRenjaRinc90"."Descr",
                                                            "trRenjaRinc90"."isSKPD",
                                                            "trRenjaRinc90"."isReses"'))  
                                            ->where('Privilege',0)
                                            ->findOrFail($id);        
                  
            break;
            default :
                $dbViewName = null;
        }   
        
        if (!is_null($renja) ) 
        {  
            return view("pages.$theme.rkpd.pembahasanrenja.edit")->with(['page_active'=>$this->NameOfPage,
                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                        'label_transfer'=>$this->LabelTransfer,
                                                                        'renja'=>$renja,                                                                   
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

        $pembahasanmusrenkab = RenjaRincianModel::find($id);        
        $pembahasanmusrenkab->Status = $request->input('Status');
        $pembahasanmusrenkab->save();

        $RenjaID = $pembahasanmusrenkab->RenjaID;
        if (RenjaRincianModel::where('RenjaID',$RenjaID)->where('Status',1)->count() > 0)
        {
            RenjaModel::where('RenjaID',$RenjaID)->update(['Status'=>1]);
        }
        else
        {
            RenjaModel::where('RenjaID',$RenjaID)->update(['Status'=>0]);
        }        
        if ($request->ajax()) 
        {
            $filters=$this->getControllerStateSession($this->SessionName,'filters');

            $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession($this->SessionName);
            $data = $this->populateData($currentpage);           
            
            $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
                                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                            
                                                                                    'label_transfer'=>$this->LabelTransfer,
                                                                                    'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                    'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                    'data'=>$data])->render();
            
            $totalpaguindikatifopd = RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']);                        
            $totalpaguindikatifunitkerja = RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']);
                        
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.',
                'totalpaguindikatifopd'=>$totalpaguindikatifopd,
                'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,
                'datatable'=>$datatable
            ],200);
        }
        else
        {
            return redirect(route(\Helper::getNameOfPage('index')))->with('success','Data ini telah berhasil disimpan.');
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
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',
            'Target'=>'required',
            'Jumlah'=>'required',
            'Prioritas' => 'required'            
        ]);

        \DB::transaction(function () use ($request,$rinciankegiatan) 
        { 
            $transfer_ke=$request->input('transfer_ke');
            switch ($this->NameOfPage) 
            {            
                case 'pembahasanprarenjaopd' :                    
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka1 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian1 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target1 = $request->input('Target');
                    $rinciankegiatan->Jumlah1 = $request->input('Jumlah');  
                    $rinciankegiatan->Prioritas = $request->input('Prioritas');  
                    $rinciankegiatan->Descr = $request->input('Descr');
                    $rinciankegiatan->Status = $request->input('Status');
                    $rinciankegiatan->save();

                    $renja = $rinciankegiatan->renja;            
                    $renja->NilaiUsulan1=RenjaRincianModel::where('RenjaID',$renja->RenjaID)->sum('Jumlah1');            
                    $renja->save();                    
                break;
                case 'pembahasanrakorbidang' :                    
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka2 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian2 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target2 = $request->input('Target');
                    $rinciankegiatan->Jumlah2 = $request->input('Jumlah');  
                    $rinciankegiatan->Prioritas = $request->input('Prioritas');  
                    $rinciankegiatan->Descr = $request->input('Descr');
                    $rinciankegiatan->Status = $request->input('Status');
                    $rinciankegiatan->save();
        
                    $renja = $rinciankegiatan->renja;            
                    $renja->NilaiUsulan2=RenjaRincianModel::where('RenjaID',$renja->RenjaID)->sum('Jumlah2');            
                    $renja->save();
                break;
                case 'pembahasanforumopd' :                    
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka3 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian3 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target3 = $request->input('Target');
                    $rinciankegiatan->Jumlah3 = $request->input('Jumlah');  
                    $rinciankegiatan->Prioritas = $request->input('Prioritas');  
                    $rinciankegiatan->Descr = $request->input('Descr');
                    $rinciankegiatan->Status = $request->input('Status');
                    $rinciankegiatan->save();
        
                    $renja = $rinciankegiatan->renja;            
                    $renja->NilaiUsulan3=RenjaRincianModel::where('RenjaID',$renja->RenjaID)->sum('Jumlah3');            
                    $renja->save();
                break;
                case 'pembahasanmusrenkab' :                    
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka4 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian4 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target4 = $request->input('Target');
                    $rinciankegiatan->Jumlah4 = $request->input('Jumlah');  
                    $rinciankegiatan->Prioritas = $request->input('Prioritas');  
                    $rinciankegiatan->Descr = $request->input('Descr');
                    $rinciankegiatan->Status = $request->input('Status');
                    $rinciankegiatan->save();
        
                    $renja = $rinciankegiatan->renja;            
                    $renja->NilaiUsulan4=RenjaRincianModel::where('RenjaID',$renja->RenjaID)->sum('Jumlah4');            
                    $renja->save();
                break;                
                case 'verifikasirenja' :                    
                    $rinciankegiatan->Uraian = $request->input('Uraian');
                    $rinciankegiatan->Sasaran_Angka5 = $request->input('Sasaran_Angka'); 
                    $rinciankegiatan->Sasaran_Uraian5 = $request->input('Sasaran_Uraian');
                    $rinciankegiatan->Target5 = $request->input('Target');
                    $rinciankegiatan->Jumlah5 = $request->input('Jumlah');  
                    $rinciankegiatan->Prioritas = $request->input('Prioritas');  
                    $rinciankegiatan->Descr = $request->input('Descr');
                    $rinciankegiatan->Status = $request->input('Status');
                    $rinciankegiatan->save();
        
                    $renja = $rinciankegiatan->renja;            
                    $renja->NilaiUsulan5=RenjaRincianModel::where('RenjaID',$renja->RenjaID)->sum('Jumlah5');            
                    $renja->save();
                break;                
            }               
            if ($transfer_ke=='1')
            {                
                $request->replace(['RenjaRincID'=>$rinciankegiatan->RenjaRincID]);                                
                $this->transfer($request);
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
            return redirect(route(\Helper::getNameOfPage('showrincian'),['uuid'=>$rinciankegiatan->RenjaRincID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
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

        if ($request->exists('RenjaRincID') && $request->input('RenjaRincID')!='')
        {
            $RenjaRincID=$request->input('RenjaRincID');                                    
            $rincian_kegiatan=\DB::transaction(function () use ($RenjaRincID) {
                
                $rincian_kegiatan = RenjaRincianModel::find($RenjaRincID);               
                $RenjaID=$rincian_kegiatan->RenjaID;
                
                switch ($this->NameOfPage) 
                {            
                    case 'pembahasanprarenjaopd' :
                        //check renja id sudah ada belum di 
                        $old_renja = RenjaModel::select('RenjaID')
                                        ->where('RenjaID_Src',$RenjaID)
                                        ->get()
                                        ->pluck('RenjaID')->toArray();

                        if (count($old_renja) > 0)
                        {
                            $newRenjaID=$old_renja[0];
                        }
                        else
                        {
                            $renja = RenjaModel::find($RenjaID);   
                            $renja->Privilege=1;
                            $renja->save();

                            #new renja
                            $newRenjaID=uniqid ('uid');
                            $newrenja = $renja->replicate();
                            $newrenja->RenjaID = $newRenjaID;
                            $newrenja->Sasaran_Uraian2 = $newrenja->Sasaran_Uraian1;
                            $newrenja->Sasaran_Angka2 = $newrenja->Sasaran_Angka1;
                            $newrenja->Target2 = $newrenja->Target1;
                            $newrenja->NilaiUsulan2 = $newrenja->NilaiUsulan1;
                            $newrenja->EntryLvl = 1;
                            $newrenja->Status = 0;
                            $newrenja->Privilege = 0;
                            $newrenja->RenjaID_Src = $RenjaID;
                            $newrenja->created_at = \Carbon\Carbon::now();
                            $newrenja->updated_at = \Carbon\Carbon::now();
                            $newrenja->save();
                            
                        }
                        $str_rinciankegiatan = '
                            INSERT INTO "trRenjaRinc90" (
                                "RenjaRincID", 
                                "RenjaID",
                                "UsulanKecID",
                                "PMProvID",
                                "PmKotaID",
                                "PmKecamatanID",
                                "PmDesaID",
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Uraian2",              
                                "Sasaran_Angka1",
                                "Sasaran_Angka2",               
                                "Target1",
                                "Target2",                      
                                "Jumlah1", 
                                "Jumlah2", 
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
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Uraian1" AS Sasaran_Uraian2,              
                                "Sasaran_Angka1",
                                "Sasaran_Angka1" AS "Sasaran_Angka2",               
                                "Target1",
                                "Target1" AS "Target2",                      
                                "Jumlah1", 
                                "Jumlah1" AS "Jumlah2", 
                                "isReses",
                                "isReses_Uraian",
                                "isSKPD",
                                0 AS "Status",
                                1 AS "EntryLvl",
                                "Prioritas",
                                "Descr",
                                "TA",
                                "RenjaRincID" AS "RenjaID_Src",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaRinc90" 
                            WHERE "RenjaRincID"=\''.$RenjaRincID.'\' AND
                                ("Status"=1 OR "Status"=2) AND
                                "Privilege"=0             
                        ';
                        \DB::statement($str_rinciankegiatan);     

                        //hapus indikator kinerja  
                        \DB::statement('DELETE FROM "trRenjaIndikator90" WHERE "RenjaID"=\''.$newRenjaID.'\'');      
                        $str_kinerja='
                            INSERT INTO "trRenjaIndikator90" (
                                "RenjaIndikatorID", 
                                "IndikatorKinerjaID",
                                "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",  
                                "Descr",
                                "TA",
                                "created_at", 
                                "updated_at"
                            )
                            SELECT 
                                REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaIndikatorID",
                                "IndikatorKinerjaID",
                                \''.$newRenjaID.'\' AS "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",                                
                                "Descr",                                
                                "TA",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaIndikator90" 
                            WHERE 
                                "RenjaID"=\''.$RenjaID.'\'
                        ';
                        \DB::statement($str_kinerja);
                        
                        //update NilaiUsulan2
                        $newRenja = RenjaModel::find($newRenjaID);
                        $newRenja->NilaiUsulan2 = \DB::table('trRenjaRinc90')
                                                    ->where('RenjaID',$newRenjaID)
                                                    ->sum('Jumlah2');
                        $newRenja->save();
                        
                        RenjaRincianModel::where('RenjaRincID',$RenjaRincID)
                                            ->update(['Privilege'=>1,'updated_at'=>\Carbon\Carbon::now()]);
                        RenjaIndikatorModel::where('RenjaID',$RenjaID)
                                            ->update(['updated_at'=>\Carbon\Carbon::now()]);

                    break; //end pembahasanprarenjaopd
                    case 'pembahasanrakorbidang' :
                        //check renja id sudah ada belum di RenjaID_Old
                        $old_renja = RenjaModel::select('RenjaID')
                                        ->where('RenjaID_Src',$RenjaID)
                                        ->get()
                                        ->pluck('RenjaID')->toArray();

                        if (count($old_renja) > 0)
                        {
                            $newRenjaID=$old_renja[0];
                        }
                        else
                        {
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
                            $newrenja->created_at = \Carbon\Carbon::now();
                            $newrenja->updated_at = \Carbon\Carbon::now();
                            $newrenja->save();
                        }
                        $str_rinciankegiatan = '
                            INSERT INTO "trRenjaRinc90" (
                                "RenjaRincID", 
                                "RenjaID",
                                "UsulanKecID",
                                "PMProvID",
                                "PmKotaID",
                                "PmKecamatanID",
                                "PmDesaID",
                                "Lokasi",
                                "Latitude",
                                "Longitude",
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
                                "Lokasi",
                                "Latitude",
                                "Longitude",
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
                                "trRenjaRinc90" 
                            WHERE "RenjaRincID"=\''.$RenjaRincID.'\' AND
                                ("Status"=1 OR "Status"=2) AND
                                "Privilege"=0       
                        ';
                        \DB::statement($str_rinciankegiatan); 

                        //hapus indikator kinerja  
                        \DB::statement('DELETE FROM "trRenjaIndikator90" WHERE "RenjaID"=\''.$newRenjaID.'\'');      
                        $str_kinerja='
                            INSERT INTO "trRenjaIndikator90" (
                                "RenjaIndikatorID", 
                                "IndikatorKinerjaID",
                                "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",                                  
                                "Descr",                                
                                "TA",                                
                                "created_at", 
                                "updated_at"
                            )
                            SELECT 
                                REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaIndikatorID",
                                "IndikatorKinerjaID",
                                \''.$newRenjaID.'\' AS "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",                                
                                "Descr",                                
                                "TA",                                
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaIndikator90" 
                            WHERE 
                                "RenjaID"=\''.$RenjaID.'\'
                        ';                        
                        \DB::statement($str_kinerja);
                        
                        //update NilaiUsulan3
                        $newRenja = RenjaModel::find($newRenjaID);
                        $newRenja->NilaiUsulan3 = \DB::table('trRenjaRinc90')
                                                    ->where('RenjaID',$newRenjaID)
                                                    ->sum('Jumlah3');
                        $newRenja->save();
                        
                        RenjaRincianModel::where('RenjaRincID',$RenjaRincID)
                                            ->update(['Privilege'=>1,'updated_at'=>\Carbon\Carbon::now()]);                        
                        RenjaIndikatorModel::where('RenjaID',$RenjaID)
                                            ->update(['updated_at'=>\Carbon\Carbon::now()]);                                            
                    break;
                    case 'pembahasanforumopd' :
                        //check renja id sudah ada belum di RenjaID_Old
                        $old_renja = RenjaModel::select('RenjaID')
                                        ->where('RenjaID_Src',$RenjaID)
                                        ->get()
                                        ->pluck('RenjaID')->toArray();

                        if (count($old_renja) > 0)
                        {
                            $newRenjaID=$old_renja[0];
                        }
                        else
                        {
                            $renja = RenjaModel::find($RenjaID);   
                            $renja->Privilege=1;
                            $renja->save();

                            // #new renja
                            $newRenjaID=uniqid ('uid');
                            $newrenja = $renja->replicate();
                            $newrenja->RenjaID = $newRenjaID;
                            $newrenja->Sasaran_Uraian4 = $newrenja->Sasaran_Uraian3;
                            $newrenja->Sasaran_Angka4 = $newrenja->Sasaran_Angka3;
                            $newrenja->Target4 = $newrenja->Target3;
                            $newrenja->NilaiUsulan4 = $newrenja->NilaiUsulan3;
                            $newrenja->EntryLvl = 3;
                            $newrenja->Status = 0;
                            $newrenja->Privilege = 0;
                            $newrenja->RenjaID_Src = $RenjaID;
                            $newrenja->created_at = \Carbon\Carbon::now();
                            $newrenja->updated_at = \Carbon\Carbon::now();
                            $newrenja->save();
                        }
                        $str_rinciankegiatan = '
                            INSERT INTO "trRenjaRinc90" (
                                "RenjaRincID", 
                                "RenjaID",
                                "UsulanKecID",
                                "PMProvID",
                                "PmKotaID",
                                "PmKecamatanID",
                                "PmDesaID",
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Uraian2",              
                                "Sasaran_Uraian3",              
                                "Sasaran_Uraian4",              
                                "Sasaran_Angka1",
                                "Sasaran_Angka2",               
                                "Sasaran_Angka3",               
                                "Sasaran_Angka4",               
                                "Target1",
                                "Target2",                      
                                "Target3",                      
                                "Target4",                      
                                "Jumlah1", 
                                "Jumlah2", 
                                "Jumlah3", 
                                "Jumlah4", 
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
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Uraian2",
                                "Sasaran_Uraian3",
                                "Sasaran_Uraian3" AS Sasaran_Uraian4,              
                                "Sasaran_Angka1",
                                "Sasaran_Angka2",
                                "Sasaran_Angka3",
                                "Sasaran_Angka3" AS "Sasaran_Angka4",               
                                "Target1",
                                "Target2",
                                "Target3",
                                "Target3" AS "Target4",                      
                                "Jumlah1", 
                                "Jumlah2", 
                                "Jumlah3", 
                                "Jumlah3" AS "Jumlah4", 
                                "isReses",
                                "isReses_Uraian",
                                "isSKPD",
                                0 AS "Status",
                                3 AS "EntryLvl",
                                "Prioritas",
                                "Descr",
                                "TA",
                                "RenjaRincID" AS "RenjaID_Src",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaRinc90" 
                            WHERE "RenjaRincID"=\''.$RenjaRincID.'\'  AND
                                ("Status"=1 OR "Status"=2) AND
                                "Privilege"=0              
                        ';
                        \DB::statement($str_rinciankegiatan);       
                        
                        //hapus indikator kinerja  
                        \DB::statement('DELETE FROM "trRenjaIndikator90" WHERE "RenjaID"=\''.$newRenjaID.'\'');      
                        $str_kinerja='
                            INSERT INTO "trRenjaIndikator90" (
                                "RenjaIndikatorID", 
                                "IndikatorKinerjaID",
                                "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",                                  
                                "Descr",
                                "TA",
                                "created_at", 
                                "updated_at"
                            )
                            SELECT 
                                REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaIndikatorID",
                                "IndikatorKinerjaID",
                                \''.$newRenjaID.'\' AS "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",
                                "Descr",
                                "TA",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaIndikator90" 
                            WHERE 
                                "RenjaID"=\''.$RenjaID.'\'
                        ';
                        \DB::statement($str_kinerja);
                        
                        //update NilaiUsulan4
                        $newRenja = RenjaModel::find($newRenjaID);
                        $newRenja->NilaiUsulan4 = \DB::table('trRenjaRinc90')
                                                    ->where('RenjaID',$newRenjaID)
                                                    ->sum('Jumlah4');
                        $newRenja->save();

                        RenjaRincianModel::where('RenjaRincID',$RenjaRincID)
                                            ->update(['Privilege'=>1,'updated_at'=>\Carbon\Carbon::now()]);
                        RenjaIndikatorModel::where('RenjaID',$RenjaID)
                                            ->update(['updated_at'=>\Carbon\Carbon::now()]);
                        
                    break; //end pembahasanforumopd
                    case 'pembahasanmusrenkab' :
                        //check renja id sudah ada belum di RenjaID_Old
                        $old_renja = RenjaModel::select('RenjaID')
                                        ->where('RenjaID_Src',$RenjaID)
                                        ->get()
                                        ->pluck('RenjaID')->toArray();

                        if (count($old_renja) > 0)
                        {
                            $newRenjaID=$old_renja[0];
                        }
                        else
                        {
                            $renja = RenjaModel::find($RenjaID);   
                            $renja->Privilege=1;
                            $renja->save();

                            // #new renja
                            $newRenjaID=uniqid ('uid');
                            $newrenja = $renja->replicate();
                            $newrenja->RenjaID = $newRenjaID;
                            $newrenja->Sasaran_Uraian5 = $newrenja->Sasaran_Uraian4;
                            $newrenja->Sasaran_Angka5 = $newrenja->Sasaran_Angka4;
                            $newrenja->Target5 = $newrenja->Target4;
                            $newrenja->NilaiUsulan5 = $newrenja->NilaiUsulan4;
                            $newrenja->EntryLvl = 4;
                            $newrenja->Status = 0;
                            $newrenja->Privilege = 0;
                            $newrenja->RenjaID_Src = $RenjaID;
                            $newrenja->created_at = \Carbon\Carbon::now();
                            $newrenja->updated_at = \Carbon\Carbon::now();
                            $newrenja->save();
                        }
                        $str_rinciankegiatan = '
                            INSERT INTO "trRenjaRinc90" (
                                "RenjaRincID", 
                                "RenjaID",
                                "UsulanKecID",
                                "PMProvID",
                                "PmKotaID",
                                "PmKecamatanID",
                                "PmDesaID",
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Uraian2",              
                                "Sasaran_Uraian3",              
                                "Sasaran_Uraian4",                                      
                                "Sasaran_Uraian5",
                                "Sasaran_Angka1",
                                "Sasaran_Angka2",               
                                "Sasaran_Angka3",               
                                "Sasaran_Angka4",               
                                "Sasaran_Angka5",               
                                "Target1",
                                "Target2",                      
                                "Target3",                      
                                "Target4",                      
                                "Target5",                      
                                "Jumlah1", 
                                "Jumlah2", 
                                "Jumlah3", 
                                "Jumlah4", 
                                "Jumlah5", 
                                "isReses",
                                "isReses_Uraian",
                                "isSKPD",
                                "Status",
                                "EntryLvl",
                                "Prioritas",
                                "Descr",
                                "TA",
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
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Uraian2",
                                "Sasaran_Uraian3",
                                "Sasaran_Uraian4",
                                "Sasaran_Uraian4" AS Sasaran_Angka5,              
                                "Sasaran_Angka1",
                                "Sasaran_Angka2",
                                "Sasaran_Angka3",
                                "Sasaran_Angka4",
                                "Sasaran_Angka4" AS "Sasaran_Angka5",               
                                "Target1",
                                "Target2",
                                "Target3",
                                "Target4",
                                "Target4" AS "Target5",                      
                                "Jumlah1", 
                                "Jumlah2", 
                                "Jumlah3", 
                                "Jumlah4", 
                                "Jumlah4" AS "Jumlah5", 
                                "isReses",
                                "isReses_Uraian",
                                "isSKPD",
                                0 AS "Status",
                                4 AS "EntryLvl",
                                "Prioritas",
                                "Descr",
                                "TA",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaRinc90" 
                            WHERE "RenjaRincID"=\''.$RenjaRincID.'\' AND
                                ("Status"=1 OR "Status"=2) AND
                                "Privilege"=0      
                        ';                
                        \DB::statement($str_rinciankegiatan);       

                        //hapus indikator kinerja  
                        \DB::statement('DELETE FROM "trRenjaIndikator90" WHERE "RenjaID"=\''.$newRenjaID.'\'');      
                        $str_kinerja='
                            INSERT INTO "trRenjaIndikator90" (
                                "RenjaIndikatorID", 
                                "IndikatorKinerjaID",
                                "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",  
                                "Descr",
                                "TA",
                                "created_at", 
                                "updated_at"
                            )
                            SELECT 
                                REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaIndikatorID",
                                "IndikatorKinerjaID",
                                \''.$newRenjaID.'\' AS "RenjaID",
                                "Target_Angka",
                                "Target_Uraian",
                                "Descr",
                                "TA",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaIndikator90" 
                            WHERE 
                                "RenjaID"=\''.$RenjaID.'\'
                        ';
                        \DB::statement($str_kinerja);
                                    
                        //update NilaiUsulan5
                        $newRenja = RenjaModel::find($newRenjaID);
                        $newRenja->NilaiUsulan5 = \DB::table('trRenjaRinc90')
                                                    ->where('RenjaID',$newRenjaID)
                                                    ->sum('Jumlah5');
                        $newRenja->save();

                        RenjaRincianModel::where('RenjaRincID',$RenjaRincID)
                                            ->update(['Privilege'=>1,'updated_at'=>\Carbon\Carbon::now()]);
                        RenjaIndikatorModel::where('RenjaID',$RenjaID)
                                            ->update(['updated_at'=>\Carbon\Carbon::now()]);

                    break; //end pembahasanmusrenkab       
                    case 'verifikasirenja' :
                        $tanggal_posting=\Carbon\Carbon::now();
                         #new rkpd
                        $renja=$rincian_kegiatan->renja;  
                        $RKPDID=$renja->RenjaID;

                        $rkpd=RKPDModel::find($RKPDID);
                        if ($rkpd == null)
                        {                    
                            RKPDModel::Create([
                                'RKPDID'=>$RKPDID,   
                                'RenjaID'=>$RKPDID,   
                                'OrgID'=>$renja->OrgID,
                                'SOrgID'=>$renja->SOrgID,
                                'SubKgtID'=>$renja->SubKgtID,
                                'SumberDanaID'=>$renja->SumberDanaID,
                                'NamaIndikator'=>$renja->NamaIndikator,
                                'Sasaran_Uraian1'=>$renja->Sasaran_Uraian5,
                                'Sasaran_Angka1'=>$renja->Sasaran_Angka5,                                                    
                                'NilaiUsulan1'=>$rincian_kegiatan->Jumlah5,                                                    
                                'Target1'=>$renja->Target5,                                                    
                                'Sasaran_AngkaSetelah'=>$renja->Sasaran_AngkaSetelah,
                                'Sasaran_UraianSetelah'=>$renja->Sasaran_UraianSetelah,
                                'NilaiSetelah'=>$renja->NilaiSetelah,
                                'NilaiSebelum'=>$renja->NilaiSebelum,
                                'Tgl_Posting'=>$tanggal_posting,
                                'Descr'=>$renja->Descr,
                                'TA'=>$renja->TA,
                                'Status'=>1, //artinya sudah disetujui di rkpd murni
                                'Status_Indikator'=>$renja->Status_Indikator,
                                'EntryLvl'=>1,//artinya di level RKPD
                                'Privilege'=>0,//artinya dianggap sudah diproses                                    
                            ]);
                        } 
                        else
                        {
                            $NilaiUsulan1=(RKPDRincianModel::where('RKPDID',$RKPDID)->sum('NilaiUsulan1'))+$rincian_kegiatan->Jumlah5;
                            $rkpd->NilaiUsulan1=$NilaiUsulan1;
                            $rkpd->save();
                        }       

                        //kondisi awal saat di transfer ke RKPD adalah entrillvl = 1 (RKPD)
                        $str_rincianrenja = '
                            INSERT INTO "trRKPDRinc90" (
                                "RKPDRincID",
                                "RKPDID", 
                                "PMProvID",
                                "PmKotaID",
                                "PmKecamatanID",
                                "PmDesaID",
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "UsulanKecID",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian1",
                                "Sasaran_Angka1",                        
                                "NilaiUsulan1",                       
                                "Target1",                        
                                "Tgl_Posting",                         
                                "isReses",
                                "isReses_Uraian",
                                "isSKPD",
                                "Descr",
                                "TA",
                                "Status",
                                "EntryLvl",
                                "Privilege",                   
                                "created_at", 
                                "updated_at"
                            ) 
                            SELECT 
                                "RenjaRincID" AS "RKPDRincID",
                                \''.$RKPDID.'\' AS "RKPDID",
                                "PMProvID",
                                "PmKotaID",
                                "PmKecamatanID",
                                "PmDesaID",
                                "Lokasi",
                                "Latitude",
                                "Longitude",
                                "UsulanKecID",
                                "PokPirID",
                                "Uraian",
                                "No",
                                "Sasaran_Uraian5" AS "Sasaran_Uraian1",
                                "Sasaran_Angka5" AS "Sasaran_Angka1",        
                                "Jumlah5" AS "NilaiUsulan1",       
                                "Target5" AS "Target1",                                             
                                \''.$tanggal_posting.'\' AS Tgl_Posting,
                                "isReses",
                                "isReses_Uraian",
                                "isSKPD",
                                "Descr",
                                "TA",
                                1 AS "Status", 
                                1 AS "EntryLvl",
                                0 AS "Privilege",  
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaRinc90" 
                            WHERE "RenjaRincID"=\''.$rincian_kegiatan->RenjaRincID.'\' AND
                                ("Status"=1 OR "Status"=2) AND
                                "Privilege"=0  
                        ';
                        \DB::statement($str_rincianrenja); 
                        
                        //hapus indikator kinerja  
                        \DB::statement('DELETE FROM "trRKPDIndikator90" WHERE "RKPDID"=\''.$RKPDID.'\'');
                        $str_kinerja='
                            INSERT INTO "trRKPDIndikator90" (
                                "RKPDIndikatorID", 
                                "RKPDID",
                                "IndikatorKinerjaID",                        
                                "Target_Angka",
                                "Target_Uraian",  
                                "Descr",
                                "TA",
                                "created_at", 
                                "updated_at"
                            )
                            SELECT 
                                REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RKPDIndikatorID",
                                \''.$RKPDID.'\' AS "RKPDID",
                                "IndikatorKinerjaID",                        
                                "Target_Angka",
                                "Target_Uraian",
                                "Descr",
                                "TA",
                                NOW() AS created_at,
                                NOW() AS updated_at
                            FROM 
                                "trRenjaIndikator90" 
                            WHERE 
                                "RenjaID"=\''.$renja->RenjaID.'\'
                        ';
                        \DB::statement($str_kinerja);
                        
                        //rincian renja finish
                        $rincian_kegiatan->Privilege=1;
                        $rincian_kegiatan->updated_at=\Carbon\Carbon::now();                        
                        $rincian_kegiatan->save();

                        //renja finish
                        $renja->Privilege=1;
                        $renja->updated_at=\Carbon\Carbon::now();
                        $renja->Status=1;
                        
                        $renja->save();
                        
                    break; //end verifikasi renja
                }
                return $rincian_kegiatan;
            });            

            if ($request->ajax()) 
            {
                $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession($this->SessionName);
                $data = $this->populateData($currentpage);                
                
                $datatable = view("pages.$theme.rkpd.pembahasanrenja.datatable")->with(['page_active'=>$this->NameOfPage, 
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                            
                                                                                        'label_transfer'=>$this->LabelTransfer,
                                                                                        'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                        'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                        'data'=>$data])->render();
                return response()->json([
                    'success'=>true,
                    'message'=>'Data ini telah berhasil ditransfer ke tahap '.$this->LabelTransfer,
                    'datatable'=>$datatable
                ],200);
            }
            else
            {
                return redirect(route(\Helper::getNameOfPage('showrincian'),['uuid'=>$rincian_kegiatan->RenjaRincID]))->with('success','Data ini telah berhasil disimpan.');
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
                return redirect(route(\Helper::getNameOfPage('showrincian'),['uuid'=>$rinciankegiatan->RenjaRincID]))->with('error','Data ini gagal diubah.');
            }
        }
    }
}