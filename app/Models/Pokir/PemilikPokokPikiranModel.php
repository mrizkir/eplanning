<?php

namespace App\Models\Pokir;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PemilikPokokPikiranModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPemilikPokok';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PemilikPokokID', 
        'Kd_PK', 
        'NmPk',
         
        'Jumlah_Kegiatan1', 
        'Jumlah_Kegiatan2', 
        'Jumlah_Kegiatan3', 
        'Jumlah_Kegiatan4', 
        'Jumlah_Kegiatan5', 

        'Jumlah1', 
        'Jumlah2', 
        'Jumlah3', 
        'Jumlah4', 
        'Jumlah5', 

        'Descr', 
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PemilikPokokID';
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
    protected static $logName = 'PemilikPokokPikiranController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = [ 'PemilikPokokID', 'Kd_PK', 'NmPk', 'Jumlah1', 'Jumlah2'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public function PokokPikiran()
    {
        return $this->hasMany('\App\Models\Pokir\PokokPikiranModel','PemilikPokokID');
    }
}
