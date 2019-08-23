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
            'RpjmdVisiID'=> 'uid5d45a8c234042',
            'Nm_RpjmdVisi'=>'Terwujudnya Kabupaten Bintan yang Madani dan Sejahtera Melalui Pencapaian Bintan Gemilang 2025 (Gerakan Melangkah Maju di Bidang Kelautan, Pariwisata, dan Kebudayaan',
            'Descr'=>'PERDA KAB. BINTAN NOMOR 5 TAHUN 2016',
            'TA_Awal'=>2015,
            'Descr'=>'default'
        ]);        
    }
}
