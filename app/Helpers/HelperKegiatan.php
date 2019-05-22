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
}