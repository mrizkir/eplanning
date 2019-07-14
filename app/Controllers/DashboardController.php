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
                return view("pages.{$theme}.dashboard.indexDewan")->with(['page_active'=>'dashboard',
                                                                                    'data'=>$data]);    
            break;
            case 'kecamatan' :                                       
                return view("pages.{$theme}.dashboard.indexKecamatan")->with(['page_active'=>'dashboard',
                                                                                    'data'=>$data]);    
            break;
            case 'desa' :                                       
                return view("pages.{$theme}.dashboard.indexDesa")->with(['page_active'=>'dashboard',
                                                                                    'data'=>$data]);    
            break;
        }      
        
        
    }    
}