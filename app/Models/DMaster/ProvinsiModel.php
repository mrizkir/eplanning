<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProvinsiModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPMProv';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PMProvID', 'Kd_Prov', 'Nm_Prov', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PMProvID';
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
    protected static $logName = 'ProvinsiController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PMProvID', 'Kd_Prov', 'Nm_Prov'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public static function getDaftarProvinsi ($ta,$PMProvID=NULL,$prepend=true) 
    {
        $r=ProvinsiModel::where('TA',$ta)->orderBy('Kd_Prov');
        if ($PMProvID != NULL) 
        {
            $r=$r->where('PmProvID',$PMProvID);
        }
        $r=$r->get();
        
        $daftar_provinsi=($prepend==true)?['none'=>'DAFTAR KOTA']:[];        
        foreach ($r as $k=>$v)
        {
            $daftar_provinsi[$v->PMProvID]=$v->Nm_Prov;
        }         
        return $daftar_provinsi;
    }
}
