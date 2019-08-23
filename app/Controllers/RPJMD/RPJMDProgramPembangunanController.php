<?php

namespace App\Controllers\RPJMD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDProgramPembangunanModel;
use App\Models\RPJMD\RPJMDSasaranModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;

class RPJMDProgramPembangunanController extends Controller {
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
     * @return resourcesIndikatorKinerjaID  
     */
    public function populateData ($currentpage=1) 
    {        
        $select=\DB::raw('"RPJMDProgramPembangunanID",
                            "PrioritasSasaranKabID",
                            "Nm_PrioritasKab",
                            "Nm_Tujuan",
                            "Nm_Sasaran",
                            "PrgNm",
                            "NamaIndikator",
                            "Satuan",                            
                            "KondisiAwal",
                            "TargetN1",
                            "PaguDanaN1",
                            "TargetN2",
                            "PaguDanaN2",
                            "TargetN3",
                            "PaguDanaN3",
                            "TargetN4",
                            "PaguDanaN4",
                            "TargetN5",
                            "PaguDanaN5",
                            "KondisiAkhirTarget",
                            "KondisiAkhirPaguDana",
                            "Descr",
                            "TA",
                            "created_at",
                            "updated_at"
                        ');
        $columns=['*'];       
        if (!$this->checkStateIsExistSession('rpjmdprogrampembangunan','orderby')) 
        {            
           $this->putControllerStateSession('rpjmdprogrampembangunan','orderby',['column_name'=>'NamaIndikator','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','column_name'); 
        $direction=$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('rpjmdprogrampembangunan','search')) 
        {
            $search=$this->getControllerStateSession('rpjmdprogrampembangunan','search');
            switch ($search['kriteria']) 
            {                
                case 'NamaIndikator' :
                    $data = \DB::table('v_indikator_kinerja1')
                                ->where('NamaIndikator', 'ilike', '%' . $search['isikriteria'] . '%')
                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_indikator_kinerja1') 
                    ->select($select)                   
                    ->where('TA',\HelperKegiatan::getRPJMDTahunMulai())
                    ->orderBy($column_order,$direction)
                    ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('rpjmdprogrampembangunan.index'));
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
        
        $this->setCurrentPageInsideSession('rpjmdprogrampembangunan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdprogrampembangunan.datatable")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                                                'search'=>$this->getControllerStateSession('rpjmdprogrampembangunan','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','order'),
                                                                                'data'=>$data])->render();      
        return response()->json(['success'=>true,'datatable'=>$datatable],200);
    }
    /**
     * digunakan untuk mengurutkan record 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * digunakan untuk mengurutkan record 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orderby (Request $request) 
    {
        return response()->json(['success'=>true,'datatable'=>null],200);
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

        $this->setCurrentPageInsideSession('rpjmdprogrampembangunan',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rpjmd.rpjmdprogrampembangunan.datatable")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                                            'search'=>$this->getControllerStateSession('rpjmdprogrampembangunan','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','order'),
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
            $this->destroyControllerStateSession('rpjmdprogrampembangunan','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('rpjmdprogrampembangunan','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('rpjmdprogrampembangunan',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rpjmd.rpjmdprogrampembangunan.datatable")->with(['page_active'=>'rpjmdprogrampembangunan',                                                            
                                                            'search'=>$this->getControllerStateSession('rpjmdprogrampembangunan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','order'),
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
        $json_data = [];
        //create
        
        if ($request->exists('PrioritasSasaranKabID') && $request->exists('create') )
        {
            $PrioritasSasaranKabID = $request->input('PrioritasSasaranKabID')==''?'none':$request->input('PrioritasSasaranKabID');            
            $daftar_indikatorsasaran=\App\Models\RPJMD\RPJMDSasaranIndikatorModel::select(\DB::raw('"PrioritasIndikatorSasaranID","NamaIndikator"'))
                                                                                ->where('PrioritasSasaranKabID',$PrioritasSasaranKabID)
                                                                                ->get()
                                                                                ->pluck('NamaIndikator','PrioritasIndikatorSasaranID')
                                                                                ->toArray();

            $json_data = ['success'=>true,'daftar_indikatorsasaran'=>$daftar_indikatorsasaran];
        } 

        if ($request->exists('UrsID') && $request->exists('create') )
        {
            $UrsID = $request->input('UrsID')==''?'none':$request->input('UrsID');            
            $daftar_program=\App\Models\DMaster\ProgramModel::getDaftarProgram(\HelperKegiatan::getRPJMDTahunMulai(),false,$UrsID);            
            $json_data = ['success'=>true,'daftar_program'=>$daftar_program];
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

        $search=$this->getControllerStateSession('rpjmdprogrampembangunan','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('rpjmdprogrampembangunan'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('rpjmdprogrampembangunan',$data->currentPage());
        
        return view("pages.$theme.rpjmd.rpjmdprogrampembangunan.index")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                'search'=>$this->getControllerStateSession('rpjmdprogrampembangunan','search'),
                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                'column_order'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','column_name'),
                                                'direction'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','order'),
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
        $daftar_sasaran = RPJMDSasaranModel::getDaftarSasaranNotInIndikatorSasaran(\HelperKegiatan::getRPJMDTahunMulai(),false);
        $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusan(\HelperKegiatan::getRPJMDTahunMulai(),false);  
        return view("pages.$theme.rpjmd.rpjmdprogrampembangunan.create")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                                                'daftar_sasaran'=>$daftar_sasaran,
                                                                                'daftar_urusan'=>$daftar_urusan,
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
            'PrioritasSasaranKabID'=>'required',
            'PrgID'=>'required',            
            'PaguDanaN1'=>'required',
            'PaguDanaN2'=>'required',
            'PaguDanaN3'=>'required',
            'PaguDanaN4'=>'required',
            'PaguDanaN5'=>'required',
            'KondisiAkhirPaguDana'=>'required',            
        ]);
        $rpjmdprogrampembangunan = RPJMDProgramPembangunanModel::create([
            'RPJMDProgramPembangunanID' => uniqid ('uid'),
            'PrioritasSasaranKabID' => $request->input('PrioritasSasaranKabID'),
            'UrsID' => $request->input('UrsID'),
            'PrgID' => $request->input('PrgID'),                       
            'PaguDanaN1' => $request->input('PaguDanaN1'),
            'PaguDanaN2' => $request->input('PaguDanaN2'),
            'PaguDanaN3' => $request->input('PaguDanaN3'),
            'PaguDanaN4' => $request->input('PaguDanaN4'),
            'PaguDanaN5' => $request->input('PaguDanaN5'),            
            'KondisiAkhirPaguDana' => $request->input('KondisiAkhirPaguDana'),
            'Descr' => $request->input('Descr'),
            'TA' => \HelperKegiatan::getRPJMDTahunMulai()            
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
            return redirect(route('rpjmdprogrampembangunan.show',['id'=>$rpjmdprogrampembangunan->RPJMDProgramPembangunanID]))->with('success','Data ini telah berhasil disimpan.');
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

        $data = \DB::table('v_indikator_kinerja1')
                    ->where('RPJMDProgramPembangunanID',$id)
                    ->first();

        if (!is_null($data) )  
        {
            return view("pages.$theme.rpjmd.rpjmdprogrampembangunan.show")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                                                'data'=>$data
                                                                                ]);
        }
        else
        {
            return view("pages.$theme.rpjmd.rpjmdprogrampembangunan.error")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                                                'errormessage'=>"ID Indikator Kinerja ($id) tidak ditemukan."
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
        
        $data = RPJMDProgramPembangunanModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            $daftar_sasaran = RPJMDSasaranModel::getDaftarSasaranNotInIndikatorSasaran(\HelperKegiatan::getRPJMDTahunMulai(),false);
            $daftar_indikatorsasaran=\App\Models\RPJMD\RPJMDSasaranIndikatorModel::select(\DB::raw('"PrioritasIndikatorSasaranID","NamaIndikator"'))
                                                                                ->where('PrioritasSasaranKabID',$data->PrioritasSasaranKabID)
                                                                                ->get()
                                                                                ->pluck('NamaIndikator','PrioritasIndikatorSasaranID')
                                                                                ->toArray();
            $indikatorsasaranid=1;
            $daftar_program = \App\Models\DMaster\ProgramModel::getDaftarProgram(\HelperKegiatan::getRPJMDTahunMulai(),false,$data->UrsID);            
            $daftar_urusan=\App\Models\DMaster\UrusanModel::getDaftarUrusan(\HelperKegiatan::getRPJMDTahunMulai(),false);  
            return view("pages.$theme.rpjmd.rpjmdprogrampembangunan.edit")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                                                'data'=>$data,
                                                                                'daftar_sasaran'=>$daftar_sasaran,
                                                                                'daftar_indikatorsasaran'=>$daftar_indikatorsasaran,
                                                                                'daftar_urusan'=>$daftar_urusan,
                                                                                'daftar_program'=>$daftar_program,                                                                                                                              
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
        $rpjmdprogrampembangunan = RPJMDProgramPembangunanModel::find($id);

        $this->validate($request, [
            'PrioritasSasaranKabID'=>'required',
            'PrgID'=>'required',            
            'PaguDanaN1'=>'required',
            'PaguDanaN2'=>'required',
            'PaguDanaN3'=>'required',
            'PaguDanaN4'=>'required',
            'PaguDanaN5'=>'required',
            'KondisiAkhirPaguDana'=>'required',            
        ]);
        
        $rpjmdprogrampembangunan->PrioritasSasaranKabID=$request->input('PrioritasSasaranKabID');
        $rpjmdprogrampembangunan->UrsID=$request->input('UrsID');
        $rpjmdprogrampembangunan->PrgID=$request->input('PrgID');                       
        $rpjmdprogrampembangunan->PaguDanaN1=$request->input('PaguDanaN1');
        $rpjmdprogrampembangunan->PaguDanaN2=$request->input('PaguDanaN2');
        $rpjmdprogrampembangunan->PaguDanaN3=$request->input('PaguDanaN3');
        $rpjmdprogrampembangunan->PaguDanaN4=$request->input('PaguDanaN4');
        $rpjmdprogrampembangunan->PaguDanaN5=$request->input('PaguDanaN5');            
        $rpjmdprogrampembangunan->KondisiAkhirPaguDana=$request->input('KondisiAkhirPaguDana');
        $rpjmdprogrampembangunan->Descr=$request->input('Descr');        
        $rpjmdprogrampembangunan->save();

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdprogrampembangunan.show',['id'=>$id]))->with('success','Data ini telah berhasil disimpan.');
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
        
        $rpjmdprogrampembangunan = RPJMDProgramPembangunanModel::find($id);
        $result=$rpjmdprogrampembangunan->delete();
        if ($request->ajax()) 
        {
            $currentpage=$this->getCurrentPageInsideSession('rpjmdprogrampembangunan'); 
            $data=$this->populateData($currentpage);
            if ($currentpage > $data->lastPage())
            {            
                $data = $this->populateData($data->lastPage());
            }
            $datatable = view("pages.$theme.rpjmd.rpjmdprogrampembangunan.datatable")->with(['page_active'=>'rpjmdprogrampembangunan',
                                                            'search'=>$this->getControllerStateSession('rpjmdprogrampembangunan','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                            'column_order'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('rpjmdprogrampembangunan.orderby','order'),
                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rpjmdprogrampembangunan.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}
