<?php

namespace App\Helpers;

class HelperKegiatan {
    /**
     * Daftar Level Entri
     */ 
    private static $LevelEntri =['none'=>'DAFTAR POSISI ENTRI',
                                0=>'RENJA SKPD',
                                1=>'RAKOR BIDANG',
                                2=>'FORUM SKPD',
                                3=>'MUSRENBANG KABUPATEN',
                                4=>'RKPD (APBD)',
                                5=>'RKPD (APBDP)'];  

    /**
     * Daftar Prioritas
     */ 
    private static $DaftarPrioritas =['none'=>'DAFTAR PRIORITAS',
                                1=>'P1',
                                2=>'P2',
                                3=>'P3',
                                4=>'P4',
                                5=>'P5',
                                6=>'P6']; 

    /**
     * Daftar Status Kegiatan
     */ 
    private static $StatusKegiatan =[0=>'DRAFT',
                                    1=>'SETUJU',
                                    2=>'SETUJU DG. CATATAN',
                                    3=>'PENDING']; 
    
    /**
     * Daftar Status RKPD
     */ 
    private static $StatusRKPD =[1=>'MURNI',
                                2=>'PERUBAHAN',//record perubahan tidak bisa bisa dihapus
                                3=>'PERUBAHAN' //record perubahan bisa dihapus
                                ];
    /**
     * digunakan untuk mendapatkan tahun perencanaan saat user login
     */ 
    public static function getTahunPerencanaan ()
    {
        return request()->session()->get("global_controller.tahun_perencanaan");
    }
    /**
     * digunakan untuk mendapatkan tahun penyerapan saat user login
     */ 
    public static function getTahunPenyerapan ()
    {
        return request()->session()->get("global_controller.tahun_penyerapan");
    }
    /**
    * digunakan untuk mendapatkan entri level
    */
    public static function getDaftarLevelEntri () {
      return HelperKegiatan::$LevelEntri;
    }
    /**
    * digunakan untuk mendapatkan entri level
    */
    public static function getLevelEntriByName ($level_name) {        
        switch ($level_name) 
        {            
            case 'usulanprarenjaopd' :
                $level = 0;
            break;
            case 'usulanrakorbidang' :
                $level = 1;
            break;
            case 'usulanforumopd' :
                $level = 2;
            break;
            case 'usulanmusrenkab' :
                $level = 3;
            break;
            case 'pembahasanprarenjaopd' :
                $level = 0;
            break;
            case 'pembahasanrakorbidang' :
                $level = 1;
            break;
            case 'pembahasanforumopd' :
                $level = 2;
            break;
            case 'pembahasanmusrenkab' :
                $level = 3;
            break;
            case 'verifikasirenja' :
                $level = 4;
            break;
            case 'rkpdmurni' :
                $level = 4;
            break;
            case 'rkpdperubahan' :
                $level = 5;
            break;
            default :
                $level = null;
        }
        return $level;
    }
    /**
    * digunakan untuk mendapatkan nama entri level
    */
    public static function getNamaLevelEntri ($EntryLvl) {
      return HelperKegiatan::$LevelEntri[$EntryLvl];
    }
    /**
    * digunakan untuk mendapatkan status kegiatan
    */
    public static function getStatusKegiatan ($StatusKegiatan=null) {
      if ($StatusKegiatan === null)
      {
        return HelperKegiatan::$StatusKegiatan;
      }
      else
      {
        return HelperKegiatan::$StatusKegiatan[$StatusKegiatan];
      }      
    }
    /**
    * digunakan untuk mendapatkan status RKPD
    */
    public static function getStatusRKPD ($StatusRKPD=null) {
        if ($StatusRKPD === null)
        {
          return HelperKegiatan::$StatusRKPD;
        }
        else
        {
          return HelperKegiatan::$StatusRKPD[$StatusRKPD];
        }      
      }
    /**
    * digunakan untuk mendapatkan daftar prioritas
    */
    public static function getDaftarPrioritas () {
      return HelperKegiatan::$DaftarPrioritas;
    }
    /**
    * digunakan untuk mendapatkan nama prioritas
    */
    public static function getNamaPrioritas ($PrioritasID) {
      return HelperKegiatan::$DaftarPrioritas[$PrioritasID];
    }
    /**
     * digunakan untuk memberikan style css
     */
    public static function setStyleForRekapMode1 ($a,$b)
    {
      if ($a < $b)
      {
        return 'info';
      }
      elseif ($a==$b)
      {
        return 'success';
      }else
      {
        return 'danger';
      }
    }
    public static function getPageTitle($nameofpage)
    {
        switch ($nameofpage) 
        {            
            case 'usulanprarenjaopd' :
                $pagetitle = 'USULAN PRA RENJA OPD/SKPD';
            break;
            case 'usulanrakorbidang' :
                $pagetitle = 'USULAN RAKOR BIDANG';
            break;
            case 'usulanforumopd' :
                $pagetitle = 'USULAN FORUM OPD / SKPD';
            break;
            case 'usulanmusrenkab' :
                $pagetitle = 'USULAN MUSRENBANG KABUPATEN';
            break;
            
            case 'pembahasanprarenjaopd' :
               $pagetitle = 'PEMBAHASAN PRA RENJA OPD/SKPD';
            break;
            case 'pembahasanrakorbidang' :
                $pagetitle = 'PEMBAHASAN RAKOR BIDANG';
            break;
            case 'pembahasanforumopd' :
                $pagetitle = 'PEMBAHASAN FORUM OPD / SKPD';
            break;
            case 'pembahasanmusrenkab' :
                $pagetitle = 'PEMBAHASAN MUSRENBANG KABUPATEN';                
            break;           
            case 'verifikasirenja' :
                $pagetitle = 'VERIFIKASI TAPD';                
            break;           

            case 'rkpdmurni' :
                $pagetitle = 'RKPD MURNI';                
            break;           
            case 'rkpdperubahan' :
                $pagetitle = 'RKPD PERUBAHAN';                
            break;           
            default :
                $pagetitle = 'WORKFLOW';
        }
        return $pagetitle;
    }
    public static function getLabelTransfer($nameofpage)
    {
        switch ($nameofpage) 
        {            
            case 'pembahasanprarenjaopd' :
               $pagetitle = 'RAKOR BIDANG';
            break;
            case 'pembahasanrakorbidang' :
                $pagetitle = 'FORUM OPD / SKPD';
            break;
            case 'pembahasanforumopd' :
                $pagetitle = 'MUSRENBANG KABUPATEN';
            break;
            case 'pembahasanmusrenkab' :
                $pagetitle = 'VERIFIKASI TAPD';                
            break;           
            case 'verifikasirenja' :
                $pagetitle = 'RKPD';                
            break;           
            default :
                $pagetitle = 'WORKFLOW';
        }
        return $pagetitle;
    }
    public static function getRouteUsulanFromPembahasan(string $nameofpage,string $action)
    {
        switch ($nameofpage) 
        {            
            case 'pembahasanprarenjaopd' :
                return "usulanprarenjaopd.$action";
            break;
            case 'pembahasanrakorbidang' :
                return "usulanrakorbidang.$action";
            break;
            case 'pembahasanforumopd' :
                return "usulanforumopd.$action";
            break;
            case 'pembahasanmusrenkab' :
            case 'verifikasirenja' :
                return "usulanmusrenkab.$action";
            break;                       
            default :
                $pagetitle = 'WORKFLOW';
        }
        return $pagetitle;
    }
    /**
     * digunakan untuk mendapatkan nama view db
     */
    public static function getViewName ($nameofpage)
    {
        switch ($nameofpage) 
        {         
            case 'usulanprarenjaopd' :
            case 'pembahasanprarenjaopd' :
                $dbViewName = 'v_usulan_pra_renja_opd';
            break;
            case 'usulanrakorbidang' :
            case 'pembahasanrakorbidang' :
                $dbViewName = 'v_usulan_rakor_bidang';
            break;
            case 'usulanforumopd' :
            case 'pembahasanforumopd' :
                $dbViewName = 'v_usulan_forum_opd';
            break;
            case 'usulanmusrenkab' :
            case 'pembahasanmusrenkab' :
                $dbViewName = 'v_usulan_musren_kab';
            break;  
            case 'verifikasirenja' :
                $dbViewName = 'v_verifikasi_renja';
            break;    
            case 'rkpdmurni' :                        
            case 'rkpdperubahan' :
                $dbViewName = 'v_rkpd_rinci';
            break;          
            default :
                $dbViewName = null;
        }
        return $dbViewName;
    }   
    /**
     * digunakan untuk mendapatkan nama view db
     */
    public static function getField ($nameofpage)
    {
        switch ($nameofpage) 
        {            
            case 'usulanprarenjaopd' :
            case 'pembahasanprarenjaopd' :
                $rawSql = \DB::raw('"RenjaID",                                    
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "No",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka1" AS "Sasaran_Angka",
                                    "Sasaran_Uraian1" AS "Sasaran_Uraian",
                                    "Target1" AS "Target",
                                    "Jumlah1" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanrakorbidang' :
            case 'pembahasanrakorbidang' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "No",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka2" AS "Sasaran_Angka",
                                    "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                    "Target2" AS "Target",
                                    "Jumlah2" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanforumopd' :
            case 'pembahasanforumopd' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "No",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka3" AS "Sasaran_Angka",
                                    "Sasaran_Uraian3" AS "Sasaran_Uraian",
                                    "Target3" AS "Target",
                                    "Jumlah3" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanmusrenkab' :
            case 'pembahasanmusrenkab' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka4" AS "Sasaran_Angka",
                                    "Sasaran_Uraian4" AS "Sasaran_Uraian",
                                    "Target4" AS "Target",
                                    "Jumlah4" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'verifikasirenja' :
                $rawSql = \DB::raw('"RenjaID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka5" AS "Sasaran_Angka",
                                    "Sasaran_Uraian5" AS "Sasaran_Uraian",
                                    "Target5" AS "Target",
                                    "Jumlah5" AS "Jumlah",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'rkpdperubahan' :
                $rawSql = \DB::raw('"RKPDRincID",
                                    "RKPDID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "Uraian",
                                    "Sasaran_Angka2" AS "Sasaran_Angka",
                                    "Sasaran_Angka1",
                                    "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                    "Sasaran_Uraian1",
                                    "Target2" AS "Target",
                                    "Target1",
                                    "NilaiUsulan2" AS "Jumlah",
                                    "NilaiUsulan1",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "EntryLvl",
                                    "Descr"');
            break;
            default :
                $rawSql = null;
        }        
        return $rawSql;
    }
}