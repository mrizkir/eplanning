<?php

namespace App\Models\RPJMD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RPJMDProgramKebijakanModel extends Model {
    use LogsActivity;
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPrioritasProgramKebijakan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ProgramKebijakanID','PrioritasKebijakanKabID', 'UrsID', 'PrgID', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'ProgramKebijakanID';
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
    protected static $logName = 'RPJMDKebijakanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['ProgramKebijakanID','PrioritasKebijakanKabID', 'UrsID', 'PrgID'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;   
}
