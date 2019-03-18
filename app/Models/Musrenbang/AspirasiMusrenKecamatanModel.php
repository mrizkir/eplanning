<?php

namespace App\Models\Musrenbang;

use Illuminate\Database\Eloquent\Model;

class AspirasiMusrenKecamatanModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trUsulanKec';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UsulanKecID', 
        'UsulanDesaID',
        'PmKecamatanID',
        'PmDesaID',
        'PrioritasSasaranKabID',
        'PrgID',
        'OrgID',
        'SumberDanaID',
        'No_usulan',
        'NamaKegiatan',
        'Output',
        'Lokasi',
        'NilaiUsulan',
        'Target_Angka',
        'Jeniskeg',
        'Target_Uraian',
        'Prioritas',
        'Bobot',
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'UsulanKecID';
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
    protected static $logName = 'AspirasiMusrenKecamatanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['UsulanKecID', 'NamaKegiatan'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
