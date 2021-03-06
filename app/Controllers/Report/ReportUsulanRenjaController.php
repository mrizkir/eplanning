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
    public function populateData ()
    {
        $auth = \Auth::user();    
        $roles=$auth->getRoleNames();
        
        $daftar_opd=[];
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :
            case 'tapd' :  
                $daftar_opd=\DB::table('v_urusan_organisasi')
                                ->select(\DB::raw('v_urusan_organisasi."OrgID",v_urusan_organisasi.kode_organisasi,v_urusan_organisasi."OrgNm","tmPaguAnggaranOPD"."Jumlah1","tmPaguAnggaranOPD"."Jumlah2"'))
                                ->leftJoin('tmPaguAnggaranOPD','tmPaguAnggaranOPD.OrgID','v_urusan_organisasi.OrgID')
                                ->where('v_urusan_organisasi.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('kode_organisasi','ASC')
                                ->get(); 
                
            break;  
            case 'opd' :
                $daftar_opd=\DB::table('usersopd')
                                ->select(\DB::raw('v_urusan_organisasi."OrgID",v_urusan_organisasi.kode_organisasi,v_urusan_organisasi."OrgNm","tmPaguAnggaranOPD"."Jumlah1","tmPaguAnggaranOPD"."Jumlah2"'))
                                ->join('v_urusan_organisasi','v_urusan_organisasi.OrgID','usersopd.OrgID')
                                ->leftJoin('tmPaguAnggaranOPD','tmPaguAnggaranOPD.OrgID','v_urusan_organisasi.OrgID')
                                ->where('id',$auth->id)  
                                ->where('v_urusan_organisasi.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('kode_organisasi','ASC')
                                ->get(); 

            break;
        }        
        return $daftar_opd;
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
        //index
        if ($request->exists('status_kegiatan'))
        {
            $status = $request->input('status_kegiatan');
            $filters['status_kegiatan']=$status;

            $this->putControllerStateSession($this->SessionName,'filters',$filters);

            $daftar_opd=$this->populateData();
           
            return view("pages.$theme.report.reportusulanrenja.datatable")->with([
                                                                                    'page_active'=>$this->NameOfPage, 
                                                                                    'daftar_opd'=>$daftar_opd,                                                                                    
                                                                                    'filters'=>$filters,
                                                                                ])->render();       
        }
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
                                                                            'status_kegiatan'=>-1,                                                                            
                                                                        ]);
        }  
        $daftar_opd=$this->populateData();
        $filters=$this->getControllerStateSession($this->SessionName,'filters');
        
        $status_kegiatan['none']='SELURUH STATUS KEGIATAN';
        $status_kegiatan=$status_kegiatan+\HelperKegiatan::getStatusKegiatan();
        return view("pages.$theme.report.reportusulanrenja.index")->with(['page_active'=>$this->NameOfPage, 
                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                            
                                                                        'filters'=>$filters,
                                                                        'label_transfer'=>$this->LabelTransfer,
                                                                        'daftar_opd'=>$daftar_opd,
                                                                        'status_kegiatan'=>$status_kegiatan
                                                                    ]);            
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
            $filters=$this->getControllerStateSession($this->SessionName,'filters');
            $data_report['NameOfPage']=$this->NameOfPage;
            $data_report['OrgID']=$id;
            $data_report['OrgNm']=$organisasi->OrgNm;
            $data_report['kode_organisasi']=$organisasi->kode_organisasi;
            $data_report['NamaKepalaSKPD']=$organisasi->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$organisasi->NIPKepalaSKPD; 
            $data_report['SOrgID']='none';
            $data_report['status_kegiatan']=$filters['status_kegiatan'];
            switch ($this->NameOfPage) 
            {            
                case 'reportusulanprarenjaopd' :
                    $data_report['sheetname']='PRA Renja OPD';
                    $data_report['EntryLvl']=0;
                    
                    $filename="usulanprarenja_$generate_date.xlsx";
                break;
                case 'reportrakorbidang' :
                    $data_report['sheetname']='RAKOR Bidang';
                    $data_report['EntryLvl']=1;
                    
                    $filename="rakorbidang_$generate_date.xlsx";
                break;
                case 'reportforumopd' :
                    $data_report['sheetname']='Forum OPD';
                    $data_report['EntryLvl']=2;
                    
                    $filename="forumopd_$generate_date.xlsx";
                break;
                case 'reportmusrenkab' :
                    $data_report['sheetname']='Musren Kab';
                    $data_report['EntryLvl']=3;
                    
                    $filename="musrenkab_$generate_date.xlsx";
                break;
                case 'reportverifikasitapd' :
                    $data_report['sheetname']='Verifikasi TAPD';
                    $data_report['EntryLvl']=4;
                    
                    $filename="verifikasitapd_$generate_date.xlsx";
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
            $filters=$this->getControllerStateSession($this->SessionName,'filters');
            $data_report['NameOfPage']=$this->NameOfPage;
            $data_report['OrgID']=$id;
            $data_report['OrgNm']=$organisasi->OrgNm;
            $data_report['kode_organisasi']=$organisasi->kode_organisasi;
            $data_report['NamaKepalaSKPD']=$organisasi->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$organisasi->NIPKepalaSKPD; 
            $data_report['SOrgID']='none';
            $data_report['status_kegiatan']=$filters['status_kegiatan'];

            switch ($this->NameOfPage) 
            {            
                case 'reportusulanprarenjaopd' :
                    $data_report['sheetname']='PRA Renja OPD';
                    $data_report['EntryLvl']=0;
                    
                    $filename="usulanprarenja_$generate_date.xlsx";
                break;
                case 'reportrakorbidang' :
                    $data_report['sheetname']='RAKOR Bidang';
                    $data_report['EntryLvl']=1;
                    
                    $filename="rakorbidang_$generate_date.xlsx";
                break;
                case 'reportforumopd' :
                    $data_report['sheetname']='Forum OPD';
                    $data_report['EntryLvl']=2;
                    
                    $filename="forumopd_$generate_date.xlsx";
                break;
                case 'reportmusrenkab' :
                    $data_report['sheetname']='Musren Kab';
                    $data_report['EntryLvl']=3;
                    
                    $filename="musrenkab_$generate_date.xlsx";
                break;
                case 'reportverifikasitapd' :
                    $data_report['sheetname']='Verifikasi TAPD';
                    $data_report['EntryLvl']=4;
                    
                    $filename="verifikasitapd_$generate_date.xlsx";
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
}