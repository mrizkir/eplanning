<?php

namespace App\Models\RENSTRA;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RENSTRASasaranIndikatorModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmRenstraIndikatorSasaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RenstraIndikatorSasaranID', 
        'RenstraSasaranID', 
        'NamaIndikator', 
        'KondisiAwal', 
        'N1', 
        'N2', 
        'N3', 
        'N4', 
        'N5', 
        'KondisiAkhir', 
        'Satuan', 
        'Descr', 
        'TA', 
        'RenstraIndikatorSasaranID_Src'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RenstraIndikatorSasaranID';
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
    protected static $logName = 'RENSTRASasaranController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RenstraIndikatorSasaranID', 'NamaIndikator'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    
    public function sasaran()
    {
        return $this->belongsTo('\App\Models\RENSTRA\RENSTRASasaranModel','RenstraSasaranID');
    }
}
