<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class CopyDataController extends Controller 
{
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        if (!$this->checkStateIsExistSession('copydata','filters')) 
        {            
            $this->putControllerStateSession('copydata','filters',['TA'=>\HelperKegiatan::getTahunPerencanaan()]);
        }
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

        $TA = $request->input('TA')==''?'none':$request->input('TA');

        return response()->json(['TA'=>$TA],200);  
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;
        $this->populateData();
        $TA= $this->getControllerStateSession('copydata.filters','TA'); 
        return view("pages.$theme.setting.copydata.index")->with(['page_active'=>'copydata',
                                                                'TA'=>$TA           
                                                                ]);               
    }
}