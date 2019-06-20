<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersdewanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersdewan', function (Blueprint $table) {
            $table->increments('userdewan');            
            $table->integer('id');            
            $table->year('ta');          
            $table->string('PemilikPokokID',19);  
            $table->string('Kd_PK',16);
            $table->string('NmPk');
            $table->boolean('locked')->default(0); 
            $table->timestamps();
            
            $table->index('id');
            $table->index('PemilikPokokID');  

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('PemilikPokokID')
                    ->references('PemilikPokokID')
                    ->on('tmPemilikPokok')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');            
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usersdewan');
    }
}
