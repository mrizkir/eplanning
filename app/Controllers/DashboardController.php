<?php

namespace App\Controllers;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DashboardModel;

class DashboardController extends Controller {
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
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter (Request $request) 
    {
        $filters=$this->getControllerStateSession('dashboard','filters');
        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $filters['PmKecamatanID']=$PmKecamatanID;
        }   
        $this->putControllerStateSession('dashboard','filters',$filters);  
        return response()->json(['success'=>true],200);         

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
                 $stats = \DB::table('trRekapPaguIndikatifOPD')
                            ->select(\DB::raw('SUM(jumlah_kegiatan6) AS jumlahkegiatan,SUM(rkpd1) AS totalrkpdm,SUM(rkpd2) AS totalrkpdp,SUM("Jumlah1") AS "Jumlah1",SUM("Jumlah2") AS "Jumlah2"'))
                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                            ->first();

                $data = [
                    'jumlahkegiatan'=>$stats->jumlahkegiatan,
                    'pagum'=>$stats->Jumlah1,
                    'pagup'=>$stats->Jumlah2,
                    'totalrkpdm'=>$stats->totalrkpdm,
                    'totalrkpdp'=>$stats->totalrkpdp,
                ];
                return view("pages.{$theme}.dashboard.index")->with(['page_active'=>'dashboard',
                                                                            'data'=>$data]);                       
            break;
            case 'opd' :               
                $daftar_opd=\App\Models\UserOPD::getOPD(false,true);
                $OrgID=[];
                foreach ($daftar_opd as $k=>$v)
                {
                    $OrgID[]=$k;
                }
               
                $stats = \DB::table('trRekapPaguIndikatifOPD')
                                ->select(\DB::raw('SUM(jumlah_kegiatan6) AS jumlahkegiatan,SUM(rkpd1) AS totalrkpdm,SUM(rkpd2) AS totalrkpdp,SUM("Jumlah1") AS "Jumlah1",SUM("Jumlah2") AS "Jumlah2"'))
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->whereIn('OrgID', $OrgID)
                                ->first();
                                
                $data = [
                    'jumlahkegiatan'=>$stats->jumlahkegiatan,
                    'pagum'=>$stats->Jumlah1,
                    'pagup'=>$stats->Jumlah2,
                    'totalrkpdm'=>$stats->totalrkpdm,
                    'totalrkpdp'=>$stats->totalrkpdp,
                ];
                return view("pages.{$theme}.dashboard.indexOPD")->with(['page_active'=>'dashboard',
                                                                            'data'=>$data]);    
            break;
            case 'dewan' :
                $data = [
                    'jumlahkegiatan'=>0,
                    'pagum'=>0,
                    'pagup'=>0,
                    'totalrkpdm'=>0,
                    'totalrkpdp'=>0,
                ];
                return view("pages.{$theme}.dashboard.indexDewan")->with(['page_active'=>'dashboard',
                                                                            'data'=>$data
                                                                        ]);    
            break;
            case 'kecamatan' : 
                //filter
                if (!$this->checkStateIsExistSession('dashboard','filters')) 
                {            
                    $this->putControllerStateSession('dashboard','filters',[
                                                                        'PmKecamatanID'=>'none',
                                                                    ]);
                }        
                $filters=$this->getControllerStateSession('dashboard','filters');
                $PmKecamatanID=$filters['PmKecamatanID'];
                $daftar_kecamatan=\App\Models\UserKecamatan::getKecamatan(); 

                $usulankec = \DB::table('trUsulanKec')
                                    ->select(\DB::raw('COUNT("UsulanKecID") AS jumlahkegiatan,COALESCE(SUM("NilaiUsulan"),0) AS jumlahpagu'))
                                    ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                    ->where('PmKecamatanID',$PmKecamatanID)
                                    ->get()
                                    ->toArray();

                $data = [
                    'jumlahkegiatan'=>$usulankec[0]->jumlahkegiatan,
                    'jumlahpagu'=>$usulankec[0]->jumlahpagu,
                ];                

                $subquery = \DB::table('trUsulanKec')
                ->select(\DB::raw('"PmDesaID",SUM("NilaiUsulan") AS jumlahpagu,COUNT("UsulanKecID") AS jumlahkegiatan'))
                ->where('PmKecamatanID',$PmKecamatanID)
                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                ->groupBy('PmDesaID');
                
                $rekap_desa = \DB::table('tmPmDesa')
                                ->select(\DB::raw('"tmPmDesa"."PmDesaID","tmPmDesa"."Nm_Desa",COALESCE(temp.jumlahkegiatan,0) AS jumlahkegiatan,COALESCE(temp.jumlahpagu,0) AS jumlahpagu'))
                                ->leftJoinSub($subquery,'temp',function($join){
                                    $join->on('tmPmDesa.PmDesaID','=','temp.PmDesaID');
                                })
                                ->where('tmPmDesa.PmKecamatanID',$PmKecamatanID)
                                ->get();
                return view("pages.{$theme}.dashboard.indexKecamatan")->with(['page_active'=>'dashboard',
                                                                                'filters'=>$filters,
                                                                                'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                'rekap_desa'=>$rekap_desa,
                                                                                'data'=>$data
                                                                            ]);    
            break;
            case 'desa' :                   
                $data = [
                    'jumlahkegiatan'=>0,
                    'pagum'=>0,
                    'pagup'=>0,
                    'totalrkpdm'=>0,
                    'totalrkpdp'=>0,
                ];                    
                return view("pages.{$theme}.dashboard.indexDesa")->with(['page_active'=>'dashboard',
                                                                            'data'=>$data
                                                                        ]);    
            break;
        }
        
    }    
}