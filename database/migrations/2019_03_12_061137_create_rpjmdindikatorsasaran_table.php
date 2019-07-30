<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpjmdindikatorsasaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmPrioritasIndikatorSasaran', function (Blueprint $table) {
            $table->string('PrioritasIndikatorSasaranID',19);
            $table->string('PrioritasSasaranKabID',19);            
            $table->text('NamaIndikator');
            $table->decimal('KondisiAwal',6,2);
            $table->decimal('N1',6,2);
            $table->decimal('N2',6,2);
            $table->decimal('N3',6,2);
            $table->decimal('N4',6,2);
            $table->decimal('N5',6,2);
            $table->decimal('KondisiAkhir',6,2);
            $table->string('Satuan');           
            $table->string('Operator',10);           
            $table->string('Descr')->nullable();
            $table->year('TA');
            $table->string('PrioritasIndikatorSasaranID_Src',19)->nullable();
            $table->boolean('Locked')->default(0);

            $table->timestamps();
            
            $table->primary('PrioritasIndikatorSasaranID');

            $table->index('PrioritasSasaranKabID');

            $table->foreign('PrioritasSasaranKabID')
                    ->references('PrioritasSasaranKabID')
                    ->on('tmPrioritasSasaranKab')
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
        Schema::dropIfExists('tmPrioritasIndikatorSasaran');
    }
}
