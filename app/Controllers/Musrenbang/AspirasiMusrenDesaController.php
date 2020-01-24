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
        $this->middleware(['auth','role:superadmin|bapelitbang|kecamatan|desa']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('aspirasimusrendesa','orderby')) 
        {            
           $this->putControllerStateSession('aspirasimusrendesa','orderby',['column_name'=>'tmPmDesa.Nm_Desa','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'); 
        $direction=$this->getControllerStateSession('aspirasimusrendesa.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');   
        $filters=$this->getControllerStateSession('aspirasimusrendesa','filters');
        if ($this->checkStateIsExistSession('aspirasimusrendesa','search')) 
        {
            $search=$this->getControllerStateSession('aspirasimusrendesa','search');
            switch ($search['kriteria']) 
            {
                case 'No_usulan' :                    
                    $data = AspirasiMusrenDesaModel::select(\DB::raw('"trUsulanDesa"."UsulanDesaID","tmPmDesa"."Nm_Desa","tmPmKecamatan"."Nm_Kecamatan","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot","trUsulanDesa"."Privilege","trUsulanKec"."UsulanKecID","trUsulanDesa"."TA"'))
                                                    ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                                                    ->join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                                    ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                                    ->where('tmPmDesa.PmKecamatanID',$filters['PmKecamatanID'])
                                                    ->where('trUsulanDesa.TA', \HelperKegiatan::getTahunPerencanaan())
                                                    ->where(['No_usulan'=>(int)$search['isikriteria']])
                                                    ->orderBy('Prioritas','ASC')
                                                    ->orderBy($column_order,$direction);
                break;
                case 'NamaKegiatan' :
                    $data = AspirasiMusrenDesaModel::select(\DB::raw('"trUsulanDesa"."UsulanDesaID","tmPmDesa"."Nm_Desa","tmPmKecamatan"."Nm_Kecamatan","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot","trUsulanDesa"."Privilege","trUsulanKec"."UsulanKecID","trUsulanDesa"."TA"'))
                                                    ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                                                    ->join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                                    ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                                    ->where('tmPmDesa.PmKecamatanID',$filters['PmKecamatanID'])
                                                    ->where('trUsulanDesa.TA', \HelperKegiatan::getTahunPerencanaan())
                                                    ->where('NamaKegiatan', 'ilike', '%' . $search['isikriteria'] . '%')
                                                    ->orderBy('Prioritas','ASC')                                        
                                                    ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = AspirasiMusrenDesaModel::select(\DB::raw('"trUsulanDesa"."UsulanDesaID","tmPmDesa"."Nm_Desa","tmPmKecamatan"."Nm_Kecamatan","trUsulanDesa"."No_usulan","trUsulanDesa"."NamaKegiatan","trUsulanDesa"."Output","trUsulanDesa"."NilaiUsulan","trUsulanDesa"."Target_Angka","trUsulanDesa"."Target_Uraian","trUsulanDesa"."Jeniskeg","trUsulanDesa"."Prioritas","trUsulanDesa"."Bobot","trUsulanDesa"."Privilege","trUsulanKec"."UsulanKecID","trUsulanDesa"."TA"'))
                                            ->leftJoin('trUsulanKec','trUsulanKec.UsulanDesaID','trUsulanDesa.UsulanDesaID')
                                            ->join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                            ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                            ->where('tmPmDesa.PmKecamatanID',$filters['PmKecamatanID'])
                                            ->where('trUsulanDesa.TA', \HelperKegiatan::getTahunPerencanaan())
                                            ->orderBy('trUsulanDesa.Prioritas','ASC')
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
            case 'col-No_usulan' :
                $column_name = 'trUsulanDesa.No_usulan';
            break;
            case 'col-Nm_Desa' :
                $column_name = 'tmPmDesa.Nm_Desa';
            break;
            case 'col-Nm_Kecamatan' :
                $column_name = 'tmPmKecamatan.Nm_Kecamatan';
            break;
            case 'col-NamaKegiatan' :
                $column_name = 'trUsulanDesa.NamaKegiatan';
            break;
            case 'col-NilaiUsulan' :
                $column_name = 'trUsulanDesa.NilaiUsulan';
            break;        
            default :
                $column_name = 'trUsulanDesa.No_usulan';
        }
        $this->putControllerStateSession('aspirasimusrendesa','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('aspirasimusrendesa'); 
        $data = $this->populateData($currentpage);
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
     * filter resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter (Request $request) 
    {
        $theme = \Auth::user()->theme;

        $filters=$this->getControllerStateSession('aspirasimusrendesa','filters');

        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $filters['PmKecamatanID']=$PmKecamatanID;
        }   

        $this->putControllerStateSession('aspirasimusrendesa','filters',$filters);   
        $this->setCurrentPageInsideSession('aspirasimusrendesa',1);

        $data=$this->populateData();        
        $datatable = view("pages.$theme.musrenbang.aspirasimusrendesa.datatable")->with(['page_active'=>'aspirasimusrendesa',                                                            
                                                                                        'search'=>$this->getControllerStateSession('aspirasimusrendesa','search'),
                                                                                        'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                        'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                                                        'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
                                                                                        'filters'=>$filters,
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
        $auth=\Auth::user();
        $theme = $auth->theme;

        //filter
        if (!$this->checkStateIsExistSession('aspirasimusrendesa','filters')) 
        {            
            $this->putControllerStateSession('aspirasimusrendesa','filters',[
                                                                            'PmKecamatanID'=>'none',
                                                                        ]);
        }        
        $filters=$this->getControllerStateSession('aspirasimusrendesa','filters');
        
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
                    $this->putControllerStateSession('aspirasimusrendesa','filters',$filters);

                    return view("pages.$theme.musrenbang.aspirasimusrendesa.error")->with(['page_active'=>'aspirasimusrendesa', 
                                                                                                'page_title'=>'ASPIRASI MUSRENBANG DESA',
                                                                                                'errormessage'=>'Anda Tidak Diperkenankan Mengakses Halaman ini, karena Sudah dikunci oleh BAPELITBANG',
                                                                                            ]);
                }    
            break;
        }
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
                                                                                'filters'=>$filters,
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                'column_order'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('aspirasimusrendesa.orderby','order'),
                                                                                'daftar_kecamatan'=>$daftar_kecamatan,
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
        $filters=$this->getControllerStateSession('aspirasimusrendesa','filters');
        $PmKecamatanID=$filters['PmKecamatanID'];
        if ($PmKecamatanID == 'none')
        {
            return view("pages.$theme.musrenbang.aspirasimusrendesa.error")->with(['page_active'=>'aspirasimusrendesa', 
                                                                                                'page_title'=>'ASPIRASI MUSRENBANG DESA',
                                                                                                'errormessage'=>'Mohon terlebih dahulu, dipilih Kecamatan',
                                                                                            ]);
        }
        else
        {
            $kecamatan = \App\Models\DMaster\KecamatanModel::find($PmKecamatanID);
            $daftar_desa = DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$PmKecamatanID,false);
            $sumber_dana = SumberDanaModel::getDaftarSumberDana(\HelperKegiatan::getTahunPerencanaan(),false);
            return view("pages.$theme.musrenbang.aspirasimusrendesa.create")->with(['page_active'=>'aspirasimusrendesa',
                                                                                'daftar_desa'=>$daftar_desa,
                                                                                'sumber_dana'=>$sumber_dana,
                                                                                'kecamatan'=>$kecamatan,
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
            'PmDesaID'=>'required',
            'NamaKegiatan'=>'required',
            'Output'=>'required',
            'Lokasi'=>'required',
            'NilaiUsulan'=>'required',
            'Target_Uraian'=>'required',
            'Target_Angka'=>'required',
            'Prioritas'=>'required|not_in:none'            
        ]);
        $jeniskeg=$request->has('Jeniskeg')?$request->input('Jeniskeg'):0;
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
            'Jeniskeg' => $jeniskeg,
            'Prioritas' => $request->input('Prioritas'),
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
            return redirect(route('aspirasimusrendesa.show',['uuid'=>$aspirasimusrendesa->UsulanDesaID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                        ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                        ->where('trUsulanDesa.TA', \HelperKegiatan::getTahunPerencanaan())
                                        ->findOrFail($id);
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
        
        $data = AspirasiMusrenDesaModel::join('tmPmDesa','tmPmDesa.PmDesaID','trUsulanDesa.PmDesaID')
                                        ->join('tmPmKecamatan','tmPmDesa.PmKecamatanID','tmPmKecamatan.PmKecamatanID')
                                        ->where('Privilege',0)
                                        ->findOrFail($id);
        if (!is_null($data) ) 
        {
            $kecamatan = \App\Models\DMaster\KecamatanModel::find($data->PmKecamatanID);
            $daftar_desa = DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$data->PmKecamatanID,false);
            $sumber_dana = SumberDanaModel::getDaftarSumberDana(\HelperKegiatan::getTahunPerencanaan(),false);
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
            'PmDesaID'=>'required',
            'NamaKegiatan'=>'required',
            'Output'=>'required',
            'Lokasi'=>'required',
            'NilaiUsulan'=>'required',
            'Target_Uraian'=>'required',
            'Target_Angka'=>'required',
            'Prioritas'=>'required|not_in:none'            
        ]);
        
        $aspirasimusrendesa->PmDesaID=$request->input('PmDesaID');
        $aspirasimusrendesa->SumberDanaID=$request->input('SumberDanaID');        
        $aspirasimusrendesa->NamaKegiatan=$request->input('NamaKegiatan');
        $aspirasimusrendesa->Output=$request->input('Output');
        $aspirasimusrendesa->Lokasi=$request->input('Lokasi');
        $aspirasimusrendesa->NilaiUsulan=$request->input('NilaiUsulan');
        $aspirasimusrendesa->Target_Angka=$request->input('Target_Angka');
        $aspirasimusrendesa->Target_Uraian=$request->input('Target_Uraian');
        $aspirasimusrendesa->Jeniskeg=$request->input('Jeniskeg');
        $aspirasimusrendesa->Prioritas=$request->input('Prioritas');
        $aspirasimusrendesa->Descr=$request->input('Descr');        
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
            return redirect(route('aspirasimusrendesa.show',['uuid'=>$aspirasimusrendesa->UsulanDesaID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $aspirasimusrendesa = AspirasiMusrenDesaModel::where('Privilege',0)
                                                        ->find($id);        
        if ($request->ajax()) 
        {
            $bool=false;
            if ($aspirasimusrendesa != null)            
            {
                $bool=true;
                $result=$aspirasimusrendesa->delete();
            }            
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
            
            return response()->json(['success'=>$bool,'datatable'=>$datatable],200); 
        }
        else
        {
            if ($aspirasimusrendesa == null)            
            {
                return redirect(route('aspirasimusrendesa.error'))->with('error',"Data ini dengan ($id) telah gagal dihapus.");
            }
            else
            {                
                $result=$aspirasimusrendesa->delete();
                return redirect(route('aspirasimusrendesa.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
            }
        }        
    }
}