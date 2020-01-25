<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Rules\IgnoreIfDataIsEqualValidation;

class UsersDewanController extends Controller {
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
    public function populateDataDewan ($userid) 
    {        
        $data = \App\Models\UserDewan::where('id',$userid)
                                    ->where('ta',\HelperKegiatan::getTahunPerencanaan())
                                    ->get();
        return $data;
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('usersdewan','orderby')) 
        {            
           $this->putControllerStateSession('usersdewan','orderby',['column_name'=>'id','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('usersdewan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('usersdewan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('usersdewan','search')) 
        {
            $search=$this->getControllerStateSession('usersdewan','search');
            switch ($search['kriteria']) 
            {
                case 'id' :
                    $data = User::role('dewan')->where(['users.id'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'username' :
                    $data = User::role('dewan')->where('username', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
                case 'nama' :
                    $data = User::role('dewan')->where('name', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
                case 'email' :
                    $data = User::role('dewan')->where('email', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = User::role('dewan')->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }                
        $data->setPath(route('usersdewan.index'));
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
        
        $this->setCurrentPageInsideSession('usersdewan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.usersdewan.datatable")->with(['page_active'=>'usersdewan',
                                                                                'search'=>$this->getControllerStateSession('usersdewan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('usersdewan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('usersdewan.orderby','order'),
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
        $this->putControllerStateSession('usersdewan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.usersdewan.datatable")->with(['page_active'=>'usersdewan',
                                                            'search'=>$this->getControllerStateSession('usersdewan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('usersdewan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usersdewan.orderby','order'),
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

        $this->setCurrentPageInsideSession('usersdewan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.setting.usersdewan.datatable")->with(['page_active'=>'usersdewan',
                                                                            'search'=>$this->getControllerStateSession('usersdewan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('usersdewan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('usersdewan.orderby','order'),
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
            $this->destroyControllerStateSession('usersdewan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('usersdewan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('usersdewan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.setting.usersdewan.datatable")->with(['page_active'=>'usersdewan',                                                            
                                                            'search'=>$this->getControllerStateSession('usersdewan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('usersdewan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usersdewan.orderby','order'),
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
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(\HelperKegiatan::getTahunPerencanaan(),false,$OrgID);  
            
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

        $search=$this->getControllerStateSession('usersdewan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('usersdewan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('usersdewan',$data->currentPage());
        
        return view("pages.$theme.setting.usersdewan.index")->with(['page_active'=>'usersdewan',
                                                'search'=>$this->getControllerStateSession('usersdewan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('usersdewan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('usersdewan.orderby','order'),
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
        return view("pages.$theme.setting.usersdewan.create")->with(['page_active'=>'usersdewan',
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
        ]);
        $OrgID=$request->input('OrgID');
        $SOrgID=$request->input('SOrgID');
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'username'=> $request->input('username'),
            'password'=>\Hash::make($request->input('password')),            
            'email_verified_at'=>\Carbon\Carbon::now(),
            'theme'=> $request->input('theme'),
            'created_at'=>$now, 
            'updated_at'=>$now
        ]);                    
        $user->assignRole('dewan');        
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
            return redirect(route('usersdewan.show',['id'=>$user->id]))->with('success','Data ini telah berhasil disimpan.');
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
            $datadewan=$this->populateDataDewan($data->id);
            $daftar_dewan=\App\Models\Pokir\PemilikPokokPikiranModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                    ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))      
                                                                    ->WhereNotIn('PemilikPokokID',function($query) use ($id){
                                                                        $query->select('PemilikPokokID')
                                                                            ->from('usersdewan')
                                                                            ->where('id', $id)
                                                                            ->where('TA',\HelperKegiatan::getTahunPerencanaan());
                                                                    })                                                                 
                                                                    ->get()
                                                                    ->pluck('NmPk','PemilikPokokID')                                                                        
                                                                    ->toArray();
                                                                    
            return view("pages.$theme.setting.usersdewan.show")->with(['page_active'=>'usersdewan',
                                                                    'daftar_dewan'=>$daftar_dewan,
                                                                    'datadewan'=>$datadewan,
                                                                    'data'=>$data
                                                                ]);
        }        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request, $id)
    {
        $this->validate($request, [              
            'PemilikPokokID'=>'required',
        ]);
        $PemilikPokokID=$request->input('PemilikPokokID');
        $anggota_dewan=\App\Models\Pokir\PemilikPokokPikiranModel::find($PemilikPokokID);        
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=\App\Models\UserDewan::create([
            'id'=>$id,            
            'ta'=>\HelperKegiatan::getTahunPerencanaan(),            
            'PemilikPokokID'=> $PemilikPokokID,
            'Kd_PK'=> $anggota_dewan->Kd_PK,
            'NmPk'=> $anggota_dewan->NmPk,
            'created_at'=>$now, 
            'updated_at'=>$now
        ]); 
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usersdewan.show',['id'=>$user->id]))->with('success','Data ini telah berhasil disimpan.');
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
            $daftar_theme = $this->listOfthemes;   
            return view("pages.$theme.setting.usersdewan.edit")->with(['page_active'=>'usersdewan',                                                                   
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
        ]);        
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        if (!empty(trim($request->input('password')))) {
            $user->password = \Hash::make($request->input('password'));
        }  
        $user->theme = $request->input('theme');
        $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();        
        $user->save();

        $user->syncRoles('dewan');
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
            return redirect(route('usersdewan.show',['id'=>$user->id]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        if ($request->exists('userdewan'))
        {
            $user=\App\Models\UserDewan::find($id);
            $userid=$user->id;
            $result=$user->delete();

            if ($request->ajax()) 
            {
                $datadewan=$this->populateDataDewan($userid);
                $datatable = view("pages.$theme.setting.usersdewan.datatabledewan")->with(['page_active'=>'usersdewan',                                                                                
                                                                                'datadewan'=>$datadewan])->render(); 
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('usersdewan.show',['id'=>$userid]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }
        else
        {
            $usersdewan = User::find($id);
            $result=$usersdewan->delete();
            if ($request->ajax()) 
            {
                $currentpage=$this->getCurrentPageInsideSession('usersdewan'); 
                $data=$this->populateData($currentpage);
                if ($currentpage > $data->lastPage())
                {            
                    $data = $this->populateData($data->lastPage());
                }
                $datatable = view("pages.$theme.setting.usersdewan.datatable")->with(['page_active'=>'usersdewan',
                                                                'search'=>$this->getControllerStateSession('usersdewan','search'),
                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                'column_order'=>$this->getControllerStateSession('usersdewan.orderby','column_name'),
                                                                'direction'=>$this->getControllerStateSession('usersdewan.orderby','order'),
                                                                'data'=>$data])->render();      
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('usersdewan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }        
    }
}