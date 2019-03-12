<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPmDesa', function (Blueprint $table) {
            $table->string('PmDesaID',19);
            $table->string('PmKecamatanID',19);
            $table->tinyInteger('Kd_Desa');
            $table->string('Nm_Desa',100);            
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PmDesaID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);
            
            $table->timestamps();

            $table->primary('PmDesaID');
            $table->index('PmKecamatanID');

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
        Schema::dropIfExists('tmPmDesa');
    }
}
