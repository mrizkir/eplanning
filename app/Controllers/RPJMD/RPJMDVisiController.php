<?php
namespace App\Controllers\RPJMD;
use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\RPJMD\RPJMDVisiModel;
use App\Rules\CheckRecordIsExistValidation;
use App\Rules\IgnoreIfDataIsEqualValidation;
class RPJMDVisiController extends Controller {
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
        $data = RPJMDVisiModel::orderBy('TA_Awal','ASC')
                                ->get(); 
            
        return $data;
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
        $data = $this->populateData();        
        return view("pages.$theme.rpjmd.rpjmdvisi.index")->with(['page_active'=>'rpjmdvisi',
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
        return view("pages.$theme.rpjmd.rpjmdvisi.create")->with(['page_active'=>'rpjmdvisi',
                                                                        
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
            'Nm_RpjmdVisi'=>'required',
            'Descr'=>'required',
            'TA_Awal'=>[new CheckRecordIsExistValidation('tmRpjmdVisi'),
                        'required'
                        ],
        ]);
        
        $rpjmdvisi = RPJMDVisiModel::create([
            'RpjmdVisiID'=> uniqid ('uid'),
            'Nm_RpjmdVisi' => $request->input('Nm_RpjmdVisi'),
            'Descr' => $request->input('Descr'),
            'TA_Awal' => $request->input('TA_Awal'),
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
            return redirect(route('rpjmdvisi.show',['id'=>$rpjmdvisi->RpjmdVisiID]))->with('success','Data ini telah berhasil disimpan.');
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
        $data = RPJMDVisiModel::findOrFail($id);
        if (!is_null($data) )  
        {
            return view("pages.$theme.rpjmd.rpjmdvisi.show")->with(['page_active'=>'rpjmdvisi',
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
        
        $data = RPJMDVisiModel::findOrFail($id);
        if (!is_null($data) ) 
        {
            return view("pages.$theme.rpjmd.rpjmdvisi.edit")->with(['page_active'=>'rpjmdvisi',
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
        $rpjmdvisi = RPJMDVisiModel::find($id);
        
        $this->validate($request, [
            'Nm_RpjmdVisi'=>'required',
            'Descr'=>'required',
            'TA_Awal'=>[new IgnoreIfDataIsEqualValidation('tmRpjmdVisi',$rpjmdvisi->TA_Awal),
                        'required'
                        ],
        ]);
        
        $ta_awal = $request->input('TA_Awal');
        $rpjmdvisi->Nm_RpjmdVisi = $request->input('Nm_RpjmdVisi');
        $rpjmdvisi->Descr = $request->input('Descr');
        $rpjmdvisi->TA_Awal = $ta_awal;
        $rpjmdvisi->save();
        
        $this->putControllerStateSession('global_controller','rpjmd_tahun_awal',$ta_awal);
        $this->putControllerStateSession('global_controller','rpjmd_tahun_mulai',$ta_awal+1);
        $this->putControllerStateSession('global_controller','rpjmd_tahun_akhir',$ta_awal+5);

        $this->putControllerStateSession('global_controller','renstra_tahun_awal',$ta_awal);
        $this->putControllerStateSession('global_controller','renstra_tahun_mulai',$ta_awal+1);
        $this->putControllerStateSession('global_controller','renstra_tahun_akhir',$ta_awal+5);

        //ubah tahun
        \DB::table('tmPrioritasKab')
            ->where('RpjmdVisiID',$id)
            ->update(['TA'=>$request->input('TA_Awal')+1]);

        if ($request->ajax()) 
        {
            return response()->json([
                'success'=>true,
                'message'=>'Data ini telah berhasil diubah.'
            ]);
        }
        else
        {
            return redirect(route('rpjmdvisi.show',['id'=>$rpjmdvisi->RpjmdVisiID]))->with('success',"Data dengan id ($id) telah berhasil diubah.");
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
        
        $rpjmdvisi = RPJMDVisiModel::find($id);
        $result=$rpjmdvisi->delete();
        if ($request->ajax()) 
        {           
            $data=$this->populateData();           
            $datatable = view("pages.$theme.rpjmd.rpjmdvisi.datatable")->with(['page_active'=>'rpjmdvisi',                                                            
                                                                            'data'=>$data])->render();      
            
            return response()->json(['success'=>true,'datatable'=>$datatable],200); 
        }
        else
        {
            return redirect(route('rpjmdvisi.index'))->with('success',"Data ini dengan ($id) telah berhasil dihapus.");
        }        
    }
}