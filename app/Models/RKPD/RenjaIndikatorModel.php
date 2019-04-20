<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RenjaIndikatorModel extends Model {
    use LogsActivity;
    /**
    * nama tabel model ini.
    *
    * @var string
    */
   protected $table = 'trRenjaIndikator';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'RenjaIndikatorID', 
       'IndikatorKinerjaID',
       'RenjaID',
       'Target_Angka',
       'Target_Uraian',  
       'Tahun',      
       'Descr',
       'TA',
   ];
   /**
    * primary key tabel ini.
    *
    * @var string
    */
   protected $primaryKey = 'RenjaIndikatorID';
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