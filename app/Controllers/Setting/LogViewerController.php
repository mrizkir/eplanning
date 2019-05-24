<?php

namespace App\Controllers\Setting;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Setting\Logs\LogViewerModel;

class LogViewerController extends Controller 
{
    /**
     * @var LogViewer
     */
    private $log_viewer;

    /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin']);

        $this->log_viewer = new LogViewerModel();
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;    
        $data = $this->populateData();            
        return view("pages.$theme.setting.logviewer.index")->with(['page_active'=>'logviewer',
                                                                'search'=>$this->getControllerStateSession('logviewer','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('logviewer.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('logviewer.orderby','order'),
                                                                'data'=>$data]);               
    }  
}