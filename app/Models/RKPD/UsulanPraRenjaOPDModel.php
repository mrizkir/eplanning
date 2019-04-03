<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;

class UsulanPraRenjaOPDModel extends Model {
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'v_usulan_pra_renja_opd';   

    /**
    * primary key tabel ini.
    *
    * @var string
    */
   protected $primaryKey = 'RenjaID';

    public static function create (array $attributes=[]) 
    {
        $model=Renja::create($attributes['renja']);
        RenjaRincian::create($attributes['renjarinc']);

        return $model;
    }
    public static function destroy ($uuid) 
    {
        Renja::destroy($uuid);                
    }
}

class Renja extends Model {
    /**
    * nama tabel model ini.
    *
    * @var string
    */
   protected $table = 'trRenja';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'RenjaID', 
       'OrgID',
       'SOrgID',
       'KgtID',
       'SumberDanaID',
       'NamaIndikator',
       'Sasaran_Uraian1',
       'Sasaran_Uraian2',
       'Sasaran_Uraian3',
       'Sasaran_Uraian4',
       'Sasaran_Uraian5',
       'Sasaran_Uraian6',
       'Sasaran_Angka1',
       'Sasaran_Angka2',
       'Sasaran_Angka3',
       'Sasaran_Angka4',
       'Sasaran_Angka5',
       'Sasaran_Angka6',
       'Target1',
       'Target2',    
       'Target3',    
       'Target4',    
       'Target5',    
       'Target6',   
       'NilaiUsulan1', 
       'NilaiUsulan2', 
       'NilaiUsulan3', 
       'NilaiUsulan4', 
       'NilaiUsulan5', 
       'NilaiUsulan6', 
       'Sasaran_AngkaSetelah',
       'Sasaran_UraianSetelah',
       'NilaiSebelum',
       'NilaiSetelah',
       'Descr',
       'TA',
       'Status',
       'EntryLvl',
       'Prioritas'
   ];
   /**
    * primary key tabel ini.
    *
    * @var string
    */
   protected $primaryKey = 'RenjaID';
   /**
    * enable auto_increment.
    *
    * @var string
    */
   public $incrementing = false;
   /**
    * activated timestamps.
    *
    * @var string
    */
   public $timestamps = true;
}

class RenjaRincian extends Model {
    /**
    * nama tabel model ini.
    *
    * @var string
    */
   protected $table = 'trRenjaRinc';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'RenjaRincID', 
       'RenjaID',
       'UsulanKecID',
       'PmDesaID',
       'PokPirID',
       'Uraian',
       'No',
       'Sasaran_Uraian1',
       'Sasaran_Uraian2',
       'Sasaran_Uraian3',
       'Sasaran_Uraian4',
       'Sasaran_Uraian5',
       'Sasaran_Uraian6',
       'Sasaran_Angka1',
       'Sasaran_Angka2',
       'Sasaran_Angka3',
       'Sasaran_Angka4',
       'Sasaran_Angka5',
       'Sasaran_Angka6',
       'Target1',
       'Target2',    
       'Target3',    
       'Target4',    
       'Target5',    
       'Target6',   
       'Jumlah1', 
       'Jumlah2', 
       'Jumlah3', 
       'Jumlah4', 
       'Jumlah5', 
       'Jumlah6', 
       'isReses',
       'isReses_Uraian',
       'isSKPD',
       'Status',
       'EntryLvl',
       'Prioritas',
       'Prioritas',
       'Descr',
       'TA',
   ];
   /**
    * primary key tabel ini.
    *
    * @var string
    */
   protected $primaryKey = 'RenjaRincID';
   /**
    * enable auto_increment.
    *
    * @var string
    */
   public $incrementing = false;
   /**
    * activated timestamps.
    *
    * @var string
    */
   public $timestamps = true;
}
