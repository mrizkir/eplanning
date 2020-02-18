<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;


class ReportKegiatanMusrenKecOPDController extends Controller {    
    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        //set nama halaman saat ini
        $this->middleware(['auth','role:superadmin|bapelitbang|tapd|opd|kecamatan']);
        
        //set nama session 
        $this->SessionName=$this->getNameForSession();    
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

        $roles=$auth->getRoleNames();
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :
            case 'tapd' :  
                $daftar_opd=\DB::table('v_urusan_organisasi')
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('kode_organisasi','ASC')
                                ->get();

            break;  
            case 'opd' :
                $daftar_opd=\DB::table('usersopd')
                                ->join('v_urusan_organisasi','v_urusan_organisasi.OrgID','usersopd.OrgID')
                                ->where('id',$auth->id)  
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('kode_organisasi','ASC')
                                ->get();
                
                if (!count($daftar_opd) > 0)
                {
                    return view("pages.$theme.report.reportkegiatanmusrenkecopd.error")->with(['page_active'=>'reportkegiatanmusrenkecopd', 
                                                                                                'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                            ]);
                }       
            break;
        }
        return view("pages.$theme.report.reportkegiatanmusrenkecopd.index")->with(['page_active'=>'reportkegiatanmusrenkecopd', 
                                                                                        'daftar_opd'=>$daftar_opd,
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
            $data_report['NameOfPage']=$this->NameOfPage;
            $data_report['OrgID']=$id;
            $data_report['OrgNm']=$organisasi->OrgNm;
            $data_report['kode_organisasi']=$organisasi->kode_organisasi;
            $data_report['NamaKepalaSKPD']=$organisasi->NamaKepalaSKPD;
            $data_report['NIPKepalaSKPD']=$organisasi->NIPKepalaSKPD; 
            $data_report['mode']='byorgid';

            $report= new \App\Models\Report\ReportMusrenbangKecamatanModel ($data_report);
            return $report->download("laporan_musrenbang_kecamatan_$generate_date.xlsx");
        }
        else
        {
            return view("pages.$theme.report.reportkegiatanmusrenkecopd.error")->with(['page_active'=>'reportkegiatanmusrenkecopd',
                                                                                        'errormessage'=>"ID OPD ($id) tidak terdaftar di Database",
                                                                                        ]);
        }
    }
   
}