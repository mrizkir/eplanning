<?php

namespace App\Controllers\RENSTRA;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RENSTRA\RENSTRAStrategiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RENSTRAStrategiController extends Controller {
     /**
     * Membuat sebuah objek
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth','role:superadmin|bapelitbang|opd']);
    }
    /**
     * collect data from resources for index view
     *
     * @return resources
     */
    public function populateData ($currentpage=1) 
    {        
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('renstrastrategi','orderby')) 
        {            
           $this->putControllerStateSession('renstrastrategi','orderby',['column_name'=>'Nm_Strategi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('renstrastrategi.orderby','column_name'); 
        $direction=$this->getControllerStateSession('renstrastrategi.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('renstrastrategi','search')) 
        {
            $search=$this->getControllerStateSession('renstrastrategi','search');
            switch ($search['kriteria']) 
            {
                case 'Kd_Strategi' :
                    $data = RENSTRAStrategiModel::where(['Kd_Strategi'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'Nm_Strategi' :
                    $data = RENSTRAStrategiModel::where('Nm_Strategi', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = RENSTRAStrategiModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('renstrastrategi.index'));
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
        
        $this->setCurrentPageInsideSession('renstrastrategi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                                                'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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
            case 'Nm_Strategi' :
                $column_name = 'Nm_Strategi';
            break;           
            default :
                $column_name = 'Nm_Strategi';
        }
        $this->putControllerStateSession('renstrastrategi','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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

        $this->setCurrentPageInsideSession('renstrastrategi',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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
            $this->destroyControllerStateSession('renstrastrategi','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('renstrastrategi','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('renstrastrategi',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',                                                            
                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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

        $search=$this->getControllerStateSession('renstrastrategi','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('renstrastrategi'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('renstrastrategi',$data->currentPage());
        
        return view("pages.$theme.renstra.renstrastrategi.index")->with(['page_active'=>'renstrastrategi',
                                                'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
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

        $daftar_sasaran=\App\Models\RENSTRA\RENSTRASasaranModel::select(\DB::raw('"PrioritasSasaranKabID",CONCAT(\'[\',"Kd_Sasaran",\']. \',"Nm_Sasaran") AS "Nm_Sasaran"'))
                                                            ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                            ->orderBy('Kd_Sasaran','ASC')
                                                            ->get()
                                                            ->pluck('Nm_Sasaran','PrioritasSasaranKabID')
                                                            ->toArray();

        return view("pages.$theme.renstra.renstrastrategi.create")->with(['page_active'=>'renstrastrategi',
                                                                    'daftar_sasaran'=>$daftar_sasaran
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
            'Kd_Strategi'=>[new CheckRecordIsExistValidation('tmPrioritasStrategiKab',['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]]),
                            'required'
                        ],
            'PrioritasSasaranKabID'=>'required',
            'Nm_Strategi'=>'required',
        ]);
        
        $renstrastrategi = RENSTRAStrategiModel::create([
            'PrioritasStrategiKabID'=> uniqid ('uid'),
            'PrioritasSasaranKabID' => $request->input('PrioritasSasaranKabID'),
            'Kd_Strategi' => $request->input('Kd_Strategi'),
            'Nm_Strategi' => $request->input('Nm_Strategi'),
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
            return redirect(route('renstrastrategi.show',['id'=>$renstrastrategi->PrioritasStrategiKabID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = RENSTRAStrategiModel::select(\DB::raw('"tmPrioritasStrategiKab"."PrioritasStrategiKabID",
                                                    "tmPrioritasSasaranKab"."Kd_Sasaran",
                                                    "tmPrioritasSasaranKab"."Nm_Sasaran",
                                                    "tmPrioritasStrategiKab"."Kd_Strategi",
                                                    "tmPrioritasStrategiKab"."Nm_Strategi",
                                                    "tmPrioritasStrategiKab"."Descr",
                                                    "tmPrioritasStrategiKab"."PrioritasStrategiKabID_Src",
                                                    "tmPrioritasStrategiKab"."created_at",
                                                    "tmPrioritasStrategiKab"."updated_at"'))
                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                                ->findOrFail($id);

        if (!is_null($data) )  
        {
            return view("pages.$theme.renstra.renstrastrategi.show")->with(['page_active'=>'renstrastrategi',
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
        
        $data = RENSTRAStrategiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_sasaran=\App\Models\RENSTRA\RENSTRASasaranModel::select(\DB::raw('"PrioritasSasaranKabID",CONCAT(\'[\',"Kd_Sasaran",\']. \',"Nm_Sasaran") AS "Nm_Sasaran"'))
                                                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                                                ->orderBy('Kd_Sasaran','ASC')
                                                                ->get()
                                                                ->pluck('Nm_Sasaran','PrioritasSasaranKabID')
                                                                ->toArray();

            return view("pages.$theme.renstra.renstrastrategi.edit")->with(['page_active'=>'renstrastrategi',
                                                                    'data'=>$data,
                                                                    'daftar_sasaran'=>$daftar_sasaran
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
        $renstrastrategi = RENSTRAStrategiModel::find($id);
        
        $this->validate($request, [
            'Kd_Strategi'=>['required',new IgnoreIfDataIsEqualValidation('tmPrioritasStrategiKab',
                                                                        $renstrastrategi->Kd_Strategi,
                                                                        ['where'=>['TA','=',\HelperKegiatan::getTahunPerencanaan()]],
                                                                        'Kode Strategi')],
            'PrioritasSasaranKabID'=>'required',
            'Nm_Strategi'=>'required',
        ]);
               
        $renstrastrategi->PrioritasSasaranKabID = $request->input('PrioritasSasaranKabID');
        $renstrastrategi->Kd_Strategi = $request->input('Kd_Strategi');
        $renstrastrategi->Nm_Strategi = $request->input('Nm_Strategi');
        $renstrastrategi->Descr = $request->input('Descr');
        $renstrastrategi->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('renstrastrategi.show',['id'=>$renstrastrategi->PrioritasStrategiKabID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $renstrastrategi = RENSTRAStrategiModel::find($id);
        $result=$renstrastrategi->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('renstrastrategi'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.renstra.renstrastrategi.datatable")->with(['page_active'=>'renstrastrategi',
                                                            'search'=>$this->getControllerStateSession('renstrastrategi','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('renstrastrategi.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('renstrastrategi.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('renstrastrategi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
