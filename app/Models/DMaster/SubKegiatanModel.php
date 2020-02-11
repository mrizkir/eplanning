<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubKegiatanModel extends Model {
    use LogsActivity;
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmSubKgt';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SubKgtID', 'KgtID', 'Kd_SubKeg', 'SubKgtNm', 'Descr', 'TA', 'SubKgtID_Src'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'SubKgtID';
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
    protected static $logName = 'SubKegiatanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['SubKgtID', 'KgtID', 'Kd_SubKeg', 'SubKgtNm'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    public static function getDaftarKegiatan ($ta,$prepend=true,$PrgID=null)
    {
        $r=\DB::table('v_program_kegiatan')
                ->where('TA',$ta)
                ->orderBy('Kd_Keg')
                ->orderBy('kode_kegiatan');

        $r = $PrgID == null ? $r->get():  $r->where('PrgID',$PrgID)->get();                 
        
        $daftar_kegiatan=($prepend==true)?['none'=>'DAFTAR KEGIATAN']:[];        
        foreach ($r as $k=>$v)
        {
            if ($v->Jns)
            {
                $daftar_kegiatan[$v->KgtID]=$v->kode_kegiatan.'. '.$v->KgtNm;
            }
            else
            {
                $daftar_kegiatan[$v->KgtID]=$v->kode_kegiatan.'. '.$v->KgtNm;
            }
            
        }
        return $daftar_kegiatan;
    }

    public function kegiatan () 
    {
        return $this->belongsTo('App\Models\DMaster\ProgramKegiatanModel','KgtID');
    }
}
