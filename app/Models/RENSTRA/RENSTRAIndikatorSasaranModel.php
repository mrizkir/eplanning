<?php

namespace App\Models\RPJMD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RPJMDIndikatorSasaranModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trIndikatorSasaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'IndikatorSasaranID',
        'PrioritasKebijakanKabID', 
        'UrsID', 
        'PrgID', 
        'OrgID', 
        'OrgID2', 
        'OrgID3', 
        'NamaIndikator',
        'TA_N',
        'TargetN',
        'TargetN1',
        'TargetN2',
        'TargetN3',
        'TargetN4',
        'TargetN5',
        'PaguDanaN',
        'PaguDanaN1',
        'PaguDanaN2',
        'PaguDanaN3',
        'PaguDanaN4',
        'PaguDanaN5',
        'Descr',
        'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'IndikatorSasaranID';
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
    protected static $logName = 'RPJMDIndikatorSasaranController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['IndikatorSasaranID', 'PrioritasKebijakanKabID', 'NamaIndikator'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public static function getDaftarIndikatorSasaran($UrsID,$PrgID=null,$OrgID=null,$prepend=true)
    {   
        $data = RPJMDIndikatorSasaranModel::where('UrsID',$UrsID)
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
                ->pluck('NamaIndikator','IndikatorSasaranID')
                ->prepend('DAFTAR INDIKATOR KINERJA','none')
                ->toArray()
                :
                $data->get()
                ->pluck('NamaIndikator','IndikatorSasaranID')
                ->toArray();
        
        return $daftar_indikator;
    }
    public static function getIndikatorSasaranByID($IndikatorSasaranID,$ta)
    {
        $data = RPJMDIndikatorSasaranModel::find($IndikatorSasaranID);
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
