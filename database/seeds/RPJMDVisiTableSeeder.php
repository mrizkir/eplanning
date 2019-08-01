<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Helpers\SQL;
use App\Models\RPJMD\RPJMDVisiModel;

class RPJMDVisiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        SQL::truncate('"tmRpjmdVisi"');

        $ta = RPJMDVisiModel::create ([
            'RpjmdVisiID'=> uniqid ('uid'),
            'Nm_RpjmdVisi'=>'default',
            'Descr'=>'default',
            'TA_Awal'=>date('Y'),
            'Descr'=>'default'
        ]);        
    }
}
