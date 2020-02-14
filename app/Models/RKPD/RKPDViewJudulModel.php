<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDViewJudulModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'v_rkpd';    
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
    public $timestamps = false;
}
