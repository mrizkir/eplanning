<?php

namespace App\Models\RENSTRA;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RENSTRAProgramKegiatanPendanaanModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRenstraProgramKegiatanPendanaan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RenstraIndikatorID',
        'RenstraKebijakanID', 
        'IndikatorKinerjaID', 
        'UrsID', 
        'PrgID', 
        'OrgIDRPJMD',         
        'NamaIndikator',
        'Descr',        
        'TA',        
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RenstraIndikatorID';
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
    protected static $logName = 'RENSTRAIndikatorSasaranController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RenstraIndikatorID', 'RenstraKebijakanID', 'NamaIndikator'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public static function getDaftarIndikatorSasaran($UrsID,$PrgID=null,$OrgIDRPJMD=null,$prepend=true)
    {   
        $data = RENSTRAIndikatorSasaranModel::where('UrsID',$UrsID)
                                            ->where('TA',config('eplanning.renstra_tahun_mulai'));

        if ($PrgID != null)
        {
            $data = $data->where('PrgID',$PrgID);
        }
        if ($OrgIDRPJMD != null)
        {
            $data = $data->where('OrgIDRPJMD',$OrgIDRPJMD);
        }
        
        $daftar_indikator = $prepend==true ? $data->get()
                ->pluck('NamaIndikator','RenstraIndikatorID')
                ->prepend('DAFTAR INDIKATOR SASARAN','none')
                ->toArray()
                :
                $data->get()
                ->pluck('NamaIndikator','RenstraIndikatorID')
                ->toArray();
        
        return $daftar_indikator;
    }
    public static function getIndikatorSasaranByID($RenstraIndikatorID,$ta)
    {
        $data = RENSTRAIndikatorSasaranModel::find($RenstraIndikatorID);
        $data_indikator=null;
        if (!is_null($data) )  
        {   
            $tahun_n=($ta-config('eplanning.renstra_tahun_mulai'))+1;
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
