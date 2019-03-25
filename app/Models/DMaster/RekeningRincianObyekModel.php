<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class RekeningRincianObyekModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmROby';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RObyID', 'ObyID', 'Kd_Rek_5', 'RObyNm', 'DH', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RObyID';
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
    protected static $logName = 'RekeningRincianObyekController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RObyID', 'RObyNm'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
