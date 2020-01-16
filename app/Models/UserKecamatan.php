<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserKecamatan extends Model
{
    use LogsActivity;
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'userskecamatan';   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userkecamatan', 'id', 'ta','PmKecamatanID','Kd_Kecamatan','Nm_Kecamatan'
    ];
    /**
    * primary key tabel ini.
    *
    * @var string
    */
    protected $primaryKey = 'userkecamatan';
    /**
    * enable auto_increment.
    *
    * @var string
    */
    public $incrementing = true;
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
    protected static $logName = 'setting\UsersKecamatanController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = [
        'userkecamatan', 'id', 'ta','PmKecamatanID','Kd_Kecamatan','Nm_Kecamatan'
    ];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];     
    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];     
    public static function getKecamatan($listlocked=true,$ignorelocked=false)
    {        
        if ($ignorelocked == true)
        {
            $daftar_kecamatan=\App\Models\UserKecamatan::where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                ->where('id',\Auth::user()->id)                                                
                                                ->pluck('Nm_Kecamatan','PmKecamatanID');      
        }
        else
        {
            $daftar_kecamatan=\App\Models\UserKecamatan::where('ta',\HelperKegiatan::getTahunPerencanaan())
                                                ->where('id',\Auth::user()->id)
                                                ->where('locked',!$listlocked)
                                                ->pluck('Nm_Kecamatan','PmKecamatanID');      
        }
        return $daftar_kecamatan;
    }
}
