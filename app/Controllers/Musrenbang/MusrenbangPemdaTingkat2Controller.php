<?php

namespace App\Controllers\Musrenbang;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\Musrenbang\MusrenbangPemdaTingkat2Model;

class MusrenbangPemdaTingkat2Controller extends Controller {
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
        //if (!$this->checkStateIsExistSession('musrenbangpemdatingkat2','orderby')) 
        //{            
        //    $this->putControllerStateSession('musrenbangpemdatingkat2','orderby',['column_name'=>'replace_it','order'=>'asc']);
        //}
        //$column_order=$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'); 
        //$direction=$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('musrenbangpemdatingkat2','search')) 
        {
            $search=$this->getControllerStateSession('musrenbangpemdatingkat2','search');
            switch ($search['kriteria']) 
            {
                case 'replaceit' :
                    $data = MusrenbangPemdaTingkat2Model::where(['replaceit'=>$search['isikriteria']])->orderBy($column_order,$direction); 
                break;
                case 'replaceit' :
                    $data = MusrenbangPemdaTingkat2Model::where('replaceit', 'like', '%' . $search['isikriteria'] . '%')->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = MusrenbangPemdaTingkat2Model::orderBy($column_order,$direction)->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('musrenbangpemdatingkat2.index'));
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
        
        $this->setCurrentPageInsideSession('musrenbangpemdatingkat2',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.musrenbangpemdatingkat2.datatable")->with(['page_active'=>'musrenbangpemdatingkat2',
                                                                                'search'=>$this->getControllerStateSession('musrenbangpemdatingkat2','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'),
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
        $this->putControllerStateSession('musrenbangpemdatingkat2','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.musrenbangpemdatingkat2.datatable")->with(['page_active'=>'musrenbangpemdatingkat2',
                                                            'search'=>$this->getControllerStateSession('musrenbangpemdatingkat2','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'),
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

        $this->setCurrentPageInsideSession('musrenbangpemdatingkat2',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.musrenbang.musrenbangpemdatingkat2.datatable")->with(['page_active'=>'musrenbangpemdatingkat2',
                                                                            'search'=>$this->getControllerStateSession('musrenbangpemdatingkat2','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'),
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
            $this->destroyControllerStateSession('musrenbangpemdatingkat2','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('musrenbangpemdatingkat2','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('musrenbangpemdatingkat2',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.musrenbang.musrenbangpemdatingkat2.datatable")->with(['page_active'=>'musrenbangpemdatingkat2',                                                            
                                                            'search'=>$this->getControllerStateSession('musrenbangpemdatingkat2','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'),
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

        $search=$this->getControllerStateSession('musrenbangpemdatingkat2','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('musrenbangpemdatingkat2'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('musrenbangpemdatingkat2',$data->currentPage());
        
        return view("pages.$theme.musrenbang.musrenbangpemdatingkat2.index")->with(['page_active'=>'musrenbangpemdatingkat2',
                                                'search'=>$this->getControllerStateSession('musrenbangpemdatingkat2','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'),
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

        return view("pages.$theme.musrenbang.musrenbangpemdatingkat2.create")->with(['page_active'=>'musrenbangpemdatingkat2',
                                                                    
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
        
        $musrenbangpemdatingkat2 = MusrenbangPemdaTingkat2Model::create([
            'replaceit' => $request->input('replaceit'),
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
            return redirect(route('musrenbangpemdatingkat2.index'))->with('success','Data ini telah berhasil disimpan.');
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

        $data = MusrenbangPemdaTingkat2Model::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.musrenbang.musrenbangpemdatingkat2.show")->with(['page_active'=>'musrenbangpemdatingkat2',
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
        
        $data = MusrenbangPemdaTingkat2Model::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.musrenbang.musrenbangpemdatingkat2.edit")->with(['page_active'=>'musrenbangpemdatingkat2',
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
        $musrenbangpemdatingkat2 = MusrenbangPemdaTingkat2Model::find($id);
        
        $this->validate($request, [
            'replaceit'=>'required',
        ]);
        
        $musrenbangpemdatingkat2->replaceit = $request->input('replaceit');
        $musrenbangpemdatingkat2->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('musrenbangpemdatingkat2.index'))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $musrenbangpemdatingkat2 = MusrenbangPemdaTingkat2Model::find($id);
        $result=$musrenbangpemdatingkat2->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('musrenbangpemdatingkat2'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.musrenbang.musrenbangpemdatingkat2.datatable")->with(['page_active'=>'musrenbangpemdatingkat2',
                                                            'search'=>$this->getControllerStateSession('musrenbangpemdatingkat2','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('musrenbangpemdatingkat2.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('musrenbangpemdatingkat2.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}