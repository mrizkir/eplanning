<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePokokpikiranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trPokPir', function (Blueprint $table) {
            $table->string('PokPirID',19);
            $table->string('PemilikPokokID',19);
            $table->string('OrgID',19)->nullable();
            $table->string('SOrgID',19)->nullable();
            $table->string('PmKecamatanID',19)->nullable();
            $table->string('PmDesaID',19)->nullable();
            $table->string('SumberDanaID',19)->nullable();

            $table->string('NamaUsulanKegiatan');
            $table->string('Lokasi')->nullable();
            $table->decimal('Sasaran_Angka',15,2)->nullable();
            $table->string('Sasaran_Uraian')->nullable();

            $table->decimal('NilaiUsulan',15,2)->nullable();
            $table->tinyInteger('Status');
            $table->tinyInteger('EntryLvl')->nullable();
            $table->string('Output')->nullable();
            $table->tinyInteger('Jeniskeg')->nullable();
            $table->tinyInteger('Prioritas');
            $table->double('Bobot',3,2)->default(0.00);
            $table->string('Descr')->nullable();            
            $table->year('TA');

            $table->boolean('Locked')->default(0);
            $table->tinyInteger('Privilege')->default(0);        
            $table->primary('PokPirID');
            
            $table->timestamps();
            
            $table->index('PemilikPokokID');            
            $table->index('OrgID');
            
            $table->index('SOrgID');
            $table->index('PmKecamatanID');
            $table->index('PmDesaID');
            $table->index('SumberDanaID');

            $table->foreign('PemilikPokokID')
                    ->references('PemilikPokokID')
                    ->on('tmPemilikPokok')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('OrgID')
                    ->references('OrgID')
                    ->on('tmOrg')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('SOrgID')
                    ->references('SOrgID')
                    ->on('tmSOrg')
                    ->onDelete('set null')
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
        Schema::dropIfExists('trPokPir');
    }
}
