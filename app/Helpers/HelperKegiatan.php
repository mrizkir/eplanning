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
     * digunakan untuk mendapatkan tahun awal rpjmd saat user login
     */ 
    public static function getRPJMDTahunAwal ()
    {
        return request()->session()->get("global_controller.rpjmd_tahun_awal");
    }
    /**
     * digunakan untuk mendapatkan tahun mulai rpjmd saat user login
     */ 
    public static function getRPJMDTahunMulai ($api=false)
    {
        if ($api)
        {
            return 2016;
        }
        else
        {
            return request()->session()->get("global_controller.rpjmd_tahun_mulai");
        }
    }
    /**
     * digunakan untuk mendapatkan tahun akhir rpjmd saat user login
     */ 
    public static function getRPJMDTahunAkhir ($api=false)
    {        
        return request()->session()->get("global_controller.rpjmd_tahun_akhir");        
    }
    /**
     * digunakan untuk mendapatkan tahun mulai renstra saat user login
     */ 
    public static function getRENSTRATahunMulai ()
    {
        return request()->session()->get("global_controller.renstra_tahun_mulai");
    }
    /**
     * digunakan untuk mendapatkan tahun akhir renstra saat user login
     */ 
    public static function getRENSTRATahunAkhir ()
    {
        return request()->session()->get("global_controller.renstra_tahun_akhir");
    }
    /**
     * digunakan untuk mendapatkan tahun perencanaan saat user login
     */ 
    public static function getTahunPerencanaan ($api=false)
    {
        if ($api)
        {
            return 2021;
        }
        else
        {
            return request()->session()->get("global_controller.tahun_perencanaan");
        }
    }
    /**
     * digunakan untuk mendapatkan tahun penyerapan saat user login
     */ 
    public static function getTahunPenyerapan ()
    {
        return request()->session()->get("global_controller.tahun_penyerapan");
    }
    /**
     * digunakan untuk mendapatkan tahun penyerapan saat user login
     */ 
    public static function getTahunN ()
    {
        return request()->session()->get("global_controller.tahun_N");
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
            case 'reportusulanprarenjaopd' :
                $level = 0;
            break;
            case 'usulanrakorbidang' :
            case 'reportrakorbidang' :
                $level = 1;
            break;
            case 'usulanforumopd' :
            case 'reportforumopd' :
                $level = 2;
            break;
            case 'usulanmusrenkab' :
            case 'reportmusrenkab' :
                $level = 3;
            break;
            case 'verifikasirenja' :
            case 'reportverifikasitapd' :
                $level = 4;
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
            case 'rkpdmurni' :
                $level = 1;
            break;
            case 'pembahasanrkpd' :
                $level = 2;
            break;
            case 'rkpdperubahan' :
                $level = 3;
            break;
            case 'pembahasanrkpdp' :
                $level = 4;
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
    * digunakan untuk mendapatkan daftar prioritas
    */
    public static function getDaftarPrioritas () {
      return HelperKegiatan::$DaftarPrioritas;
    }
    /**
    * digunakan untuk mendapatkan nama prioritas
    */
    public static function getNamaPrioritas ($PrioritasID) {
        return "P$PrioritasID";
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
            case 'reportusulanprarenjaopd' :
                $pagetitle = 'LAPORAN USULAN PRA RENJA OPD/SKPD';
            break;
            case 'usulanrakorbidang' :
                $pagetitle = 'USULAN RAKOR BIDANG';
            break;
            case 'reportrakorbidang' :
                $pagetitle = 'LAPORAN RAKOR BIDANG';
            break;
            case 'usulanforumopd' :
                $pagetitle = 'USULAN FORUM OPD / SKPD';
            break;
            case 'reportforumopd' :
                $pagetitle = 'LAPORAN FORUM OPD / SKPD';
            break;
            case 'usulanmusrenkab' :
                $pagetitle = 'USULAN MUSRENBANG KABUPATEN';
            break;
            case 'reportmusrenkab' :
                $pagetitle = 'LAPORAN MUSRENBANG KABUPATEN';
            break;
            case 'reportverifikasitapd' :
                $pagetitle = 'LAPORAN VERIFIKASI TAPD';
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
            case 'pembahasanrkpd' :
                $pagetitle = 'PEMBAHASAN RKPD MURNI';                
            break;           
            case 'rkpdperubahan' :
                $pagetitle = 'RKPD PERUBAHAN';                
            break;           
            case 'pembahasanrkpdp' :
                $pagetitle = 'PEMBAHASAN RKPD PERUBAHAN';                
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
            case 'pembahasanrkpdp' :
                $pagetitle = 'PEMBAHASAN RKPD PERUBAHAN';                
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
            case 'reportusulanprarenjaopd' :
                $dbViewName = 'v_usulan_pra_renja_opd';
            break;
            case 'usulanrakorbidang' :
            case 'pembahasanrakorbidang' :
            case 'reportrakorbidang' :
                $dbViewName = 'v_usulan_rakor_bidang';
            break;
            case 'usulanforumopd' :
            case 'pembahasanforumopd' :
            case 'reportforumopd' :
                $dbViewName = 'v_usulan_forum_opd';
            break;
            case 'usulanmusrenkab' :
            case 'pembahasanmusrenkab' :
            case 'reportmusrenkab' :
                $dbViewName = 'v_usulan_musren_kab';
            break;  
            case 'verifikasirenja' :
            case 'reportverifikasitapd' :
                $dbViewName = 'v_verifikasi_renja';
            break;    
            case 'rkpdmurni' :                        
            case 'rkpdperubahan' :
            case 'pembahasanrkpdp' :
                $dbViewName = 'v_rkpd_rinci';
            break;          
            default :
                $dbViewName = null;
        }
        return $dbViewName;
    }   
    /**
     * digunakan untuk mendapatkan nama field dari untuk view
     */
    public static function getField ($nameofpage)
    {
        switch ($nameofpage) 
        {            
            case 'usulanprarenjaopd' :
            case 'pembahasanprarenjaopd' :
            case 'reportusulanprarenjaopd' :
                $rawSql = \DB::raw('"RenjaID",                                    
                                    "KgtID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "No",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka1" AS "Sasaran_Angka",
                                    "Sasaran_Uraian1" AS "Sasaran_Uraian",
                                    "Target1" AS "Target",
                                    "Jumlah1" AS "Jumlah",
                                    "Nm_SumberDana",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Locked",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanrakorbidang' :
            case 'pembahasanrakorbidang' :
            case 'reportrakorbidang' :
                $rawSql = \DB::raw('"RenjaID",
                                    "KgtID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "No",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka2" AS "Sasaran_Angka",
                                    "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                    "Target2" AS "Target",
                                    "Jumlah2" AS "Jumlah",
                                    "Nm_SumberDana",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Locked",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanforumopd' :
            case 'pembahasanforumopd' :
            case 'reportforumopd' :
                $rawSql = \DB::raw('"RenjaID",
                                    "KgtID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "No",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka3" AS "Sasaran_Angka",
                                    "Sasaran_Uraian3" AS "Sasaran_Uraian",
                                    "Target3" AS "Target",
                                    "Jumlah3" AS "Jumlah",
                                    "Nm_SumberDana",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Locked",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'usulanmusrenkab' :
            case 'pembahasanmusrenkab' :
            case 'reportmusrenkab' :
                $rawSql = \DB::raw('"RenjaID",
                                    "KgtID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka4" AS "Sasaran_Angka",
                                    "Sasaran_Uraian4" AS "Sasaran_Uraian",
                                    "Target4" AS "Target",
                                    "Jumlah4" AS "Jumlah",
                                    "Nm_SumberDana",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Locked",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'verifikasirenja' :
            case 'reportverifikasitapd' :
                $rawSql = \DB::raw('"RenjaID",
                                    "KgtID",
                                    "RenjaRincID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka5" AS "Sasaran_Angka",
                                    "Sasaran_Uraian5" AS "Sasaran_Uraian",
                                    "Target5" AS "Target",
                                    "Jumlah5" AS "Jumlah",
                                    "Nm_SumberDana",
                                    "Prioritas",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Locked",
                                    "Status_Indikator",
                                    "Descr"');
            break;
            case 'rkpdmurni' :
                $rawSql = \DB::raw('"RKPDRincID",
                                    "RKPDID",
                                    "PrgID",
                                    "KgtID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka1" AS "Sasaran_Angka",
                                    "Sasaran_Uraian1" AS "Sasaran_Uraian",
                                    "Target1" AS "Target",
                                    "NilaiUsulan1" AS "Jumlah",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "EntryLvl",
                                    "Descr"');
            break;
            case 'pembahasanrkpd' :
                $rawSql = \DB::raw('"RKPDRincID",
                                    "RKPDID",
                                    "PrgID",
                                    "KgtID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka2" AS "Sasaran_Angka",
                                    "Sasaran_Uraian2" AS "Sasaran_Uraian",
                                    "Target2" AS "Target",
                                    "NilaiUsulan1" AS "Jumlah",
                                    "NilaiUsulan2" AS "Jumlah2",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "EntryLvl",
                                    "Descr"');
            break;
            case 'rkpdperubahan' :
                $rawSql = \DB::raw('"RKPDRincID",
                                    "RKPDID",
                                    "PrgID",
                                    "KgtID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka3" AS "Sasaran_Angka",
                                    "Sasaran_Angka2",
                                    "Sasaran_Uraian3" AS "Sasaran_Uraian",
                                    "Sasaran_Uraian2",
                                    "Target3" AS "Target",
                                    "Target2",
                                    "NilaiUsulan2" AS "Jumlah",
                                    "NilaiUsulan3" AS "Jumlah2",
                                    "isSKPD",
                                    "isReses",
                                    "isReses_Uraian",
                                    "Status",
                                    "Privilege",
                                    "Status_Indikator",
                                    "EntryLvl",
                                    "Descr"');
            break;
            case 'pembahasanrkpdp' :
                $rawSql = \DB::raw('"RKPDRincID",
                                    "RKPDID",
                                    "UsulanKecID",
                                    "No",
                                    "Nm_Kecamatan",
                                    "kode_kegiatan",
                                    "KgtNm",
                                    "NamaIndikator",
                                    "Sasaran_AngkaSetelah",
                                    "Sasaran_UraianSetelah",
                                    "NilaiSetelah",
                                    "NilaiSebelum",
                                    "Uraian",
                                    "Sasaran_Angka4" AS "Sasaran_Angka",
                                    "Sasaran_Angka3",
                                    "Sasaran_Uraian4" AS "Sasaran_Uraian",
                                    "Sasaran_Uraian3",
                                    "Target4" AS "Target",
                                    "Target3",
                                    "NilaiUsulan3" AS "Jumlah3",
                                    "NilaiUsulan4" AS "Jumlah4",
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