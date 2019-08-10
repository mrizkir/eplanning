<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RKPDViewRincianModel;
use App\Models\RKPD\RKPDIndikatorModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;


class ReportRKPDMurniOPDRinciController extends Controller 
{    
    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|opd|tapd']);
        //set nama session 
        $this->SessionName=$this->getNameForSession();      
        //set nama halaman saat ini
        $this->NameOfPage = \Helper::getNameOfPage();        
    }  
    public function orderby ()
    {
        
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
            
            $datatable = view("pages.$theme.report.reportrkpdmurniopdrinci.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                            'filters'=>$filters,                                                                                                                                                        
                                                                            ])->render();

            

            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession($this->SessionName,'filters',$filters);
            
            $datatable = view("pages.$theme.report.reportrkpdmurniopdrinci.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
                                                                                'search'=>$this->getControllerStateSession($this->SessionName,'search'),
                                                                                'filters'=>$filters,
                                                                               ])->render();                                                                                       
                        
            
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
                $daftar_opd=\App\Models\UserOPD::getOPD();    
                $daftar_unitkerja=array();                                     
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$filters['OrgID']);        
                }                   
            break;
        }
        return view("pages.$theme.report.reportrkpdmurniopdrinci.index")->with(['page_active'=>$this->NameOfPage, 
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                'daftar_opd'=>$daftar_opd,
                                                                                'daftar_unitkerja'=>$daftar_unitkerja,
                                                                                'filters'=>$filters,
                                                                                'column_order'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'column_name'),
                                                                                'direction'=>$this->getControllerStateSession(\Helper::getNameOfPage('orderby'),'order'),                                                                    
                                                                            ]);
    }   
    /**
     * Print to Excel
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexcel ()
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
            return view("pages.$theme.report.reportrkpdmurniopdrinci.error")->with(['page_active'=>$this->NameOfPage,
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu. bila sudah terpilih ternyata tidak bisa, berarti saudara tidak diperkenankan menambah kegiatan karena telah dikunci.'
                                                                ]);  
        }     
    }
}