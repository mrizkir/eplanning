<?php

namespace App\Controllers\DMaster;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\UrusanModel;

class UrusanController extends Controller {
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
        //if (!$this->checkStateIsExistSession('urusan','orderby')) 
        //{            
        //    $this->putControllerStateSession('urusan','orderby',['column_name'=>'replace_it','order'=>'asc']);
        //}
        //$column_order=$this->getControllerStateSession('urusan.orderby','column_name'); 
        //$direction=$this->getControllerStateSession('urusan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('urusan','search')) 
        {
            $search=$this->getControllerStateSession('urusan','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = UrusanModel::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = UrusanModel::where('replaceit', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = UrusanModel::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('urusan.index'));
        return $data;
    }
    /**
     * digunakan untuk mengganti jumlah record per halaman
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changenumberrecordperpage (Request $request) 
    {
        $numberRecordPerPage = $request->input('numberRecordPerPage');
        $this->putControllerStateSession('global_controller','numberRecordPerPage',$numberRecordPerPage);
        
        $this->setCurrentPageInsideSession('urusan',1);
        $data=$this->populateData();

        $datatable = view("pages.{$this->theme}.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                                                'search'=>$this->getControllerStateSession('urusan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
        $this->putControllerStateSession('urusan','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.{$this->theme}.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
        $this->setCurrentPageInsideSession('urusan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.{$this->theme}.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
        $action = $request->input('action');
        if ($action == 'reset') 
        {
            $this->destroyControllerStateSession('urusan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('urusan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('urusan',1);
        $data=$this->populateData();

        $datatable = view("pages.{$this->theme}.dmaster.urusan.datatable")->with(['page_active'=>'urusan',                                                            
                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
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
        $search=$this->getControllerStateSession('urusan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('urusan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('urusan',$data->currentPage());
        
        return view("pages.{$this->theme}.dmaster.urusan.index")->with(['page_active'=>'urusan',
                                                'search'=>$this->getControllerStateSession('urusan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
                                                'data'=>$data]);               
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        return view("pages.{$this->theme}.dmaster.urusan.create")->with(['page_active'=>'urusan',
                                                                    
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
        
        $urusan = new UrusanModel;
        $urusan->replaceit = $request->input('replaceit');
        $urusan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil disimpan.'
            ]);
        }
        else
        {
            return redirect(route('urusan.index'))->with('success','Data ini telah berhasil disimpan.');
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
        $data = UrusanModel::find($id);
        if (!is_null($data) )  
        {
            return view("pages.{$this->theme}.dmaster.urusan.show")->with(['page_active'=>'urusan',
                                                    'data'=>$data
                                                    ]);
        }
        else
        {
            $errormessage="Data dengan ID ($id) tidak ditemukan.";            
            return view("pages.{$this->theme}.dmaster.urusan.error")->with(['page_active'=>'permissions',
                                                                    'errormessage'=>$errormessage
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
        $data = UrusanModel::find($id);
        if (!is_null($data) ) 
        {
            return view("pages.{$this->theme}.dmaster.urusan.edit")->with(['page_active'=>'urusan',
                                                    'data'=>$data
                                                    ]);
        }
        else
        {
            $errormessage="Data dengan ID ($id) tidak ditemukan.";            
            return view("pages.{$this->theme}.dmaster.urusan.error")->with(['page_active'=>'permissions',
                                                                    'errormessage'=>$errormessage
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
            'replaceit'=>'required',
        ]);
        
        $urusan = UrusanModel::find($id);
        $urusan->replaceit = $request->input('replaceit');
        $urusan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('urusan.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        $urusan = UrusanModel::find($id);
        $result=$urusan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('urusan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.{$this->theme}.dmaster.urusan.datatable")->with(['page_active'=>'urusan',
                                                            'search'=>$this->getControllerStateSession('urusan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('urusan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('urusan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('urusan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}