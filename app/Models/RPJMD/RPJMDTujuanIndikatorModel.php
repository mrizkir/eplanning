<?php

namespace App\Models\RPJMD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RPJMDTujuanIndikatorModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPrioritasIndikatorTujuan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrioritasIndikatorTujuanID', 'PrioritasTujuanKabID', 'NamaIndikator', 'KondisiAwal', 'KondisiAkhir', 'Satuan', 'Operator','Descr', 'TA', 'PrioritasIndikatorTujuanID_Src'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PrioritasIndikatorTujuanID';
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
    protected static $logName = 'RPJMDTujuanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PrioritasIndikatorTujuanID', 'NamaIndikator'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    
    public function tujuan()
    {
        return $this->belongsTo('\App\Models\RPJMD\RPJMDTujuanModel','PrioritasTujuanKabID');
    }
}
