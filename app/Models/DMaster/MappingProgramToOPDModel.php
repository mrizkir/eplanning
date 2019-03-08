<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class MappingProgramToOPDModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trOrgProgram';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orgProgramID', 'OrgID', 'PrgID', 'Descr','TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'orgProgramID';
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
    protected static $logName = 'MappingProgramToOPDController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['orgProgramID', 'OrgID','PrgID'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
