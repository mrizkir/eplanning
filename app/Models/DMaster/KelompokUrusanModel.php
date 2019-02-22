<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class KelompokUrusanModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmKUrs';
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'KUrsID';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'KUrsID', 'Kd_Urusan', 'Nm_Urusan', 'Descr','TA',
    ];
    /**
     * disable auto_increment.
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
    protected static $logName = 'KelompokUrusanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['Kd_Urusan', 'Nm_Urusan'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
