<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemilikbansoshibahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPemilikBansosHibah', function (Blueprint $table) {
            $table->string('PemilikBansosHibahID',19);
            $table->string('Kd_PK',16);
            $table->string('NmPk');

            $table->mediumInteger('Jumlah_Kegiatan1')->default(0);           
            $table->decimal('Jumlah1',15,2)->default(0);                

            $table->string('Descr')->nullable();   
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('PemilikBansosHibahID');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmPemilikBansosHibah');
    }
}
