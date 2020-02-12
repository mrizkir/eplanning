<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\RKPDViewJudulModel;
use App\Models\RKPD\RKPDViewRincianModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;
use App\Models\RKPD\RKPDMurniIndikatorModel;

class RKPDMurniController extends Controller {
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
        $data = RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID",
                                                "trRKPDRinc"."RKPDID",
                                                "trRKPDRinc"."UsulanKecID",
                                                "Nm_Kecamatan",
                                                "trRKPDRinc"."Uraian",
                                                "trRKPDRinc"."No",
                                                "trRKPDRinc"."Sasaran_Angka1",
                                                "trRKPDRinc"."Sasaran_Uraian1",
                                                "trRKPDRinc"."Target1",
                                                "trRKPDRinc"."NilaiUsulan1",
                                                "trRKPDRinc"."Status",
                                                "trRKPDRinc"."Privilege",
                                                "trRKPDRinc"."Descr",
                                                "isSKPD",
                                                "isReses",
                                                "isReses_Uraian",
                                                "trRKPDRinc"."Descr"'))
                                ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRKPDRinc.PmKecamatanID')
                                ->leftJoin('trPokPir','trPokPir.PokPirID','trRKPDRinc.PokPirID')
                                ->leftJoin('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                ->where('trRKPDRinc.EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                ->where('RKPDID',$RKPDID)
                                ->get();
    
        return $data;
    }
    private function populateIndikatorKegiatan($RKPDID)
    {
        
        $data = RKPDMurniIndikatorModel::join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRKPDIndikator.IndikatorKinerjaID')
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
        if (!$this->checkStateIsExistSession('rkpdmurni','orderby')) 
        {            
           $this->putControllerStateSession('rkpdmurni','orderby',['column_name'=>'kode_subkegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rkpdmurni.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rkpdmurni.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        $SOrgID= $this->getControllerStateSession('rkpdmurni.filters','SOrgID');        

        if ($this->checkStateIsExistSession('rkpdmurni','search')) 
        {
            $search=$this->getControllerStateSession('rkpdmurni','search');
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
                case 'kode_subkegiatan' :
                    $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                                ->where('SOrgID',$SOrgID)                                            
                                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())    
                                                ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                                ->where(['kode_subkegiatan'=>$search['isikriteria']])                                                                                             
                                                ->orderBy($column_order,$direction);                                       
                break;
                case 'SubKgtNm' :                                                    
                    $data = RKPDViewRincianModel::select(\HelperKegiatan::getField($this->NameOfPage))
                                                ->where('SOrgID',$SOrgID)                                            
                                                ->where('TA', \HelperKegiatan::getTahunPerencanaan())    
                                                ->where('EntryLvl',\HelperKegiatan::getLevelEntriByName($this->NameOfPage))                                  
                                                ->where('SubKgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
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
        $data->setPath(route('rkpdmurni.index'));                  
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

        $filters=$this->getControllerStateSession('rkpdmurni','filters');
        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('rkpdmurni',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni',
                                                                                'filters'=>$filters,
                                                                                'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
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

        $filters=$this->getControllerStateSession('rkpdmurni','filters');
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
            case 'col-Sasaran_Angka4' :
                $column_name = 'Sasaran_Angka4';
            break;  
            case 'col-Jumlah4' :
                $column_name = 'Jumlah4';
            break;
            default :
                $column_name = 'kode_subkegiatan';
        }
        $this->putControllerStateSession('rkpdmurni','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rkpdmurni');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni',
                                                                                    'filters'=>$filters,
                                                                                    'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
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
        $filters=$this->getControllerStateSession('rkpdmurni','filters');

        $this->setCurrentPageInsideSession('rkpdmurni',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni',
                                                                            'filters'=>$filters,
                                                                            'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
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

        $filters=$this->getControllerStateSession('rkpdmurni','filters');
        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('rkpdmurni','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rkpdmurni','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rkpdmurni',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni',     
                                                            'filters'=>$filters,
                                                            'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
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

        $filters=$this->getControllerStateSession('rkpdmurni','filters');
        $daftar_unitkerja=[];
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$OrgID);  
            
            $this->putControllerStateSession('rkpdmurni','filters',$filters);

            $data = [];            
            $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni', 
                                                                                    'filters'=>$filters,                                                           
                                                                                    'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
                                                                                    'data'=>$data])->render();

            $totalpaguopd = RKPDRincianModel::getTotalPaguByOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']);            
            
            $totalpaguunitkerja['murni']=0;

            $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                        ->where('OrgID',$filters['OrgID'])
                                                                        ->value('Jumlah1');
            
            $json_data = ['success'=>true,'paguanggaranopd'=>$paguanggaranopd,'totalpaguopd'=>$totalpaguopd,'totalpaguunitkerja'=>$totalpaguunitkerja,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('rkpdmurni','filters',$filters);
            $this->setCurrentPageInsideSession('rkpdmurni',1);

            $data = $this->populateData();                        
            $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni',  
                                                                            'filters'=>$filters,                                                          
                                                                            'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
                                                                            'data'=>$data])->render();                                                                                       

            $totalpaguunitkerja = RKPDRincianModel::getTotalPaguByUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']);            

            $json_data = ['success'=>true,'totalpaguunitkerja'=>$totalpaguunitkerja,'datatable'=>$datatable];    
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
        if (!$this->checkStateIsExistSession('rkpdmurni','filters')) 
        {            
            $this->putControllerStateSession('rkpdmurni','filters',[
                                                                    'OrgID'=>'none',
                                                                    'SOrgID'=>'none',
                                                                    ]);
        }        
        $filters=$this->getControllerStateSession('rkpdmurni','filters');
        
        $roles=$auth->getRoleNames();        
        $daftar_unitkerja=[];
        switch ($roles[0])
        {
            case 'superadmin' :                 
            case 'bapelitbang' :                 
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);  
                $daftar_unitkerja=array();           
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

                    return view("pages.$theme.rkpd.rkpdmurni.error")->with(['page_active'=>$this->NameOfPage, 
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                        'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                        ]);
                }        
            break;
        }

        $search=$this->getControllerStateSession('rkpdmurni','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rkpdmurni'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rkpdmurni',$data->currentPage());
        
        $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                    ->where('OrgID',$filters['OrgID'])                                                    
                                                                    ->value('Jumlah1');
        
        return view("pages.$theme.rkpd.rkpdmurni.index")->with(['page_active'=>'rkpdmurni',
                                                                'daftar_opd'=>$daftar_opd,
                                                                'daftar_unitkerja'=>$daftar_unitkerja,
                                                                'filters'=>$filters,
                                                                'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
                                                                'paguanggaranopd'=>$paguanggaranopd,
                                                                'totalpaguopd'=>RKPDRincianModel::getTotalPaguByOPD(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['OrgID']),
                                                                'totalpaguunitkerja' => RKPDRincianModel::getTotalPaguByUnitKerja(\HelperKegiatan::getTahunPerencanaan(),\HelperKegiatan::getLevelEntriByName($this->NameOfPage),$filters['SOrgID']),            
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

        $rkpd = RKPDViewJudulModel::select(\DB::raw('"RKPDID",
                                            "Kd_Urusan",
                                            "Nm_Urusan",
                                            "Kd_Bidang",
                                            "Nm_Bidang",
                                            "kode_organisasi",
                                            "OrgNm",
                                            "kode_suborganisasi",
                                            "SOrgNm",
                                            "Kd_Prog",
                                            "PrgNm",
                                            "Kd_SubKeg",
                                            "kode_subkegiatan",
                                            "SubKgtNm",
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
                                            "Privilege",
                                            "Status",
                                            "EntryLvl",
                                            "created_at",
                                            "updated_at"
                                            '))                            
                            ->findOrFail($id);
        if (!is_null($rkpd) )  
        {            
            $datarinciankegiatan = $this->populateRincianKegiatan($id);
            return view("pages.$theme.rkpd.rkpdmurni.show")->with(['page_active'=>'rkpdmurni',
                                                                        'rkpd'=>$rkpd,
                                                                        'datarinciankegiatan'=>$datarinciankegiatan
                                                                    ]);
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

        $filters=$this->getControllerStateSession('rkpdmurni','filters');    
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

            $report= new \App\Models\Report\ReportRKPDMurniModel ($data_report);
            return $report->download("rkpd_$generate_date.xlsx");
        }
        elseif ($OrgID != 'none'&&$OrgID != ''&&$OrgID != null)       
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
            $data_report['SOrgID']=$SOrgID;
            $data_report['NamaKepalaSKPD']=$opd->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$opd->NIPKepalaSKPD;            
            $report= new \App\Models\Report\ReportRKPDMurniModel ($data_report);
            return $report->download("rkpd_$generate_date.xlsx");
        }
        else
        {
            return view("pages.$theme.rkpd.rkpdmurni.error")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu. bila sudah terpilih ternyata tidak bisa, berarti saudara tidak diperkenankan menambah kegiatan karena telah dikunci.'
                                                                ]);  
        }
    }
}