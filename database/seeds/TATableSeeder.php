<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Helpers\SQL;
use App\Models\DMaster\TAModel;

class TATableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        SQL::truncate('"tmTA"');

        $ta = TAModel::create ([
            'TAID'=> uniqid ('uid'),
            'TACd'=>date('Y'),
            'TANm'=>date('Y'),
            'Descr'=>'Tahun Anggaran dan Perencanaan '.date('Y')
        ]);        
    }
}
