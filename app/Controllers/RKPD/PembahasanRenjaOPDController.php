<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\UsulanPraRenjaOPDModel;

class PembahasanRenjaOPDController extends Controller {
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
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('pembahasanrenjaopd','orderby')) 
        {            
           $this->putControllerStateSession('pembahasanrenjaopd','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        

        //filter
        if (!$this->checkStateIsExistSession('pembahasanrenjaopd','filters')) 
        {            
            $this->putControllerStateSession('pembahasanrenjaopd','filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $filters=$this->getControllerStateSession('pembahasanrenjaopd','filters');
        $OrgID= $filters['OrgID'];
        $SOrgID= $filters['SOrgID'];

        if ($this->checkStateIsExistSession('pembahasanrenjaopd','search')) 
        {
            $search=$this->getControllerStateSession('pembahasanrenjaopd','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = UsulanPraRenjaOPDModel::where(['kode_kegiatan'=>$search['isikriteria']])
                                                    ->where('OrgID',$OrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = UsulanPraRenjaOPDModel::where('KgtNm', 'like', '%' . $search['isikriteria'] . '%')
                                                    ->where('OrgID',$OrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = UsulanPraRenjaOPDModel::where('SOrgID',$SOrgID)
                                            ->where('TA', config('globalsettings.tahun_perencanaan'))                                            
                                            ->orderBy($column_order,$direction)
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        
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
        
        $this->setCurrentPageInsideSession('pembahasanrenjaopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.pembahasanrenjaopd.datatable")->with(['page_active'=>'pembahasanrenjaopd',
                                                                                'search'=>$this->getControllerStateSession('pembahasanrenjaopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'),
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
            case 'col-kode_kegiatan' :
                $column_name = 'kode_kegiatan';
            break;    
            case 'col-KgtNm' :
                $column_name = 'KgtNm';
            break;    
            case 'col-Uraian' :
                $column_name = 'Uraian';
            break;    
            case 'col-Sasaran_Angka1' :
                $column_name = 'Sasaran_Angka1';
            break;  
            case 'col-Jumlah1' :
                $column_name = 'Jumlah1';
            break; 
            case 'col-Status' :
                $column_name = 'Status';
            break;
            default :
                $column_name = 'kode_kegiatan';
        }
        $this->putControllerStateSession('pembahasanrenjaopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanrenjaopd');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.pembahasanrenjaopd.datatable")->with(['page_active'=>'pembahasanrenjaopd',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanrenjaopd','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'),
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

        $this->setCurrentPageInsideSession('pembahasanrenjaopd',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.pembahasanrenjaopd.datatable")->with(['page_active'=>'pembahasanrenjaopd',
                                                                            'search'=>$this->getControllerStateSession('pembahasanrenjaopd','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'),
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
            $this->destroyControllerStateSession('pembahasanrenjaopd','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pembahasanrenjaopd','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pembahasanrenjaopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.pembahasanrenjaopd.datatable")->with(['page_active'=>'pembahasanrenjaopd',                                                            
                                                            'search'=>$this->getControllerStateSession('pembahasanrenjaopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'),
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

        $filters=$this->getControllerStateSession('usulanprarenjaopd','filters');
        $daftar_unitkerja=[];
        $json_data = [];
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$OrgID);  
            
            $this->putControllerStateSession('usulanprarenjaopd','filters',$filters);

            $data = $this->populateData();

            $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',                                                            
                                                                                    'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
                                                                                    'data'=>$data])->render();

            $json_data = ['success'=>true,'daftar_unitkerja'=>$daftar_unitkerja,'datatable'=>$datatable];
        } 
        
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('usulanprarenjaopd','filters',$filters);
            $this->setCurrentPageInsideSession('usulanprarenjaopd',1);

            $data = $this->populateData();

            $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',                                                            
                                                                                    'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
                                                                                    'data'=>$data])->render();                                                                                       
                                                                                    
            $json_data = ['success'=>true,'datatable'=>$datatable];            
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

        $search=$this->getControllerStateSession('pembahasanrenjaopd','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanrenjaopd'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pembahasanrenjaopd',$data->currentPage());
        
        return view("pages.$theme.rkpd.pembahasanrenjaopd.index")->with(['page_active'=>'pembahasanrenjaopd',
                                                'search'=>$this->getControllerStateSession('pembahasanrenjaopd','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'),
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

        return view("pages.$theme.rkpd.pembahasanrenjaopd.create")->with(['page_active'=>'pembahasanrenjaopd',
                                                                    
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
            'replaceit'=>'required',
        ]);
        
        $pembahasanrenjaopd = PembahasanRenjaOPDModel::create([
            'replaceit' => $request->input('replaceit'),
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
            return redirect(route('pembahasanrenjaopd.show',['id'=>$pembahasanrenjaopd->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = PembahasanRenjaOPDModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rkpd.pembahasanrenjaopd.show")->with(['page_active'=>'pembahasanrenjaopd',
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
        
        $data = PembahasanRenjaOPDModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.rkpd.pembahasanrenjaopd.edit")->with(['page_active'=>'pembahasanrenjaopd',
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
        $pembahasanrenjaopd = PembahasanRenjaOPDModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $pembahasanrenjaopd->replaceit = $request->input('replaceit');
        $pembahasanrenjaopd->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('pembahasanrenjaopd.show',['id'=>$pembahasanrenjaopd->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $pembahasanrenjaopd = PembahasanRenjaOPDModel::find($id);
        $result=$pembahasanrenjaopd->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('pembahasanrenjaopd'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rkpd.pembahasanrenjaopd.datatable")->with(['page_active'=>'pembahasanrenjaopd',
                                                            'search'=>$this->getControllerStateSession('pembahasanrenjaopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pembahasanrenjaopd.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('pembahasanrenjaopd.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}