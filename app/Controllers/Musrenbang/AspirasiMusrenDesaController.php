<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\DesaModel;
use App\Models\DMaster\SumberDanaModel;
use App\Models\Musrenbang\AspirasiMusrenDesaModel;

class AspirasiMusrenDesaController extends Controller {
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
        $columns=['UsulanDesaID','No_usulan','Nm_Desa','Nm_Kecamatan','NamaKegiatan','NilaiUsulan','Target_Angka','Target_Uraian','Jeniskeg','Prioritas'];       
        if (!$this->checkStateIsExistSession('aspirasimusrendesa','orderby')) 
        {            
           $this->putControllerStateSession('aspirasimusrendesa','orderby',['column_name'=>'No_usulan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'); 
        $direction=$this->getControllerStateSession('aspirasimusrendesa.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('aspirasimusrendesa','search')) 
        {
            $search=$this->getControllerStateSession('aspirasimusrendesa','search');
            switch ($search['kriteria']) 
            {
                case 'No_Usulan' :
                    $data = AspirasiMusrenDesaModel::where(['No_Usulan'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                                    ->join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                                    ->where('NamaKegiatan', 'like', '%' . $search['isikriteria'] . '%')
                                                    ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                            ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                            ->where('trUsulanDesa.TA', config('globalsettings.tahun_perencanaan'))
                                            ->orderBy($column_order,$direction)
                                            ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('aspirasimusrendesa.index'));
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
        
        $this->setCurrentPageInsideSession('aspirasimusrendesa',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.aspirasimusrendesa.datatable")->with(['page_active'=>'aspirasimusrendesa',
                                                                                'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
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
            case 'NamaKegiatan' :
                $column_name = 'NamaKegiatan';
            break;           
            default :
                $column_name = 'No_Usulan';
        }
        $this->putControllerStateSession('aspirasimusrendesa','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.aspirasimusrendesa.datatable")->with(['page_active'=>'aspirasimusrendesa',
                                                            'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
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

        $this->setCurrentPageInsideSession('aspirasimusrendesa',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.musrenbang.aspirasimusrendesa.datatable")->with(['page_active'=>'aspirasimusrendesa',
                                                                            'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
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
            $this->destroyControllerStateSession('aspirasimusrendesa','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('aspirasimusrendesa','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('aspirasimusrendesa',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.aspirasimusrendesa.datatable")->with(['page_active'=>'aspirasimusrendesa',                                                            
                                                            'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
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

        $search=$this->getControllerStateSession('aspirasimusrendesa','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('aspirasimusrendesa'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('aspirasimusrendesa',$data->currentPage());
        
        return view("pages.$theme.musrenbang.aspirasimusrendesa.index")->with(['page_active'=>'aspirasimusrendesa',
                                                'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
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
        $daftar_desa = DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),false);
        $sumber_dana = SumberDanaModel::getDaftarSumberDana(config('globalsettings.tahun_perencanaan'),false);
        return view("pages.$theme.musrenbang.aspirasimusrendesa.create")->with(['page_active'=>'aspirasimusrendesa',
                                                                            'daftar_desa'=>$daftar_desa,
                                                                            'sumber_dana'=>$sumber_dana
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
            'PmDesaID'=>'required',
            'NamaKegiatan'=>'required',
            'Output'=>'required',
            'Lokasi'=>'required',
            'NilaiUsulan'=>'required',
            'Target_Angka'=>'required',
            'Prioritas'=>'required',
            'Prioritas'=>'required|not_in:none'            
        ]);
        
        $aspirasimusrendesa = AspirasiMusrenDesaModel::create([
            'UsulanDesaID' => uniqid ('uid'),
            'PmDesaID' => $request->input('PmDesaID'),
            'SumberDanaID' => $request->input('SumberDanaID'),
            'No_usulan' => AspirasiMusrenDesaModel::max('No_usulan')+1,
            'NamaKegiatan' => $request->input('NamaKegiatan'),
            'Output' => $request->input('Output'),
            'Lokasi' => $request->input('Lokasi'),
            'NilaiUsulan' => $request->input('NilaiUsulan'),
            'Target_Angka' => $request->input('Target_Angka'),
            'Target_Uraian' => $request->input('Target_Uraian'),
            'Jeniskeg' => $request->input('Jeniskeg'),
            'Prioritas' => $request->input('Prioritas'),
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan')
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
            return redirect(route('aspirasimusrendesa.index'))->with('success','Data ini telah berhasil disimpan.');
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

        $data = AspirasiMusrenDesaModel::findOrFail($id);
        if (!is_null($data) )  
        {
            
            return view("pages.$theme.musrenbang.aspirasimusrendesa.show")->with(['page_active'=>'aspirasimusrendesa',
                                                                                   
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
        
        $data = AspirasiMusrenDesaModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_desa = DesaModel::getDaftarDesa(config('globalsettings.tahun_perencanaan'),false);
            $sumber_dana = SumberDanaModel::getDaftarSumberDana(config('globalsettings.tahun_perencanaan'),false);
            return view("pages.$theme.musrenbang.aspirasimusrendesa.edit")->with(['page_active'=>'aspirasimusrendesa',
                                                                                    'daftar_desa'=>$daftar_desa,
                                                                                    'sumber_dana'=>$sumber_dana,
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
        $aspirasimusrendesa = AspirasiMusrenDesaModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $aspirasimusrendesa->replaceit = $request->input('replaceit');
        $aspirasimusrendesa->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('aspirasimusrendesa.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $aspirasimusrendesa = AspirasiMusrenDesaModel::find($id);
        $result=$aspirasimusrendesa->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('aspirasimusrendesa'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.musrenbang.aspirasimusrendesa.datatable")->with(['page_active'=>'aspirasimusrendesa',
                                                            'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('aspirasimusrendesa.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}