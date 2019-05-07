<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RKPD\VerifikasiRenjaModel;

class VerifikasiRenjaController extends Controller {
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
        //if (!$this->checkStateIsExistSession('verifikasirenja','orderby')) 
        //{            
        //    $this->putControllerStateSession('verifikasirenja','orderby',['column_name'=>'replace_it','order'=>'asc']);
        //}
        //$column_order=$this->getControllerStateSession('verifikasirenja.orderby','column_name'); 
        //$direction=$this->getControllerStateSession('verifikasirenja.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('verifikasirenja','search')) 
        {
            $search=$this->getControllerStateSession('verifikasirenja','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = VerifikasiRenjaModel::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = VerifikasiRenjaModel::where('replaceit', 'ilike', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = VerifikasiRenjaModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('verifikasirenja.index'));
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
        
        $this->setCurrentPageInsideSession('verifikasirenja',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.verifikasirenja.datatable")->with(['page_active'=>'verifikasirenja',
                                                                                'search'=>$this->getControllerStateSession('verifikasirenja','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('verifikasirenja.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('verifikasirenja.orderby','order'),
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
            case 'replace_it' :
                $column_name = 'replace_it';
            break;           
            default :
                $column_name = 'replace_it';
        }
        $this->putControllerStateSession('verifikasirenja','orderby',['column_name'=>$column_name,'order'=>$orderby]);      

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('verifikasirenja');         
        $data=$this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        
        $datatable = view("pages.$theme.rkpd.verifikasirenja.datatable")->with(['page_active'=>'verifikasirenja',
                                                            'search'=>$this->getControllerStateSession('verifikasirenja','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('verifikasirenja.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('verifikasirenja.orderby','order'),
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

        $this->setCurrentPageInsideSession('verifikasirenja',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.verifikasirenja.datatable")->with(['page_active'=>'verifikasirenja',
                                                                            'search'=>$this->getControllerStateSession('verifikasirenja','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('verifikasirenja.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('verifikasirenja.orderby','order'),
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
            $this->destroyControllerStateSession('verifikasirenja','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('verifikasirenja','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('verifikasirenja',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.verifikasirenja.datatable")->with(['page_active'=>'verifikasirenja',                                                            
                                                            'search'=>$this->getControllerStateSession('verifikasirenja','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('verifikasirenja.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('verifikasirenja.orderby','order'),
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

        $search=$this->getControllerStateSession('verifikasirenja','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('verifikasirenja'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('verifikasirenja',$data->currentPage());
        
        return view("pages.$theme.rkpd.verifikasirenja.index")->with(['page_active'=>'verifikasirenja',
                                                'search'=>$this->getControllerStateSession('verifikasirenja','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('verifikasirenja.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('verifikasirenja.orderby','order'),
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

        return view("pages.$theme.rkpd.verifikasirenja.create")->with(['page_active'=>'verifikasirenja',
                                                                    
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
        
        $verifikasirenja = VerifikasiRenjaModel::create([
            'replaceit' => $request->input('replaceit'),
        ]);        
        
        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ],200);
        }
        else
        {
            return redirect(route('verifikasirenja.show',['id'=>$verifikasirenja->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = VerifikasiRenjaModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rkpd.verifikasirenja.show")->with(['page_active'=>'verifikasirenja',
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
        
        $data = VerifikasiRenjaModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.rkpd.verifikasirenja.edit")->with(['page_active'=>'verifikasirenja',
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
        $verifikasirenja = VerifikasiRenjaModel::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $verifikasirenja->replaceit = $request->input('replaceit');
        $verifikasirenja->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ],200);
        }
        else
        {
            return redirect(route('verifikasirenja.show',['id'=>$verifikasirenja->replaceit]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $verifikasirenja = VerifikasiRenjaModel::find($id);
        $result=$verifikasirenja->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('verifikasirenja'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rkpd.verifikasirenja.datatable")->with(['page_active'=>'verifikasirenja',
                                                            'search'=>$this->getControllerStateSession('verifikasirenja','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('verifikasirenja.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('verifikasirenja.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('verifikasirenja.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}