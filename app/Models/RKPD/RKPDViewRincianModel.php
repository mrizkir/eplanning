<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RKPDViewRincianModel extends Model {
    use LogsActivity;

     /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'v_rkpd_rinci';    
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
    public $timestamps = false;
}
