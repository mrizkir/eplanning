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
        'Sasaran_Angka1',
        'Sasaran_Angka2',
        'NilaiUsulan1',
        'NilaiUsulan2',
        'Target1',
        'Target2',    
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
    public static function getTotalPaguIndikatifByStatusAndOPD ($tahun_perencanaan,$EntryLvl,string $OrgID=null)
    {
        switch($EntryLvl)
        {
            case 4 :
                $field=1;
            break;
            case 5 :
                $field=2;
            break;
        }              
        $data=\DB::table('trRKPDRinc')
                ->select(\DB::raw('"trRKPDRinc"."Status",SUM("trRKPDRinc"."NilaiUsulan'.$field.'") AS "Jumlah"'))
                ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')
                ->where('trRKPDRinc.TA',$tahun_perencanaan)
                ->where('trRKPD.OrgID',$OrgID)
                ->where('trRKPDRinc.EntryLvl',$EntryLvl)
                ->groupBy('trRKPDRinc.Status')
                ->orderBy('trRKPDRinc.Status')
                ->get()
                ->pluck('Jumlah','Status')
                ->toArray();
        $totalpagustatus = \HelperKegiatan::getStatusKegiatan();
        
        $totalpagustatus[0]=isset($data[0])?$data[0]:0;
        $totalpagustatus[1]=isset($data[1])?$data[1]:0;
        $totalpagustatus[2]=isset($data[2])?$data[2]:0;
        $totalpagustatus[3]=isset($data[3])?$data[3]:0;
        $totalpagustatus['total']=$totalpagustatus[0]+$totalpagustatus[1]+$totalpagustatus[2]+$totalpagustatus[3];        
        return $totalpagustatus;
    }
    /**
     * digunakan untuk mendapatkan total pagu indikatif berdasarkan status dan opd
     */
    public static function getTotalPaguIndikatifByStatusAndUnitKerja ($tahun_perencanaan,$EntryLvl,string $SOrgID=null)
    {
        switch($EntryLvl)
        {
            case 4 :
                $field=1;
            break;
            case 5 :
                $field=2;
            break;
        }        
        $data=\DB::table('trRKPDRinc')
                ->select(\DB::raw('"trRKPDRinc"."Status",SUM("trRKPDRinc"."NilaiUsulan'.$field.'") AS "Jumlah"'))
                ->join('trRKPD','trRKPDRinc.RKPDID','trRKPD.RKPDID')
                ->where('trRKPDRinc.TA',$tahun_perencanaan)
                ->where('trRKPD.SOrgID',$SOrgID)
                ->where('trRKPDRinc.EntryLvl',$EntryLvl)
                ->groupBy('trRKPDRinc.Status')
                ->orderBy('trRKPDRinc.Status')
                ->get()
                ->pluck('Jumlah','Status')
                ->toArray();
        $totalpagustatus = \HelperKegiatan::getStatusKegiatan();
        
        $totalpagustatus[0]=isset($data[0])?$data[0]:0;
        $totalpagustatus[1]=isset($data[1])?$data[1]:0;
        $totalpagustatus[2]=isset($data[2])?$data[2]:0;
        $totalpagustatus[3]=isset($data[3])?$data[3]:0;
        $totalpagustatus['total']=$totalpagustatus[0]+$totalpagustatus[1]+$totalpagustatus[2]+$totalpagustatus[3];       
                
        return $totalpagustatus;
    }
}
