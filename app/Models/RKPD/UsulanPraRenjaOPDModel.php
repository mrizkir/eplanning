<?php

namespace App\Models\RKPD;

use Illuminate\Database\Eloquent\Model;

class UsulanPraRenjaOPDModel extends Model {
    /**
     * nama tabel model ini.
     *
     * @var string
     */
    protected $table = 'v_usulan_pra_renja_opd';   

    /**
    * primary key tabel ini.
    *
    * @var string
    */
    protected $primaryKey = 'RenjaID';
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
    
    /**
     * digunakan untuk mendapatkan rencana kerja berdasarkan ID
     */
    public static function findRenja($RenjaID)
    {
        return RenjaModel::find($RenjaID);
    }
    /**
     * digunakan untuk mendapatkan rencana kerja berdasarkan ID
     */
    public static function findOrFailRenja($RenjaID)
    {
        return RenjaModel::findOrFail($RenjaID);
    }
    public static function create (array $attributes=[]) 
    {
        $model=RenjaModel::create($attributes);
        return $model;
    }
    
    //Renja Indikator
    
    public static function createrenjaindikator (array $attributes=[]) 
    {
        $model = RenjaIndikatorModel::create($attributes);
        return $model;
    }

    //Renja Rincian
    public static function createrenjarinc (array $attributes=[]) 
    {
        $model = RenjaRincianModel::create($attributes);
        return $model;
    }
    public static function destroy ($uuid) 
    {
        $model = RenjaMdel::destroy($uuid);                
        return $model;
    }
}