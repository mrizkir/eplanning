<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemilikpokokpikiranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPemilikPokok', function (Blueprint $table) {
            $table->string('PemilikPokokID',19);
            $table->string('Kd_PK',16);
            $table->string('NmPk');     
            $table->decimal('Jumlah1',15,2);
            $table->decimal('Jumlah2',15,2);        
            $table->string('Descr')->nullable();   
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('PemilikPokokID');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmPemilikPokok');
    }
}
