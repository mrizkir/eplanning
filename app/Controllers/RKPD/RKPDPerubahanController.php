<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\RKPDPerubahanModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;
use App\Models\RKPD\RKPDPerubahanIndikatorModel;

class RKPDPerubahanController extends Controller {
    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|opd']);
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
        
        $data = RKPDPerubahanIndikatorModel::join('trIndikatorKinerja','trIndikatorKinerja.IndikatorKinerjaID','trRKPDIndikator.IndikatorKinerjaID')
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
        if (!$this->checkStateIsExistSession('rkpdperubahan','orderby')) 
        {            
           $this->putControllerStateSession('rkpdperubahan','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rkpdperubahan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rkpdperubahan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('rkpdperubahan','filters')) 
        {            
            $this->putControllerStateSession('rkpdperubahan','filters',[
                                                                    'OrgID'=>'none',
                                                                    'SOrgID'=>'none',
                                                                    ]);
        }        
        $SOrgID= $this->getControllerStateSession('rkpdperubahan.filters','SOrgID');        

        if ($this->checkStateIsExistSession('rkpdperubahan','search')) 
        {
            $search=$this->getControllerStateSession('rkpdperubahan','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = RKPDPerubahanModel::where(['kode_kegiatan'=>$search['isikriteria']])                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('eplanning.tahun_penyerapan'))
                                                    ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = RKPDPerubahanModel::where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('eplanning.tahun_penyerapan'))
                                                    ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = RKPDPerubahanModel::where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('eplanning.tahun_penyerapan'))
                                                    ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RKPDPerubahanModel::where('SOrgID',$SOrgID)                                                                                      
                                            ->where('TA', config('eplanning.tahun_penyerapan'))                                            
                                            ->orderBy($column_order,$direction)                                            
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }        
        $data->setPath(route('rkpdperubahan.index'));                  
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

        $filters=$this->getControllerStateSession('rkpdperubahan','filters');
        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('rkpdperubahan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.rkpdperubahan.datatable")->with(['page_active'=>'rkpdperubahan',
                                                                                'filters'=>$filters,
                                                                                'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
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

        $filters=$this->getControllerStateSession('rkpdperubahan','filters');
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
        $this->putControllerStateSession('rkpdperubahan','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rkpdperubahan');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.rkpdperubahan.datatable")->with(['page_active'=>'rkpdperubahan',
                                                                                    'filters'=>$filters,
                                                                                    'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
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
        $filters=$this->getControllerStateSession('rkpdperubahan','filters');

        $this->setCurrentPageInsideSession('rkpdperubahan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.rkpdperubahan.datatable")->with(['page_active'=>'rkpdperubahan',
                                                                            'filters'=>$filters,
                                                                            'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
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

        $filters=$this->getControllerStateSession('rkpdperubahan','filters');
        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('rkpdperubahan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rkpdperubahan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rkpdperubahan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.rkpdperubahan.datatable")->with(['page_active'=>'rkpdperubahan',     
                                                            'filters'=>$filters,
                                                            'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
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

        $filters=$this->getControllerStateSession('rkpdperubahan','filters');
        $daftar_unitkerja=[];
        $json_data = [];

        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('eplanning.tahun_penyerapan'),false,$OrgID);  
            
            $this->putControllerStateSession('rkpdperubahan','filters',$filters);

            $data = [];            
            $datatable = view("pages.$theme.rkpd.rkpdperubahan.datatable")->with(['page_active'=>'rkpdperubahan', 
                                                                                    'filters'=>$filters,                                                           
                                                                                    'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
                                                                                    'data'=>$data])->render();

            $totalpaguindikatifopd = RKPDRincianModel::getTotalPaguIndikatifByStatusAndOPD(config('eplanning.tahun_penyerapan'),\HelperKegiatan::getLevelEntriByName('rkpdperubahan'),$filters['OrgID']);            
                  
            $totalpaguindikatifunitkerja[0]=0;
            $totalpaguindikatifunitkerja[1]=0;
            $totalpaguindikatifunitkerja[2]=0;
            $totalpaguindikatifunitkerja[3]=0;  

            $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                        ->where('OrgID',$filters['OrgID'])
                                                                        ->value('Jumlah1');
            $json_data = ['success'=>true,
                        'paguanggaranopd'=>$paguanggaranopd,
                        'daftar_unitkerja'=>$daftar_unitkerja,
                        'totalpaguindikatifopd'=>$totalpaguindikatifopd,
                        'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,
                        'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('rkpdperubahan','filters',$filters);
            $this->setCurrentPageInsideSession('rkpdperubahan',1);

            $data = $this->populateData();                        
            $datatable = view("pages.$theme.rkpd.rkpdperubahan.datatable")->with(['page_active'=>'rkpdperubahan',  
                                                                            'filters'=>$filters,                                                          
                                                                            'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
                                                                            'data'=>$data])->render();                                                                                       

            $totalpaguindikatifunitkerja = RKPDRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(config('eplanning.tahun_penyerapan'),\HelperKegiatan::getLevelEntriByName('rkpdperubahan'),$filters['SOrgID']);            

            $json_data = ['success'=>true,
                        'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,
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

        $filters=$this->getControllerStateSession('rkpdperubahan','filters');
        $roles=$auth->getRoleNames();        
        switch ($roles[0])
        {
            case 'superadmin' :                 
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('eplanning.tahun_penyerapan'),false);  
                $daftar_unitkerja=array();           
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('eplanning.tahun_penyerapan'),false,$filters['OrgID']);        
                }    
            break;
            case 'opd' :
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('eplanning.tahun_penyerapan'),false,NULL,$auth->OrgID);  
                $filters['OrgID']=$auth->OrgID;                
                if (empty($auth->SOrgID)) 
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('eplanning.tahun_penyerapan'),false,$auth->OrgID);  
                    $filters['SOrgID']=empty($filters['SOrgID'])?'':$filters['SOrgID'];                    
                }   
                else
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('eplanning.tahun_penyerapan'),false,$auth->OrgID,$auth->SOrgID);
                    $filters['SOrgID']=$auth->SOrgID;
                }                
                $this->putControllerStateSession('rkpdperubahan','filters',$filters);
            break;
        }

        $search=$this->getControllerStateSession('rkpdperubahan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rkpdperubahan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rkpdperubahan',$data->currentPage());

        $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                    ->where('OrgID',$filters['OrgID'])                                                    
                                                                    ->value('Jumlah1');
                                                                    
        return view("pages.$theme.rkpd.rkpdperubahan.index")->with(['page_active'=>'rkpdperubahan',
                                                                'daftar_opd'=>$daftar_opd,
                                                                'daftar_unitkerja'=>$daftar_unitkerja,
                                                                'filters'=>$filters,
                                                                'search'=>$this->getControllerStateSession('rkpdperubahan','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('rkpdperubahan.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('rkpdperubahan.orderby','order'),
                                                                'paguanggaranopd'=>$paguanggaranopd,
                                                                'totalpaguindikatifopd'=>RKPDRincianModel::getTotalPaguIndikatifByStatusAndOPD(config('eplanning.tahun_penyerapan'),\HelperKegiatan::getLevelEntriByName('rkpdperubahan'),$filters['OrgID']),
                                                                'totalpaguindikatifunitkerja' => RKPDRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(config('eplanning.tahun_penyerapan'),\HelperKegiatan::getLevelEntriByName('rkpdperubahan'),$filters['SOrgID']),            
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
            return view("pages.$theme.rkpd.rkpdperubahan.show")->with(['page_active'=>'rkpdperubahan',
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
        $OrgID=$filters=$this->getControllerStateSession('rkpdperubahan','filters.OrgID');        
        
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
        
        $report= new \App\Models\Report\ReportRKPDPerubahanModel ($data_report);
        return $report->download("rkpd_perubahan_$generate_date.xlsx");
    }
}