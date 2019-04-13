<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\UsulanPraRenjaOPDModel;
use App\Models\DMaster\OrganisasiModel;
use App\Models\DMaster\SubOrganisasiModel;
use App\Models\DMaster\UrusanModel;
use App\Models\DMaster\ProgramModel;
use App\Models\DMaster\ProgramKegiatanModel;
use App\Models\DMaster\UrusanProgramModel;
use App\Models\DMaster\SumberDanaModel;

class UsulanPraRenjaOPDController extends Controller {
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
        if (!$this->checkStateIsExistSession('usulanprarenjaopd','orderby')) 
        {            
           $this->putControllerStateSession('usulanprarenjaopd','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'); 
        $direction=$this->getControllerStateSession('usulanprarenjaopd.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('usulanprarenjaopd','filters')) 
        {            
            $this->putControllerStateSession('usulanprarenjaopd','filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $SOrgID= $this->getControllerStateSession('usulanprarenjaopd.filters','SOrgID');        

        if ($this->checkStateIsExistSession('usulanprarenjaopd','search')) 
        {
            $search=$this->getControllerStateSession('usulanprarenjaopd','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = UsulanPraRenjaOPDModel::where(['kode_kegiatan'=>$search['isikriteria']])
                                                    ->where('SOrgID',$SOrgID)
                                                    ->where('TA', config('globalsettings.tahun_perencanaan'))
                                                    ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = UsulanPraRenjaOPDModel::where('KgtNm', 'like', '%' . $search['isikriteria'] . '%')
                                                    ->where('SOrgID',$SOrgID)
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
        $data->setPath(route('usulanprarenjaopd.index'));        
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
        
        $this->setCurrentPageInsideSession('usulanprarenjaopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',
                                                                                'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
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
        $this->putControllerStateSession('usulanprarenjaopd','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanrenjaopd');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',
                                                                                    'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
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

        $this->setCurrentPageInsideSession('usulanprarenjaopd',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
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
            $this->destroyControllerStateSession('usulanprarenjaopd','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('usulanprarenjaopd','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('usulanprarenjaopd',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',                                                            
                                                                                'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
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

        $search=$this->getControllerStateSession('usulanprarenjaopd','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('usulanprarenjaopd'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('usulanprarenjaopd',$data->currentPage());
        
        $filters=$this->getControllerStateSession('usulanprarenjaopd','filters'); 
         
        $daftar_opd=OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false);      
        $daftar_unitkerja=array();           
        if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
        {
            $daftar_unitkerja=SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$filters['OrgID']);        
        }                
        return view("pages.$theme.rkpd.usulanprarenjaopd.index")->with(['page_active'=>'usulanprarenjaopd',
                                                                        'daftar_opd'=>$daftar_opd,
                                                                        'daftar_unitkerja'=>$daftar_unitkerja,
                                                                        'filters'=>$filters,
                                                                        'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                        'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                                        'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
                                                                        'data'=>$data]);               
    }
    public function pilihusulankegiatan(Request $request)
    {
        $json_data=[];
        if ($request->exists('UrsID'))
        {
            $UrsID = $request->input('UrsID')==''?'none':$request->input('UrsID');
            $daftar_program = ProgramModel::getDaftarProgram(config('globalsettings.tahun_perencanaan'),false,$UrsID);
            $json_data['success']=true;
            $json_data['daftar_program']=$daftar_program;
        }

        if ($request->exists('PrgID'))
        {
            $PrgID = $request->input('PrgID')==''?'none':$request->input('PrgID');
            $daftar_kegiatan = ProgramKegiatanModel::getDaftarKegiatan(config('globalsettings.tahun_perencanaan'),false,$PrgID);
            $json_data['success']=true;
            $json_data['daftar_kegiatan']=$daftar_kegiatan;
        }
        return response()->json($json_data,200);  
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanprarenjaopd','filters'); 

        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $SOrgID=$filters['SOrgID'];            
            $OrgID=$filters['OrgID'];

            $organisasi=OrganisasiModel::find($OrgID);            
            $UrsID=$organisasi->UrsID;

            $daftar_urusan=UrusanModel::getDaftarUrusan(config('globalsettings.tahun_perencanaan'),false);   
            $daftar_program = ProgramModel::getDaftarProgram(config('globalsettings.tahun_perencanaan'),false,$UrsID);
            $sumber_dana = SumberDanaModel::getDaftarSumberDana(config('globalsettings.tahun_perencanaan'),false);     
            
            return view("pages.$theme.rkpd.usulanprarenjaopd.create")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'daftar_urusan'=>$daftar_urusan,
                                                                            'daftar_program'=>$daftar_program,
                                                                            'UrsID_selected'=>$UrsID,
                                                                            'sumber_dana'=>$sumber_dana
                                                                        ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanprarenjaopd.error")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create1($renjaid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanprarenjaopd','filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $renja=UsulanPraRenjaOPDModel::findOrFailRenja($renjaid);
            $OrgID=$renja->OrgID;
            $SOrgID=$renja->SOrgID;
            $PrgID=ProgramKegiatanModel::find($renja->KgtID)->PrgID;
            
            $daftar_indikatorkinerja=[];
            
            $data = [];
            return view("pages.$theme.rkpd.usulanprarenjaopd.create1")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'daftar_indikatorkinerja'=>$daftar_indikatorkinerja,
                                                                            'renja'=>$renja,
                                                                            'data'=>$data
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanprarenjaopd.error")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create2($renjaid)
    {        
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('usulanprarenjaopd','filters'); 
        if ($filters['SOrgID'] != 'none'&&$filters['SOrgID'] != ''&&$filters['SOrgID'] != null)
        {
            $renja=UsulanPraRenjaOPDModel::findOrFailRenja($renjaid);

            $data = [];
            return view("pages.$theme.rkpd.usulanprarenjaopd.create2")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'renja'=>$renja,
                                                                            'data'=>$data
                                                                            ]);  
        }
        else
        {
            return view("pages.$theme.rkpd.usulanprarenjaopd.error")->with(['page_active'=>'usulanprarenjaopd',
                                                                            'errormessage'=>'Mohon unit kerja untuk di pilih terlebih dahulu.'
                                                                            ]);  
        }
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
            'UrsID'=>'required',
            'PrgID'=>'required',
            'KgtID'=>'required',
            'SumberDanaID'=>'required',
            'Sasaran_Angka1'=>'required',
            'Sasaran_Uraian1' => 'required',
            'Sasaran_AngkaSetelah'=>'required',
            'Sasaran_UraianSetelah'=>'required',
            'Target1'=>'required',
            'NilaiSebelum'=>'required',
            'NilaiUsulan1'=>'required',
            'NilaiSetelah'=>'required',
            'NamaIndikator'=>'required'
        ]);
        $filters=$this->getControllerStateSession('usulanprarenjaopd','filters'); 
        $RenjaID=uniqid ('uid');
        $renja=[            
            'RenjaID' => $RenjaID,            
            'OrgID' => $filters['OrgID'],
            'SOrgID' => $filters['SOrgID'],
            'UrsID' => $request->input('UrsID'),
            'PrgID' => $request->input('PrgID'),
            'KgtID' => $request->input('KgtID'),
            'SumberDanaID' => $request->input('SumberDanaID'),
            'Sasaran_Angka1' => $request->input('Sasaran_Angka1'),
            'Sasaran_Uraian1' => $request->input('Sasaran_Uraian1'),
            'Sasaran_AngkaSetelah' => $request->input('Sasaran_AngkaSetelah'),
            'Sasaran_UraianSetelah' => $request->input('Sasaran_UraianSetelah'),
            'Target1' => $request->input('Target1'),
            'NilaiSebelum' => $request->input('NilaiSebelum'),
            'NilaiUsulan1' => $request->input('NilaiUsulan1'),
            'NilaiSetelah' => $request->input('NilaiSetelah'),
            'NamaIndikator' => $request->input('NamaIndikator'),            
            'Descr' => $request->input('Descr'),
            'TA' => config('globalsettings.tahun_perencanaan'),
            'EntryLvl'=>0
        ];
        $data['renja']=$renja;
        $usulanprarenjaopd = UsulanPraRenjaOPDModel::create($data);        
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('usulanprarenjaopd.create1',['id'=>$RenjaID]))->with('success','Data kegiatan telah berhasil disimpan. Selanjutnya isi Indikator Kinerja Kegiatan dari RPMJD');
        }

    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store1(Request $request)
    {
        
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

        $data = UsulanPraRenjaOPDModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rkpd.usulanprarenjaopd.show")->with(['page_active'=>'usulanprarenjaopd',
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
        
        $data = UsulanPraRenjaOPDModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.rkpd.usulanprarenjaopd.edit")->with(['page_active'=>'usulanprarenjaopd',
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
        $usulanprarenjaopd = UsulanPraRenjaOPDModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $usulanprarenjaopd->replaceit = $request->input('replaceit');
        $usulanprarenjaopd->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('usulanprarenjaopd.show',['id'=>$usulanprarenjaopd->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $usulanprarenjaopd = UsulanPraRenjaOPDModel::find($id);
        $result=$usulanprarenjaopd->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('usulanprarenjaopd'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rkpd.usulanprarenjaopd.datatable")->with(['page_active'=>'usulanprarenjaopd',
                                                            'search'=>$this->getControllerStateSession('usulanprarenjaopd','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('usulanprarenjaopd.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('usulanprarenjaopd.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
