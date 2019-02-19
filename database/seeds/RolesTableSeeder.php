<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Helpers\SQL;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SQL::truncate('roles');
                
        \DB::table('roles')->insert([
            [
                'name'=>'superadmin',
                'guard_name'=>'web',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],            
        ]);
    }
}
