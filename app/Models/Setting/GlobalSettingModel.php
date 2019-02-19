<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class GlobalSettingModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'globalsetting';
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'globalsetting_id';
    /**
     * disable auto_increment.
     *
     * @var string
     */
    public $incremening = true;
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
    // protected static $logName = 'GlobalSettingController';
    /**
     * log the changed attributes for all these events 
     */
    // protected static $logAttributes = ['replace_it', 'replace_it'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
