<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBansosHibahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trBansosHibah', function (Blueprint $table) {
            $table->string('BansosHibahID',19);
            $table->string('PemilikBansosHibahID',19)->nullable();
            $table->string('PmKecamatanID',19)->nullable();
            $table->string('PmDesaID',19)->nullable();
            $table->string('SumberDanaID',19)->nullable();

            $table->string('NamaUsulanKegiatan');
            $table->string('Lokasi')->nullable();
            $table->decimal('Sasaran_Angka',15,2)->nullable();
            $table->string('Sasaran_Uraian')->nullable();

            $table->decimal('NilaiUsulan',15,2)->nullable();
            $table->string('Output')->nullable();
            $table->tinyInteger('Prioritas');     
            $table->tinyInteger('Privilege')->default(0);   
            $table->tinyInteger('Jeniskeg')->default(1);
            $table->string('Descr')->nullable();            
            $table->year('TA');

            $table->primary('BansosHibahID');
            
            $table->timestamps();            
            
            $table->index('PemilikBansosHibahID');
            $table->index('PmKecamatanID');
            $table->index('PmDesaID');
            $table->index('SumberDanaID');

            $table->foreign('PemilikBansosHibahID')
                    ->references('PemilikBansosHibahID')
                    ->on('tmPemilikBansosHibah')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                    
           	$table->foreign('PmDesaID')
                    ->references('PmDesaID')
                    ->on('tmPmDesa')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('PmKecamatanID')
                    ->references('PmKecamatanID')
                    ->on('tmPmKecamatan')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('SumberDanaID')
                    ->references('SumberDanaID')
                    ->on('tmSumberDana')
                    ->onDelete('set null')
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
        Schema::dropIfExists('trBansosHibah');
    }
}
