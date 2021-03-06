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
                $table->string('UsulanDesaID',19)->nullable();
                $table->string('PmKecamatanID',19);
                $table->string('PmDesaID',19)->nullable();
                $table->string('PrioritasSasaranKabID',19)->nullable();
                $table->string('PrgID',19)->nullable();
                $table->string('OrgID',19)->nullable();
                $table->string('SumberDanaID',19)->nullable();

                $table->tinyInteger('No_usulan');
                $table->text('NamaKegiatan');           
                $table->string('Output');           
                $table->string('Lokasi');        
                $table->decimal('NilaiUsulan',15,2);
                $table->integer('Target_Angka');                
                $table->string('Target_Uraian'); 
                $table->tinyInteger('Jeniskeg')->default(0);               
                $table->tinyInteger('Prioritas');   
                $table->double('Bobot',3,2)->default(0.00);
                $table->text('Descr')->nullable();            
                $table->year('TA');
                $table->tinyInteger('Privilege')->default(0);
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
                        ->onDelete('cascade')
                        ->onUpdate('cascade');


                $table->foreign('PrioritasSasaranKabID')
                        ->references('PrioritasSasaranKabID')
                        ->on('tmPrioritasSasaranKab')
                        ->onDelete('set null')
                        ->onUpdate('cascade');

                $table->foreign('PrgID')
                        ->references('PrgID')
                        ->on('tmPrg')
                        ->onDelete('set null')
                        ->onUpdate('cascade');

                $table->foreign('OrgID')
                        ->references('OrgID')
                        ->on('tmOrg')
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
        Schema::dropIfExists('trUsulanKec');
    }
}
