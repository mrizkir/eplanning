<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersController extends Controller {
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
     * dapatkan daftar roles
     */
    private function getDaftarRoles($withPreprend=false)
    {
        if ($withPreprend)
        {
            $daftar_roles=Role::all()->pluck('name', 'name')->prepend('DAFTAR ROLES', 'none')->toArray();
        }
        else
        {
            $daftar_roles=Role::all()->pluck('name', 'name')->toArray();
            
        }
        return $daftar_roles;
        
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) {        
        $columns=['*'];       
        //orderby
        if (!$this->checkStateIsExistSession('users','orderby')) 
        {            
            $this->putControllerStateSession('users','orderby',['column_name'=>'id','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('users.orderby','column_name'); 
        $direction=$this->getControllerStateSession('users.orderby','order'); 

        //number of record
        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');       

        //filter
        if (!$this->checkStateIsExistSession('users','filters')) 
        {            
            $this->putControllerStateSession('users','filters',['role_id'=>'none']);
        }
        $filter_role_id=$this->getControllerStateSession('users.filters','role_id'); 
 
        if ($this->checkStateIsExistSession('users','search')) 
        {
            $search=$this->getControllerStateSession('users','search');
            switch ($search['kriteria']) 
            {
                case 'id' :
                    $data = User::with('roles:name')->where(['users.id'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'username' :
                    $data = User::with('roles:name')->where('username', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
                case 'nama' :
                    $data = User::with('roles:name')->where('name', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
                case 'email' :
                    $data = User::with('roles:name')->where('email', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {

            $data = $filter_role_id == 'none' ? 
                                            User::with('roles:name')->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage)             
                                            :
                                            User::whereHas('roles', function ($q){
                                                $q->where('name', $this->getControllerStateSession('users.filters','role_id'));
                                            })
                                            ->orderBy($column_order,$direction)
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }   

        $data->setPath(route('users.index'));
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
        
        $this->setCurrentPageInsideSession('users',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.users.datatable")->with(['page_active'=>'users',
                                                                            'search'=>$this->getControllerStateSession('users','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('users.orderby','order'),                                                                                
                                                                            'daftar_roles'=>$this->getDaftarRoles(true),                                             
                                                                            'filter_role_selected'=>$this->getControllerStateSession('users.filters','role_id'),
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
            default :
                $column_name = 'id';
        }
        $this->putControllerStateSession('users','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();
        
        $datatable = view("pages.$theme.setting.users.datatable")->with(['page_active'=>'users',
                                                            'search'=>$this->getControllerStateSession('users','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('users.orderby','order'), 
                                                            'daftar_roles'=>$this->getDaftarRoles(true),                                             
                                                            'filter_role_selected'=>$this->getControllerStateSession('users.filters','role_id'),                                                           
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

        $this->setCurrentPageInsideSession('users',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.setting.users.datatable")->with(['page_active'=>'users',
                                                                            'search'=>$this->getControllerStateSession('users','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('users.orderby','order'),                                                                            
                                                                            'daftar_roles'=>$this->getDaftarRoles(true),                                             
                                                                            'filter_role_selected'=>$this->getControllerStateSession('users.filters','role_id'),
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
            $this->destroyControllerStateSession('users','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('users','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('users',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.users.datatable")->with(['page_active'=>'users',                                                            
                                                            'search'=>$this->getControllerStateSession('users','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('users.orderby','order'),  
                                                            'daftar_roles'=>$this->getDaftarRoles(true),                                             
                                                            'filter_role_selected'=>$this->getControllerStateSession('users.filters','role_id'),                                                          
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

        $this->setCurrentPageInsideSession('users',1);

        $role_id = $request->input('role_id');
        $this->putControllerStateSession('users','filters',['role_id'=>$role_id]);
        
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.users.datatable")->with(['page_active'=>'users',
                                                                            'search'=>$this->getControllerStateSession('users','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('users.orderby','order'),                                                                                
                                                                            'daftar_roles'=>$this->getDaftarRoles(true),                                             
                                                                            'filter_role_selected'=>$this->getControllerStateSession('users.filters','role_id'),
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

        $search=$this->getControllerStateSession('users','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('users'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $this->setCurrentPageInsideSession('users',$data->currentPage());
        
        return view("pages.$theme.setting.users.index")->with(['page_active'=>'users',
                                                'search'=>$this->getControllerStateSession('users','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('users.orderby','order'),   
                                                'daftar_roles'=>$this->getDaftarRoles(true),                                             
                                                'filter_role_selected'=>$this->getControllerStateSession('users.filters','role_id'),
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
        
        $daftar_roles=$this->getDaftarRoles();
        $daftar_theme = \File::directories(public_path());    
        return view("pages.$theme.setting.users.create")->with(['page_active'=>'users',
                                                                'daftar_roles'=>$daftar_roles
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
            'role_name'=>'required',
        ]);
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>\Hash::make($request->input('password')),
            'email_verified_at'=>\Carbon\Carbon::now(),
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);            
        $roles=$request->input('role_name');   
        $user->assignRole($roles);        
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
            return redirect(route('users.index'))->with('success','Data ini telah berhasil disimpan.');
        }

    }
    /**
     * Store a user permission resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeuserpermission(Request $request)
    {      
        $post = $request->all();
        $permissions = isset($post['chkpermission'])?$post['chkpermission']:[];
        $id = $post['user_id'];

        $user = User::find($id);
        $user->syncPermissions($permissions);
        
        return redirect(route('users.show',$id))->with('success','Permission user ('.$user->name.') berhasil diproses');
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

        $user = User::find($id);
        if (!is_null($user) )  
        {             
            $permission_selected = $user->permissions->pluck('name','id')->toArray();                        
            return view("pages.$theme.setting.users.show")->with(['page_active'=>'users',
                                                                    'data'=>$user,
                                                                    'data_permission'=>$user->getPermissionsViaRoles(),
                                                                    'permission_selected'=>$permission_selected
                                                                ]);
        }
        else
        {
            $errormessage="Data dengan ID ($id) tidak ditemukan.";            
            return view("pages.$theme.setting.users.error")->with(['page_active'=>'permissions',
                                                                    'errormessage'=>$errormessage
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

        $data = User::find($id);
        if (!is_null($data) ) 
        {
            $daftar_roles=$this->getDaftarRoles();
            $user = \App\Models\User::with('roles')->find($id);
            $user_roles=$user->roles->pluck('name', 'name')->toArray();           
            return view("pages.$theme.setting.users.edit")->with(['page_active'=>'users',
                                                                    'daftar_roles'=>$daftar_roles,
                                                                    'user_roles'=>$user_roles,
                                                                    'data'=>$data,
                                                                ]);
        }
        else
        {
            $errormessage="Data dengan ID ($id) tidak ditemukan.";            
            return view("pages.$theme.setting.users.error")->with(['page_active'=>'permissions',
                                                                    'errormessage'=>$errormessage
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
        $this->validate($request, [
            'username'=>['required',new IgnoreIfDataIsEqualValidation('users',$request->input('old_username'))],           
            'name'=>'required',            
            'email'=>'required|string|email|unique:users,email,'.$id,              
            'role_name'=>'required',
        ]); 
        
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        if (!empty(trim($request->input('password')))) {
            $user->password = Hash::make($request->input('password'));
        }    
        $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->save();

        $role_name = $request->input('role_name');
        $user->syncRoles($role_name);
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
            return redirect(route('users.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $user = User::find($id);
        $result=$user->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('users'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.setting.users.datatable")->with(['page_active'=>'users',
                                                            'search'=>$this->getControllerStateSession('users','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('users.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('users.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('users.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}