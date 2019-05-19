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

        $data = RekapPaguIndikatifOPDModel::where('TA',config('globalsettings.tahun_perencanaan'))
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
        RekapPaguIndikatifOPDModel::where('TA',config('globalsettings.tahun_perencanaan'))->delete();

        $str_rincianrenja = '
            INSERT INTO "trRekapPaguIndikatifOPD" (
                "OrgID",
                "Kode_Organisasi", 
                "OrgNm",
                "Jumlah1",
                "Jumlah2",
                "prarenja1",
                "prarenja2",
                "rakorbidang1",
                "rakorbidang2",
                "forumopd1",
                "forumopd2",
                "musrenkab1",                        
                "musrenkab2",                        
                "renjafinal1",                        
                "renjafinal2",                         
                "rkpd1",
                "rkpd2",
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
					D."Jumlah1"
				END AS "Jumlah2",
                0 AS "prarenja1",
                0 AS "prarenja2",
                0 AS "rakorbidang1",
                0 AS "rakorbidang2",
                0 AS "forumopd1",
                0 AS "forumopd2",
                0 AS "musrenkab1",                        
                0 AS "musrenkab2",                        
                0 AS "renjafinal1",                        
                0 AS "renjafinal2",                         
                0 AS "rkpd1",
                0 AS "rkpd2",
                \'inisialisasi awal\' AS Desc,
                A."TA", 
                NOW() AS created_at,
                NOW() AS updated_at
            FROM "tmOrg" A 
                JOIN "tmUrs" B ON A."UrsID"=B."UrsID"
                JOIN "tmKUrs" C ON B."KUrsID"=C."KUrsID"		
                LEFT JOIN "tmPaguAnggaranOPD" D  ON A."OrgID"=D."OrgID"
            WHERE 
                A."TA"='.config('globalsettings.tahun_perencanaan').' 
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
        $ta = config('globalsettings.tahun_perencanaan');
        switch($id)
        {
            case 'uidPrarenja' :
                $data = \DB::table('trRenjaRinc')
                            ->select(\DB::raw('"trRenja"."OrgID", SUM("trRenjaRinc"."Jumlah1") AS jumlah'))
                            ->join('trRenja','trRenjaRinc.RenjaID','trRenja.RenjaID')                            
                            ->where('trRenjaRinc.EntryLvl',0)                
                            ->where('trRenjaRinc.Status',1)                                      
                            ->orWhere('trRenjaRinc.Status',2)                                      
                            ->where('trRenjaRinc.TA',$ta)
                            ->groupBy('trRenja.OrgID')->get()->pluck('jumlah','OrgID')->toArray();


                foreach ($data as $k=>$v)
                {
                    \DB::table('trRekapPaguIndikatifOPD')->where('OrgID',$k)->update(['prarenja1'=>$v,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidRakorBidang' :
                $data = \DB::table('trRenjaRinc')
                            ->select(\DB::raw('"trRenja"."OrgID", SUM("trRenjaRinc"."Jumlah2") AS jumlah'))
                            ->join('trRenja','trRenjaRinc.RenjaID','trRenja.RenjaID')                            
                            ->where('trRenjaRinc.EntryLvl',1)                        
                            ->where('trRenjaRinc.Status',1)                                      
                            ->orWhere('trRenjaRinc.Status',2)                                                                    
                            ->where('trRenjaRinc.TA',$ta)
                            ->groupBy('trRenja.OrgID')->get()->pluck('jumlah','OrgID')->toArray();


                foreach ($data as $k=>$v)
                {
                    \DB::table('trRekapPaguIndikatifOPD')->where('OrgID',$k)->update(['rakorbidang1'=>$v,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidForumOPD' :
                $data = \DB::table('trRenjaRinc')
                            ->select(\DB::raw('"trRenja"."OrgID", SUM("trRenjaRinc"."Jumlah3") AS jumlah'))
                            ->join('trRenja','trRenjaRinc.RenjaID','trRenja.RenjaID')                            
                            ->where('trRenjaRinc.EntryLvl',2)                      
                            ->where('trRenjaRinc.Status',1)                                      
                            ->orWhere('trRenjaRinc.Status',2)                                                                      
                            ->where('trRenjaRinc.TA',$ta)
                            ->groupBy('trRenja.OrgID')->get()->pluck('jumlah','OrgID')->toArray();
                            
                foreach ($data as $k=>$v)
                {
                    \DB::table('trRekapPaguIndikatifOPD')->where('OrgID',$k)->update(['forumopd1'=>$v,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;            
            case 'uidMusrenKab' :
                $data = \DB::table('trRenjaRinc')
                            ->select(\DB::raw('"trRenja"."OrgID", SUM("trRenjaRinc"."Jumlah4") AS jumlah'))
                            ->join('trRenja','trRenjaRinc.RenjaID','trRenja.RenjaID')                            
                            ->where('trRenjaRinc.EntryLvl',3)             
                            ->where('trRenjaRinc.Status',1)                                      
                            ->orWhere('trRenjaRinc.Status',2)                                                                               
                            ->where('trRenjaRinc.TA',$ta)
                            ->groupBy('trRenja.OrgID')->get()->pluck('jumlah','OrgID')->toArray();

                foreach ($data as $k=>$v)
                {
                    \DB::table('trRekapPaguIndikatifOPD')->where('OrgID',$k)->update(['musrenkab1'=>$v,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidRenjaFinal' :
                $data = \DB::table('trRenjaRinc')
                            ->select(\DB::raw('"trRenja"."OrgID", SUM("trRenjaRinc"."Jumlah5") AS jumlah'))
                            ->join('trRenja','trRenjaRinc.RenjaID','trRenja.RenjaID')                            
                            ->where('trRenjaRinc.EntryLvl',4)            
                            ->where('trRenjaRinc.Status',1)                                      
                            ->orWhere('trRenjaRinc.Status',2)                                                                                
                            ->where('trRenjaRinc.TA',$ta)
                            ->groupBy('trRenja.OrgID')->get()->pluck('jumlah','OrgID')->toArray();

                foreach ($data as $k=>$v)
                {
                    \DB::table('trRekapPaguIndikatifOPD')->where('OrgID',$k)->update(['renjafinal1'=>$v,'updated_at'=>\Carbon\Carbon::now()]);
                }
            break;
            case 'uidRKPD' :
                $data = \DB::table('trRKPDRinc')
                            ->select(\DB::raw('"trRKPD"."OrgID", SUM("trRKPDRinc"."NilaiUsulan1") AS jumlah'))
                            ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')                            
                            ->where('trRKPDRinc.EntryLvl',4)  
                            ->where('trRKPDRinc.Status',0)                                      
                            ->orWhere('trRKPDRinc.Status',0)                                                                                          
                            ->where('trRKPDRinc.TA',$ta)
                            ->groupBy('trRKPD.OrgID')->get()->pluck('jumlah','OrgID')->toArray();

                foreach ($data as $k=>$v)
                {
                    \DB::table('trRekapPaguIndikatifOPD')->where('OrgID',$k)->update(['NilaiUsulan1'=>$v,'updated_at'=>\Carbon\Carbon::now()]);
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
}