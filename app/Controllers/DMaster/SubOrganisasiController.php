<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\OrganisasiModel;
use App\Models\DMaster\SubOrganisasiModel;

class SubOrganisasiController extends Controller {
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
        if (!$this->checkStateIsExistSession('suborganisasi','orderby')) 
        {            
           $this->putControllerStateSession('suborganisasi','orderby',['column_name'=>'kode_suborganisasi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('suborganisasi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('suborganisasi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('suborganisasi','search')) 
        {
            $search=$this->getControllerStateSession('suborganisasi','search');
            switch ($search['kriteria']) 
            {
                case 'kode_suborganisasi' :
                    $data =\DB::table('v_suborganisasi') 
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->where(['kode_suborganisasi'=>$search['isikriteria']])
                                ->orderBy($column_order,$direction); 
                break;
                case 'SOrgNm' :
                    $data =\DB::table('v_suborganisasi') 
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->where('SOrgNm', 'ilike', '%' . $search['isikriteria'] . '%')
                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_suborganisasi') 
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('suborganisasi.index'));
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
        
        $this->setCurrentPageInsideSession('suborganisasi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.suborganisasi.datatable")->with(['page_active'=>'suborganisasi',
                                                                                'search'=>$this->getControllerStateSession('suborganisasi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('suborganisasi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('suborganisasi.orderby','order'),
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
            case 'kode_suborganisasi' :
                $column_name = 'kode_suborganisasi';
            break; 
            case 'SOrgNm' :
                $column_name = 'SOrgNm';
            break; 
            case 'Nm_Urusan' :
                $column_name = 'Nm_Urusan';
            break;           
            default :
                $column_name = 'kode_suborganisasi';
        }
        $this->putControllerStateSession('suborganisasi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('suborganisasi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.dmaster.suborganisasi.datatable")->with(['page_active'=>'suborganisasi',
                                                            'search'=>$this->getControllerStateSession('suborganisasi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('suborganisasi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('suborganisasi.orderby','order'),
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

        $this->setCurrentPageInsideSession('suborganisasi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.dmaster.suborganisasi.datatable")->with(['page_active'=>'suborganisasi',
                                                                            'search'=>$this->getControllerStateSession('suborganisasi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('suborganisasi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('suborganisasi.orderby','order'),
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
            $this->destroyControllerStateSession('suborganisasi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('suborganisasi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('suborganisasi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.dmaster.suborganisasi.datatable")->with(['page_active'=>'suborganisasi',                                                            
                                                            'search'=>$this->getControllerStateSession('suborganisasi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('suborganisasi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('suborganisasi.orderby','order'),
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

        $search=$this->getControllerStateSession('suborganisasi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('suborganisasi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('suborganisasi',$data->currentPage());
        
        return view("pages.$theme.dmaster.suborganisasi.index")->with(['page_active'=>'suborganisasi',
                                                'search'=>$this->getControllerStateSession('suborganisasi','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('suborganisasi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('suborganisasi.orderby','order'),
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
        $daftar_opd=OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);
        return view("pages.$theme.dmaster.suborganisasi.create")->with(['page_active'=>'suborganisasi',
                                                                    'daftar_opd'=>$daftar_opd
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
            'SOrgCd'=>'required|min:1|max:4|regex:/^[0-9]+$/',
            'SOrgNm'=>'required|min:5',
            'Alamat'=>'required|min:5',
        ]);
        
        $suborganisasi = SubOrganisasiModel::create([
            'SOrgID' => uniqid ('uid'),
            'OrgID' => $request->input('OrgID'),
            'SOrgCd' => $request->input('SOrgCd'),
            'SOrgNm' => $request->input('SOrgNm'),
            'Alamat' => $request->input('Alamat'),
            'NamaKepalaSKPD' => '-',
            'NIPKepalaSKPD' => '-',
            'Descr' => $request->input('Descr'),
            'TA'=>\HelperKegiatan::getTahunPerencanaan(),
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
            return redirect(route('suborganisasi.index'))->with('success','Data ini telah berhasil disimpan.');
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

        $data = SubOrganisasiModel::leftJoin('v_suborganisasi','v_suborganisasi.SOrgID','tmSOrg.SOrgID')
                                ->where('tmSOrg.SOrgID',$id)
                                ->firstOrFail(['tmSOrg.SOrgID','v_suborganisasi.kode_suborganisasi','tmSOrg.SOrgNm','v_suborganisasi.Nm_Urusan','tmSOrg.TA']);
 
        if (!is_null($data) )  
        {
            return view("pages.$theme.dmaster.suborganisasi.show")->with(['page_active'=>'suborganisasi',
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
        
        $data = SubOrganisasiModel::findOrFail($id);
        if (!is_null($data) ) 
        {            
            $daftar_opd=OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);
            return view("pages.$theme.dmaster.suborganisasi.edit")->with(['page_active'=>'suborganisasi',
                                                                    'daftar_opd'=>$daftar_opd,
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
            'SOrgCd'=>'required|min:1|max:4|regex:/^[0-9]+$/',
            'SOrgNm'=>'required|min:5',
            'Alamat'=>'required|min:5',
        ]);
        
        $suborganisasi = SubOrganisasiModel::find($id);
        $suborganisasi->OrgID = $request->input('OrgID');
        $suborganisasi->SOrgCd = $request->input('SOrgCd');
        $suborganisasi->SOrgNm = $request->input('SOrgNm');
        $suborganisasi->Alamat = $request->input('Alamat');
        $suborganisasi->NamaKepalaSKPD = '-';
        $suborganisasi->NIPKepalaSKPD = '-';
        $suborganisasi->Descr = $request->input('Descr');
        $suborganisasi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('suborganisasi.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $suborganisasi = SubOrganisasiModel::find($id);
        $result=$suborganisasi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('suborganisasi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.dmaster.suborganisasi.datatable")->with(['page_active'=>'suborganisasi',
                                                            'search'=>$this->getControllerStateSession('suborganisasi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('suborganisasi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('suborganisasi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('suborganisasi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}