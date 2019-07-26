<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDIndikatorModel extends Model {
    use LogsActivity;
    /**
    * nama tabel model ini.
    *
    * @var string
    */
   protected $table = 'trRKPDIndikator';
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'RKPDIndikatorID', 
       'RKPDID',
       'IndikatorKinerjaID',       
       'Target_Angka',
       'Target_Uraian',  
       'Tahun',      
       'Descr',
       'TA',
       'Privilege',
       'RKPDIndikatorID_Src'
   ];
   /**
    * primary key tabel ini.
    *
    * @var string
    */
   protected $primaryKey = 'RKPDIndikatorID';
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
   /**
     * make the model use another name than the default
     *
     * @var string
     */
    protected static $logName = 'RKPDMurniController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RKPDIndikatorID', 'IndikatorKinerjaID', 'RKPDID','Target_Angka','Target_Uraian'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public function rkpd()
    {
        return $this->belongsTo('\App\Models\RKPD\RKPDModel','RKPDID');
    }
}