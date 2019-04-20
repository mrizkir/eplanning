<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RenjaRincianModel extends Model {
    use LogsActivity;
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
       'PMProvID',
       'PmKotaID',
       'PmKecamatanID',
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