<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Helpers\SQL;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        SQL::truncate('users');

        $user=User::create([
            'username'=>'admin',
            'password'=>Hash::make('1234'),                
            'name'=>'rizki',                
            'email'=>'support@yacanet.com',                
            'theme'=>'limitless',
            'email_verified_at'=>Carbon::now(),
            'isdeleted'=>'false',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);  
        
        $user->assignRole('superadmin');
    }
}
