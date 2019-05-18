<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Musrenbang\UsulanMusrenKabModel;
use App\Models\RKPD\RenjaModel;
use App\Models\RKPD\RenjaRincianModel;
use App\Models\RKPD\RenjaIndikatorModel;

class PembahasanMusrenKabController extends Controller {
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
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('pembahasanmusrenkab','orderby')) 
        {            
           $this->putControllerStateSession('pembahasanmusrenkab','orderby',['column_name'=>'kode_kegiatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');
        
        //filter
        if (!$this->checkStateIsExistSession('pembahasanmusrenkab','filters')) 
        {            
            $this->putControllerStateSession('pembahasanmusrenkab','filters',[
                                                                            'OrgID'=>'none',
                                                                            'SOrgID'=>'none',
                                                                            ]);
        }        
        $SOrgID= $this->getControllerStateSession('pembahasanmusrenkab.filters','SOrgID');        

        if ($this->checkStateIsExistSession('pembahasanmusrenkab','search')) 
        {
            $search=$this->getControllerStateSession('pembahasanmusrenkab','search');
            switch ($search['kriteria']) 
            {
                case 'kode_kegiatan' :
                    $data = \DB::table('v_usulan_musren_kab')
                                ->where('kode_kegiatan',$search['isikriteria'])                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction); 
                break;
                case 'KgtNm' :
                    $data = \DB::table('v_usulan_musren_kab')
                                ->where('KgtNm', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
                case 'Uraian' :
                    $data = \DB::table('v_usulan_musren_kab')
                                ->where('Uraian', 'ilike', '%' . $search['isikriteria'] . '%')                                                    
                                ->where('SOrgID',$SOrgID)
                                ->whereNotNull('RenjaRincID')
                                ->where('TA', config('globalsettings.tahun_perencanaan'))
                                ->orderBy('Prioritas','ASC')
                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_usulan_musren_kab')
                        ->where('SOrgID',$SOrgID)                                     
                        ->whereNotNull('RenjaRincID')       
                        ->where('TA', config('globalsettings.tahun_perencanaan'))                                            
                        ->orderBy('Prioritas','ASC')
                        ->orderBy($column_order,$direction)                                            
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }        
        $data->setPath(route('pembahasanmusrenkab.index'));          
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
        
        $this->setCurrentPageInsideSession('pembahasanmusrenkab',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',
                                                                                        'label_transfer'=>'Verifikasi Renja',
                                                                                        'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
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
            case 'col-Sasaran_Angka4' :
                $column_name = 'Sasaran_Angka4';
            break;  
            case 'col-Jumlah4' :
                $column_name = 'Jumlah4';
            break;
            case 'col-Status' :
                $column_name = 'Status';
            break;
            default :
                $column_name = 'kode_kegiatan';
        }
        $this->putControllerStateSession('pembahasanmusrenkab','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanmusrenkab');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',
                                                                                    'label_transfer'=>'Verifikasi Renja',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
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

        $this->setCurrentPageInsideSession('pembahasanmusrenkab',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',
                                                                                'label_transfer'=>'Verifikasi Renja',
                                                                                'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
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
            $this->destroyControllerStateSession('pembahasanmusrenkab','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pembahasanmusrenkab','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pembahasanmusrenkab',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',                                                            
                                                                                        'label_transfer'=>'Verifikasi Renja',
                                                                                        'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
                                                                                        'data'=>$data])->render();      
        
        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request) 
    {
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('pembahasanmusrenkab','filters');
        $daftar_unitkerja=[];
        $json_data = [];
        
        // //index        
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $filters['SOrgID']='none';
            $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$OrgID);  
            
            $this->putControllerStateSession('pembahasanmusrenkab','filters',$filters);

            $data = [];

            $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',                                                            
                                                                                    'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
                                                                                    'data'=>$data])->render();
            
            $totalpaguindikatifopd = RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(config('globalsettings.tahun_perencanaan'),3,$filters['OrgID']);            
                  
            $totalpaguindikatifunitkerja[0]=0;
            $totalpaguindikatifunitkerja[1]=0;
            $totalpaguindikatifunitkerja[2]=0;
            $totalpaguindikatifunitkerja[3]=0;  
            
            $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                        ->where('OrgID',$filters['OrgID'])
                                                                        ->value('Jumlah1');

            $json_data = ['success'=>true,'paguanggaranopd'=>$paguanggaranopd,'daftar_unitkerja'=>$daftar_unitkerja,'totalpaguindikatifopd'=>$totalpaguindikatifopd,'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,'datatable'=>$datatable];
        } 
        //index
        if ($request->exists('SOrgID'))
        {
            $SOrgID = $request->input('SOrgID')==''?'none':$request->input('SOrgID');
            $filters['SOrgID']=$SOrgID;
            $this->putControllerStateSession('pembahasanmusrenkab','filters',$filters);
            $this->setCurrentPageInsideSession('pembahasanmusrenkab',1);

            $data = $this->populateData();

            $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',                                                            
                                                                                    'label_transfer'=>'Verifikasi Renja',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
                                                                                    'data'=>$data])->render(); 
                                                                                    
            $totalpaguindikatifunitkerja = RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(config('globalsettings.tahun_perencanaan'),3,$filters['SOrgID']);            
                                                                                    
            $json_data = ['success'=>true,'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,'datatable'=>$datatable];    
        }
        return $json_data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $auth = \Auth::user();    
        $theme = $auth->theme;
        
        $filters=$this->getControllerStateSession('pembahasanmusrenkab','filters');
        $roles=$auth->getRoleNames();        
        switch ($roles[0])
        {
            case 'superadmin' :                 
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false);  
                $daftar_unitkerja=array();           
                if ($filters['OrgID'] != 'none'&&$filters['OrgID'] != ''&&$filters['OrgID'] != null)
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$filters['OrgID']);        
                }    
            break;
            case 'opd' :
                $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(config('globalsettings.tahun_perencanaan'),false,NULL,$auth->OrgID);  
                $filters['OrgID']=$auth->OrgID;                
                if (empty($auth->SOrgID)) 
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$auth->OrgID);  
                    $filters['SOrgID']=empty($filters['SOrgID'])?'':$filters['SOrgID'];                    
                }   
                else
                {
                    $daftar_unitkerja=\App\Models\DMaster\SubOrganisasiModel::getDaftarUnitKerja(config('globalsettings.tahun_perencanaan'),false,$auth->OrgID,$auth->SOrgID);
                    $filters['SOrgID']=$auth->SOrgID;
                }                
                $this->putControllerStateSession('pembahasanmusrenkab','filters',$filters);
            break;
        }
        $search=$this->getControllerStateSession('pembahasanmusrenkab','search');        
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanmusrenkab'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pembahasanmusrenkab',$data->currentPage());
        $paguanggaranopd=\App\Models\DMaster\PaguAnggaranOPDModel::select('Jumlah1')
                                                                    ->where('OrgID',$filters['OrgID'])                                                    
                                                                    ->value('Jumlah1');
        
        return view("pages.$theme.musrenbang.pembahasanmusrenkab.index")->with(['page_active'=>'pembahasanmusrenkab',                                                                            
                                                                            'label_transfer'=>'Verifikasi Renja',
                                                                            'daftar_opd'=>$daftar_opd,
                                                                            'daftar_unitkerja'=>$daftar_unitkerja,
                                                                            'filters'=>$filters,
                                                                            'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
                                                                            'paguanggaranopd'=>$paguanggaranopd,
                                                                            'totalpaguindikatifopd'=>RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(config('globalsettings.tahun_perencanaan'),3,$filters['OrgID']),
                                                                            'totalpaguindikatifunitkerja' => RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(config('globalsettings.tahun_perencanaan'),3,$filters['SOrgID']),            
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

        return view("pages.$theme.musrenbang.pembahasanmusrenkab.create")->with(['page_active'=>'pembahasanmusrenkab',
                                                                    
                                                                           ]);  
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

        $data = RenjaRincianModel::findOrFail($id);
        if (!is_null($data) )  
        {            
            return view("pages.$theme.musrenbang.pembahasanmusrenkab.show")->with(['page_active'=>'pembahasanmusrenkab',
                                                                                'renja'=>$data,
                                                                                'item'=>$data
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
        $auth=\Auth::user();
        $theme = $auth->theme;
       
        $renja = RenjaRincianModel::select(\DB::raw('"trRenjaRinc"."RenjaRincID","trRenjaRinc"."RenjaID","trRenjaRinc"."PmKecamatanID","trRenjaRinc"."PmDesaID","trRenjaRinc"."No","trRenjaRinc"."No","trRenjaRinc"."Uraian","trRenjaRinc"."Sasaran_Angka4","trRenjaRinc"."Sasaran_Angka4","trRenjaRinc"."Sasaran_Uraian4","trRenjaRinc"."Target4","trRenjaRinc"."Jumlah4","trRenjaRinc"."Prioritas","trRenjaRinc"."Descr","trRenjaRinc"."isSKPD","trRenjaRinc"."isReses"'))                                                                                        
                                    ->findOrFail($id);       

        if (!is_null($renja) ) 
        {
            return view("pages.$theme.musrenbang.pembahasanmusrenkab.edit")->with(['page_active'=>'pembahasanmusrenkab',
                                                                            'renja'=>$renja,
         
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
        $theme = \Auth::user()->theme;

        $pembahasanmusrenkab = RenjaRincianModel::find($id);        
        $pembahasanmusrenkab->Status = $request->input('Status');
        $pembahasanmusrenkab->save();

        $RenjaID = $pembahasanmusrenkab->RenjaID;
        if (RenjaRincianModel::where('RenjaID',$RenjaID)->where('Status',1)->count() > 0)
        {
            RenjaModel::where('RenjaID',$RenjaID)->update(['Status'=>1]);
        }
        else
        {
            RenjaModel::where('RenjaID',$RenjaID)->update(['Status'=>0]);
        }        
        if ($request->ajax()) 
        {
            $filters=$this->getControllerStateSession('pembahasanmusrenkab','filters');

            $data = $this->populateData();
            
            $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',                                                            
                                                                                    'label_transfer'=>'Verifikasi Renja',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),                                                                                    
                                                                                    'data'=>$data])->render();
            
            $totalpaguindikatifopd = RenjaRincianModel::getTotalPaguIndikatifByStatusAndOPD(config('globalsettings.tahun_perencanaan'),3,$filters['OrgID']);                        
            $totalpaguindikatifunitkerja = RenjaRincianModel::getTotalPaguIndikatifByStatusAndUnitKerja(config('globalsettings.tahun_perencanaan'),3,$filters['SOrgID']);
                        
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.',
                'totalpaguindikatifopd'=>$totalpaguindikatifopd,
                'totalpaguindikatifunitkerja'=>$totalpaguindikatifunitkerja,
                'datatable'=>$datatable
            ],200);
        }
        else
        {
            return redirect(route('pembahasanmusrenkab.show',['id'=>$pembahasanmusrenkab->RenjaRincID]))->with('success','Data ini telah berhasil disimpan.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update2(Request $request, $id)
    {
        $rinciankegiatan = RenjaRincianModel::find($id);        
        $this->validate($request, [
            'No'=>'required',
            'Uraian'=>'required',
            'Sasaran_Angka4'=>'required',
            'Sasaran_Uraian4'=>'required',
            'Target4'=>'required',
            'Jumlah4'=>'required',
            'Prioritas' => 'required'            
        ]);
        $rinciankegiatan->PmKecamatanID = $request->input('PmKecamatanID');
        $rinciankegiatan->PmDesaID = $request->input('PmDesaID');
        $rinciankegiatan->Uraian = $request->input('Uraian');
        $rinciankegiatan->Sasaran_Angka4 = $request->input('Sasaran_Angka4'); 
        $rinciankegiatan->Sasaran_Uraian4 = $request->input('Sasaran_Uraian4');
        $rinciankegiatan->Target4 = $request->input('Target4');
        $rinciankegiatan->Jumlah4 = $request->input('Jumlah4');  
        $rinciankegiatan->Prioritas = $request->input('Prioritas');
        $rinciankegiatan->Status=$request->input('cmbStatus');                          
        $rinciankegiatan->Descr = $request->input('Descr');
        $rinciankegiatan->save();
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('pembahasanmusrenkab.show',['id'=>$rinciankegiatan->RenjaRincID]))->with('success','Data Rincian kegiatan telah berhasil disimpan.');
        } 
    }    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        $theme = \Auth::user()->theme;

        if ($request->exists('RenjaRincID'))
        {
            $RenjaRincID=$request->input('RenjaRincID');                                    
            $rincian_kegiatan=\DB::transaction(function () use ($RenjaRincID) {
                $rincian_kegiatan = RenjaRincianModel::find($RenjaRincID);               

                //check renja id sudah ada belum di RenjaID_Old
                $old_renja = RenjaModel::select('RenjaID')
                                        ->where('RenjaID_Src',$rincian_kegiatan->RenjaID)
                                        ->get()
                                        ->pluck('RenjaID')->toArray();

                
                if (count($old_renja) > 0)
                {
                    $RenjaID=$old_renja[0];
                    $newRenjaiD=$RenjaID;
                    $renja = RenjaModel::find($RenjaID);  
                }
                else
                {
                    $RenjaID=$rincian_kegiatan->RenjaID;
                    $renja = RenjaModel::find($RenjaID);   
                    $renja->Privilege=1;
                    $renja->save();

                    // #new renja
                    $newRenjaiD=uniqid ('uid');
                    $newrenja = $renja->replicate();
                    $newrenja->RenjaID = $newRenjaiD;
                    $newrenja->Sasaran_Uraian5 = $newrenja->Sasaran_Uraian4;
                    $newrenja->Sasaran_Angka5 = $newrenja->Sasaran_Angka4;
                    $newrenja->Target5 = $newrenja->Target4;
                    $newrenja->NilaiUsulan5 = $newrenja->NilaiUsulan4;
                    $newrenja->EntryLvl = 4;
                    $newrenja->Status = 0;
                    $newrenja->Privilege = 0;
                    $newrenja->RenjaID_Src = $RenjaID;
                    $newrenja->save();
                }               

                $str_rinciankegiatan = '
                    INSERT INTO "trRenjaRinc" (
                        "RenjaRincID", 
                        "RenjaID",
                        "UsulanKecID",
                        "PMProvID",
                        "PmKotaID",
                        "PmKecamatanID",
                        "PmDesaID",
                        "PokPirID",
                        "Uraian",
                        "No",
                        "Sasaran_Uraian1",
                        "Sasaran_Uraian2",              
                        "Sasaran_Uraian3",              
                        "Sasaran_Uraian4",                                      
                        "Sasaran_Uraian5",
                        "Sasaran_Angka1",
                        "Sasaran_Angka2",               
                        "Sasaran_Angka3",               
                        "Sasaran_Angka4",               
                        "Sasaran_Angka5",               
                        "Target1",
                        "Target2",                      
                        "Target3",                      
                        "Target4",                      
                        "Target5",                      
                        "Jumlah1", 
                        "Jumlah2", 
                        "Jumlah3", 
                        "Jumlah4", 
                        "Jumlah5", 
                        "isReses",
                        "isReses_Uraian",
                        "isSKPD",
                        "Status",
                        "EntryLvl",
                        "Prioritas",
                        "Descr",
                        "TA",
                        "created_at", 
                        "updated_at"
                    ) 
                    SELECT 
                        REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaRincID",
                        \''.$newRenjaiD.'\' AS "RenjaID",
                        "UsulanKecID",
                        "PMProvID",
                        "PmKotaID",
                        "PmKecamatanID",
                        "PmDesaID",
                        "PokPirID",
                        "Uraian",
                        "No",
                        "Sasaran_Uraian1",
                        "Sasaran_Uraian2",
                        "Sasaran_Uraian3",
                        "Sasaran_Uraian4",
                        "Sasaran_Uraian4" AS Sasaran_Angka5,              
                        "Sasaran_Angka1",
                        "Sasaran_Angka2",
                        "Sasaran_Angka3",
                        "Sasaran_Angka4",
                        "Sasaran_Angka4" AS "Sasaran_Angka5",               
                        "Target1",
                        "Target2",
                        "Target3",
                        "Target4",
                        "Target4" AS "Target5",                      
                        "Jumlah1", 
                        "Jumlah2", 
                        "Jumlah3", 
                        "Jumlah4", 
                        "Jumlah4" AS "Jumlah5", 
                        "isReses",
                        "isReses_Uraian",
                        "isSKPD",
                        0 AS "Status",
                        4 AS "EntryLvl",
                        "Prioritas",
                        "Descr",
                        "TA",
                        NOW() AS created_at,
                        NOW() AS updated_at
                    FROM 
                        "trRenjaRinc" 
                    WHERE "RenjaRincID"=\''.$RenjaRincID.'\' AND
                        ("Status"=1 OR "Status"=2) AND
                        "Privilege"=0      
                ';                
                \DB::statement($str_rinciankegiatan);       
                $str_kinerja='
                    INSERT INTO "trRenjaIndikator" (
                        "RenjaIndikatorID", 
                        "IndikatorKinerjaID",
                        "RenjaID",
                        "Target_Angka",
                        "Target_Uraian",  
                        "Tahun",      
                        "Descr",
                        "Privilege",
                        "TA",
                        "RenjaIndikatorID_Src",
                        "created_at", 
                        "updated_at"
                    )
                    SELECT 
                        REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RenjaIndikatorID",
                        "IndikatorKinerjaID",
                        \''.$newRenjaiD.'\' AS "RenjaID",
                        "Target_Angka",
                        "Target_Uraian",
                        "Tahun",
                        "Descr",
                        1 AS "Privilege",
                        "TA",
                        "RenjaIndikatorID" AS "RenjaIndikatorID_Src",
                        NOW() AS created_at,
                        NOW() AS updated_at
                    FROM 
                        "trRenjaIndikator" 
                    WHERE 
                        "RenjaID"=\''.$RenjaID.'\' AND
                        "Privilege"=0 
                ';
                \DB::statement($str_kinerja);

                RenjaRincianModel::where('RenjaRincID',$RenjaRincID)
                                    ->update(['Privilege'=>1]);
                RenjaIndikatorModel::where('RenjaID',$RenjaID)
                                    ->update(['Privilege'=>1]);

                return $rincian_kegiatan;
            });            

            if ($request->ajax()) 
            {
                $data = $this->populateData();
                
                $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkab.datatable")->with(['page_active'=>'pembahasanmusrenkab',                                                            
                                                                                    'label_transfer'=>'Verifikasi Renja',
                                                                                    'search'=>$this->getControllerStateSession('pembahasanmusrenkab','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanmusrenkab.orderby','order'),
                                                                                    'data'=>$data])->render();
                return response()->json([
                    'success'=>true,
                    'message'=>'Data ini telah berhasil ditransfer ke tahap verifikasi renja.',
                    'datatable'=>$datatable
                ],200);
            }
            else
            {
                return redirect(route('pembahasanmusrenkab.show',['id'=>$pembahasanmusrenkab->RenjaRincID]))->with('success','Data ini telah berhasil disimpan.');
            }
        }
        else
        {
            if ($request->ajax()) 
            {
                return response()->json([
                    'success'=>0,
                    'message'=>'Data ini gagal diubah.'
                ],200);
            }
            else
            {
                return redirect(route('pembahasanmusrenkab.error'))->with('error','Data ini gagal diubah.');
            }
        }
    }
}