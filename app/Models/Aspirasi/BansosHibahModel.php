<?php

namespace App\Models\Aspirasi;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BansosHibahModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trBansosHibah';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BansosHibahID', 
        'PemilikBansosHibahID', 
        'PmKecamatanID', 
        'PmDesaID', 
        'SumberDanaID',
        'NamaUsulanKegiatan',
        'Lokasi', 
        'Sasaran_Angka', 
        'Sasaran_Uraian',
        'NilaiUsulan',
        'Status',
        'Output',
        'Jeniskeg',
        'Prioritas',
        'Privilege',
        'Bobot',
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'BansosHibahID';
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
    protected static $logName = 'BansosHibahController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['BansosHibahID', 
                                        'PemilikBansosHibahID', 
                                        'PmKecamatanID', 
                                        'PmDesaID', 
                                        'SumberDanaID', 
                                        'NamaUsulanKegiatan', 
                                        'Lokasi', 
                                        'Sasaran_Angka', 
                                        'Sasaran_Uraian',
                                        'NilaiUsulan',
                                        'Output',
                                        'Jeniskeg',
                                        'Prioritas',
                                        'Privilege',
                                        'Descr',
                                        'TA'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

}
