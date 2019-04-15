<?php

namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class ProgramKegiatanModel extends Model {
     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'tmKgt';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'KgtID', 'PrgID', 'Kd_Keg', 'KgtNm', 'Descr', 'TA'
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'KgtID';
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
    protected static $logName = 'ProgramKegiatanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['KgtID', 'PrgID', 'Kd_Keg', 'KgtNm'];
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

    public function program () 
    {
        return $this->belongsTo('App\Models\DMaster\ProgramModel','PrgID');
    }
}
