<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusrenkecamatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trUsulanKec', function (Blueprint $table) {
                $table->string('UsulanKecID',19);
                $table->string('UsulanDesaID',19);
                $table->string('PmKecamatanID',19);
                $table->string('PmDesaID',19);
                $table->string('PrioritasSasaranKabID',19);
                $table->string('PrgID',19);
                $table->string('OrgID',19);
                $table->string('SumberDanaID',19);

                $table->tinyInteger('No_usulan');
                $table->string('NamaKegiatan');           
                $table->string('Output');           
                $table->string('Lokasi');        
                $table->decimal('NilaiUsulan',15,2);
                $table->decimal('Target_Angka',15,2);
                $table->tinyInteger('Jeniskeg');            
                $table->string('Target_Uraian');    
                $table->tinyInteger('Prioritas');   
                $table->string('Descr')->nullable();            
                $table->year('TA');
                $table->boolean('Locked')->default(0);

                $table->timestamps();

                $table->primary('UsulanKecID');
                
                $table->index('UsulanDesaID');
                $table->index('PmKecamatanID');
                $table->index('PmDesaID');
                $table->index('PrioritasSasaranKabID');
                $table->index('PrgID');
                $table->index('OrgID');
                $table->index('SumberDanaID');

                $table->foreign('UsulanDesaID')
                    ->references('UsulanDesaID')
                    ->on('trUsulanDesa')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('PmDesaID')
                    ->references('PmDesaID')
                    ->on('tmPmDesa')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('PmKecamatanID')
                        ->references('PmKecamatanID')
                        ->on('tmPmKecamatan')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');


                $table->foreign('PrioritasSasaranKabID')
                        ->references('PrioritasSasaranKabID')
                        ->on('tmPrioritasSasaranKab')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('PrgID')
                        ->references('PrgID')
                        ->on('tmPrg')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('OrgID')
                        ->references('OrgID')
                        ->on('tmOrg')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                $table->foreign('SumberDanaID')
                        ->references('SumberDanaID')
                        ->on('tmSumberDana')
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
        Schema::dropIfExists('trUsulanKec');
    }
}
