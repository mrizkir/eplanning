<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class KecamatanModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPmKecamatan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PmKecamatanID', 'PmKotaID', 'Kd_Kecamatan', 'Nm_Kecamatan', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PmKecamatanID';
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
    // protected static $logName = 'KecamatanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PmKecamatanID', 'PmKotaID', 'Kd_Kecamatan', 'Nm_Kecamatan', 'Descr', 'TA'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public static function getDaftarKecamatan ($ta,$prepend=true) 
    {
        $r=KecamatanModel::where('TA',$ta)->orderBy('Kd_Kecamatan')->get();
        $daftar_kecamatan=($prepend==true)?['none'=>'DAFTAR KECAMATAN']:[];        
        foreach ($r as $k=>$v)
        {
            $daftar_kecamatan[$v->PmKecamatanID]=$v->Nm_Kecamatan;
        } 
        return $daftar_kecamatan;
    }
}