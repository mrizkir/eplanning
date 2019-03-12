<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKecamatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPmKecamatan', function (Blueprint $table) {
            $table->string('PmKecamatanID',19);
            $table->string('PmKotaID',19);
            $table->tinyInteger('Kd_Kecamatan');
            $table->string('Nm_Kecamatan',100);            
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PmKecamatanID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            
            $table->timestamps();

            $table->primary('PmKecamatanID');
            $table->index('PmKotaID');

            $table->foreign('PmKotaID')
                ->references('PmKotaID')
                ->on('tmPmKota')
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
        Schema::dropIfExists('kecamatan');
    }
}
