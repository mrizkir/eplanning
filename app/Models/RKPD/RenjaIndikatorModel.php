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
       'Descr',
       'Privilege',
       'TA',
       'RenjaIndikatorID_Src'
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
   /**
     * make the model use another name than the default
     *
     * @var string
     */
    protected static $logName = 'UsulanPraRenjaOPDController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RenjaIndikatorID', 'IndikatorKinerjaID', 'RenjaID','Target_Angka','Target_Uraian'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    
    public function renja()
    {
        return $this->belongsTo('\App\Models\RKPD\RenjaModel','RenjaID');
    }
}