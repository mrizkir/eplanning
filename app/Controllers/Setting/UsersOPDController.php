<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Rules\IgnoreIfDataIsEqualValidation;

class UsersOPDController extends Controller {
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
                    $data = User::role('opd')->where('username', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
                case 'nama' :
                    $data = User::role('opd')->where('name', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
                case 'email' :
                    $data = User::role('opd')->where('email', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = User::role('opd')->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
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
        
        $this->setCurrentPageInsideSession('usersopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.usersopd.datatable")->with(['page_active'=>'usersopd',
                                                                                'search'=>$this->getControllerStateSession('usersopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('usersopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('usersopd.orderby','order'),
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
            case 'col-OrgNm' :
                $column_name = 'OrgNm';
            break;
            case 'col-SOrgNm' :
                $column_name = 'SOrgNm';
            break;  
            default :
                $column_name = 'id';
        }
        $this->putControllerStateSession('usersopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.usersopd.datatable")->with(['page_active'=>'usersopd',
                                                            'search'=>$this->getControllerStateSession('usersopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('usersopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usersopd.orderby','order'),
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

        $this->setCurrentPageInsideSession('usersopd',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.setting.usersopd.datatable")->with(['page_active'=>'usersopd',
                                                                            'search'=>$this->getControllerStateSession('usersopd','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('usersopd.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('usersopd.orderby','order'),
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
            $this->destroyControllerStateSession('usersopd','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('usersopd','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('usersopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.usersopd.datatable")->with(['page_active'=>'usersopd',                                                            
                                                            'search'=>$this->getControllerStateSession('usersopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('usersopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usersopd.orderby','order'),
                                                            'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $json_data = [];
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');            
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$OrgID);  
            
            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja];
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
        $theme = \Auth::user()->theme;

        $search=$this->getControllerStateSession('usersopd','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('usersopd'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('usersopd',$data->currentPage());
        
        return view("pages.$theme.setting.usersopd.index")->with(['page_active'=>'usersopd',
                                                'search'=>$this->getControllerStateSession('usersopd','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('usersopd.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('usersopd.orderby','order'),
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
        $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false);

        $daftar_theme = $this->listOfthemes;             
        return view("pages.$theme.setting.usersopd.create")->with(['page_active'=>'usersopd',
                                                                    'daftar_opd'=>$daftar_opd,
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
            'OrgID'=>'required',
        ]);
        $OrgID=$request->input('OrgID');
        $SOrgID=$request->input('SOrgID');
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>\Hash::make($request->input('password')),
            'OrgID'=> $OrgID,
            'OrgNm'=> \App\Models\DMaster\OrganisasiModel::find($OrgID)->OrgNm,
            'SOrgID'=> $SOrgID,
            'SOrgNm'=> \App\Models\DMaster\SubOrganisasiModel::getNamaUnitKerjaByID($request->input('SOrgID')),
            'email_verified_at'=>\Carbon\Carbon::now(),
            'theme'=> $request->input('theme'),
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);                    
        $user->assignRole('opd');        
        if ($request->input('do_sync')==1)
        {
            $user->syncPermissions($user->getPermissionsViaRoles()->pluck('name')->toArray());
        }    
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usersopd.show',['id'=>$user->id]))->with('success','Data ini telah berhasil disimpan.');
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
            return view("pages.$theme.setting.usersopd.show")->with(['page_active'=>'usersopd',
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
            $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false);
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$data->OrgID);
            $daftar_theme = $this->listOfthemes;   
            return view("pages.$theme.setting.usersopd.edit")->with(['page_active'=>'usersopd',
                                                                    'daftar_opd'=>$daftar_opd,
                                                                    'daftar_unitkerja'=>$daftar_unitkerja,
                                                                    'daftar_theme'=>$daftar_theme,
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
        $user = User::find($id);

        $this->validate($request, [
            'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$user->username)],           
            'name'=>'required',            
            'email'=>'required|string|email|unique:users,email,'.$id,              
            'OrgID'=>'required',
        ]);        
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        if (!empty(trim($request->input('password')))) {
            $user->password = \Hash::make($request->input('password'));
        }    
        $user->OrgID = $request->input('OrgID');
        $user->OrgNm =\App\Models\DMaster\OrganisasiModel::find($request->input('OrgID'))->OrgNm;
        $user->SOrgID = $request->input('SOrgID');
        $user->SOrgNm = \App\Models\DMaster\SubOrganisasiModel::getNamaUnitKerjaByID($request->input('SOrgID'));
        $user->theme = $request->input('theme');
        $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();        
        $user->save();

        $user->syncRoles('opd');
        if ($request->input('do_sync')==1)
        {
            $user->syncPermissions($user->getPermissionsViaRoles()->pluck('name')->toArray());
        }
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('usersopd.show',['id'=>$user->id]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $usersopd = User::find($id);
        $result=$usersopd->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('usersopd'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.setting.usersopd.datatable")->with(['page_active'=>'usersopd',
                                                            'search'=>$this->getControllerStateSession('usersopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('usersopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usersopd.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('usersopd.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}