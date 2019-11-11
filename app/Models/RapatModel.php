<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RapatModel extends Model
{
    use LogsActivity;
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'rapat';   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'RapatID', 'Judul', 'Isi','anggota','Tanggal_Rapat','TA'
    ];
    /**
    * primary key tabel ini.
    *
    * @var string
    */
    protected $primaryKey = 'RapatID';
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
    protected static $logName = 'RapatController';
    /**
     * log the changed attributes for all these events 
     */
    protected static $logAttributes = ['RapatID', 'Judul', 'Tanggal_Rapat'];
    /**
     * log changes to all the $fillable attributes of the model
     */
    protected static $logFillable = true;
}   
