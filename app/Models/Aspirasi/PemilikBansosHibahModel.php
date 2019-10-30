<?php

namespace App\Models\Aspirasi;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PemilikBansosHibahModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPemilikBansosHibah';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PemilikBansosHibahID', 
        'Kd_PK', 
        'NmPk',         
        'Jumlah_Kegiatan1', 
        'Jumlah1', 
        'Descr', 
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PemilikBansosHibahID';
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
    protected static $logName = 'PemilikBansosHibahController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = [ 'PemilikBansosHibahID', 'Kd_PK', 'NmPk', 'Jumlah_Kegiatan1', 'Jumlah1'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

}
