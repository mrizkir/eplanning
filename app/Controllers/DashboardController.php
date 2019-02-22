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
        $theme = \Auth::user()->theme;
        
        $data = array();
        return view("pages.{$this->theme}.dashboard.index")->with(['page_active'=>'dashboard',
                                                                'data'=>$data]);               
    }    
}