<?php

namespace App\Models\RPJMD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RpjmdIndikatorKinerjaModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trIndikatorKinerja';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'IndikatorKinerjaID',
        'PrioritasKebijakanKabID', 
        'UrsID', 
        'PrgID', 
        'OrgID', 
        'OrgID2', 
        'OrgID3', 
        'NamaIndikator',
        'TA_N',
        'TargetN',
        'TargetN1',
        'TargetN2',
        'TargetN3',
        'TargetN4',
        'TargetN5',
        'PaguDanaN',
        'PaguDanaN1',
        'PaguDanaN2',
        'PaguDanaN3',
        'PaguDanaN4',
        'PaguDanaN5',
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'IndikatorKinerjaID';
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
    protected static $logName = 'RpjmdIndikatorKinerjaController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['IndikatorKinerjaID', 'PrioritasKebijakanKabID', 'NamaIndikator'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public function getDaftarIndikatorKinerja($ta_saat_ini)
    {
        
    }
}
