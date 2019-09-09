<?php

namespace App\Controllers\RKPD;

use Illuminate\Http\Request;
use App\Controllers\Controller;
use App\Models\DMaster\OrganisasiModel;

class TransferPembahasanRKPDTOPerubahan2 extends Controller {
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
        if (!$this->checkStateIsExistSession('transferpembahasanrkpdtoperubahan2','orderby')) 
        {            
           $this->putControllerStateSession('transferpembahasanrkpdtoperubahan2','orderby',['column_name'=>'kode_organisasi','order'=>'asc']);
        }
        $column_order=$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','column_name'); 
        $direction=$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','order'); 

        if (!$this->checkStateIsExistSession('global_controller','numberRecordPerPage')) 
        {            
            $this->putControllerStateSession('global_controller','numberRecordPerPage',10);
        }
        $numberRecordPerPage=$this->getControllerStateSession('global_controller','numberRecordPerPage');        
        if ($this->checkStateIsExistSession('transferpembahasanrkpdtoperubahan2','search')) 
        {
            $search=$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search');
            switch ($search['kriteria']) 
            {
                case 'kode_organisasi' :
                    $data =\DB::table('v_urusan_organisasi') 
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->where(['kode_organisasi'=>$search['isikriteria']])
                                ->orderBy($column_order,$direction); 
                break;
                case 'OrgNm' :
                    $data =\DB::table('v_urusan_organisasi') 
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->where('OrgNm', 'ilike', '%' . $search['isikriteria'] . '%')
                                ->orderBy($column_order,$direction);                                        
                break;
            }           
            $data = $data->paginate($numberRecordPerPage, $columns, 'page', $currentpage);  
        }
        else
        {
            $data = \DB::table('v_urusan_organisasi') 
                                ->where('TA',\HelperKegiatan::getTahunPerencanaan())
                                ->orderBy($column_order,$direction)
                                ->paginate($numberRecordPerPage, $columns, 'page', $currentpage); 
        }        
        $data->setPath(route('transferpembahasanrkpdtoperubahan2.index'));
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
        
        $this->setCurrentPageInsideSession('transferpembahasanrkpdtoperubahan2',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.transferpembahasanrkpdtoperubahan2.datatable")->with(['page_active'=>'transferpembahasanrkpdtoperubahan2',
                                                                                'search'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search'),
                                                                                'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                                'column_order'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','column_name'),
                                                                                'direction'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','order'),
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
            case 'kode_organisasi' :
                $column_name = 'kode_organisasi';
            break; 
            case 'NmOrg' :
                $column_name = 'NmOrg';
            break;  
            case 'Nm_Urusan' :
                $column_name = 'Nm_Urusan';
            break;         
            default :
                $column_name = 'kode_organisasi';
        }
        $this->putControllerStateSession('transferpembahasanrkpdtoperubahan2','orderby',['column_name'=>$column_name,'order'=>$orderby]);        

        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('transferpembahasanrkpdtoperubahan2'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }

        $datatable = view("pages.$theme.rkpd.transferpembahasanrkpdtoperubahan2.datatable")->with(['page_active'=>'transferpembahasanrkpdtoperubahan2',
                                                            'search'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','order'),
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

        $this->setCurrentPageInsideSession('transferpembahasanrkpdtoperubahan2',$id);
        $data=$this->populateData($id);
        $datatable = view("pages.$theme.rkpd.transferpembahasanrkpdtoperubahan2.datatable")->with(['page_active'=>'transferpembahasanrkpdtoperubahan2',
                                                                            'search'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search'),
                                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                                            'column_order'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','column_name'),
                                                                            'direction'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','order'),
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
            $this->destroyControllerStateSession('transferpembahasanrkpdtoperubahan2','search');
        }
        else
        {
            $kriteria = $request->input('cmbKriteria');
            $isikriteria = $request->input('txtKriteria');
            $this->putControllerStateSession('transferpembahasanrkpdtoperubahan2','search',['kriteria'=>$kriteria,'isikriteria'=>$isikriteria]);
        }      
        $this->setCurrentPageInsideSession('transferpembahasanrkpdtoperubahan2',1);
        $data=$this->populateData();

        $datatable = view("pages.$theme.rkpd.transferpembahasanrkpdtoperubahan2.datatable")->with(['page_active'=>'transferpembahasanrkpdtoperubahan2',                                                            
                                                            'search'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search'),
                                                            'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),
                                                            'column_order'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','column_name'),
                                                            'direction'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','order'),
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

        $search=$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search');
        $currentpage=$request->has('page') ? $request->get('page') : $this->getCurrentPageInsideSession('transferpembahasanrkpdtoperubahan2'); 
        $data = $this->populateData($currentpage);
        if ($currentpage > $data->lastPage())
        {            
            $data = $this->populateData($data->lastPage());
        }
        $this->setCurrentPageInsideSession('transferpembahasanrkpdtoperubahan2',$data->currentPage());
        
        return view("pages.$theme.rkpd.transferpembahasanrkpdtoperubahan2.index")->with(['page_active'=>'transferpembahasanrkpdtoperubahan2',
                                                                                    'search'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2','search'),
                                                                                    'numberRecordPerPage'=>$this->getControllerStateSession('global_controller','numberRecordPerPage'),                                                                    
                                                                                    'column_order'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','column_name'),
                                                                                    'direction'=>$this->getControllerStateSession('transferpembahasanrkpdtoperubahan2.orderby','order'),
                                                                                    'data'=>$data]);               
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
        ]);
        $OrgID= $request->input('OrgID');
        switch ($request->input('btnTransfer'))
        {
            case 1 :
                $this->transfer1($OrgID);
            break;
            case 2 :
                $this->transfer2($OrgID);
            break;
        }
    }
    /**
     * transfer ke entrylvl2 dengan cara menghapus data didalam entrylvl2
     */
    private function transfer1 ($OrgID)
    {
        // \App\Models\RKPD\RKPDModel::where('EntryLvl',3)
        //                             ->where('OrgID',$OrgID)
        //                             ->delete();

        echo "Hapus Pembahasan RKPD Murni Entrly Level ke 2 untuk OrgID = $OrgID <br>";
        echo "Berhasil <br><br>";
        
        $data = \App\Models\RKPD\RKPDModel::where('OrgID',$OrgID)
                                            ->where('EntryLvl',100)
                                            ->chunk(25, function ($rkpd){
                                                $tanggal_posting=\Carbon\Carbon::now();
                                                foreach ($rkpd as $old) {
                                                    $oldRKPDID=$old->RKPDID;
                                                    echo "Transfer Kegiatan RKPD dengan ID=".$oldRKPDID;
                                                    $newRKPDID=uniqid ('uid');
                                                    $new=$old->replicate();
                                                    $new->RKPDID=$newRKPDID;
                                                    $new->Sasaran_Uraian1=$new->Sasaran_Uraian1;
                                                    $new->Sasaran_Uraian2=0;
                                                    $new->Sasaran_Uraian3=0;
                                                    $new->Sasaran_Uraian4=0;
                                                    $new->Sasaran_Angka1=$new->Sasaran_Angka1;
                                                    $new->Sasaran_Angka2=0;
                                                    $new->Sasaran_Angka3=0;
                                                    $new->Sasaran_Angka4=0;
                                                    $new->NilaiUsulan1=$new->NilaiUsulan1;                                                            
                                                    $new->NilaiUsulan2=0;                                                            
                                                    $new->NilaiUsulan3=0;                                                            
                                                    $new->NilaiUsulan4=0;                                                            
                                                    $new->Target1=$new->Target1;          
                                                    $new->Target2=0;          
                                                    $new->Target3=0;          
                                                    $new->Target4=0;          
                                                    $new->Tgl_Posting=$tanggal_posting;                                                  
                                                    $new->EntryLvl=1;
                                                    $new->Privilege=1;                                                                                                                        
                                                    $new->Status=1;                                                                                                                        
                                                    $new->RKPDID_Src=$oldRKPDID;                                                            
                                                    $new->created_at=$tanggal_posting;                                                            
                                                    $new->updated_at=$tanggal_posting;                                                            
                                                    $new->RKPDID_Src=$oldRKPDID;                                                            
                                                    $new->save();

                                                    $old->Privilege=1;
                                                    $old->save();

                                                    $str_rincianrkpd = '
                                                        INSERT INTO "trRKPDRinc" (
                                                            "RKPDRincID",
                                                            "RKPDID", 
                                                            "PMProvID",
                                                            "PmKotaID",
                                                            "PmKecamatanID",
                                                            "PmDesaID",
                                                            "Lokasi",
                                                            "Latitude",
                                                            "Longitude",
                                                            "UsulanKecID",
                                                            "PokPirID",
                                                            "Uraian",
                                                            "No",
                                                            "Sasaran_Uraian1",
                                                            "Sasaran_Uraian2",
                                                            "Sasaran_Uraian3",
                                                            "Sasaran_Uraian4",
                                                            "Sasaran_Angka1",                        
                                                            "Sasaran_Angka2",                        
                                                            "Sasaran_Angka3",                        
                                                            "Sasaran_Angka4",                        
                                                            "NilaiUsulan1",                       
                                                            "NilaiUsulan2",                       
                                                            "NilaiUsulan3",                       
                                                            "NilaiUsulan4",                       
                                                            "Target1",                        
                                                            "Target2",                        
                                                            "Target3",                        
                                                            "Target4",                        
                                                            "Tgl_Posting",                         
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "isSKPD",
                                                            "Descr",
                                                            "TA",
                                                            "Status",
                                                            "EntryLvl",
                                                            "Privilege",                   
                                                            "created_at", 
                                                            "updated_at"
                                                        ) 
                                                        SELECT 
                                                            REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RKPDRincID",
                                                            \''.$newRKPDID.'\' AS "RKPDID",
                                                            "PMProvID",
                                                            "PmKotaID",
                                                            "PmKecamatanID",
                                                            "PmDesaID",
                                                            "Lokasi",
                                                            "Latitude",
                                                            "Longitude",
                                                            "UsulanKecID",
                                                            "PokPirID",
                                                            "Uraian",
                                                            "No",
                                                            "Sasaran_Uraian1",
                                                            \'-\' AS "Sasaran_Uraian2",
                                                            \'-\' AS "Sasaran_Uraian3",
                                                            \'-\' AS "Sasaran_Uraian4",
                                                            "Sasaran_Angka1",        
                                                            \'-\' AS "Sasaran_Angka2",        
                                                            \'-\' AS "Sasaran_Angka3",        
                                                            \'-\' AS "Sasaran_Angka4",        
                                                            "NilaiUsulan1",       
                                                            0 AS "NilaiUsulan2",       
                                                            0 AS "NilaiUsulan3",       
                                                            0 AS "NilaiUsulan4",       
                                                            "Target1",                                             
                                                            0 AS "Target2",                                             
                                                            0 AS "Target3",                                             
                                                            0 AS "Target4",                                             
                                                            \''.$tanggal_posting.'\' AS Tgl_Posting,
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "isSKPD",
                                                            "Descr",
                                                            "TA",
                                                            1 AS "Status", 
                                                            1 AS "EntryLvl",
                                                            1 AS "Privilege",  
                                                            NOW() AS created_at,
                                                            NOW() AS updated_at
                                                        FROM 
                                                            "trRKPDRinc" 
                                                        WHERE "RKPDID"=\''.$oldRKPDID.'\'
                                                    ';
                                                    \DB::statement($str_rincianrkpd); 
                                                    $str_kinerja='
                                                        INSERT INTO "trRKPDIndikator" (
                                                            "RKPDIndikatorID", 
                                                            "RKPDID",
                                                            "IndikatorKinerjaID",                        
                                                            "Target_Angka",
                                                            "Target_Uraian",  
                                                            "Descr",
                                                            "TA",
                                                            "RKPDIndikatorID_Src",  
                                                            "created_at", 
                                                            "updated_at"
                                                        )
                                                        SELECT 
                                                            REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RKPDIndikatorID",
                                                            \''.$newRKPDID.'\' AS "RKPDID",
                                                            "IndikatorKinerjaID",                        
                                                            "Target_Angka",
                                                            "Target_Uraian",
                                                            "Descr",
                                                            "TA",
                                                            "RKPDIndikatorID" AS "RKPDIndikatorID_Src",  
                                                            NOW() AS created_at,
                                                            NOW() AS updated_at
                                                        FROM 
                                                            "trRKPDIndikator" 
                                                        WHERE 
                                                            "RKPDID"=\''.$oldRKPDID.'\'
                                                    ';

                                                    \DB::statement($str_kinerja);
                                                    echo "->OK<br>";
                                                }
                                            });

        echo "<br><br> TRANSFER DATA RKPD ENTRY LEVEL 1 SELESAI DAN SUKSES";
    }
    /**
     * transfer ke entrylvl2 dengan cara menghapus data didalam entrylvl2
     */
    private function transfer2 ($OrgID)
    {
        \App\Models\RKPD\RKPDModel::where('EntryLvl',2)
                                    ->where('OrgID',$OrgID)
                                    ->where('NilaiUsulan1','>',0)
                                    ->delete();

        echo "Hapus Pembahasan RKPD Murni Entrly Level ke 2 untuk OrgID = $OrgID <br>";
        echo "Berhasil <br><br>";
        $data = \App\Models\RKPD\RKPDModel::where('OrgID',$OrgID)
                                            ->where('EntryLvl',1)
                                            ->chunk(25, function ($rkpd){
                                                $tanggal_posting=\Carbon\Carbon::now();
                                                foreach ($rkpd as $old) {
                                                    $oldRKPDID=$old->RKPDID;
                                                    echo "Transfer Kegiatan RKPD dengan ID=".$oldRKPDID;
                                                    $newRKPDID=uniqid ('uid');
                                                    $new=$old->replicate();
                                                    $new->RKPDID=$newRKPDID;
                                                    $new->Sasaran_Uraian2=$new->Sasaran_Uraian1;
                                                    $new->Sasaran_Angka2=$new->Sasaran_Angka1;
                                                    $new->NilaiUsulan2=$new->NilaiUsulan1;                                                            
                                                    $new->Target2=$new->Target1;          
                                                    $new->Tgl_Posting=$tanggal_posting;                                                  
                                                    $new->EntryLvl=2;
                                                    $new->Privilege=0;                                                                                                                        
                                                    $new->RKPDID_Src=$oldRKPDID;                                                            
                                                    $new->created_at=$tanggal_posting;                                                            
                                                    $new->updated_at=$tanggal_posting;                                                            
                                                    $new->RKPDID_Src=$oldRKPDID;                                                            
                                                    $new->save();

                                                    $old->Privilege=1;
                                                    $old->save();

                                                    $str_rincianrkpd = '
                                                        INSERT INTO "trRKPDRinc" (
                                                            "RKPDRincID",
                                                            "RKPDID", 
                                                            "PMProvID",
                                                            "PmKotaID",
                                                            "PmKecamatanID",
                                                            "PmDesaID",
                                                            "Lokasi",
                                                            "Latitude",
                                                            "Longitude",
                                                            "UsulanKecID",
                                                            "PokPirID",
                                                            "Uraian",
                                                            "No",
                                                            "Sasaran_Uraian1",
                                                            "Sasaran_Uraian2",
                                                            "Sasaran_Angka1",                        
                                                            "Sasaran_Angka2",                        
                                                            "NilaiUsulan1",                       
                                                            "NilaiUsulan2",                       
                                                            "Target1",                        
                                                            "Target2",                        
                                                            "Tgl_Posting",                         
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "isSKPD",
                                                            "Descr",
                                                            "TA",
                                                            "Status",
                                                            "EntryLvl",
                                                            "Privilege",                   
                                                            "RKPDRincID_Src",                   
                                                            "created_at", 
                                                            "updated_at"
                                                        ) 
                                                        SELECT 
                                                            REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RKPDRincID",
                                                            \''.$newRKPDID.'\' AS "RKPDID",
                                                            "PMProvID",
                                                            "PmKotaID",
                                                            "PmKecamatanID",
                                                            "PmDesaID",
                                                            "Lokasi",
                                                            "Latitude",
                                                            "Longitude",
                                                            "UsulanKecID",
                                                            "PokPirID",
                                                            "Uraian",
                                                            "No",
                                                            "Sasaran_Uraian1",
                                                            "Sasaran_Uraian1" AS "Sasaran_Uraian2",
                                                            "Sasaran_Angka1",        
                                                            "Sasaran_Angka1" AS "Sasaran_Angka2",        
                                                            "NilaiUsulan1",       
                                                            "NilaiUsulan1" AS "NilaiUsulan2",       
                                                            "Target1",                                             
                                                            "Target1" AS "Target2",                                             
                                                            \''.$tanggal_posting.'\' AS Tgl_Posting,
                                                            "isReses",
                                                            "isReses_Uraian",
                                                            "isSKPD",
                                                            "Descr",
                                                            "TA",
                                                            1 AS "Status", 
                                                            2 AS "EntryLvl",
                                                            0 AS "Privilege",  
                                                            "RKPDRincID" AS "RKPDRincID_Src",  
                                                            NOW() AS created_at,
                                                            NOW() AS updated_at
                                                        FROM 
                                                            "trRKPDRinc" 
                                                        WHERE "RKPDID"=\''.$oldRKPDID.'\'
                                                    ';
                                                    \DB::statement($str_rincianrkpd); 
                                                    $str_kinerja='
                                                        INSERT INTO "trRKPDIndikator" (
                                                            "RKPDIndikatorID", 
                                                            "RKPDID",
                                                            "IndikatorKinerjaID",                        
                                                            "Target_Angka",
                                                            "Target_Uraian",  
                                                            "Descr",
                                                            "TA",
                                                            "RKPDIndikatorID_Src",  
                                                            "created_at", 
                                                            "updated_at"
                                                        )
                                                        SELECT 
                                                            REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "RKPDIndikatorID",
                                                            \''.$newRKPDID.'\' AS "RKPDID",
                                                            "IndikatorKinerjaID",                        
                                                            "Target_Angka",
                                                            "Target_Uraian",
                                                            "Descr",
                                                            "TA",
                                                            "RKPDIndikatorID" AS "RKPDIndikatorID_Src",  
                                                            NOW() AS created_at,
                                                            NOW() AS updated_at
                                                        FROM 
                                                            "trRKPDIndikator" 
                                                        WHERE 
                                                            "RKPDID"=\''.$oldRKPDID.'\'
                                                    ';

                                                    \DB::statement($str_kinerja);
                                                    echo "->OK<br>";
                                                }
                                            });

        echo "<br><br> TRANSFER DATA RKPD ENTRY LEVEL 1 SELESAI DAN SUKSES";
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

        $data = OrganisasiModel::leftJoin('v_urusan_organisasi','v_urusan_organisasi.OrgID','tmOrg.OrgID')
                                ->where('tmOrg.OrgID',$id)
                                ->firstOrFail(['tmOrg.OrgID','v_urusan_organisasi.kode_organisasi','tmOrg.OrgNm','v_urusan_organisasi.Nm_Urusan','tmOrg.TA']);
 
        if (!is_null($data) )  
        {
            return view("pages.$theme.rkpd.transferpembahasanrkpdtoperubahan2.show")->with(['page_active'=>'transferpembahasanrkpdtoperubahan2',
                                                    'data'=>$data
                                                    ]);
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
        
    }
}