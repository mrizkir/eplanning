<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusrendesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trUsulanDesa', function (Blueprint $table) {
            $table->string('UsulanDesaID',19);
            $table->string('PmDesaID',19);
            $table->string('SumberDanaID',19);

            $table->integer('No_usulan');
            $table->string('NamaKegiatan');           
            $table->string('Output');           
            $table->string('Lokasi');     
            $table->decimal('NilaiUsulan',15,2);
            $table->integer('Target_Angka');                    
            $table->string('Target_Uraian');    
            $table->tinyInteger('Jeniskeg')->default(0);    
            $table->tinyInteger('Prioritas');   
            $table->string('Descr')->nullable();            
            $table->year('TA');
            $table->boolean('Locked')->default(0);

            $table->timestamps();

            $table->primary('UsulanDesaID');
            $table->index('PmDesaID');
            $table->index('SumberDanaID');

            $table->foreign('PmDesaID')
                ->references('PmDesaID')
                ->on('tmPmDesa')
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
        Schema::dropIfExists('trUsulanDesa');
    }
}
