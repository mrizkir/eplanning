<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFungsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmFungsi', function (Blueprint $table) {
            $table->string('FungsiID',19);
            $table->tinyInteger('Kd_Fungsi');
            $table->string('Nm_Fungsi',100);           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('FungsiID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            $table->primary('FungsiID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmFungsi');
    }
}
