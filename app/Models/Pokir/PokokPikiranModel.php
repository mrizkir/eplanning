<?php

namespace App\Models\Pokir;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PokokPikiranModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trPokPir';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PokPirID', 
        'PemilikPokokID', 
        'OrgID', 
        'SOrgID', 
        'PmKecamatanID', 
        'PmDesaID', 
        'SumberDanaID',
        'NamaUsulanKegiatan',
        'Lokasi', 
        'Sasaran_Angka', 
        'Sasaran_Uraian',
        'NilaiUsulan',
        'Status',
        'EntryLvl',
        'Output',
        'Jeniskeg',
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
    protected $primaryKey = 'PokPirID';
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
    protected static $logName = 'PokokPikiranController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PokPirID', 
                                        'PemilikPokokID', 
                                        'OrgID', 
                                        'SOrgID', 
                                        'PmKecamatanID', 
                                        'PmDesaID', 
                                        'SumberDanaID', 
                                        'Lokasi', 
                                        'Sasaran_Angka', 
                                        'Sasaran_Uraian',
                                        'NilaiUsulan',
                                        'Status',
                                        'EntryLvl',
                                        'Output',
                                        'Jeniskeg',
                                        'Prioritas',
                                        'Bobot',
                                        'Descr',
                                        'TA'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
}
