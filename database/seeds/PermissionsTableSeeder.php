<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Helpers\SQL;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SQL::truncate('permissions');
                
        \DB::table('permissions')->insert([
            [
                'name'=>'browse_asn',
                'guard_name'=>'web',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],    
            [
                'name'=>'read_asn',
                'guard_name'=>'web',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],    
            [
                'name'=>'add_asn',
                'guard_name'=>'web',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],    
            [
                'name'=>'edit_asn',
                'guard_name'=>'web',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],    
            [
                'name'=>'delete_asn',
                'guard_name'=>'web',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],            
        ]);
    }
}
