<?php

namespace App\Models\Report;

use Spatie\Activitylog\Traits\LogsActivity;

class RekapPaguIndikatifOPDModel extends ReportModel {
    use LogsActivity;
    public function __construct()
    {
        
    }
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRekapPaguIndikatifOPD';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'OrgID', 
        'Kode_Organisasi', 
        'OrgNm', 
        'Jumlah1', 
        'Jumlah2', 
        'prarenja1', 
        'jumlah_program1', 
        'jumlah_kegiatan1', 
        'rakorbidang1', 
        'jumlah_program2', 
        'jumlah_kegiatan2', 
        'forumopd1', 
        'jumlah_program3', 
        'jumlah_kegiatan3', 
        'musrenkab1', 
        'jumlah_program4', 
        'jumlah_kegiatan4', 
        'renjafinal1', 
        'jumlah_program5', 
        'jumlah_kegiatan5', 
        'renjafinal1', 
        'jumlah_program5', 
        'jumlah_kegiatan5', 
        'rkpd1', 
        'jumlah_program6', 
        'jumlah_kegiatan6', 
        'rkpd2', 
        'jumlah_program7', 
        'jumlah_kegiatan7', 
        'Descr', 
        'TA', 
        
    ];
    /**
     * primary key tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'OrgID';
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
    protected static $logName = 'RekapPaguIndikatorOPDController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['OrgID', 'Kode_Organisasi', 'OrgNm'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    // protected static $logFillable = true;

    //only the `deleted` event will get logged automatically
    // protected static $recordEvents = ['deleted'];
    
}
