<?php

namespace App\Models\Musrenbang;

use Illuminate\Database\Eloquent\Model;

class PembahasanMusrenDesaModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'pembahasanmusrendesa';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'replace_it', 'replace_it'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'pembahasanmusrendesa_id';
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
    // protected static $logName = 'PembahasanMusrenDesaController';
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
