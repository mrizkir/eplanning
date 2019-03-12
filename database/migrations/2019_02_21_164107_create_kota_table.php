<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPMPKota', function (Blueprint $table) {
            $table->string('PMKotaID',19);
            $table->string('PMProvID',19);
            $table->tinyInteger('Kd_Kota');
            $table->string('Nm_Kota',100);            
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PMKotaID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            
            $table->timestamps();

            $table->primary('PMKotaID');
            $table->index('PMProvID');

            $table->foreign('PMProvID')
                ->references('PMProvID')
                ->on('tmPMPProv')
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
        Schema::dropIfExists('kota');
    }
}
