<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RenjaRincianModel extends Model {
    use LogsActivity;
    /**
    * nama tabel model ini.
    *
    * @var string
    */
    protected $table = 'trRenjaRinc90';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
       'RenjaRincID', 
       'RenjaID',
       'UsulanKecID',
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
       'Sasaran_Uraian5',
       'Sasaran_Uraian6',
       'Sasaran_Angka1',
       'Sasaran_Angka2',
       'Sasaran_Angka3',
       'Sasaran_Angka4',
       'Sasaran_Angka5',
       'Sasaran_Angka6',
       'Target1',
       'Target2',    
       'Target3',    
       'Target4',    
       'Target5',    
       'Target6',   
       'Jumlah1', 
       'Jumlah2', 
       'Jumlah3', 
       'Jumlah4', 
       'Jumlah5', 
       'Jumlah6', 
       'isReses',
       'isReses_Uraian',
       'isSKPD',
       'Status',
       'EntryLvl',
       'Prioritas',
       'Descr',
       'TA',
       'RenjaRincID_Src'
   ];
    /**
    * primary key tabel ini.
    *
    * @var string
    */
    protected $primaryKey = 'RenjaRincID';
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
    protected static $logName = 'UsulanPraRenjaOPDController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RenjaRincID', 'Uraian', 'Jumlah1'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public function renja()
    {
        return $this->belongsTo('\App\Models\RKPD\RenjaModel','RenjaID');
    }
    /**
     * digunakan untuk mendapatkan total pagu indikatif berdasarkan status dan opd
     */
    public static function getTotalPaguIndikatifByStatusAndOPD ($tahun_perencanaan,$EntryLvl,string $OrgID=null)
    {
        $field = $EntryLvl+1;        
        $data=\DB::table('trRenjaRinc90')
                ->select(\DB::raw('"trRenjaRinc90"."Status",SUM("trRenjaRinc90"."Jumlah'.$field.'") AS "Jumlah"'))
                ->join('trRenja90','trRenjaRinc90.RenjaID','trRenja90.RenjaID')
                ->where('trRenjaRinc90.TA',$tahun_perencanaan)
                ->where('trRenja90.OrgID',$OrgID)
                ->where('trRenjaRinc90.EntryLvl',$EntryLvl)
                ->groupBy('trRenjaRinc90.Status')
                ->orderBy('trRenjaRinc90.Status')
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
        $field = $EntryLvl+1; 
        $data=\DB::table('trRenjaRinc90')
                ->select(\DB::raw('"trRenjaRinc90"."Status",SUM("trRenjaRinc90"."Jumlah'.$field.'") AS "Jumlah"'))
                ->join('trRenja90','trRenjaRinc90.RenjaID','trRenja90.RenjaID')
                ->where('trRenjaRinc90.TA',$tahun_perencanaan)
                ->where('trRenja90.SOrgID',$SOrgID)
                ->where('trRenjaRinc90.EntryLvl',$EntryLvl)
                ->groupBy('trRenjaRinc90.Status')
                ->orderBy('trRenjaRinc90.Status')
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