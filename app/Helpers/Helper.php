<?php

namespace App\Helpers;
use Carbon\Carbon;
use URL;
class Helper {
    /**
     * Daftar Prioritas
     */ 
    private static $daftar_prioritas =['none'=>'DAFTAR PRIORITAS',
                                1=>'P1',
                                2=>'P2',
                                3=>'P3',
                                4=>'P4',
                                5=>'P5',
                                6=>'P6'];

    /**
     * digunakan controller yang sedang diakses
     */
    public static function getCurrentController() 
    {
        $controller_name=strtolower(class_basename(\Route::current()->controller));
        $controller=str_replace('controller','',$controller_name); 
        return $controller;    
    } 
    /**
     * digunakan untuk mendapatkan url halaman yang sedang diakses
     */
    public static function getCurrentPageURL() 
    {        
        $controller_name=Helper::getCurrentController() ;
        return (\Route("$controller_name.index"));    
    }
    /**
     * digunakan untuk mendapatkan status aktif menu
     */
    public static function isMenuActive ($current_page_active,$page_name,$callback='active') 
    {
        if ($current_page_active == $page_name) {
            return $callback;
        }else{
            return '';
        }
    }
    /**
     * digunakan untuk memformat tanggal
     * @param type $format
     * @param type $date
     * @return type date
     */
    public static function tanggal($format, $date=null) {
        if ($date == null){
            $tanggal=Carbon::parse(Carbon::now())->format($format);
        }else{
            $tanggal = Carbon::parse($date)->format($format);
        }        
        return $tanggal;
    }   
    /**
	* digunakan untuk mem-format uang
	*/
	public static function formatUang ($uang=0) {
		$formatted = number_format((float)$uang,0,'.','.');
        return $formatted;
    }
    /**
	* digunakan untuk mem-format angka
	*/
	public static function formatAngka ($angka=0) {
        $bil = intval($angka);
        $formatted = ($bil < $angka) ? $angka : $bil;
        return $formatted;
    }
    /**
	* digunakan untuk mem-format persentase
	*/
	public function formatPersen ($pembilang,$penyebut=0,$dec_sep=2) {
        $result=0.00;
		if ($pembilang > 0) {
            $temp=number_format((float)($pembilang/$penyebut)*100,$dec_sep);
            $result = $temp > 100 ? 100.00 : $temp;
        }
        return $result;
	}
    /**
	* digunakan untuk mem-format pecahan
	*/
	public function formatPecahan ($pembilang,$penyebut=0,$dec_sep=2) {
        $result=0;
		if ($pembilang > 0) {
            $result=number_format((float)($pembilang/$penyebut),$dec_sep);
        }
        return $result;
	}
    /**
	* digunakan untuk mendapatkan daftar prioritas
	*/
	public static function getDaftarPrioritas () {
		return Helper::$daftar_prioritas;
    }
    /**
	* digunakan untuk mendapatkan nama prioritas
	*/
	public static function getNamaPrioritas ($PrioritasID) {
		return Helper::$daftar_prioritas[$PrioritasID];
	}
}