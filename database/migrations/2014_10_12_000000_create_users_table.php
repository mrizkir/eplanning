<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();  
            $table->string('OrgID',19)->nullable();  
            $table->string('OrgNm')->nullable();
            $table->string('SOrgID',19)->nullable();  
            $table->string('SOrgNm')->nullable();  
            $table->string('PemilikPokokID',19)->nullable();  
            $table->string('NmPk')->nullable();  
            $table->string('PmKecamatanID',19)->nullable();  
            $table->string('Nm_Kecamatan')->nullable(); 
            $table->string('PmDesaID',19)->nullable();  
            $table->string('Nm_Desa')->nullable(); 
            $table->string('theme')->default('default');
            $table->string('foto')->default('storage/images/users/no_photo.png');
            $table->boolean('active')->default(1);
            $table->boolean('isdeleted')->default(1);
            $table->string('token')->nullable();;
            $table->rememberToken();
            $table->timestamps();

            $table->index('OrgID');  
            $table->index('SOrgID');
            $table->index('PemilikPokokID');
            $table->index('PmKecamatanID');
            $table->index('PmDesaID');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
