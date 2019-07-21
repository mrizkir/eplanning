<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\User;

class UsersKecamatanController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang']);  
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('usersopd','orderby')) 
        {            
           $this->putControllerStateSession('usersopd','orderby',['column_name'=>'id','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('usersopd.orderby','column_name'); 
        $direction=$this->getControllerStateSession('usersopd.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('usersopd','search')) 
        {
            $search=$this->getControllerStateSession('usersopd','search');
            switch ($search['kriteria']) 
            {
                case 'id' :
                    $data = User::role('opd')->where(['users.id'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'username' :
                    $data = User::role('opd')->where('username', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
                case 'nama' :
                    $data = User::role('opd')->where('name', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
                case 'email' :
                    $data = User::role('opd')->where('email', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = User::role('desa')->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }                
        $data->setPath(route('usersopd.index'));
        return $data;
    }
    /**
     * digunakan untuk mengganti jumlah record per halaman
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changenumberrecordperpage (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('userskecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.userskecamatan.datatable")->with(['page_active'=>'userskecamatan',
                                                                                'search'=>$this->getControllerStateSession('userskecamatan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('userskecamatan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('userskecamatan.orderby','order'),
                                                                                'data'=>$data])->render();      
        return response()->json(['success'=>true,'datatable'=>$datatable],200);
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
            case 'col-id' :
                $column_name = 'id';
            break;                
            case 'col-username' :
                $column_name = 'username';
            break; 
            case 'col-nama' :
                $column_name = 'nama';
            break; 
            case 'col-email' :
                $column_name = 'email';
            break;  
            case 'col-Nm_Desa' :
                $column_name = 'Nm_Desa';
            break;           
            default :
                $column_name = 'id';
        }
        $this->putControllerStateSession('userskecamatan','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('userskecamatan');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.setting.userskecamatan.datatable")->with(['page_active'=>'userskecamatan',
                                                            'search'=>$this->getControllerStateSession('userskecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('userskecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('userskecamatan.orderby','order'),
                                                            'data'=>$data])->render();     

        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * paginate resource in storage called by ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paginate ($id) 
    {
        $theme = \Auth::user()->theme;

        $this->setCurrentPageInsideSession('userskecamatan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.setting.userskecamatan.datatable")->with(['page_active'=>'userskecamatan',
                                                                            'search'=>$this->getControllerStateSession('userskecamatan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('userskecamatan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('userskecamatan.orderby','order'),
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
            $this->destroyControllerStateSession('userskecamatan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('userskecamatan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('userskecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.userskecamatan.datatable")->with(['page_active'=>'userskecamatan',                                                            
                                                            'search'=>$this->getControllerStateSession('userskecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('userskecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('userskecamatan.orderby','order'),
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

        $search=$this->getControllerStateSession('userskecamatan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('userskecamatan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('userskecamatan',$data->currentPage());
        
        return view("pages.$theme.setting.userskecamatan.index")->with(['page_active'=>'userskecamatan',
                                                'search'=>$this->getControllerStateSession('userskecamatan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('userskecamatan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('userskecamatan.orderby','order'),
                                                'data'=>$data]);               
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;
        $daftar_theme = $this->listOfthemes;             
        $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),null,false);         
        return view("pages.$theme.setting.userskecamatan.create")->with(['page_active'=>'userskecamatan',
                                                                    'daftar_desa'=>$daftar_desa,
                                                                    'daftar_theme'=>$daftar_theme
                                                                    ]);  
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'username'=>'required|string|unique:users',
            'password'=>'required',            
            'PmDesaID'=>'required',
        ]);
        $PmDesaID=$request->input('PmDesaID');
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $user=User::create ([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>\Hash::make($request->input('password')),
            'PmDesaID'=> $PmDesaID,
            'Nm_Desa'=> \App\Models\DMaster\DesaModel::find($PmDesaID)->Nm_Desa,            
            'email_verified_at'=>\Carbon\Carbon::now(),
            'theme'=> $request->input('theme'),
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);        
        
        $user->assignRole('desa');        
        if ($request->input('do_sync')==1)
        {
            $user->syncPermissions($user->getPermissionsViaRoles()->pluck('name')->toArray());
        }    

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ],200);
        }
        else
        {
            return redirect(route('userskecamatan.show',['id'=>$user->ID]))->with('success','Data ini telah berhasil disimpan.');
        }

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

        $data = User::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.setting.userskecamatan.show")->with(['page_active'=>'userskecamatan',
                                                    'data'=>$data
                                                    ]);
        }        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $theme = \Auth::user()->theme;
        
        $data = User::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.setting.userskecamatan.edit")->with(['page_active'=>'userskecamatan',
                                                    'data'=>$data
                                                    ]);
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
        $userskecamatan = User::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $userskecamatan->replaceit = $request->input('replaceit');
        $userskecamatan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ],200);
        }
        else
        {
            return redirect(route('userskecamatan.show',['id'=>$userskecamatan->replaceit]))->with('success','Data ini telah berhasil disimpan.');
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $theme = \Auth::user()->theme;
        
        $userskecamatan = User::find($id);
        $result=$userskecamatan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('userskecamatan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.setting.userskecamatan.datatable")->with(['page_active'=>'userskecamatan',
                                                            'search'=>$this->getControllerStateSession('userskecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('userskecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('userskecamatan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('userskecamatan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}