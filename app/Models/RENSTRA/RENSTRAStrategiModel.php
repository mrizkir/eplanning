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
    protected $table = 'tmRenstraStrategi';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RenstraStrategiID', 'RenstraSasaranID', 'OrgIDRPJMD', 'Kd_RenstraStrategi', 'Nm_RenstraStrategi', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RenstraStrategiID';
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
    protected static $logAttributes = ['RenstraStrategiID', 'RenstraSasaranID', 'Kd_RenstraStrategi', 'Nm_RenstraStrategi'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public static function getRPJDMStrategi ($ta,$prepend=true) 
    {
        $r=RENSTRAStrategiModel::join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.RenstraSasaranID','tmRenstraStrategi.RenstraSasaranID')
                            ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                            ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                            ->where('tmRenstraStrategi.TA',$ta)
                            ->orderBy('Kd_PrioritasKab')
                            ->orderBy('Kd_Tujuan')
                            ->orderBy('Kd_Sasaran')
                            ->orderBy('Kd_RenstraStrategi')->get();
        $strategi_rpjmd=($prepend==true)?['none'=>'DAFTAR RENSTRA STRATEGI']:[];        
        foreach ($r as $k=>$v)
        {
            $strategi_rpjmd[$v->RenstraStrategiID]=$v->Kd_PrioritasKab.'.'.
                                                        $v->Kd_Tujuan.'.'.
                                                        $v->Kd_Sasaran.'.'.
                                                        $v->Kd_RenstraStrategi.'.'.
                                                        $v->Nm_RenstraStrategi;
        } 
        return $strategi_rpjmd;
    }
}
