<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class SubOrganisasiModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmSOrg';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SOrgID', 'OrgID', 'SOrgCd', 'SOrgNm', 'Alamat', 'NamaKepalaSKPD', 'NIPKepalaSKPD', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'SOrgID';
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
    protected static $logName = 'SubOrganisasiController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['SOrgID', 'OrgID', 'SOrgNm'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    /**
     * digunakan untuk mendapatkan kode urusan
     */
    public static function getDaftarOPD ($ta,$prepend=true) 
    {
        $r=\DB::table('v_suborganisasi')
                ->where('TA',$ta)
                ->orderBy('kode_suborganisasi')->get();
        
        $daftar_organisasi=($prepend==true)?['none'=>'DAFTAR OPD / SKPD']:[];        
        foreach ($r as $k=>$v)
        {
            $daftar_organisasi[$v->SOrgID]=$v->kode_suborganisasi.'. '.$v->SOrgNm;
        } 
        return $daftar_organisasi;
    }
    /**
     * digunakan untuk mendapatkan kode urusan
     */
    public static function getNamaOPDByID ($SOrgID) 
    {
        $r = \DB::table('v_suborganisasi')->where('SOrgID',$SOrgID)->pluck('SOrgNm')->toArray();
        if (isset($r[0]))
        {
            return $r[0];
        }
        else
        {
            return null;
        }
    }
}
