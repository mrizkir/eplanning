<?php

namespace App\Models\RENSTRA;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RENSTRATujuanModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPrioritasTujuanKab';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrioritasTujuanKabID', 'PrioritasKabID', 'OrgID', 'Kd_Tujuan', 'Nm_Tujuan', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PrioritasTujuanKabID';
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
    protected static $logName = 'RENSTRATujuanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PrioritasTujuanKabID', 'Nm_Tujuan'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
