<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRKPD';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RKPDID', 
        'RenjaID',
        'OrgID',
        'SOrgID',
        'KgtID',
        'SumberDanaID',
        'NamaIndikator',
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
        'Sasaran_AngkaSetelah',
        'Sasaran_UraianSetelah',
        'NilaiSetelah',
        'NilaiSebelum',
        'Tgl_Posting',
        'Descr',
        'TA',
        'Status',
        'Status_Indikator',
        'EntryLvl',
        'Privilege',
        'RKPDID_Src'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'RKPDID';
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
    protected static $logAttributes = ['RKPDID', 'KgtID', 'RKPDID_Src'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];

    public static function getDaftarKegiatanRKPD ($OrgID,$ta,$EntryLvl)
    {
        $data = \DB::table('v_rkpd')
                    ->select(\DB::raw('"RKPDID",
                                        "KgtID",
                                        "OrgID",
                                        kode_kegiatan,
                                        "KgtNm",
                                        (SELECT COUNT("RKPDRincID") FROM "trRKPDRinc" B WHERE v_rkpd."RKPDID"=B."RKPDID") AS jumlah_rincian'
                    ))
                    ->where('OrgID',$OrgID)
                    ->where('TA',$ta)
                    ->where('EntryLvl',$EntryLvl)
                    ->get();
        
        $daftar_kegiatan = [''=>''];
        foreach ($data as $v)
        {
            $daftar_kegiatan[$v->RKPDID]='['.$v->kode_kegiatan.'] '.$v->KgtNm.' ('.$v->jumlah_rincian.') [RKPDID='.$v->RKPDID.']';
        }
        return $daftar_kegiatan;
    }
}
