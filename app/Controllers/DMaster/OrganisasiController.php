<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\UrusanModel;
use App\Models\DMaster\OrganisasiModel;
use App\Models\DMaster\SubOrganisasiModel;

class OrganisasiController extends Controller {
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
        if (!$this->checkStateIsExistSession('organisasi','orderby')) 
        {            
           $this->putControllerStateSession('organisasi','orderby',['column_name'=>'kode_organisasi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('organisasi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('organisasi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('organisasi','search')) 
        {
            $search=$this->getControllerStateSession('organisasi','search');
            switch ($search['kriteria']) 
            {
                case 'kode_organisasi' :
                    $data =\DB::table('v_urusan_organisasi') 
                                ->where('TA',config('eplanning.tahun_perencanaan'))
                                ->where(['kode_organisasi'=>$search['isikriteria']])
                                ->orderBy($column_order,$direction); 
                break;
                case 'OrgNm' :
                    $data =\DB::table('v_urusan_organisasi') 
                                ->where('TA',config('eplanning.tahun_perencanaan'))
                                ->where('OrgNm', 'ilike', '%' . $search['isikriteria'] . '%')
                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_urusan_organisasi') 
                                ->where('TA',config('eplanning.tahun_perencanaan'))
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('organisasi.index'));
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
        
        $this->setCurrentPageInsideSession('organisasi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.organisasi.datatable")->with(['page_active'=>'organisasi',
                                                                                'search'=>$this->getControllerStateSession('organisasi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('organisasi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('organisasi.orderby','order'),
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
            case 'kode_organisasi' :
                $column_name = 'kode_organisasi';
            break; 
            case 'NmOrg' :
                $column_name = 'NmOrg';
            break;  
            case 'Nm_Urusan' :
                $column_name = 'Nm_Urusan';
            break;         
            default :
                $column_name = 'kode_organisasi';
        }
        $this->putControllerStateSession('organisasi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('organisasi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.organisasi.datatable")->with(['page_active'=>'organisasi',
                                                            'search'=>$this->getControllerStateSession('organisasi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('organisasi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('organisasi.orderby','order'),
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

        $this->setCurrentPageInsideSession('organisasi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.organisasi.datatable")->with(['page_active'=>'organisasi',
                                                                            'search'=>$this->getControllerStateSession('organisasi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('organisasi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('organisasi.orderby','order'),
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
            $this->destroyControllerStateSession('organisasi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('organisasi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('organisasi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.organisasi.datatable")->with(['page_active'=>'organisasi',                                                            
                                                            'search'=>$this->getControllerStateSession('organisasi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('organisasi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('organisasi.orderby','order'),
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

        $search=$this->getControllerStateSession('organisasi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('organisasi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('organisasi',$data->currentPage());
        
        return view("pages.$theme.dmaster.organisasi.index")->with(['page_active'=>'organisasi',
                                                'search'=>$this->getControllerStateSession('organisasi','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('organisasi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('organisasi.orderby','order'),
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
        $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);
        return view("pages.$theme.dmaster.organisasi.create")->with(['page_active'=>'organisasi',
                                                                    'daftar_urusan'=>$daftar_urusan
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
            'OrgCd'=>'required|min:1|max:4|regex:/^[0-9]+$/',
            'OrgNm'=>'required|min:5',
            'Alamat'=>'required|min:5',
        ]);
        
        $organisasi = OrganisasiModel::create([
            'OrgID' => uniqid ('uid'),
            'UrsID' => $request->input('UrsID'),
            'OrgCd' => $request->input('OrgCd'),
            'OrgNm' => $request->input('OrgNm'),
            'Alamat' => $request->input('Alamat'),
            'NamaKepalaSKPD' => '-',
            'NIPKepalaSKPD' => '-',
            'Descr' => $request->input('Descr'),
            'TA'=>config('eplanning.tahun_perencanaan'),
        ]);        
        
        SubOrganisasiModel::create([
            'SOrgID' => uniqid ('uid'),
            'OrgID' => $organisasi->OrgID,
            'SOrgCd' => $organisasi->OrgCd,
            'SOrgNm' => $organisasi->OrgNm,
            'Alamat' => $organisasi->Alamat,
            'NamaKepalaSKPD' => '-',
            'NIPKepalaSKPD' => '-',
            'Descr' => $organisasi->Descr,
            'TA'=> $organisasi->TA,
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
            return redirect(route('organisasi.show',['id'=>$organisasi->OrgID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = OrganisasiModel::leftJoin('v_urusan_organisasi','v_urusan_organisasi.OrgID','tmOrg.OrgID')
                                ->where('tmOrg.OrgID',$id)
                                ->firstOrFail(['tmOrg.OrgID','v_urusan_organisasi.kode_organisasi','tmOrg.OrgNm','v_urusan_organisasi.Nm_Urusan','tmOrg.TA']);
 
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.organisasi.show")->with(['page_active'=>'organisasi',
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
        
        $data = OrganisasiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_urusan=UrusanModel::getDaftarUrusan(config('eplanning.tahun_perencanaan'),false);
            return view("pages.$theme.dmaster.organisasi.edit")->with(['page_active'=>'organisasi',
                                                                    'daftar_urusan'=>$daftar_urusan,
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
        $this->validate($request, [            
            'OrgCd'=>'required|min:1|max:4|regex:/^[0-9]+$/',
            'OrgNm'=>'required|min:5',
            'Alamat'=>'required|min:5',
        ]);
        
        $organisasi = OrganisasiModel::find($id);
        $organisasi->UrsID = $request->input('UrsID');
        $organisasi->OrgCd = $request->input('OrgCd');
        $organisasi->OrgNm = $request->input('OrgNm');
        $organisasi->Alamat = $request->input('Alamat');
        $organisasi->NamaKepalaSKPD = '-';
        $organisasi->NIPKepalaSKPD = '-';
        $organisasi->Descr = $request->input('Descr');
        $organisasi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('organisasi.show',['id'=>$organisasi->OrgID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $organisasi = OrganisasiModel::find($id);
        $result=$organisasi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('organisasi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.organisasi.datatable")->with(['page_active'=>'organisasi',
                                                            'search'=>$this->getControllerStateSession('organisasi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('organisasi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('organisasi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('organisasi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}