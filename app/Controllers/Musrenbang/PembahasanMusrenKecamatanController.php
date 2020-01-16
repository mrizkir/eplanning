<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\KecamatanModel;
use App\Models\Musrenbang\AspirasiMusrenKecamatanModel;

class PembahasanMusrenKecamatanController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|kecamatan']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];          
        if (!$this->checkStateIsExistSession('pembahasanmusrenkecamatan','orderby')) 
        {            
           $this->putControllerStateSession('pembahasanmusrenkecamatan','orderby',['column_name'=>'tmPmKecamatan.Nm_Kecamatan','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        
        //filter
        if (!$this->checkStateIsExistSession('pembahasanmusrenkecamatan','filters')) 
        {            
            $this->putControllerStateSession('pembahasanmusrenkecamatan','filters',['PmKecamatanID'=>'none',
                                                                                ]);
        }        
        $PmKecamatanID= $this->getControllerStateSession('pembahasanmusrenkecamatan.filters','PmKecamatanID');        

        if ($this->checkStateIsExistSession('pembahasanmusrenkecamatan','search')) 
        {
            $search=$this->getControllerStateSession('pembahasanmusrenkecamatan','search');
            switch ($search['kriteria']) 
            {
                case 'No_usulan' :
                    $data = AspirasiMusrenKecamatanModel::select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","tmPmDesa"."Nm_Desa","trUsulanKec"."No_usulan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Bobot","trUsulanKec"."Privilege"'))
                                                        ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                                        ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                                                        ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')
                                                        ->where('trUsulanKec.TA', \HelperKegiatan::getTahunPerencanaan())
                                                        ->where(['No_usulan'=>(int)$search['isikriteria']])
                                                        ->where('trUsulanKec.PmKecamatanID',$PmKecamatanID)
                                                        ->orderBy('trUsulanKec.Prioritas','ASC')
                                                        ->orderBy($column_order,$direction); 
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenKecamatanModel::select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","tmPmDesa"."Nm_Desa","trUsulanKec"."No_usulan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Bobot","trUsulanKec"."Privilege"'))
                                                        ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                                        ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                                                        ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')                                                        
                                                        ->where('trUsulanKec.TA', \HelperKegiatan::getTahunPerencanaan())
                                                        ->where('NamaKegiatan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                        ->orderBy('trUsulanKec.Prioritas','ASC')
                                                        ->where('trUsulanKec.PmKecamatanID',$PmKecamatanID)
                                                        ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenKecamatanModel::select(\DB::raw('"trUsulanKec"."UsulanKecID","tmOrg"."OrgNm","tmPmDesa"."Nm_Desa","trUsulanKec"."No_usulan","trUsulanKec"."NamaKegiatan","trUsulanKec"."Output","trUsulanKec"."NilaiUsulan","trUsulanKec"."Target_Angka","trUsulanKec"."Target_Uraian","trUsulanKec"."Jeniskeg","trUsulanKec"."Prioritas","trUsulanKec"."Bobot","trUsulanKec"."Privilege"'))
                                                ->join('tmPmKecamatan','tmPmKecamatan.PmKecamatanID','trUsulanKec.PmKecamatanID')
                                                ->join('tmOrg','tmOrg.OrgID','trUsulanKec.OrgID')
                                                ->leftJoin('tmPmDesa','tmPmDesa.PmDesaID','trUsulanKec.PmDesaID')                                                                                                
                                                ->where('trUsulanKec.TA', \HelperKegiatan::getTahunPerencanaan())
                                                ->where('trUsulanKec.PmKecamatanID',$PmKecamatanID)
                                                ->orderBy('trUsulanKec.Prioritas','ASC')
                                                ->orderBy("$column_order",$direction)
                                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('pembahasanmusrenkecamatan.index'));
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
        
        $this->setCurrentPageInsideSession('pembahasanmusrenkecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.datatable")->with(['page_active'=>'pembahasanmusrenkecamatan',
                                                                                'search'=>$this->getControllerStateSession('pembahasanmusrenkecamatan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'),
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
            case 'col-No_usulan' :
                $column_name = 'No_usulan';
            break;
            case 'col-Nm_Desa' :
                $column_name = 'Nm_Desa';
            break;
            case 'col-Nm_Kecamatan' :
                $column_name = 'Nm_Kecamatan';
            break;
            case 'col-NamaKegiatan' :
                $column_name = 'NamaKegiatan';
            break;
            case 'col-NilaiUsulan' :
                $column_name = 'NilaiUsulan';
            break;      
            case 'col-Nm_Desa' :
                $column_name = 'Nm_Desa';
            break;  
            default :
                $column_name = 'No_usulan';
        }
        $this->putControllerStateSession('pembahasanmusrenkecamatan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.datatable")->with(['page_active'=>'pembahasanmusrenkecamatan',
                                                            'search'=>$this->getControllerStateSession('pembahasanmusrenkecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'),
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

        $this->setCurrentPageInsideSession('pembahasanmusrenkecamatan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.datatable")->with(['page_active'=>'pembahasanmusrenkecamatan',
                                                                            'search'=>$this->getControllerStateSession('pembahasanmusrenkecamatan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'),
                                                                            'data'=>$data])->render(); 

        return response()->json(['success'=>true,'datatable'=>$datatable],200);        
    }
    /**
     * search resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) 
    {
        $theme = \Auth::user()->theme;

        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('pembahasanmusrenkecamatan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('pembahasanmusrenkecamatan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('pembahasanmusrenkecamatan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.datatable")->with(['page_active'=>'pembahasanmusrenkecamatan',                                                            
                                                            'search'=>$this->getControllerStateSession('pembahasanmusrenkecamatan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'),
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

        $filters=$this->getControllerStateSession('pembahasanmusrenkecamatan','filters');

        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $filters['PmKecamatanID']=$PmKecamatanID;
        }   

        $this->putControllerStateSession('pembahasanmusrenkecamatan','filters',$filters);   
        $this->setCurrentPageInsideSession('pembahasanmusrenkecamatan',1);

        $data=$this->populateData();        
        $datatable = view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.datatable")->with(['page_active'=>'pembahasanmusrenkecamatan',                                                            
                                                                                        'search'=>$this->getControllerStateSession('pembahasanmusrenkecamatan','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'),
                                                                                        'filters'=>$filters,
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

        $search=$this->getControllerStateSession('pembahasanmusrenkecamatan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('pembahasanmusrenkecamatan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('pembahasanmusrenkecamatan',$data->currentPage());
        $filters=$this->getControllerStateSession('pembahasanmusrenkecamatan','filters');        
        $roles=$auth->getRoleNames();
        $daftar_kecamatan=[];
        switch ($roles[0])
        {
            case 'superadmin' :     
            case 'bapelitbang' :     
            case 'tapd' :
                $daftar_kecamatan=KecamatanModel::getDaftarKecamatan(\HelperKegiatan::getTahunPerencanaan(),false);
            break;
            case 'kecamatan':
                $daftar_kecamatan=\App\Models\UserKecamatan::getKecamatan();                      
                if (!count($daftar_kecamatan) > 0)
                {
                    $filters['PmKecamatanID']='none';
                    $this->putControllerStateSession('pembahasanmusrenkecamatan','filters',$filters);

                    return view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.error")->with(['page_active'=>'pembahasanmusrenkecamatan', 
                                                                                                'page_title'=>'PEMBAHASAN MUSRENBANG KECAMATAN',
                                                                                                'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                            ]);
                }    
            break;
        }
        $daftar_usulan_kec_id=\App\Models\Musrenbang\AspirasiMusrenKecamatanModel::select('UsulanKecID')
                                                                                ->where('Privilege',1)
                                                                                ->whereExists(function($query){
                                                                                    $query->select(\DB::raw(1))
                                                                                        ->from('trRenjaRinc')
                                                                                        ->whereRaw('"trRenjaRinc"."UsulanKecID"="trUsulanKec"."UsulanKecID"');
                                                                                })
                                                                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                                                ->get()->pluck('UsulanKecID','UsulanKecID')->toArray();
        
        return view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.index")->with(['page_active'=>'pembahasanmusrenkecamatan',
                                                                                    'daftar_kecamatan'=>$daftar_kecamatan,
                                                                                    'search'=>$this->getControllerStateSession('pembahasanmusrenkecamatan','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                    'column_order'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('pembahasanmusrenkecamatan.orderby','order'),
                                                                                    'filters'=>$filters,
                                                                                    'daftar_usulan_kec_id'=>$daftar_usulan_kec_id,
                                                                                    'data'=>$data]);               
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

        $data = AspirasiMusrenKecamatanModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.musrenbang.pembahasanmusrenkecamatan.show")->with(['page_active'=>'pembahasanmusrenkecamatan',
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
        $pembahasanmusrenkecamatan = AspirasiMusrenKecamatanModel::find($id);
        $pembahasanmusrenkecamatan->Privilege = $request->input('Privilege');
        $pembahasanmusrenkecamatan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('pembahasanmusrenkecamatan.show',['id'=>$id]))->with('success','Data ini telah berhasil disimpan.');
        }
    }
}