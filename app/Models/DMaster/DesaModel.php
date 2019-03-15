<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class DesaModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPmDesa';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PmDesaID', 
        'PmKecamatanID',
        'Kd_Desa',
        'Nm_Desa',
        'Descr',
        'TA',
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PmDesaID';
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
    public $timestamps = false;

    /**
     * make the model use another name than the default
     *
     * @var string
     */
    // protected static $logName = 'DesaController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = [
        'PmDesaID', 
        'PmKecamatanID',
        'Kd_Desa',
        'Nm_Desa',
        'Descr',
        'TA'
    ];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public static function getDaftarDesa ($ta,$pmKecamatanID=NULL,$prepend=true) 
    {
        $r=DesaModel::where('TA',$ta)->orderBy('Kd_Desa');
        if ($pmKecamatanID !==NULL)
        {
            $r->where('pmKecamatanID',$pmKecamatanID);
        }
        $r->get();
        $daftar_desa=($prepend==true)?['none'=>'DAFTAR DESA']:[];        
        foreach ($r as $k=>$v)
        {
            $daftar_desa[$v->PmDesaID]=$v->Nm_Desa;
        } 
        return $daftar_desa;
    }
}
