<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserskecamatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userskecamatan', function (Blueprint $table) {
            $table->increments('userkecamatan');            
            $table->integer('id');            
            $table->year('ta');          
            $table->string('PmKecamatanID',19);  
            $table->string('Kd_Kecamatan',16);
            $table->string('Nm_Kecamatan');
            $table->boolean('locked')->default(0); 
            $table->timestamps();
            
            $table->index('id');
            $table->index('PmKecamatanID');  

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('PmKecamatanID')
                    ->references('PmKecamatanID')
                    ->on('tmPmKecamatan')
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
        Schema::dropIfExists('userskecamatan');
    }
}
