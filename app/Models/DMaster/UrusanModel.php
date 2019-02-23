<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class UrusanModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmUrs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UrsID', 'KUrsID','Kd_Bidang', 'Kode_Bidang', 'Nm_Bidang', 'Descr','TA',
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'UrsID';
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
    protected static $logName = 'UrusanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['UrsID','KUrsID','Kd_Bidang', 'Kode_Bidang', 'Nm_Bidang'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public function kelompokurusan () 
    {
        return $this->belongsTo('App\Models\DMaster\KelompokUrusanModel','KUrsID');
    }
}
