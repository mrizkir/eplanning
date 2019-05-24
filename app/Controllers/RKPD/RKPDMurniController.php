<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\RKPDMurniModel;
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
        $this->middleware(['auth','role:superadmin|opd']);
    }
    private function populateRincianKegiatan($RKPDID)
    {
        $data = RKPDRincianModel::select(\DB::raw('"trRKPDRinc"."RKPDRincID","trRKPDRinc"."RKPDID","trRKPDRinc"."RKPDID","trRKPDRinc"."UsulanKecID","Nm_Kecamatan","trRKPDRinc"."Uraian","trRKPDRinc"."No","trRKPDRinc"."Sasaran_Angka1","trRKPDRinc"."Sasaran_Uraian1","trRKPDRinc"."Target1","trRKPDRinc"."NilaiUsulan1","trRKPDRinc"."Status" AS "Status","trRKPDRinc"."Descr","isSKPD","isReses","isReses_Uraian","trRKPDRinc"."Descr"'))
                                ->leftJoin('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trRKPDRinc.PmKecamatanID')
                                ->leftJoin('trPokPir','trPokPir.PokPirID','trRKPDRinc.PokPirID')
                                ->leftJoin('tmPemilikPokok','tmPemilikPokok.PemilikPokokID','trPokPir.PemilikPokokID')
                                ->where('trRKPDRinc.EntryLvl',5)
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
           $this->putControllerStateSession('rkpdmurni','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rkpdmurni.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rkpdmurni.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('rkpdmurni','filters')) 
        {            
            $this->putControllerStateSession('rkpdmurni','filters',[
                                                                    'OrgID'=>'none',
                                                                    'SOrgID'=>'none',
                                                                    ]);
        }        
        $SOrgID= $this->getControllerStateSession('rkpdmurni.filters','SOrgID');        

        if ($this->checkStateIsExistSession('rkpdmurni','search')) 
        {
            $search=$this->getControllerStateSession('rkpdmurni','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = RKPDMurniModel::where(['kode_kegiatan'=>$search['isikriteria']])                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = RKPDMurniModel::where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = RKPDMurniModel::where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RKPDMurniModel::where('SOrgID',$SOrgID)                                                                                      
                                            ->where('TA', config('globalsettings.tahun_perencanaan'))                                            
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
            case 'col-kode_kegiatan' :
                $column_name = 'kode_kegiatan';
            break;    
            case 'col-KgtNm' :
                $column_name = 'KgtNm';
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
                $column_name = 'kode_kegiatan';
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
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$OrgID);  
            
            $this->putControllerStateSession('rkpdmurni','filters',$filters);

            $data = [];            
            $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni', 
                                                                                    'filters'=>$filters,                                                           
                                                                                    'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
                                                                                    'data'=>$data])->render();

            $json_data = ['success'=>true,
                        'summary'=>$summary,
                        'daftar_unitkerja'=>$daftar_unitkerja,
                        'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('rkpdmurni','filters',$filters);
            $this->setCurrentPageInsideSession('rkpdmurni',1);

            $data = $this->populateData();            
            $summary=$this->populateSummary();
            $datatable = view("pages.$theme.rkpd.rkpdmurni.datatable")->with(['page_active'=>'rkpdmurni',  
                                                                                    'filters'=>$filters,                                                          
                                                                                    'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
                                                                                    'data'=>$data])->render();                                                                                       
                                                                                    
            $json_data = ['success'=>true,
                        'datatable'=>$datatable];    
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

        $filters=$this->getControllerStateSession('rkpdmurni','filters');
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
                $this->putControllerStateSession('rkpdmurni','filters',$filters);
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

        return view("pages.$theme.rkpd.rkpdmurni.index")->with(['page_active'=>'rkpdmurni',
                                                                'daftar_opd'=>$daftar_opd,
                                                                'daftar_unitkerja'=>$daftar_unitkerja,
                                                                'filters'=>$filters,
                                                                'search'=>$this->getControllerStateSession('rkpdmurni','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('rkpdmurni.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('rkpdmurni.orderby','order'),
                                                                'data'=>$data]);               
                     
    }
    /**
     * Edit the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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

        $renja = RKPDModel::select(\DB::raw('"trRKPD"."RKPDID",                            
                            "v_program_kegiatan"."Kd_Urusan",
                            "v_program_kegiatan"."Nm_Urusan",
                            "v_program_kegiatan"."Kd_Bidang",
                            "v_program_kegiatan"."Nm_Bidang",
                            "v_program_kegiatan"."Kd_Prog",
                            "v_program_kegiatan"."PrgNm",
                            "v_program_kegiatan"."kode_kegiatan",
                            "v_program_kegiatan"."KgtNm",
                            "trRKPD"."Sasaran_Angka1",
                            "trRKPD"."Sasaran_Uraian1",
                            "trRKPD"."Sasaran_AngkaSetelah",
                            "trRKPD"."Sasaran_UraianSetelah",
                            "trRKPD"."Target1",
                            "trRKPD"."NilaiUsulan1",
                            "trRKPD"."NamaIndikator",
                            "tmSumberDana"."Nm_SumberDana",
                            "trRKPD"."created_at",
                            "trRKPD"."updated_at"'))
                            ->join('v_program_kegiatan','v_program_kegiatan.KgtID','trRKPD.KgtID')     
                            ->join('tmSumberDana','tmSumberDana.SumberDanaID','trRKPD.SumberDanaID')   
                            ->findOrFail($id);            
        if (!is_null($renja) )  
        {
            $datarinciankegiatan = $this->populateRincianKegiatan($id);
            return view("pages.$theme.rkpd.rkpdmurni.show")->with(['page_active'=>'rkpdmurni',
                                                                        'renja'=>$renja,
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
       
        $generate_date=date('Y-m-d_H_m_s');
        $OrgID=$filters=$this->getControllerStateSession('rkpdmurni','filters.OrgID');        
        
        $opd = \DB::table('v_urusan_organisasi')
                    ->where('OrgID',$OrgID)->first();  
        
        $data_report['OrgID']=$opd->OrgID;
        $data_report['Kd_Urusan']=$opd->Kd_Urusan;
        $data_report['Nm_Urusan']=$opd->Nm_Urusan;
        $data_report['Kd_Bidang']=$opd->Kd_Bidang;
        $data_report['Nm_Bidang']=$opd->Nm_Bidang;
        $data_report['kode_organisasi']=$opd->kode_organisasi;
        $data_report['OrgNm']=$opd->OrgNm;
        $data_report['NamaKepalaSKPD']=$opd->NamaKepalaSKPD;
        $data_report['NIPKepalaSKPD']=$opd->NIPKepalaSKPD;
        
        $report= new \App\Models\Report\ReportRKPDMurniModel ($data_report);
        return $report->download("rkpd_$generate_date.xlsx");
    }
}