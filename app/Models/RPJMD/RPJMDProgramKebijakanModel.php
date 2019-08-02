<?php

namespace App\Models\RPJMD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RPJMDProgramKebijakanModel extends Model {
    use LogsActivity;
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmPrioritasProgramKebijakan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ProgramKebijakanID','PrioritasKebijakanKabID', 'UrsID', 'PrgID', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'ProgramKebijakanID';
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
    protected static $logName = 'RPJMDKebijakanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['ProgramKebijakanID','PrioritasKebijakanKabID', 'UrsID', 'PrgID'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;   

    public static function getDaftarProgramKebijakan ($PrioritasKebijakanKabID,$prepend=true) 
    {
        $r = RPJMDProgramKebijakanModel::select(\DB::raw('"tmPrioritasProgramKebijakan"."ProgramKebijakanID",v_urusan_program.kode_program,v_urusan_program."PrgNm",v_urusan_program."Jns"'))
                                            ->join('v_urusan_program','v_urusan_program.PrgID','tmPrioritasProgramKebijakan.PrgID')
                                            ->where('PrioritasKebijakanKabID',$PrioritasKebijakanKabID)
                                            ->orderBy('kode_program')
                                            ->get();
        $daftar_program=($prepend==true)?['none'=>'DAFTAR PROGRAM']:[];        
        foreach ($r as $k=>$v)
        {
            if ($v->Jns)
            {
                $daftar_program[$v->ProgramKebijakanID]=$v->kode_program.'. '.$v->PrgNm;
            }
            else
            {
                $daftar_program[$v->ProgramKebijakanID]=$v->Kd_Prog.'. '.$v->PrgNm;
            }
            
        }
        return $daftar_program;
    }
}
