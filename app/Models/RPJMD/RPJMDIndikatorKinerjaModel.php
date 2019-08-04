<?php

namespace App\Models\RPJMD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RPJMDIndikatorKinerjaModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trIndikatorKinerja';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'IndikatorKinerjaID',
        'PrioritasKebijakanKabID', 
        'ProgramKebijakanID', 
        'UrsID', 
        'PrgID', 
        'OrgID', 
        'NamaIndikator',
        'KondisiAwal',
        'TargetN1',
        'TargetN2',
        'TargetN3',
        'TargetN4',
        'TargetN5',
        'PaguDanaN1',
        'PaguDanaN2',
        'PaguDanaN3',
        'PaguDanaN4',
        'PaguDanaN5',
        'KondisiAkhirTarget',
        'KondisiAkhirPaguDana',
        'Satuan',
        'Operator',
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'IndikatorKinerjaID';
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
    protected static $logName = 'RPJMDIndikatorKinerjaController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['IndikatorKinerjaID', 'PrioritasKebijakanKabID', 'NamaIndikator'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public static function getDaftarIndikatorKinerja($UrsID,$PrgID=null,$OrgID=null,$prepend=true)
    {   
        $data = RPJMDIndikatorKinerjaModel::where('UrsID',$UrsID)
                                            ->where('TA_N',config('eplanning.rpjmd_tahun_mulai'));

        if ($PrgID != null)
        {
            $data = $data->where('PrgID',$PrgID);
        }
        if ($OrgID != null)
        {
            $data = $data->where('OrgID',$OrgID);
        }
        
        $daftar_indikator = $prepend==true ? $data->get()
                ->pluck('NamaIndikator','IndikatorKinerjaID')
                ->prepend('DAFTAR INDIKATOR KINERJA','none')
                ->toArray()
                :
                $data->get()
                ->pluck('NamaIndikator','IndikatorKinerjaID')
                ->toArray();
        
        return $daftar_indikator;
    }
    public static function getIndikatorKinerjaByID($IndikatorKinerjaID,$ta)
    {
        $data = RPJMDIndikatorKinerjaModel::find($IndikatorKinerjaID);
        $data_indikator=null;
        if (!is_null($data) )  
        {   
            $tahun_n=($ta-config('eplanning.rpjmd_tahun_mulai'))+1;
            $target_n="TargetN$tahun_n";
            $pagudana_n="PaguDanaN$tahun_n";
            $data_indikator=[
                'NamaIndikator'=>$data->NamaIndikator,
                'TargetAngka'=>$data[$target_n],
                'PaguDana'=>$data[$pagudana_n]
            ];
        }
        return $data_indikator;
    }
}
