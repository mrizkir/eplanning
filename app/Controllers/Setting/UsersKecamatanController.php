<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Rules\IgnoreIfDataIsEqualValidation;

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
    public function populateDataKecamatan ($userid) 
    {        
        $data = \App\Models\UserKecamatan::where('id',$userid)
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
        if (!$this->checkStateIsExistSession('userskecamatan','orderby')) 
        {            
           $this->putControllerStateSession('userskecamatan','orderby',['column_name'=>'id','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('userskecamatan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('userskecamatan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('userskecamatan','search')) 
        {
            $search=$this->getControllerStateSession('userskecamatan','search');
            switch ($search['kriteria']) 
            {
                case 'id' :
                    $data = User::role('kecamatan')->where(['users.id'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'username' :
                    $data = User::role('kecamatan')->where('username', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
                case 'nama' :
                    $data = User::role('kecamatan')->where('name', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
                case 'email' :
                    $data = User::role('kecamatan')->where('email', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction); 
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = User::role('kecamatan')->orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }                
        $data->setPath(route('userskecamatan.index'));
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
            default :
                $column_name = 'id';
        }
        $this->putControllerStateSession('userskecamatan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

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
        return view("pages.$theme.setting.userskecamatan.create")->with(['page_active'=>'userskecamatan',
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
        $user->assignRole('kecamatan');        
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
            return redirect(route('userskecamatan.show',['uuid'=>$user->id]))->with('success','Data ini telah berhasil disimpan.');
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
            $datakecamatan=$this->populateDataKecamatan($data->id);
            $daftar_kecamatan=\App\Models\DMaster\KecamatanModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                    ->select(\DB::raw('"PmKecamatanID", CONCAT("Nm_Kecamatan",\' [\',"Kd_Kecamatan",\']\') AS "Nm_Kecamatan"'))      
                                                                    ->WhereNotIn('PmKecamatanID',function($query) use ($id){
                                                                        $query->select('PmKecamatanID')
                                                                            ->from('userskecamatan')
                                                                            ->where('id', $id)
                                                                            ->where('TA',\HelperKegiatan::getTahunPerencanaan());
                                                                    })                                                                 
                                                                    ->get()
                                                                    ->pluck('Nm_Kecamatan','PmKecamatanID')                                                                        
                                                                    ->toArray();
                                                                    
            return view("pages.$theme.setting.userskecamatan.show")->with(['page_active'=>'userskecamatan',
                                                                    'daftar_kecamatan'=>$daftar_kecamatan,
                                                                    'datakecamatan'=>$datakecamatan,
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
            'PmKecamatanID'=>'required',
        ]);
        $PmKecamatanID=$request->input('PmKecamatanID');
        $anggota_kecamatan=\App\Models\DMaster\KecamatanModel::find($PmKecamatanID);        
        $now = \Carbon\Carbon::now()->toDateTimeString();        
        $user=\App\Models\UserKecamatan::create([
            'id'=>$id,            
            'ta'=>\HelperKegiatan::getTahunPerencanaan(),            
            'PmKecamatanID'=> $PmKecamatanID,
            'Kd_Kecamatan'=> $anggota_kecamatan->Kd_Kecamatan,
            'Nm_Kecamatan'=> $anggota_kecamatan->Nm_Kecamatan,
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
            return redirect(route('userskecamatan.show',['uuid'=>$user->id]))->with('success','Data ini telah berhasil disimpan.');
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
            return view("pages.$theme.setting.userskecamatan.edit")->with(['page_active'=>'userskecamatan',                                                                   
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

        $user->syncRoles('kecamatan');
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
            return redirect(route('userskecamatan.show',['uuid'=>$user->id]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        if ($request->exists('userkecamatan'))
        {
            $user=\App\Models\UserKecamatan::find($id);
            $userid=$user->id;
            $result=$user->delete();

            if ($request->ajax()) 
            {
                $datakecamatan=$this->populateDataKecamatan($userid);
                $datatable = view("pages.$theme.setting.userskecamatan.datatablekecamatan")->with(['page_active'=>'userskecamatan',                                                                                
                                                                                'datakecamatan'=>$datakecamatan])->render(); 
                
                return response()->json(['success'=>true,'datatable'=>$datatable],200); 
            }
            else
            {
                return redirect(route('userskecamatan.show',['uuid'=>$userid]))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }
        else
        {
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
}