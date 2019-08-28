<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenstraindikatorsasaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmRenstraIndikatorSasaran', function (Blueprint $table) {
            $table->string('RenstraIndikatorSasaranID',19);
            $table->string('RenstraSasaranID',19);            
            $table->text('NamaIndikator');
            $table->string('KondisiAwal');
            $table->string('N1');
            $table->string('N2');
            $table->string('N3');
            $table->string('N4');
            $table->string('N5');
            $table->string('KondisiAkhir');
            $table->string('Satuan');           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('RenstraIndikatorSasaranID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('RenstraIndikatorSasaranID');

            $table->index('RenstraSasaranID');

            $table->foreign('RenstraSasaranID')
                    ->references('RenstraSasaranID')
                    ->on('tmRenstraSasaran')
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
        Schema::dropIfExists('tmRenstraIndikatorSasaran');
    }
}
