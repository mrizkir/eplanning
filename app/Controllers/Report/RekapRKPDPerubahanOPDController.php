<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RKPDPerubahanModel;
use App\Models\RKPD\RKPDIndikatorModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;


class RekapRKPDPerubahanOPDController extends Controller 
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
          //filter
        if (!$this->checkStateIsExistSession($this->SessionName,'filters')) 
        {            
            $this->putControllerStateSession($this->SessionName,'filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $SOrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'SOrgID');        

    
        $data = RKPDPerubahanModel::select(\DB::raw('
                                    "Kd_Urusan",
                                    "Kd_Bidang",
                                    "OrgCd",
                                    "OrgCd",
                                    "Kd_Prog",
                                    "Kd_Keg",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka2" AS "Sasaran_Angka",
                                    "Sasaran_Angka1",
                                    "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                    "Sasaran_Uraian1",
                                    "Target2" AS "Target",
                                    "Target1",
                                    "NilaiUsulan1" AS "Jumlah",
                                    "NilaiUsulan2" AS "Jumlah2",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "EntryLvl",
                                    "Descr"'))
                                    ->where('SOrgID',$SOrgID)   
                                    ->whereNotNull('RKPDRincID')                                         
                                    ->where('TA', \HelperKegiatan::getTahunPerencanaan())   
                                    ->orderBy('Kd_Prog','ASC')
                                    ->orderBy('kode_kegiatan','ASC')                                                                                                                  
                                    ->get();
                        
        
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

        $data=$this->populateData();
       
        $datatable = view("pages.$theme.report.rekaprkpdperubahanopd.datatable")->with(['page_active'=>$this->NameOfPage,
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

            $datatable = view("pages.$theme.report.rekaprkpdperubahanopd.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                            'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                            'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                            'data'=>$data])->render();

            

            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession($this->SessionName,'filters',$filters);
            
            $data = $this->populateData();            
            $datatable = view("pages.$theme.report.rekaprkpdperubahanopd.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                                'data'=>$data])->render();                                                                                       
                        
            
            $json_data = ['success'=>true,'datatable'=>$datatable];            
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
                $daftar_opd=\App\Models\UserOPD::where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                ->where('id',$auth->id)
                                                ->pluck('OrgNm','OrgID');      
                $daftar_unitkerja=array();      
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
                }  
            break;
        }        
        $data = $this->populateData();        
        
        return view("pages.$theme.report.rekaprkpdperubahanopd.index")->with(['page_active'=>$this->NameOfPage, 
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'daftar_opd'=>$daftar_opd,
                                                                    'daftar_unitkerja'=>$daftar_unitkerja,
                                                                    'filters'=>$filters,
                                                                    'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                    'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),                                                                    
                                                                    'data'=>$data]);
    }   
}