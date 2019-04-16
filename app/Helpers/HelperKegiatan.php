<?php

namespace App\Helpers;

class HelperKegiatan {
    /**
     * Daftar Prioritas
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
	* digunakan untuk mendapatkan entri level
	*/
	public static function getDaftarLevelEntri () {
		return HelperKegiatan::$LevelEntri;
    }
    /**
	* digunakan untuk mendapatkan nama entri level
	*/
	public static function getNamaLevelEntri ($EntryLvl) {
		return HelperKegiatan::$LevelEntri[$EntryLvl];
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
    

}