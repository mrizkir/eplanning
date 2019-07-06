<?php

namespace App\Controllers\Report;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Report\RekapPaguIndikatifOPDModel;

class RekapPaguIndikatifOPDController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData () 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('rekappaguindikatifopd','orderby')) 
        {            
           $this->putControllerStateSession('rekappaguindikatifopd','orderby',['column_name'=>'Kode_Organisasi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rekappaguindikatifopd.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rekappaguindikatifopd.orderby','order'); 

        $data = RekapPaguIndikatifOPDModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->orderBy($column_order,$direction)
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
            case 'col-kode_organisasi' :
                $column_name = 'Kode_Organisasi';
            break;  
            case 'col-OrgNm' :
                $column_name = 'OrgNm';
            break; 
            case 'col-prarenja1' :
                $column_name = 'prarenja1';
            break; 
            case 'col-rakorbidang1' :
                $column_name = 'rakorbidang1';
            break; 
            case 'col-forumopd1' :
                $column_name = 'forumopd1';
            break; 
            case 'col-musrenkab1' :
                $column_name = 'musrenkab1';
            break; 
            case 'col-rkpd1' :
                $column_name = 'rkpd1';
            break; 
            case 'col-Jumlah1' :
                $column_name = 'Jumlah1';
            break; 

            default :
                $column_name = 'Kode_Organisasi';
        }
        $this->putControllerStateSession('rekappaguindikatifopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);   

        $data=$this->populateData();       
        
        $datatable = view("pages.$theme.report.rekappaguindikatifopd.datatable")->with(['page_active'=>'rekappaguindikatifopd',
                                                            'search'=>$this->getControllerStateSession('rekappaguindikatifopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rekappaguindikatifopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rekappaguindikatifopd.orderby','order'),
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

        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('rekappaguindikatifopd','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rekappaguindikatifopd','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rekappaguindikatifopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.report.rekappaguindikatifopd.datatable")->with(['page_active'=>'rekappaguindikatifopd',                                                            
                                                            'search'=>$this->getControllerStateSession('rekappaguindikatifopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rekappaguindikatifopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rekappaguindikatifopd.orderby','order'),
                                                            'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;

        $search=$this->getControllerStateSession('rekappaguindikatifopd','search');
        $data = $this->populateData();        
        
        return view("pages.$theme.report.rekappaguindikatifopd.index")->with(['page_active'=>'rekappaguindikatifopd',
                                                                                'search'=>$this->getControllerStateSession('rekappaguindikatifopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession('rekappaguindikatifopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rekappaguindikatifopd.orderby','order'),
                                                                                'data'=>$data]);               
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        RekapPaguIndikatifOPDModel::where('TA',\HelperKegiatan::getTahunPerencanaan())->delete();

        $str_rincianrenja = '
            INSERT INTO "trRekapPaguIndikatifOPD" (
                "OrgID",
                "Kode_Organisasi", 
                "OrgNm",
                "Jumlah1",
                "Jumlah2",
                "prarenja1",
                "jumlah_kegiatan1",
                "rakorbidang1",
                "jumlah_kegiatan2",
                "forumopd1",
                "jumlah_kegiatan3",
                "musrenkab1",                        
                "jumlah_kegiatan4",                        
                "renjafinal1",                        
                "jumlah_kegiatan5",                         
                "rkpd1",
                "jumlah_kegiatan6",                         
                "rkpd2",
                "jumlah_kegiatan7",     
                "Descr",
                "TA",                                    
                "created_at", 
                "updated_at"
            ) 
            SELECT
                A."OrgID",
                CONCAT(C."Kd_Urusan",\'.\',B."Kd_Bidang",\'.\',A."OrgCd") AS kode_organisasi,		
                A."OrgNm",
                CASE WHEN D."Jumlah1" IS NULL THEN
					0
				ELSE	
					D."Jumlah1"
				END AS "Jumlah1",
                CASE WHEN D."Jumlah2" IS NULL THEN
					0
				ELSE	
					D."Jumlah2"
				END AS "Jumlah2",
                0 AS "prarenja1",
                0 AS "jumlah_kegiatan1",
                0 AS "rakorbidang1",
                0 AS "jumlah_kegiatan2",
                0 AS "forumopd1",
                0 AS "jumlah_kegiatan3",
                0 AS "musrenkab1",                        
                0 AS "jumlah_kegiatan4",                        
                0 AS "renjafinal1",                        
                0 AS "jumlah_kegiatan5",                         
                0 AS "rkpd1",
                0 AS "jumlah_kegiatan6",                         
                0 AS "rkpd2",
                0 AS "jumlah_kegiatan7",                         
                \'inisialisasi awal\' AS Desc,
                A."TA", 
                NOW() AS created_at,
                NOW() AS updated_at
            FROM "tmOrg" A 
                JOIN "tmUrs" B ON A."UrsID"=B."UrsID"
                JOIN "tmKUrs" C ON B."KUrsID"=C."KUrsID"		
                LEFT JOIN "tmPaguAnggaranOPD" D  ON A."OrgID"=D."OrgID"
            WHERE 
                A."TA"='.\HelperKegiatan::getTahunPerencanaan().' 
            ORDER BY
                kode_organisasi ASC
            
        ';

        \DB::statement($str_rincianrenja); 
       
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('rekappaguindikatifopd.index'))->with('success','Data rekapitulasi berhasil di reload.');
        }

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $theme = \Auth::user()->theme;
        $ta = \HelperKegiatan::getTahunPerencanaan();
        switch($id)
        {
            case 'uidPrarenja' :
                $data = \DB::table('trRenja')
                            ->select(\DB::raw('"OrgID", SUM("NilaiUsulan1") AS jumlah,COUNT("RenjaID") AS jumlah_kegiatan'))
                            ->where('EntryLvl',0)                
                            ->where('Status',1)                                      
                            ->orWhere('Status',2)                                      
                            ->where('TA',$ta)
                            ->groupBy('OrgID')->get();

                foreach ($data as $v)
                {
                    $jumlah = $v->jumlah;
                    $jumlah_kegiatan = $v->jumlah_kegiatan;
                    \DB::table('trRekapPaguIndikatifOPD')
                        ->where('OrgID',$v->OrgID)
                        ->update(['prarenja1'=>$jumlah,'jumlah_kegiatan1'=>$jumlah_kegiatan,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidRakorBidang' :

                $data = \DB::table('trRenja')
                            ->select(\DB::raw('"OrgID", SUM("NilaiUsulan2") AS jumlah,COUNT("RenjaID") AS jumlah_kegiatan'))
                            ->where('EntryLvl',1)                
                            ->where('Status',1)                                      
                            ->orWhere('Status',2)                                      
                            ->where('TA',$ta)
                            ->groupBy('OrgID')->get();

                foreach ($data as $v)
                {
                    $jumlah = $v->jumlah;
                    $jumlah_kegiatan = $v->jumlah_kegiatan;
                    \DB::table('trRekapPaguIndikatifOPD')
                        ->where('OrgID',$v->OrgID)
                        ->update(['rakorbidang1'=>$jumlah,'jumlah_kegiatan2'=>$jumlah_kegiatan,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidForumOPD' :                
                $data = \DB::table('trRenja')
                            ->select(\DB::raw('"OrgID", SUM("NilaiUsulan3") AS jumlah,COUNT("RenjaID") AS jumlah_kegiatan'))
                            ->where('EntryLvl',2)                
                            ->where('Status',1)                                      
                            ->orWhere('Status',2)                                      
                            ->where('TA',$ta)
                            ->groupBy('OrgID')->get();

                foreach ($data as $v)
                {
                    $jumlah = $v->jumlah;
                    $jumlah_kegiatan = $v->jumlah_kegiatan;
                    \DB::table('trRekapPaguIndikatifOPD')
                        ->where('OrgID',$v->OrgID)
                        ->update(['forumopd1'=>$jumlah,'jumlah_kegiatan3'=>$jumlah_kegiatan,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;            
            case 'uidMusrenKab' :                
                $data = \DB::table('trRenja')
                            ->select(\DB::raw('"OrgID", SUM("NilaiUsulan4") AS jumlah,COUNT("RenjaID") AS jumlah_kegiatan'))
                            ->where('EntryLvl',3)                
                            ->where('Status',1)                                      
                            ->orWhere('Status',2)                                      
                            ->where('TA',$ta)
                            ->groupBy('OrgID')->get();

                foreach ($data as $v)
                {
                    $jumlah = $v->jumlah;
                    $jumlah_kegiatan = $v->jumlah_kegiatan;
                    \DB::table('trRekapPaguIndikatifOPD')
                        ->where('OrgID',$v->OrgID)
                        ->update(['musrenkab1'=>$jumlah,'jumlah_kegiatan4'=>$jumlah_kegiatan,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidRenjaFinal' :                
                $data = \DB::table('trRenja')
                            ->select(\DB::raw('"OrgID", SUM("NilaiUsulan4") AS jumlah,COUNT("RenjaID") AS jumlah_kegiatan'))
                            ->where('EntryLvl',4)                
                            ->where('Status',1)                                      
                            ->orWhere('Status',2)                                      
                            ->where('TA',$ta)
                            ->groupBy('OrgID')->get();

                foreach ($data as $v)
                {
                    $jumlah = $v->jumlah;
                    $jumlah_kegiatan = $v->jumlah_kegiatan;
                    \DB::table('trRekapPaguIndikatifOPD')
                        ->where('OrgID',$v->OrgID)
                        ->update(['renjafinal1'=>$jumlah,'jumlah_kegiatan5'=>$jumlah_kegiatan,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidRKPD' :
                $data = \DB::table('trRKPD')
                            ->select(\DB::raw('"OrgID", SUM("NilaiUsulan1") AS jumlah, SUM("NilaiUsulan2") AS jumlah2'))
                            ->where('TA',$ta)
                            ->groupBy('OrgID')->get();
               
                foreach ($data as $v)
                {
                    $jumlah = $v->jumlah;
                    $jumlah2 = $v->jumlah2;
                    \DB::table('trRekapPaguIndikatifOPD')
                        ->where('OrgID',$v->OrgID)
                        ->update(['rkpd1'=>$jumlah,'rkpd2'=>$jumlah2,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
        }        
        
        if ($request->ajax()) 
        {            
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'                
            ],200);            
        }
        else
        {
            return redirect(route('rekappaguindikatifopd.index'))->with('success','Data ini telah berhasil disimpan.');
        }
    }
    /**
     * Print to Excel
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexcel ()
    {       
        $theme = \Auth::user()->theme;

        // $filters=$this->getControllerStateSession($this->SessionName,'filters');    
        // if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)       
        // {
        //     $generate_date=date('Y-m-d_H_m_s');
        //     $OrgID=$filters['OrgID'];        
        //     $SOrgID=$filters['SOrgID'];    
            
        //     $opd = \DB::table('v_urusan_organisasi')
        //                 ->where('OrgID',$OrgID)->first();  
            
        //     $data_report['OrgID']=$opd->OrgID;
        //     $data_report['SOrgID']=$SOrgID;
        //     $data_report['Kd_Urusan']=$opd->Kd_Urusan;
        //     $data_report['Nm_Urusan']=$opd->Nm_Urusan;
        //     $data_report['Kd_Bidang']=$opd->Kd_Bidang;
        //     $data_report['Nm_Bidang']=$opd->Nm_Bidang;
        //     $data_report['kode_organisasi']=$opd->kode_organisasi;
        //     $data_report['OrgNm']=$opd->OrgNm;
        //     $data_report['NamaKepalaSKPD']=$opd->NamaKepalaSKPD;
        //     $data_report['NIPKepalaSKPD']=$opd->NIPKepalaSKPD;
            
        //     $report= new \App\Models\Report\ReportRKPDPerubahanModel ($data_report);
        //     return $report->download("rkpdp_$generate_date.xlsx");
        // }
        // else
        // {            
        //     return view("pages.$theme.report.reportrkpdperubahanopd.error")->with(['page_active'=>$this->NameOfPage,
        //                                                                         'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
        //                                                                         'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu.'
        //                                                                     ]);  
        // }
    }
}