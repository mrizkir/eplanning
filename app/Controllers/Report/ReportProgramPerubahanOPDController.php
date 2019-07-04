<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;

use App\Models\RKPD\RKPDViewRincianModel;
use App\Models\RKPD\RKPDIndikatorModel;
use App\Models\RKPD\RKPDModel;
use App\Models\RKPD\RKPDRincianModel;


class ReportProgramPerubahanOPDController extends Controller 
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
            $this->putControllerStateSession($this->SessionName,'filters',$filters);
            
            $datatable = view("pages.$theme.report.reportprogramperubahanopd.datatable")->with(['page_active'=>$this->NameOfPage,   
                                                                                            'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),                                                                                                                                    
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
            break;
            case 'opd' :               
                $daftar_opd=\App\Models\UserOPD::getOPD(false); 
            break;
        }
        return view("pages.$theme.report.reportprogramperubahanopd.index")->with(['page_active'=>$this->NameOfPage, 
                                                                                'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                'daftar_opd'=>$daftar_opd,
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
        if ($OrgID != 'none'&&$OrgID != ''&&$OrgID != null)       
        {
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
            $report= new \App\Models\Report\ReportProgramRKPDPerubahanModel ($data_report);
            return $report->download("programrkpdp_$generate_date.xlsx");
        }
        else
        {
            return view("pages.$theme.report.reportprogramperubahanopd.error")->with(['page_active'=>$this->NameOfPage,
                                                                                        'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                                        'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu. bila sudah terpilih ternyata tidak bisa, berarti saudara tidak diperkenankan menambah kegiatan karena telah dikunci.'
                                                                                    ]);  
        }     
    }
}