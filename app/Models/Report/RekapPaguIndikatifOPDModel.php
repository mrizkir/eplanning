<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RekapPaguIndikatifOPDModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRekapPaguIndikatifOPD';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'OrgID', 
        'Kode_Organisasi',
        'OrgNm',
        'Jumlah1',
        'Jumlah2',
        'prarenja1',
        'prarenja2',
        'rakorbidang1',
        'rakorbidang2',
        'forumopd1',
        'forumopd2',
        'musrenkab1',
        'musrenkab2',
        'renjafinal1',
        'renjafinal2',
        'rkpd11',
        'rkpd12'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'OrgID';
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
    protected static $logName = 'RekapPaguIndikatorOPDController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['OrgID', 'Kode_Organisasi', 'OrgNm'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
