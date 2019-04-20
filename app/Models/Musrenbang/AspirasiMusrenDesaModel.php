<?php

namespace App\Models\Musrenbang;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AspirasiMusrenDesaModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trUsulanDesa';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UsulanDesaID', 
        'PmDesaID', 
        'SumberDanaID', 
        'No_usulan', 
        'NamaKegiatan', 
        'Output', 
        'Lokasi', 
        'NilaiUsulan', 
        'Target_Angka',
        'Target_Uraian',
        'Jeniskeg',
        'Prioritas',
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'UsulanDesaID';
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
    protected static $logName = 'MusrenDesaController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes =  [
        'UsulanDesaID', 
        'PmDesaID', 
        'SumberDanaID', 
        'No_usulan', 
        'NamaKegiatan', 
        'Output', 
        'Lokasi', 
        'NilaiUsulan', 
        'Target_Angka',
        'Target_Uraian',
        'Jeniskeg',
        'Prioritas',
        'Descr',
        'TA'
    ];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
