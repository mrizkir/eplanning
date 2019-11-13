<?php

namespace App\Controllers;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RapatModel;

class RapatController extends Controller {
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
    public function populateDataOPD ($userid) 
    {        
        $data = \App\Models\UserOPD::where('id',$userid)
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
        if (!$this->checkStateIsExistSession('rapat','orderby')) 
        {            
           $this->putControllerStateSession('rapat','orderby',['column_name'=>'Tanggal_Rapat','order'=>'desc']);
        }
        $column_order=$this->getControllerStateSession('rapat.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rapat.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rapat','search')) 
        {
            $search=$this->getControllerStateSession('rapat','search');
            switch ($search['kriteria']) 
            {                
                case 'Judul' :
                    $data = RapatModel::where('Judul', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;                
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RapatModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }                
        $data->setPath(route('rapat.index'));
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
        
        $this->setCurrentPageInsideSession('rapat',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rapat.datatable")->with(['page_active'=>'rapat',
                                                                                'search'=>$this->getControllerStateSession('rapat','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rapat.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rapat.orderby','order'),
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
            case 'col-Judul' :
                $column_name = 'Judul';
            break;             
            default :
                $column_name = 'Judul';
        }
        $this->putControllerStateSession('rapat','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.rapat.datatable")->with(['page_active'=>'rapat',
                                                            'search'=>$this->getControllerStateSession('rapat','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rapat.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rapat.orderby','order'),
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

        $this->setCurrentPageInsideSession('rapat',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rapat.datatable")->with(['page_active'=>'rapat',
                                                                            'search'=>$this->getControllerStateSession('rapat','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rapat.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rapat.orderby','order'),
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
            $this->destroyControllerStateSession('rapat','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rapat','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rapat',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rapat.datatable")->with(['page_active'=>'rapat',                                                            
                                                            'search'=>$this->getControllerStateSession('rapat','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rapat.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rapat.orderby','order'),
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

        $search=$this->getControllerStateSession('rapat','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rapat'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rapat',$data->currentPage());
        
        return view("pages.$theme.rapat.index")->with(['page_active'=>'rapat',
                                                'search'=>$this->getControllerStateSession('rapat','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rapat.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rapat.orderby','order'),
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
        return view("pages.$theme.rapat.create")->with(['page_active'=>'rapat',
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
            'Judul'=>'required',
            'Isi'=>'required',
            'pimpinan'=>'required',
            'anggota'=>'required',
            'Tempat_Rapat'=>'required',
        ]);
        $Tanggal_Rapat = \Carbon\Carbon::createFromFormat('d/m/Y',$request->input('Tanggal_Rapat'));
        $rapat=RapatModel::create([
            'RapatID'=>uniqid ('uid'),
            'Judul'=>$request->input('Judul'),
            'Isi'=>$request->input('Isi'),
            'pimpinan'=> $request->input('pimpinan'),
            'anggota'=> $request->input('anggota'),
            'Tempat_Rapat'=> $request->input('Tempat_Rapat'),
            'Tanggal_Rapat'=> $Tanggal_Rapat,
            'TA' => \HelperKegiatan::getTahunPerencanaan(),
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
            return redirect(route('rapat.show',['id'=>$rapat->RapatID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RapatModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rapat.show")->with(['page_active'=>'rapat',
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
        
        $data = RapatModel::findOrFail($id);
        if (!is_null($data) )         {
            
            return view("pages.$theme.rapat.edit")->with(['page_active'=>'rapat',                                                                   
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
        $rapat = RapatModel::find($id);

        $this->validate($request, [
            'Judul'=>'required',
            'Isi'=>'required',
            'pimpinan'=>'required',
            'anggota'=>'required',
            'Tempat_Rapat'=>'required',
        ]);
        $Tanggal_Rapat = \Carbon\Carbon::createFromFormat('d/m/Y',$request->input('Tanggal_Rapat'));
        $rapat->Judul = $request->input('Judul');
        $rapat->Isi = $request->input('Isi');
        $rapat->pimpinan = $request->input('pimpinan');
        $rapat->anggota = $request->input('anggota');
        $rapat->Tempat_Rapat = $request->input('Tempat_Rapat');
        $rapat->Tanggal_Rapat = $request->input('Tanggal_Rapat');
        $rapat->save();
     
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rapat.show',['id'=>$rapat->id]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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

        $rapat = RapatModel::find($id);
        $result=$rapat->delete();
        if ($request->ajax()) 
        {
        
            $currentpage=$this->getCurrentPageInsideSession('rapat'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rapat.datatable")->with(['page_active'=>'rapat',
                                                                                'search'=>$this->getControllerStateSession('rapat','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession('rapat.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rapat.orderby','order'),
                                                                                'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rapat.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }   

           
    }
}