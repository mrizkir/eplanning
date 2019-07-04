<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardModel extends Model 
{  
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'trRekapPaguIndikatifOPD';   
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
}
