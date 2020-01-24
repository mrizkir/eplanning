<?php

namespace App\Controllers\Aspirasi;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Aspirasi\LongListModel;

class LongListController extends Controller {
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
        if (!$this->checkStateIsExistSession('longlist','orderby')) 
        {            
           $this->putControllerStateSession('longlist','orderby',['column_name'=>'KgtNm','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('longlist.orderby','column_name'); 
        $direction=$this->getControllerStateSession('longlist.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');       
        //filter
        if (!$this->checkStateIsExistSession('longlist','filters')) 
        {            
            $this->putControllerStateSession('longlist','filters',[
                                                                    'OrgID'=>'none',
                                                                ]);
        }        
        $OrgID= $this->getControllerStateSession(\Helper::getNameOfPage('filters'),'OrgID');      
        if ($this->checkStateIsExistSession('longlist','search')) 
        {
            $search=$this->getControllerStateSession('longlist','search');
            switch ($search['kriteria'])    
            {
                case 'KgtNm' :
                    $data = LongListModel::select(\DB::raw('"tmLongList"."LongListID",
                                                        "tmLongList"."KgtNm",
                                                        "tmLongList"."Lokasi",
                                                    '))      
                                                ->join('v_urusan_organisasi','v_urusan_organisasi.OrgID','tmLongList.OrgID')
                                                ->where('tmLongList.TA',\HelperKegiatan::getTahunPerencanaan())                                        
                                                ->where('tmLongList.KgtNm', 'ilike', '%' . $search['isikriteria'] . '%');
                                                
                                               
                    if ($OrgID!='all')
                    {
                        $data = $data->where('tmLongList.OrgID',$OrgID);
                    }
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);              
        }
        else
        {
            $data = LongListModel::select(\DB::raw('"tmLongList"."LongListID",
                                                        "tmLongList"."KgtNm",
                                                        "tmLongList"."Sasaran_Angka",
                                                        "tmLongList"."Sasaran_Uraian",
                                                        "v_urusan_organisasi"."OrgNm",
                                                        "tmLongList"."Lokasi"
                                                    '))            
                                    ->join('v_urusan_organisasi','v_urusan_organisasi.OrgID','tmLongList.OrgID')
                                    ->where('tmLongList.TA',\HelperKegiatan::getTahunPerencanaan());
                                    // ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
            
            if ($OrgID!='none' && $OrgID!='')
            {               
                $data = $data->where('tmLongList.OrgID',$OrgID);
            }
            
            $data = $data->orderBy($column_order,$direction)
                        ->paginate($numberRecordPerPage, $columns, 'page', $currentpage);             
        }        
        $data->setPath(route('longlist.index'));
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
        $roles=\Auth::user()->getRoleNames();

        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('longlist',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.longlist.datatable")->with(['page_active'=>'longlist',
                                                                                'search'=>$this->getControllerStateSession('longlist','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                                                'role'=>$roles[0],
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
        $roles=\Auth::user()->getRoleNames();
        $orderby = $request->input('orderby') == 'asc'?'desc':'asc';
        $column=$request->input('column_name');
        switch($column) 
        {
            case 'col-KdPK' :
                $column_name = 'KdPK';
            break;           
            case 'col-NmPk' :
                $column_name = 'NmPk';
            break;           
            case 'col-KgtNm' :
                $column_name = 'KgtNm';
            break;           
            case 'col-Prioritas' :
                $column_name = 'Prioritas';
            break;                            
            default :
                $column_name = 'KdPK';
        }
        $this->putControllerStateSession('longlist','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.longlist.datatable")->with(['page_active'=>'longlist',
                                                            'search'=>$this->getControllerStateSession('longlist','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                            'role'=>$roles[0],
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
        $roles=\Auth::user()->getRoleNames();

        $this->setCurrentPageInsideSession('longlist',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.aspirasi.longlist.datatable")->with(['page_active'=>'longlist',
                                                                            'search'=>$this->getControllerStateSession('longlist','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                                            'role'=>$roles[0],
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
        $roles=$auth->getRoleNames();

        $filters=$this->getControllerStateSession('longlist','filters');

        $json_data = [];     
        //index
        if ($request->exists('OrgID'))
        {
            $OrgID = $request->input('OrgID')==''?'none':$request->input('OrgID');
            $filters['OrgID']=$OrgID;
            $this->putControllerStateSession('longlist','filters',$filters);
            $this->setCurrentPageInsideSession('longlist',1);

            $data=$this->populateData();
            $datatable = view("pages.$theme.aspirasi.longlist.datatable")->with(['page_active'=>'longlist',
                                                                                    'search'=>$this->getControllerStateSession('longlist','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                    'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                                                    'role'=>$roles[0],
                                                                                    'data'=>$data])->render();      
            return response()->json(['success'=>true,'datatable'=>$datatable],200);       
        }           
        //create4
        if ($request->exists('PmKecamatanID'))
        {
            $PmKecamatanID = $request->input('PmKecamatanID')==''?'none':$request->input('PmKecamatanID');
            $daftar_desa=\App\Models\DMaster\DesaModel::getDaftarDesa(\HelperKegiatan::getTahunPerencanaan(),$PmKecamatanID,false);
                                                                                    
            $json_data = ['success'=>true,'daftar_desa'=>$daftar_desa];            
        } 

        return response()->json($json_data,200);  
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
        $roles=\Auth::user()->getRoleNames();
        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('longlist','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('longlist','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('longlist',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.aspirasi.longlist.datatable")->with(['page_active'=>'longlist',                                                            
                                                            'search'=>$this->getControllerStateSession('longlist','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                            'role'=>$roles[0],
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
        $auth = \Auth::user();    
        $theme = $auth->theme;

        $filters=$this->getControllerStateSession('longlist','filters');

        $roles=$auth->getRoleNames(); 
        
        $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);                  
                
        $search=$this->getControllerStateSession('longlist','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('longlist'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('longlist',$data->currentPage());

        return view("pages.$theme.aspirasi.longlist.index")->with(['page_active'=>'longlist',
                                                                    'filters'=>$filters,
                                                                    'daftar_opd'=>$daftar_opd,
                                                                    'search'=>$this->getControllerStateSession('longlist','search'),
                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                    'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                                    'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                                    'role'=>$roles[0],
                                                                    'data'=>$data]);               
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $auth = \Auth::user(); 
        $theme =  $auth->theme;
        $filters = $this->getControllerStateSession('longlist','filters');

        if ($filters['OrgID']=='none' || $filters['OrgID']=='all')
        {
            return view("pages.$theme.aspirasi.longlist.error")->with(['page_active'=>'longlist',
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon OPD / SKPD untuk di pilih terlebih dahulu.'
                                                                ]);  
        }
        else
        {
            $daftar_opd=\App\Models\DMaster\OrganisasiModel::getDaftarOPD(\HelperKegiatan::getTahunPerencanaan(),false);                  

            return view("pages.$theme.aspirasi.longlist.create")->with(['page_active'=>'longlist',
                                                                            'filters'=>$this->getControllerStateSession('longlist','filters'),
                                                                            'daftar_opd'=>$daftar_opd,
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
            'OrgID'=>'required',
            'KgtNm'=>'required',
            'Lokasi'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',            
        ]);
        $longlist = LongListModel::create([
            'LongListID' => uniqid ('uid'),
            'OrgID' => $request->input('OrgID'),
            'KgtNm' => $request->input('KgtNm'),
            'Lokasi' => $request->input('Lokasi'),
            'Sasaran_Uraian' => $request->input('Sasaran_Uraian'),
            'Sasaran_Angka' => $request->input('Sasaran_Angka'),
            'NilaiUsulan' => 0,
            'Descr' => $request->input('Descr'),
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
            return redirect(route('longlist.show',['uuid'=>$longlist->LongListID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = LongListModel::join('v_urusan_organisasi','v_urusan_organisasi.OrgID','tmLongList.OrgID')
                                ->findOrFail($id);
        if (!is_null($data) )  
        {
            // dd($data);
            return view("pages.$theme.aspirasi.longlist.show")->with(['page_active'=>'longlist',                                                                        
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
        $auth = \Auth::user(); 
        $theme =  $auth->theme;

        $data = LongListModel::findOrFail($id);        
            
        if (!is_null($data) ) 
        {           
            return view("pages.$theme.aspirasi.longlist.edit")->with(['page_active'=>'longlist',
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
        $longlist = LongListModel::find($id);        
        
        $this->validate($request, [
            'KgtNm'=>'required',
            'Lokasi'=>'required',
            'Sasaran_Angka'=>'required',
            'Sasaran_Uraian'=>'required',            
        ]);       
        
        $longlist->KgtNm = $request->input('KgtNm');
        $longlist->Lokasi = $request->input('Lokasi');
        $longlist->Sasaran_Angka = $request->input('Sasaran_Angka');
        $longlist->Sasaran_Uraian = $request->input('Sasaran_Uraian');
        $longlist->Descr = $request->input('Descr');
       
        $longlist->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('longlist.show',['uuid'=>$longlist->LongListID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $longlist = LongListModel::find($id);
        $result=$longlist->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('longlist'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.aspirasi.longlist.datatable")->with(['page_active'=>'longlist',
                                                            'search'=>$this->getControllerStateSession('longlist','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('longlist.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('longlist.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('longlist.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
    /**
     * Print to Excel
     *    
     * @return \Illuminate\Http\Response
     */
    public function printtoexcel ()
    {
        $theme = \Auth::user()->theme;
        
        $data_report=$this->getControllerStateSession('longlist','filters');

        if ($data_report['OrgID'] == 'none')
        {
            return view("pages.$theme.aspirasi.longlist.error")->with(['page_active'=>'longlist',
                                                                    'page_title'=>\HelperKegiatan::getPageTitle($this->NameOfPage),
                                                                    'errormessage'=>'Mohon Pemilik Bansos dan Hibah untuk di pilih terlebih dahulu.'
                                                                ]);  
        }   
        else
        {
            $generate_date=date('Y-m-d_H_m_s');        
            $report= new \App\Models\Report\ReportLongListModel ($data_report);
            return $report->download("longlist_$generate_date.xlsx");
        }     
    }
}