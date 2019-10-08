<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class KotaModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPmKota';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PmKotaID', 'PMProvID', 'Kd_Kota', 'Nm_Kota', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PmKotaID';
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
    protected static $logName = 'KotaController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PmKotaID', 'PMProvID', 'Kd_Kota', 'Nm_Kota'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public static function getDaftarKota ($ta,$PmKotaID=NULL,$prepend=true) 
    {
        $r=KotaModel::where('TA',$ta)->orderBy('Kd_Kota');
        if ($PmKotaID != NULL) 
        {
            $r=$r->where('PmKotaID',$PmKotaID);
        }
        $r=$r->get();
        
        $daftar_kecamatan=($prepend==true)?['none'=>'DAFTAR KOTA']:[];        
        foreach ($r as $k=>$v)
        {
            $daftar_kecamatan[$v->PmKotaID]=$v->Nm_Kota;
        }         
        return $daftar_kecamatan;
    }
}
