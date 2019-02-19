<?php

namespace App\Helpers;
use Carbon\Carbon;
use URL;
class Helper { 
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
}