<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserDewan extends Model
{
    use LogsActivity;
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'usersopd';   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'useropd', 'id', 'ta','PemilikPokokID','Kd_PK','NmPk'
    ];
    /**
    * primary key tabel ini.
    *
    * @var string
    */
    protected $primaryKey = 'userdewan';
    /**
    * enable auto_increment.
    *
    * @var string
    */
    public $incrementing = true;
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
    protected static $logName = 'setting\UsersDewanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = [
        'useropd', 'id', 'ta','PemilikPokokID','Kd_PK','NmPk'
    ];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];     

}
