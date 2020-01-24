<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;
use App\Models\DMaster\PaguAnggaranDewanModel;
use App\Models\DMaster\OrganisasiModel;

class PaguAnggaranDewanController extends Controller {
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
        if (!$this->checkStateIsExistSession('paguanggarandewan','orderby')) 
        {            
           $this->putControllerStateSession('paguanggarandewan','orderby',['column_name'=>'tmPemilikPokok.Kd_PK','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('paguanggarandewan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('paguanggarandewan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('paguanggarandewan','search')) 
        {
            $search=$this->getControllerStateSession('paguanggarandewan','search');
            switch ($search['kriteria']) 
            {
                case 'NmPk' :
                    $data = PaguAnggaranDewanModel::join('tmPemilikPokok','tmPaguAnggaranDewan.PemilikPokokID','tmPemilikPokok.PemilikPokokID')
                                                ->where('NmPk', 'ilike', '%' . $search['isikriteria'] . '%')
                                                ->where('tmPaguAnggaranDewan.TA',\HelperKegiatan::getTahunPerencanaan())
                                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = PaguAnggaranDewanModel::join('tmPemilikPokok','tmPaguAnggaranDewan.PemilikPokokID','tmPemilikPokok.PemilikPokokID')
                                        ->where('tmPaguAnggaranDewan.TA',\HelperKegiatan::getTahunPerencanaan())
                                        ->orderBy($column_order,$direction)
                                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('paguanggarandewan.index'));
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
        
        $this->setCurrentPageInsideSession('paguanggarandewan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.paguanggarandewan.datatable")->with(['page_active'=>'paguanggarandewan',
                                                                                'search'=>$this->getControllerStateSession('paguanggarandewan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('paguanggarandewan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('paguanggarandewan.orderby','order'),
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
            case 'col-Kd_PK' :
                $column_name = 'tmPemilikPokok.Kd_PK';
            break; 
            case 'col-NmPk' :
                $column_name = 'tmPemilikPokok.NmPk';
            break;  
            case 'col-Jumlah1' :
                $column_name = 'tmPaguAnggaranDewan.Jumlah1';
            break; 
            case 'col-Jumlah2' :
                $column_name = 'tmPaguAnggaranDewan.Jumlah2';
            break;          
            default :
                $column_name = 'tmPemilikPokok.Kd_PK';
        }
        $this->putControllerStateSession('paguanggarandewan','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('paguanggarandewan');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.dmaster.paguanggarandewan.datatable")->with(['page_active'=>'paguanggarandewan',
                                                            'search'=>$this->getControllerStateSession('paguanggarandewan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('paguanggarandewan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('paguanggarandewan.orderby','order'),
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

        $this->setCurrentPageInsideSession('paguanggarandewan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.paguanggarandewan.datatable")->with(['page_active'=>'paguanggarandewan',
                                                                            'search'=>$this->getControllerStateSession('paguanggarandewan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('paguanggarandewan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('paguanggarandewan.orderby','order'),
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
            $this->destroyControllerStateSession('paguanggarandewan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('paguanggarandewan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('paguanggarandewan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.paguanggarandewan.datatable")->with(['page_active'=>'paguanggarandewan',                                                            
                                                            'search'=>$this->getControllerStateSession('paguanggarandewan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('paguanggarandewan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('paguanggarandewan.orderby','order'),
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

        $search=$this->getControllerStateSession('paguanggarandewan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('paguanggarandewan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('paguanggarandewan',$data->currentPage());
        
        return view("pages.$theme.dmaster.paguanggarandewan.index")->with(['page_active'=>'paguanggarandewan',
                                                'search'=>$this->getControllerStateSession('paguanggarandewan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('paguanggarandewan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('paguanggarandewan.orderby','order'),
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
        $q=\DB::table('tmPemilikPokok')
                                ->select(\DB::raw('"tmPemilikPokok"."PemilikPokokID","tmPemilikPokok"."Kd_PK","tmPemilikPokok"."NmPk"'))
                                ->leftJoin('tmPaguAnggaranDewan','tmPaguAnggaranDewan.PemilikPokokID','tmPemilikPokok.PemilikPokokID')
                                ->whereNull('tmPaguAnggaranDewan.PemilikPokokID')
                                ->where('tmPemilikPokok.TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy('Kd_PK')
                                ->get();
        $daftar_anggota=[];        
        foreach ($q as $k=>$v)
        {
            $daftar_anggota[$v->PemilikPokokID]='['.$v->Kd_PK.'] '.$v->NmPk;
        } 
        return view("pages.$theme.dmaster.paguanggarandewan.create")->with(['page_active'=>'paguanggarandewan',
                                                                        'daftar_anggota'=>$daftar_anggota
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
            'PemilikPokokID'=> [new CheckRecordIsExistValidation('tmPaguAnggaranDewan',['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]]),
                        'required'],
            'Jumlah1'=>'required|numeric',
            'Jumlah2'=>'required|numeric',
        ]);
        
        $paguanggarandewan = PaguAnggaranDewanModel::create([
            'PaguAnggaranDewanID' => uniqid ('uid'),
            'PemilikPokokID' => $request->input('PemilikPokokID'),
            'Jumlah1' => $request->input('Jumlah1'),
            'Jumlah2' => $request->input('Jumlah2'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getTahunPerencanaan()
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
            return redirect(route('paguanggarandewan.show',['uuid'=>$paguanggarandewan->PaguAnggaranDewanID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = PaguAnggaranDewanModel::join('tmPemilikPokok','tmPaguAnggaranDewan.PemilikPokokID','tmPemilikPokok.PemilikPokokID')
                                    ->where('tmPaguAnggaranDewan.TA',\HelperKegiatan::getTahunPerencanaan())
                                    ->findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.paguanggarandewan.show")->with(['page_active'=>'paguanggarandewan',
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
        
        $data = PaguAnggaranDewanModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                    ->findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_anggota= \App\Models\Pokir\PemilikPokokPikiranModel::where('TA',\HelperKegiatan::getTahunPerencanaan()) 
                                                                        ->select(\DB::raw('"PemilikPokokID", CONCAT("NmPk",\' [\',"Kd_PK",\']\') AS "NmPk"'))                                                                       
                                                                        ->get()
                                                                        ->pluck('NmPk','PemilikPokokID')   
                                                                        ->prepend('DAFTAR ANGGOTA DEWAN','none')                                                                     
                                                                        ->toArray();          
            return view("pages.$theme.dmaster.paguanggarandewan.edit")->with(['page_active'=>'paguanggarandewan',
                                                                                'data'=>$data,
                                                                                'daftar_anggota'=>$daftar_anggota
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
        $paguanggarandewan = PaguAnggaranDewanModel::find($id);
        
        $this->validate($request, [
            'PemilikPokokID'=> [new IgnoreIfDataIsEqualValidation('tmPaguAnggaranDewan',$paguanggarandewan->PemilikPokokID,['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]]),
                        'required'],
            'Jumlah1'=>'required|numeric',
            'Jumlah2'=>'required|numeric',
        ]);
        
        $paguanggarandewan->PemilikPokokID = $request->input('PemilikPokokID');
        $paguanggarandewan->Jumlah1 = $request->input('Jumlah1');
        $paguanggarandewan->Jumlah2 = $request->input('Jumlah2');
        $paguanggarandewan->Descr = $request->input('Descr');       
        
        $paguanggarandewan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('paguanggarandewan.show',['uuid'=>$paguanggarandewan->PaguAnggaranDewanID]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $paguanggarandewan = PaguAnggaranDewanModel::where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                ->find($id);
        $result=$paguanggarandewan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('paguanggarandewan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.paguanggarandewan.datatable")->with(['page_active'=>'paguanggarandewan',
                                                            'search'=>$this->getControllerStateSession('paguanggarandewan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('paguanggarandewan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('paguanggarandewan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('paguanggarandewan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}