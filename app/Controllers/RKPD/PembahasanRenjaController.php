<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RenjaIndikatorModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;

class PembahasanRenjaController extends Controller {
    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin']);  
        //set nama session 
        $this->SessionName=$this->getNameForSession();      
        //set nama halaman saat ini
        $this->NameOfPage = \Helper::getNameOfPage();
    }
    private function getPageTitle()
    {
        switch ($this->NameOfPage) 
        {            
            case 'pembahasanprarenjaopd' :
               $pagetitle = 'PEMBAHASAN PRA RENJA OPD/SKPD';
            break;
            case 'pembahasanrakorbidang' :
                $pagetitle = 'PEMBAHASAN RAKOR BIDANG';
            break;
            case 'pembahasanforumopd' :
                $pagetitle = 'PEMBAHASAN FORUM OPD / SKPD';
            break;
            case 'pembahasanmusrenkab' :
                $pagetitle = 'PEMBAHASAN MUSRENBANG KABUPATEN';                
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
    /**
     * digunakan untuk mendapatkan nama view db
     */
    private function getField ()
    {
        switch ($this->NameOfPage) 
        {            
            case 'usulanprarenjaopd' :
                $rawSql = \DB::raw('"RenjaID","RenjaRincID","UsulanKecID","Nm_Kecamatan","kode_kegiatan","KgtNm","Uraian","Sasaran_Angka1" AS "Sasaran_Angka","Sasaran_Uraian1" AS "Sasaran_Uraian","Target1" AS "Target","Jumlah1" AS "Jumlah","Prioritas","isSKPD","isReses","isReses_Uraian","Status","Privilege","Status_Indikator","Descr"');
            break;
            case 'usulanrakorbidang' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka2" AS "Sasaran_Angka",
                                    "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                    "Target2" AS "Target",
                                    "Jumlah2" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanforumopd' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka3" AS "Sasaran_Angka",
                                    "Sasaran_Uraian3" AS "Sasaran_Uraian",
                                    "Target3" AS "Target",
                                    "Jumlah3" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanmusrenkab' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka4" AS "Sasaran_Angka",
                                    "Sasaran_Uraian4" AS "Sasaran_Uraian",
                                    "Target4" AS "Target",
                                    "Jumlah4" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            default :
                $rawSql = null;
        }        
        return $rawSql;
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
                    $data = \DB::table('v_usulan_musren_kab')
                                ->where('kode_kegiatan',$search['isikriteria'])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = \DB::table('v_usulan_musren_kab')
                                ->where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = \DB::table('v_usulan_musren_kab')
                                ->where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
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
            $data = \DB::table('v_usulan_musren_kab')
                        ->where('SOrgID',$SOrgID)                                     
                        ->whereNotNull('RenjaRincID')       
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
        $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false);  
        $daftar_unitkerja=array();           
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$filters['OrgID']);        
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
                                                                        'page_title'=>$this->getPageTitle(),                                                                            
                                                                        'label_transfer'=>'Verifikasi Renja',
                                                                        'daftar_opd'=>$daftar_opd,
                                                                        'daftar_unitkerja'=>$daftar_unitkerja,
                                                                        'filters'=>$filters,
                                                                        'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                        'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                        'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),
                                                                        'paguanggaranopd'=>$paguanggaranopd,
                                                                        'totalpaguindikatifopd'=>RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(config('globalsettings.tahun_perencanaan'),3,$filters['OrgID']),
                                                                        'totalpaguindikatifunitkerja' => RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(config('globalsettings.tahun_perencanaan'),3,$filters['SOrgID']),            
                                                                        'data'=>$data]);             
    }
}