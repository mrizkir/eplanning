<?php

namespace App\Models\RENSTRA;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RENSTRAKebijakanModel extends Model {
    use LogsActivity;
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmRenstraKebijakan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RenstraKebijakanID', 'RenstraStrategiID', 'OrgID', 'Kd_RenstraKebijakan', 'Nm_RenstraKebijakan', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RenstraKebijakanID';
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
    protected static $logName = 'RENSTRAKebijakanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RenstraKebijakanID', 'Kd_RenstraKebijakan', 'Nm_RenstraKebijakan'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public static function getDaftarKebijakan($ta,$prepend=true)
    {
        $r=RENSTRAKebijakanModel::select(\DB::raw('
                                "PrioritasKebijakanKabID",
                                CONCAT(
                                \'[\',
                                "Kd_PrioritasKab",
                                \'.\',
                                "Kd_Tujuan",
                                \'.\',
                                "Kd_Sasaran",
                                \'.\',
                                "Kd_Strategi",
                                \'.\',
                                "Kd_Kebijakan",
                                \'] \',
                                "Nm_Kebijakan"
                                ) AS "Nm_Kebijakan"'))
                                ->join('tmPrioritasStrategiKab','tmPrioritasKebijakanKab.PrioritasStrategiKabID','tmPrioritasStrategiKab.PrioritasStrategiKabID')
                                ->join('tmPrioritasSasaranKab','tmPrioritasSasaranKab.PrioritasSasaranKabID','tmPrioritasStrategiKab.PrioritasSasaranKabID')
                                ->join('tmPrioritasTujuanKab','tmPrioritasTujuanKab.PrioritasTujuanKabID','tmPrioritasSasaranKab.PrioritasTujuanKabID')
                                ->join('tmPrioritasKab','tmPrioritasKab.PrioritasKabID','tmPrioritasTujuanKab.PrioritasKabID')
                                ->where('tmPrioritasStrategiKab.TA',$ta)
                                ->orderBy('Kd_PrioritasKab')
                                ->orderBy('Kd_Tujuan')
                                ->orderBy('Kd_Sasaran')
                                ->orderBy('Kd_Strategi')
                                ->get();

        $daftar_kebijakan=$prepend == true 
                                        ?
                                            $r->pluck('Nm_Kebijakan','PrioritasKebijakanKabID')
                                            ->prepend('DAFTAR KEBIJAKAN RENSTRA')
                                            ->toArray()
                                        :
                                        $r->pluck('Nm_Kebijakan','PrioritasKebijakanKabID')
                                            ->toArray();
       
        return $daftar_kebijakan;
    }
}
