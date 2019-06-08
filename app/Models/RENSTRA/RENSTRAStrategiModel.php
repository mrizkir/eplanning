<?php

namespace App\Models\RENSTRA;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RENSTRAStrategiModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPrioritasStrategiKab';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrioritasStrategiKabID', 'PrioritasSasaranKabID', 'OrgID', 'Kd_Strategi', 'Nm_Strategi', 'Descr', 'TA','PrioritasStrategiKabID_Src'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'PrioritasStrategiKabID';
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
    protected static $logName = 'RENSTRAStrategiController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['PrioritasStrategiKabID', 'PrioritasSasaranKabID', 'Kd_Strategi', 'Nm_Strategi'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public static function getRPJDMStrategi ($ta,$prepend=true) 
    {
        $r=RENSTRAStrategiModel::join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                            ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                            ->where('tmPrioritasStrategiKab.TA',$ta)
                            ->orderBy('Kd_PrioritasKab')
                            ->orderBy('Kd_Tujuan')
                            ->orderBy('Kd_Sasaran')
                            ->orderBy('Kd_Strategi')->get();
        $strategi_rpjmd=($prepend==true)?['none'=>'DAFTAR RENSTRA STRATEGI']:[];        
        foreach ($r as $k=>$v)
        {
            $strategi_rpjmd[$v->PrioritasStrategiKabID]=$v->Kd_PrioritasKab.'.'.
                                                        $v->Kd_Tujuan.'.'.
                                                        $v->Kd_Sasaran.'.'.
                                                        $v->Kd_Strategi.'.'.
                                                        $v->Nm_Strategi;
        } 
        return $strategi_rpjmd;
    }
}
