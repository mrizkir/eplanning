6<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvinsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPMPProv', function (Blueprint $table) {
            $table->string('PMProvID',19);
            $table->tinyInteger('Kd_Prov');
            $table->string('Nm_Prov',100);            
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PMProvID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            
            $table->timestamps();

            $table->primary('PMProvID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmPMPProv');
    }
}
