<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\Musrenbang\UsulanMusrenKabModel;
use App\Models\RKPD\RenjaIndikatorModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;

class UsulanController extends Controller 
{
     /**
     * nama session
     */
    private $PageTitle;
    /**
     * nama session
     */
    private $SessionName;

     /**
     * nama dari halaman saat ini 
     */
    private $NameOfPage;
    /**
     * nama session
     */
    private $LabelTransfer;
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|opd']);  
        //set nama session 
        $this->SessionName=$this->getNameForSession();      
        //set nama halaman saat ini
        $this->NameOfPage = \Helper::getNameOfPage();
    }
    private function getPageTitle()
    {
        switch ($this->NameOfPage) 
        {            
            case 'usulanprarenjaopd' :
                $pagetitle = 'USULAN PRA RENJA OPD/SKPD';
            break;
            case 'usulanrakorbidang' :
                $pagetitle = 'v_usulan_rakor_bidang';
            break;
            case 'usulanforumopd' :
                $pagetitle = 'v_usulan_forum_opd';
            break;
            case 'usulanmusrenkab' :
                $pagetitle = 'USULAN MUSRENBANG KABUPATEN';
            break;
            default :
                $pagetitle = 'WORKFLOW';
        }
        return $pagetitle;
    }
    /**
     * digunakan untuk mendapatkan nama view db
     */
    private function getViewName ()
    {
        switch ($this->NameOfPage) 
        {            
            case 'usulanprarenjaopd' :
                $dbViewName = 'v_usulan_pra_renja_opd';
            break;
            case 'usulanrakorbidang' :
                $dbViewName = 'v_usulan_rakor_bidang';
            break;
            case 'usulanforumopd' :
                $dbViewName = 'v_usulan_forum_opd';
            break;
            case 'usulanmusrenkab' :
                $dbViewName = 'v_usulan_musren_kab';
            break;
            default :
                $dbViewName = null;
        }
        return $dbViewName;
    }   
    private function populateRincianKegiatan($RenjaID)
    {
        switch ($this->NameOfPage) 
        {
            case 'usulanprarenjaopd' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."RenjaID","trRenjaRinc"."UsulanKecID","Nm_Kecamatan","trRenjaRinc"."Uraian","trRenjaRinc"."No","trRenjaRinc"."Sasaran_Angka1","trRenjaRinc"."Sasaran_Uraian1","trRenjaRinc"."Target1","trRenjaRinc"."Jumlah1","trRenjaRinc"."Status","trRenjaRinc"."Privilege","trRenjaRinc"."Prioritas","isSKPD","isReses","isReses_Uraian","trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',0);
            break;
            case 'usulanrakorbidang' :
                $data = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."RenjaID","trRenjaRinc"."UsulanKecID","Nm_Kecamatan","trRenjaRinc"."Uraian","trRenjaRinc"."No","trRenjaRinc"."Sasaran_Angka2","trRenjaRinc"."Sasaran_Uraian2","trRenjaRinc"."Target2","trRenjaRinc"."Jumlah2","trRenjaRinc"."Status","trRenjaRinc"."Privilege","trRenjaRinc"."Prioritas","isSKPD","isReses","isReses_Uraian","trRenjaRinc"."Descr"'))
                                        ->where('trRenjaRinc.EntryLvl',1);  
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
                case 'kode_kegiatan' :
                    $data = \DB::table($this->getViewName())
                                ->where(['kode_kegiatan'=>$search['isikriteria']])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = \DB::table($this->getViewName())
                                ->where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data =  \DB::table($this->getViewName())
                                    ->where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
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
            $data = \DB::table($this->getViewName())
                            ->where('SOrgID',$SOrgID)                                            
                            ->where('TA', config('globalsettings.tahun_perencanaan'))                                            
                            ->orderBy('Prioritas','ASC')
                            ->orderBy($column_order,$direction)                                            
                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);
    }        
        $data->setPath(route(\Helper::getNameOfPage('index')));                  
        return $data;
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
                $this->putControllerStateSession($this->SessionName,'filters',$filters);
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

        return view("pages.$theme.rkpd.usulan.index")->with(['page_active'=>$this->NameOfPage, 
                                                            'page_title'=>$this->getPageTitle(),                                                                           
                                                            'daftar_opd'=>$daftar_opd,
                                                            'daftar_unitkerja'=>$daftar_unitkerja,
                                                            'filters'=>$filters,
                                                            'search'=>$this->getControllerStateSession('usulanmusrenkab','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('usulanmusrenkab.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usulanmusrenkab.orderby','order'),
                                                            'data'=>$data]);
    }
    
}