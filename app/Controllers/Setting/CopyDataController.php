<?php

namespace App\Controllers\Setting;

use Illuminate\Http\Request;
use App\Controllers\Controller;

class CopyDataController extends Controller 
{
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
        if (!$this->checkStateIsExistSession('copydata','filters')) 
        {            
            $this->putControllerStateSession('copydata','filters',['TA'=>\HelperKegiatan::getTahunPerencanaan()]);
        }
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

        $TA = $request->input('TACd')==''?\HelperKegiatan::getTahunPerencanaan():$request->input('TACd');
        $filters=$this->getControllerStateSession('copydata','filters');
        $filters['TA']=$TA;
        
        $this->putControllerStateSession('copydata','filters',$filters);

        return response()->json(['TA'=>$TA,'filters'=>$filters],200);  
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        $theme = \Auth::user()->theme;
        $this->populateData();
        $TA= $this->getControllerStateSession('copydata.filters','TA'); 
        return view("pages.$theme.setting.copydata.index")->with(['page_active'=>'copydata',
                                                                'TA'=>$TA           
                                                                ]);               
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request,$id)
    {
        switch($id)
        {
            case 1 ://copy wilayah
                $this->copywilayah();
            break;  
            case 2 ://copy opd / skpd
                $this->copyOPD();
            break;  
            case 3 ://copy sumber dana
                $this->copySumberDana();
            break;  
        }
    }
    private function copywilayah ()
    {
        $dari_ta= $this->getControllerStateSession('copydata.filters','TA'); 
        $ke_ta=\HelperKegiatan::getTahunPerencanaan();
        
        try 
        {
            //copy provinsi
            echo "Hapus Data Provinsi TA = $ke_ta <br>";

            \App\Models\DMaster\ProvinsiModel::where('TA',$ke_ta)
                                                ->delete();
            echo "--> OK<br>";
            echo "Salin data provinsi dari TA $dari_ta KE $ke_ta <br>";
            $sql = '
                    INSERT INTO "tmPMProv" (
                        "PMProvID", 
                        "Kd_Prov",
                        "Nm_Prov",                        
                        "Descr",
                        "TA",  
                        "PMProvID_Src",
                        "Locked",
                        "created_at", 
                        "updated_at"
                    )
                    SELECT 
                        REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "PMProvID",
                        "Kd_Prov",
                        "Nm_Prov",                        
                        "Descr",
                        \''.$ke_ta.'\' AS "TA",
                        "PMProvID" AS "PMProvID_Src",
                        "Locked",
                        NOW() AS created_at,
                        NOW() AS updated_at
                    FROM
                        "tmPMProv" 
                    WHERE 
                        "TA"=\''.$dari_ta.'\'
                    ';
                    \DB::statement($sql);
                    echo "--> OK<br>";

                    echo "Salin data kabupaten/kota dari TA $dari_ta KE $ke_ta <br>";
                    $data = \App\Models\DMaster\ProvinsiModel::where('TA',$ke_ta)
                                            ->chunk(25, function ($provinsi) use ($ke_ta){
                                                $chunk=1;
                                                foreach ($provinsi as $record) {
                                                    $PMProvID_new=$record->PMProvID;
                                                    $PMProvID_old=$record->PMProvID_Src;

                                                    $sql = '
                                                                INSERT INTO "tmPmKota" (
                                                                    "PmKotaID", 
                                                                    "PMProvID",
                                                                    "Kd_Kota",                        
                                                                    "Nm_Kota",
                                                                    "Descr",  
                                                                    "TA",
                                                                    "PmKotaID_Src",
                                                                    "Locked",
                                                                    "created_at", 
                                                                    "updated_at"
                                                                )
                                                                SELECT 
                                                                    REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "PmKotaID",
                                                                    \''.$PMProvID_new.'\' AS "PMProvID",
                                                                    "Kd_Kota",                        
                                                                    "Nm_Kota",                                                                    
                                                                    "Descr",                                                                    
                                                                    \''.$ke_ta.'\' AS "TA",
                                                                    "PmKotaID" AS "PmKotaID_Src",
                                                                    "Locked",
                                                                    NOW() AS created_at,
                                                                    NOW() AS updated_at
                                                                FROM
                                                                    "tmPmKota" 
                                                                WHERE 
                                                                    "PMProvID"=\''.$PMProvID_old.'\'
                                                                ';
                                                                \DB::statement($sql);
                                                                echo "cunk ke = $chunk --> OK<br>";
                                                                $chunk+=1;
                                                    
                                                }
                                            });
            echo "Salin data kecamatan dari TA $dari_ta KE $ke_ta <br>";
            $data = \App\Models\DMaster\KotaModel::where('TA',$ke_ta)
                                            ->chunk(25, function ($kota) use ($ke_ta){
                                                $chunk=0;
                                                foreach ($kota as $record) {
                                                    $PmKotaID_new=$record->PmKotaID;
                                                    $PmKotaID_old=$record->PmKotaID_Src;

                                                    $sql = '
                                                                INSERT INTO "tmPmKecamatan" (
                                                                    "PmKecamatanID", 
                                                                    "PmKotaID",
                                                                    "Kd_Kecamatan",                        
                                                                    "Nm_Kecamatan",
                                                                    "Descr",  
                                                                    "TA",
                                                                    "PmKecamatanID_Src",
                                                                    "Locked",
                                                                    "created_at", 
                                                                    "updated_at"
                                                                )
                                                                SELECT 
                                                                    REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "PmKecamatanID",
                                                                    \''.$PmKotaID_new.'\' AS "PmKotaID",
                                                                    "Kd_Kecamatan",                        
                                                                    "Nm_Kecamatan",                                                                    
                                                                    "Descr",                                                                    
                                                                    \''.$ke_ta.'\' AS "TA",
                                                                    "PmKecamatanID" AS "PmKecamatanID_Src",
                                                                    "Locked",
                                                                    NOW() AS created_at,
                                                                    NOW() AS updated_at
                                                                FROM
                                                                    "tmPmKecamatan" 
                                                                WHERE 
                                                                    "PmKotaID"=\''.$PmKotaID_old.'\'
                                                                ';
                                                                \DB::statement($sql);
                                                                echo "cunk ke = $chunk --> OK<br>";
                                                                $chunk+=1;
                                                    
                                                }
                                            });
            echo "Salin data Desa/Kelurahan dari TA $dari_ta KE $ke_ta <br>";
            $data = \App\Models\DMaster\KecamatanModel::where('TA',$ke_ta)
                                            ->chunk(25, function ($kecamatan) use ($ke_ta){
                                                $chunk=1;
                                                foreach ($kecamatan as $record) {
                                                    $PmKecamatanID_new=$record->PmKecamatanID;
                                                    $PmKecamatanID_old=$record->PmKecamatanID_Src;

                                                    $sql = '
                                                                INSERT INTO "tmPmDesa" (
                                                                    "PmDesaID", 
                                                                    "PmKecamatanID",
                                                                    "Kd_Desa",                        
                                                                    "Nm_Desa",
                                                                    "Descr",  
                                                                    "TA",
                                                                    "PmDesaID_Src",
                                                                    "Locked",
                                                                    "created_at", 
                                                                    "updated_at"
                                                                )
                                                                SELECT 
                                                                    REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "PmDesaID",
                                                                    \''.$PmKecamatanID_new.'\' AS "PmKecamatanID",
                                                                    "Kd_Desa",                        
                                                                    "Nm_Desa",                                                                    
                                                                    "Descr",                                                                    
                                                                    \''.$ke_ta.'\' AS "TA",
                                                                    "PmDesaID" AS "PmDesaID_Src",
                                                                    "Locked",
                                                                    NOW() AS created_at,
                                                                    NOW() AS updated_at
                                                                FROM
                                                                    "tmPmDesa" 
                                                                WHERE 
                                                                    "PmKecamatanID"=\''.$PmKecamatanID_old.'\'
                                                                ';
                                                                \DB::statement($sql);
                                                                echo "cunk ke = $chunk --> OK<br>";
                                                                $chunk+=1;
                                                    
                                                }
                                            });

        }
        catch (\Exception $e)
        {
            echo 'Tidak bisa menghapus, karena isi table tmPMPProv/tmPmKota/tmPmKecamatan/tmPmDesa berelasi dengan tabel lain.<br>';
            echo '<span style="color:red;">'.$e->getMessage().'</span>';
        }
    }
    private function copyOPD ()
    {
        $dari_ta= $this->getControllerStateSession('copydata.filters','TA'); 
        $ke_ta=\HelperKegiatan::getTahunPerencanaan();
        
        try 
        {
            //copy opd / skpd
            echo "Hapus Data OPD / SKPD TA = $ke_ta <br>";

            \App\Models\DMaster\OrganisasiModel::where('TA',$ke_ta)
                                                ->delete();
            echo "--> OK<br>";
            echo "Salin data OPD / SKPD dari TA $dari_ta KE $ke_ta <br>";

            $sql = '
                    INSERT INTO "tmOrg" (
                        "OrgID", 
                        "OrgIDRPJMD",
                        "UrsID",                        
                        "OrgCd",                        
                        "OrgNm",                        
                        "OrgAlias",                        
                        "Alamat",                        
                        "NamaKepalaSKPD",                        
                        "NIPKepalaSKPD",       
                        "Descr",
                        "TA",  
                        "OrgID_Src",
                        "created_at", 
                        "updated_at"
                    )
                    SELECT 
                        REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "OrgID",
                        "OrgIDRPJMD",
                        "UrsID",                        
                        "OrgCd",                        
                        "OrgNm",                        
                        "OrgAlias",                        
                        "Alamat",                        
                        "NamaKepalaSKPD",                        
                        "NIPKepalaSKPD",       
                        "Descr",
                        \''.$ke_ta.'\' AS "TA",
                        "OrgID" AS "OrgID_Src",
                        NOW() AS created_at,
                        NOW() AS updated_at
                    FROM
                        "tmOrg" 
                    WHERE 
                        "TA"=\''.$dari_ta.'\'
                    ';
                    \DB::statement($sql);
                    echo "--> OK<br>";
            
            echo "Salin data Unit Kerja dari TA $dari_ta KE $ke_ta <br>";
            $data = \App\Models\DMaster\OrganisasiModel::where('TA',$ke_ta)
                                            ->chunk(25, function ($opd) use ($ke_ta){
                                                $chunk=1;
                                                foreach ($opd as $record) {
                                                    $OrgID_new=$record->OrgID;
                                                    $OrgID_old=$record->OrgID_Src;

                                                    $sql = '
                                                            INSERT INTO "tmSOrg" (
                                                                "SOrgID", 
                                                                "OrgID",
                                                                "SOrgCd",                        
                                                                "SOrgNm",                        
                                                                "SOrgAlias",                        
                                                                "Alamat",                        
                                                                "NamaKepalaSKPD",                        
                                                                "NIPKepalaSKPD",
                                                                "Descr",  
                                                                "TA",
                                                                "SOrgID_Src",
                                                                "created_at", 
                                                                "updated_at"
                                                            )
                                                            SELECT 
                                                                REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "SOrgID",
                                                                \''.$OrgID_new.'\' AS "OrgID",
                                                                "SOrgCd",                        
                                                                "SOrgNm",                        
                                                                "SOrgAlias",                        
                                                                "Alamat",                        
                                                                "NamaKepalaSKPD",                        
                                                                "NIPKepalaSKPD",                                                                    
                                                                "Descr",                                                                    
                                                                \''.$ke_ta.'\' AS "TA",
                                                                "SOrgID" AS "SOrgID_Src",
                                                                NOW() AS created_at,
                                                                NOW() AS updated_at
                                                            FROM
                                                                "tmSOrg" 
                                                            WHERE 
                                                                "OrgID"=\''.$OrgID_old.'\'
                                                            ';
                                                            \DB::statement($sql);
                                                            echo "cunk ke = $chunk --> OK<br>";
                                                            $chunk+=1;
                                                    
                                                }
                                            });

        }
        catch (\Exception $e)
        {
            echo '<span style="color:red;">'.$e->getMessage().'</span>';
        }
    }
    private function copySumberDana ()
    {
        $dari_ta= $this->getControllerStateSession('copydata.filters','TA'); 
        $ke_ta=\HelperKegiatan::getTahunPerencanaan();
        
        try 
        {
            //copy opd / skpd
            echo "Hapus Data Daftar Sumber Dana TA = $ke_ta <br>";

            \App\Models\DMaster\SumberDanaModel::where('TA',$ke_ta)
                                                ->delete();
            echo "--> OK<br>";
            echo "Salin data Daftar Sumber Dana dari TA $dari_ta KE $ke_ta <br>";

            $sql = '
                INSERT INTO "tmSumberDana" (
                    "SumberDanaID", 
                    "Kd_SumberDana",
                    "Nm_SumberDana",                        
                    "Descr",                        
                    "TA",
                    "SumberDanaID_Src",
                    "created_at", 
                    "updated_at"
                )
                SELECT 
                    REPLACE(SUBSTRING(CONCAT(\'uid\',uuid_in(md5(random()::text || clock_timestamp()::text)::cstring)) from 1 for 16),\'-\',\'\') AS "SumberDanaID",
                    "Kd_SumberDana",                        
                    "Nm_SumberDana",                                                  
                    "Descr",                                                                    
                    \''.$ke_ta.'\' AS "TA",
                    "SumberDanaID" AS "SumberDanaID_Src",
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM
                    "tmSumberDana" 
                WHERE 
                    "TA"=\''.$dari_ta.'\'
                ';
                \DB::statement($sql);
        }
        catch (\Exception $e)
        {
            echo '<span style="color:red;">'.$e->getMessage().'</span>';
        }
    }
}