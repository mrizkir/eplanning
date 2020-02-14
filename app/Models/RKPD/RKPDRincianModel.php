<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDRincianModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRKPDRinc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'RKPDRincID', 
        'RKPDID',
        'RenjaRincID',
        'PMProvID',
        'PmKotaID',
        'PmKecamatanID',
        'PmDesaID',
        'PokPirID',
        'Uraian',
        'No',
        'Sasaran_Uraian1',
        'Sasaran_Uraian2',
        'Sasaran_Uraian3',
        'Sasaran_Uraian4',
        'Sasaran_Angka1',
        'Sasaran_Angka2',
        'Sasaran_Angka3',
        'Sasaran_Angka4',
        'NilaiUsulan1',
        'NilaiUsulan2',
        'NilaiUsulan3',
        'NilaiUsulan4',
        'Target1',
        'Target2',    
        'Target3',    
        'Target4',    
        'Tgl_Posting', 
        'isReses',
        'isReses_Uraian',
        'isSKPD',
        'Descr',
        'TA',
        'Status',
        'EntryLvl',
        'Privilege',        
        'RKPDRincID_Src'        
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RKPDRincID';
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
    protected static $logName = 'RKPDMurniController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RKPDID', 'RKPDRincID', 'RKPDRincID_Src'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public function rkpd()
    {
        return $this->belongsTo('\App\Models\RKPD\RKPDModel','RKPDID');
    }

    /**
     * digunakan untuk mendapatkan total pagu indikatif berdasarkan status dan opd
     */
    public static function getTotalPaguByOPD ($tahun_perencanaan,$EntryLvl,string $OrgID=null)
    {             
        $data=\DB::table('trRKPDRinc')
                ->select(\DB::raw('SUM("trRKPDRinc"."NilaiUsulan1") AS "NilaiUsulan1",SUM("trRKPDRinc"."NilaiUsulan2") AS "NilaiUsulan2",SUM("trRKPDRinc"."NilaiUsulan3") AS "NilaiUsulan3",SUM("trRKPDRinc"."NilaiUsulan4") AS "NilaiUsulan4"'))
                ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')
                ->where('trRKPDRinc.TA',$tahun_perencanaan)
                ->where('trRKPD.OrgID',$OrgID)
                ->where('trRKPD.EntryLvl',$EntryLvl)
                ->get()                
                ->toArray();
        
        $totalpagu['murni']=is_null($data[0]->NilaiUsulan1)?0:$data[0]->NilaiUsulan1;        
        $totalpagu['pembahasanm']=is_null($data[0]->NilaiUsulan2)?0:$data[0]->NilaiUsulan2;  
        $totalpagu['selisihm']=$totalpagu['pembahasanm']-$totalpagu['murni'];
        $totalpagu['perubahan']=is_null($data[0]->NilaiUsulan3)?0:$data[0]->NilaiUsulan3;  
        $totalpagu['selisihpm']=$totalpagu['perubahan']-$totalpagu['pembahasanm'];
        $totalpagu['pembahasanp']=is_null($data[0]->NilaiUsulan4)?0:$data[0]->NilaiUsulan4;  
        $totalpagu['selisihpp']=$totalpagu['pembahasanp']-$totalpagu['perubahan'];

        return $totalpagu;
    }
    /**
     * digunakan untuk mendapatkan total pagu indikatif berdasarkan status dan opd
     */
    public static function getTotalPaguByUnitKerja ($tahun_perencanaan,$EntryLvl,string $SOrgID=null)
    {
        $data=\DB::table('trRKPDRinc')
                ->select(\DB::raw('SUM("trRKPDRinc"."NilaiUsulan1") AS "NilaiUsulan1",SUM("trRKPDRinc"."NilaiUsulan2") AS "NilaiUsulan2",SUM("trRKPDRinc"."NilaiUsulan3") AS "NilaiUsulan3",SUM("trRKPDRinc"."NilaiUsulan4") AS "NilaiUsulan4"'))
                ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')
                ->where('trRKPDRinc.TA',$tahun_perencanaan)
                ->where('trRKPD.EntryLvl',$EntryLvl)
                ->where('trRKPD.SOrgID',$SOrgID)
                ->get()                
                ->toArray();
        
        $totalpagu['murni']=is_null($data[0]->NilaiUsulan1)?0:$data[0]->NilaiUsulan1;        
        $totalpagu['pembahasanm']=is_null($data[0]->NilaiUsulan2)?0:$data[0]->NilaiUsulan2;  
        $totalpagu['selisihm']=$totalpagu['pembahasanm']-$totalpagu['murni'];
        $totalpagu['perubahan']=is_null($data[0]->NilaiUsulan3)?0:$data[0]->NilaiUsulan3;  
        $totalpagu['selisihpm']=$totalpagu['perubahan']-$totalpagu['pembahasanm'];
        $totalpagu['pembahasanp']=is_null($data[0]->NilaiUsulan4)?0:$data[0]->NilaiUsulan4;  
        $totalpagu['selisihpp']=$totalpagu['pembahasanp']-$totalpagu['perubahan'];
          
        return $totalpagu;
    }
}
