<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDRincianModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRKPDRinc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'RKPDRincID', 
        'RKPDID',
        'RenjaRincID',
        'PMProvID',
        'PmKotaID',
        'PmKecamatanID',
        'PmDesaID',
        'PokPirID',
        'Uraian',
        'No',
        'Sasaran_Uraian1',
        'Sasaran_Uraian2',
        'Sasaran_Angka1',
        'Sasaran_Angka2',
        'NilaiUsulan1',
        'NilaiUsulan2',
        'Target1',
        'Target2',    
        'Tgl_Posting', 
        'isReses',
        'isReses_Uraian',
        'isSKPD',
        'Descr',
        'TA',
        'status',
        'EntryLvl',
        'Privilege',        
        'RKPDRincID_Src'        
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RKPDRincID';
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
    protected static $logAttributes = ['RKPDID', 'RKPDRincID', 'RKPDRincID_Src'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
