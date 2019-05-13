<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRKPD';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RKPDID', 
        'RenjaID',
        'OrgID',
        'SOrgID',
        'KgtID',
        'SumberDanaID',
        'NamaIndikator',
        'Sasaran_Uraian1',
        'Sasaran_Uraian2',
        'Sasaran_Angka1',
        'Sasaran_Angka2',
        'NilaiUsulan1',
        'NilaiUsulan2',
        'Target1',
        'Target2',
        'Sasaran_AngkaSetelah',
        'Sasaran_UraianSetelah',
        'Tgl_Posting',
        'Descr',
        'TA',
        'status',
        'EntryLvl',
        'Privilege',
        'RKPDID_Src'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RKPDID';
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
    protected static $logName = 'RKPDMurniController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RKPDID', 'KgtID', 'RKPDID_Src'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
