<?php

namespace App\Models\Aspirasi;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LongListModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmLongList';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'LongListID', 
        'OrgID', 
        'KgtNm', 
        'NilaiUsulan', 
        'Sasaran_Angka',
        'Sasaran_Uraian',
        'Lokasi', 
        'Output',              
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'LongListID';
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
    protected static $logName = 'LongListController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['LongListID', 
                                        'OrgID', 
                                        'PmKecamatanID', 
                                        'KgtNm',                                       
                                        'Descr',
                                        'TA'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

}
